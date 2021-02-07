<?php
/**
 * Contribution.php
 * An abstraction class to work with contributions in the database.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE;
require __DIR__ . "/../vendor/autoload.php";
use OnIADE\Utilities\DateTimeUtil;
use OnIADE\Utilities\UploadHandler;
use PDO;
use DateTime;

class Contribution {
	const UPLOAD_DIR = "/uploads/contrib";
	const THUMBNAIL_PREFIX = "thumb_";
	const ATTACHMENT_PREFIX = "attn_";

	private $id;
	private $fullname;
	private $website;
	private $email;
	private $title;
	private $url;
	private $description;
	private $datetime;
	private $show_email;
	private $hash;

	/**
	 * Contribution object constructor.
	 * 
	 * @param int      $id          Contribution ID.
	 * @param string   $fullname    Contributor's full name.
	 * @param string   $website     Contributor's personal website.
	 * @param string   $email       Contributor's email.
	 * @param string   $title       Contribution title.
	 * @param string   $url         URL to the project/contribution.
	 * @param string   $description Brief description of the contribution.
	 * @param DateTime $datetime    Date and time it was contributed.
	 * @param boolean  $show_email  Show the email to the public?
	 * @param string   $hash        Hash representation of this contribution.
	 */
	public function __construct($id = null, $fullname = null, $website = null,
			$email = null, $title = null, $url = null, $description = null,
			$datetime = null, $show_email = null, $hash = null) {
		$this->id = $id;
		$this->fullname = $fullname;
		$this->website = $website;
		$this->email = $email;
		$this->title = $title;
		$this->url = $url;
		$this->description = $description;
		$this->datetime = $datetime;
		$this->show_email = $show_email;
		$this->hash = $hash;
	}

	/**
	 * Gets a list of all the contributions available.
	 * 
	 * @return array Array of Contribution objects.
	 */
	public static function List() {
		$contribs = array();

		// Get contributions from the database ordered.
		$dbh = Database::connect();
		$query = $dbh->prepare("SELECT id FROM contributions ORDER BY dt DESC");
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);

		// Go through the results creating contribution objects.
		foreach ($rows as $contrib) {
			array_push($contribs, Contribution::FromID($contrib["id"]));
		}

		return $contribs;
	}

	/**
	 * Creates a new contribution object and automatically inserts it into the
	 * database.
	 * 
	 * @param  string       $fullname    Contributor's full name.
	 * @param  string       $website     Contributor's personal website.
	 * @param  string       $email       Contributor's email.
	 * @param  string       $title       Contribution title.
	 * @param  string       $url         URL to the project/contribution.
	 * @param  string       $description Brief description of the contribution.
	 * @param  boolean      $show_email  Show the email to the public?
	 * @param  assoc        $thumbnail   $_FILES entry for the thumbnail.
	 * @param  assoc        $attachment  $_FILES entry for the attachment.
	 * @return Contribution              Populated and stored contribution
	 *                                   object or NULL if something went wrong.
	 */
	public static function Create($fullname = null, $website = null,
			$email = null, $title = null, $url = null, $description = null,
			$show_email = null, $thumbnail = null, $attachment = null) {
		// Check if we have a personal website.
		if (empty($website))
			$website = null;

		// Check if we have a project URL.
		if (empty($url))
			$url = null;

		// Create object.
		$contrib = new Contribution(null, $fullname, $website, $email, $title,
			$url, $description, new DateTime("NOW"), $show_email);

		// Check if our thumbnail is valid.
		if (is_null($thumbnail)) {
			return null;
		} else {
			$handler = new UploadHandler($thumbnail, Contribution::UPLOAD_DIR);

			// Check if we actually have an image and that it was uploaded.
			if ((!$handler->is_image()) || (!$handler->was_uploaded()))
				return null;

			// Move the thumbnail to its rightful place.
			if (!$handler->save($contrib->get_thumbnail_fname()))
				return null;
		}

		// Try to save the attachment.
		if (!is_null($attachment)) {
			$handler = new UploadHandler($attachment, Contribution::UPLOAD_DIR);

			// Check if we had anything uploaded.
			if ($handler->was_uploaded()) {
				// Move the attachment to its rightful place.
				if (!$handler->save($contrib->get_attachment_fname()))
					return null;
			}
		}

		// Save everything to the database and return.
		$contrib->save();
		return $contrib;
	}

	/**
	 * Constructs an object with the contribution information from the database
	 * by using its ID.
	 * 
	 * @param  int          $id Contribution ID.
	 * @return Contribution     Populated Contribution object with data from
	 *                          database, or NULL if the ID wasn't found.
	 */
	public static function FromID($id) {
		// Get contribution from database.
		$dbh = Database::connect();
		$query = $dbh->prepare("SELECT * FROM contributions WHERE id = :id LIMIT 1");
		$query->bindValue(":id", $id);
		$query->execute();
		$contrib = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if the ID was invalid.
		if (empty($contrib))
			return null;
		
		// Create a new contribution object.
		$contrib = $contrib[0];
		return new Contribution($contrib["id"], $contrib["fullname"],
			$contrib["personal_website"], $contrib["email"], $contrib["title"],
			$contrib["url"], $contrib["description"], new DateTime($contrib["dt"]),
			(bool)$contrib["show_email"], $contrib["md5"]);
	}

	/**
	 * Saves this object into the database. This will create a new record if an
	 * ID wasn't set previously.
	 */
	public function save() {
		// Get database handle.
		$dbh = Database::connect();
		$stmt = null;

		// Check if we are creating a new contribution or updating one.
		if (is_null($this->id)) {
			// Creating a new contribution.
			$stmt = $dbh->prepare("INSERT INTO contributions(dt, fullname, personal_website, email, title, url, description, show_email, md5) VALUES (:dt, :fullname, :personal_website, :email, :title, :url, :description, :show_email, :md5)");
		} else {
			// Update an existing contribution.
			$stmt = $dbh->prepare("UPDATE contributions SET dt = :dt, fullname = :fullname, personal_website = :personal_website, email = :email, title = :title, url = :url, description = :description, show_email = :show_email, md5 = :md5 WHERE id = :id");
			$stmt->bindValue(":id", $this->id);
		}

		// Bind parameters and execute.
		$stmt->bindValue(":dt", DateTimeUtil::mysql_format($this->datetime));
		$stmt->bindValue(":fullname", $this->fullname);
		$stmt->bindValue(":personal_website", $this->website);
		$stmt->bindValue(":email", $this->email);
		$stmt->bindValue(":title", $this->title);
		$stmt->bindValue(":url", $this->url);
		$stmt->bindValue(":description", $this->description);
		$stmt->bindValue(":show_email", (int)$this->show_email);
		$stmt->bindValue(":md5", $this->get_hash());
		$stmt->execute();

		// Set the contribution ID.
		if (is_null($this->id))
			$this->id = $dbh->lastInsertId();
	}

	/**
	 * Gets the thumbnail file name without an extension.
	 * 
	 * @return string Thumbnail file name without extension.
	 */
	private function get_thumbnail_fname() {
		return Contribution::THUMBNAIL_PREFIX . $this->get_hash();
	}

	/**
	 * Gets the contribution thumbnail image path.
	 * 
	 * @return string Contribution thumbnail path.
	 */
	public function get_thumbnail_path() {
		$upload = new UploadHandler(null, Contribution::UPLOAD_DIR);
		return $upload->get_path($this->get_thumbnail_fname(), true, true);
	}

	/**
	 * Gets the attachment file name without an extension.
	 * 
	 * @return string Attachment file name without extension.
	 */
	private function get_attachment_fname() {
		return Contribution::ATTACHMENT_PREFIX . $this->get_hash();
	}

	/**
	 * Checks if the contribution has an attachment.
	 * 
	 * @return boolean Do we have an attachment?
	 */
	public function has_attachment() {
		return !is_null($this->get_attachment_path());
	}

	/**
	 * Gets the contribution attachment file path.
	 * 
	 * @return string Contribution attachment path or NULL if one isn't present.
	 */
	public function get_attachment_path() {
		$upload = new UploadHandler(null, Contribution::UPLOAD_DIR);
		return $upload->get_path($this->get_attachment_fname(), true, true);
	}

	/**
	 * Gets the contribution ID.
	 * 
	 * @return int Contribution ID.
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Gets the contributor's full name.
	 * 
	 * @return string Contributor's name.
	 */
	public function get_fullname() {
		return $this->fullname;
	}

	/**
	 * Checks if we have a contributor's website.
	 * 
	 * @return boolean Do we have a contributor's website?
	 */
	public function has_personal_website() {
		return !is_null($this->website);
	}

	/**
	 * Gets the contributor's website.
	 * 
	 * @return string Contributor's website.
	 */
	public function get_personal_website() {
		return $this->website;
	}

	/**
	 * Gets the contributor's email.
	 * 
	 * @return string Contributor's email.
	 */
	public function get_email() {
		return $this->email;
	}

	/**
	 * Gets the contribution's title.
	 * 
	 * @return string Contribution's title.
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * Checks if we have a contribution URL.
	 * 
	 * @return boolean Do we have a contribution URL?
	 */
	public function has_url() {
		return !is_null($this->url);
	}

	/**
	 * Gets the contribution's website URL.
	 * 
	 * @return string Contribution's website URL.
	 */
	public function get_url() {
		return $this->url;
	}

	/**
	 * Gets the contribution's description.
	 * 
	 * @return string Contribution's description.
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Gets the contribution's date and time.
	 * 
	 * @return DateTime Contribution's date and time.
	 */
	public function get_datetime() {
		return $this->datetime;
	}

	/**
	 * Gets the contribution's date and time as a pretty string.
	 * 
	 * @return string Contribution's timestamp as a pretty string.
	 */
	public function get_timestamp() {
		return DateTimeUtil::since_string($this->datetime);
	}

	/**
	 * Checks if we are allowed to disclose the contributor's email.
	 * 
	 * @return boolean Can we show this guy's email?
	 */
	public function can_show_email() {
		return $this->show_email;
	}

	/**
	 * Gets a hash of the contribution.
	 * 
	 * @return string MD5 hash of this contribution.
	 */
	public function get_hash() {
		// Generate a hash if we don't have one.
		if (is_null($this->hash)) {
			return md5($this->fullname . $this->website . $this->email .
				$this->title . $this->url . $this->description .
				$this->show_email . date($this->datetime->format("c")));
		}

		return $this->hash;
	}

	/**
	 * Array representation of this object. Perfect for use in JSON responses.
	 * 
	 * @return array Array representation of this object.
	 */
	public function as_array() {
		return array(
			"id" => $this->id,
			"fullname" => $this->fullname,
			"website" => $this->website,
			"email" => $this->email,
			"title" => $this->title,
			"url" => $this->url,
			"description" => $this->description,
			"show_email" => $this->show_email,
			"thumbnail" => $this->get_thumbnail_path(),
			"attachment" => $this->get_attachment_path(),
			"timestamp" => array(
				"iso8601" => date($this->datetime->format("c")),
				"human_readable" => $this->get_timestamp()
			)
		);
	}
}

?>
