<?php
/**
 * OperatingSystem.php
 * Abstraction class for a device operating system.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE\Device;
require __DIR__ . "/../../vendor/autoload.php";
use OnIADE\Database;
use PDO;

class OperatingSystem {
	private $id;
	private $name;
	private $version;
	private $family;
	private $icon;

	/**
	 * Device operating system object constructor.
	 * 
	 * @param int    $id      Device operating system ID.
	 * @param string $name    Its proper name.
	 * @param string $version OS version.
	 * @param string $family  OS family.
	 * @param Icon   $icon    OS icon.
	 */
	public function __construct($id = null, $name = null, $version = null,
			$family = null, $icon = null) {
		$this->id = $id;
		$this->name = $name;
		$this->version = $version;
		$this->family = $family;
		$this->icon = $icon;

		// Handle empty icons.
		if (is_null($this->icon))
			$this->icon = new Icon();
	}

	/**
	 * Gets a list of all the device operating systems available.
	 * 
	 * @return array Array of OperatingSystem objects.
	 */
	public static function List() {
		$oses = array();

		// Get operating systems from the database ordered.
		$dbh = Database::connect();
		$query = $dbh->prepare("SELECT id FROM operating_systems");
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);

		// Go through the results creating OperatingSystem objects.
		foreach ($rows as $os)
			array_push($oses, OperatingSystem::FromID($os["id"]));

		return $oses;
	}

	/**
	 * Creates a new device OS and automatically saves it to the database.
	 * 
	 * @param  string          $name    Its proper name.
	 * @param  string          $version OS version.
	 * @param  string          $family  OS family.
	 * @param  Icon            $icon    OS icon.
	 * @return OperatingSystem          Populated device OS object.
	 */
	public static function Create($name = null, $version = null, $family = null,
			$icon = null) {
		// Check for a null name.
		if (is_null($name))
			return null;

		$os = new OperatingSystem(null, $name, $version, $family, $icon);
		$os->save();

		return $os;
	}

	/**
	 * Creates (unless already exists) a new device operating system from a
	 * \WhichBrowser\Parser object and saves it to the database.
	 * 
	 * @param  \WhichBrowser\Parser $parser       Browser parser.
	 * @param  boolean              $check_exists Check if it has been created
	 *                                            before?
	 * @return OperatingSystem                    Populated device OS object.
	 */
	public static function CreateFromBrowserParser($parser, $check_exists = true) {
		// Get our parameters.
		$name = $parser->os->name;
		$family = $parser->os->family;
		$version = null;
		if (isset($parser->os->version->alias))
			$version = $parser->os->version->alias;

		// Check if it exists before.
		if ($check_exists) {
			$os = OperatingSystem::FromDetails($name, $version, $family);
			if (!is_null($os))
				return $os;
		}

		// Create and return it.
		return OperatingSystem::Create($name, $version, $family);
	}

	/**
	 * Creates and populates this object from the database by providing an ID.
	 * 
	 * @param  int             $id Device operating system ID.
	 * @return OperatingSystem     Populated object or NULL if the ID wasn't
	 *                             found.
	 */
	public static function FromID($id) {
		// Get device OS from database.
		$dbh = Database::connect();
		$query = $dbh->prepare("SELECT * FROM operating_systems WHERE id = :id LIMIT 1");
		$query->bindValue(":id", $id);
		$query->execute();
		$os = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if the ID was invalid.
		if (empty($os))
			return null;
		
		// Create a new device OS object.
		$os = $os[0];
		return new OperatingSystem($os["id"], $os["name"], $os["version"],
			$os["family"], new Icon($os["icon"], $os["icon_color"]));
	}

	/**
	 * Creates and populates this object from the database by providing full
	 * information about it.
	 * 
	 * @param  string          $name    Operating system name.
	 * @param  string          $version OS version.
	 * @param  string          $family  OS family.
	 * @return OperatingSystem          Populated object or NULL if it wasn't
	 *                                  found.
	 */
	public static function FromDetails($name, $version, $family) {
		// Build database query statement.
		$dbh = Database::connect();
		$query = $dbh->prepare("SELECT * FROM operating_systems WHERE (name <=> :name) AND (version <=> :version) AND (family <=> :family) LIMIT 1");

		// Bind parameters and execute.
		$query->bindValue(":name", $name);
		$query->bindValue(":version", $version);
		$query->bindValue(":family", $family);
		$query->execute();
		$os = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if the information was invalid.
		if (empty($os))
			return null;
		
		// Create a new device OS object.
		$os = $os[0];
		return new OperatingSystem($os["id"], $os["name"], $os["version"],
			$os["family"], new Icon($os["icon"], $os["icon_color"]));
	}

	/**
	 * Checks if a device operating system exists in the database already.
	 * 
	 * @param  string  $name    Operating system name.
	 * @param  string  $version OS version.
	 * @param  string  $family  OS family.
	 * @return boolean          Does the operating system exist?
	 */
	public static function Exists($name, $version, $family) {
		$os = OperatingSystem::FromDetails($name, $version, $family);
		return !is_null($os);
	}

	/**
	 * Saves this device operating system object into the database. This will
	 * create a new record if an ID wasn't set previously.
	 */
	public function save() {
		// Get database handle.
		$dbh = Database::connect();
		$stmt = null;

		// Check if we are creating a new device model or updating one.
		if (is_null($this->id)) {
			// Creating a new model.
			$stmt = $dbh->prepare("INSERT INTO operating_systems(name, version, family, icon, icon_color) VALUES (:name, :version, :family, :icon, :icon_color)");
		} else {
			// Update an existing model.
			$stmt = $dbh->prepare("UPDATE operating_systems SET name = :name, version = :version, family = :family, icon = :icon, icon_color = :icon_color WHERE id = :id");
			$stmt->bindValue(":id", $this->id);
		}

		// Bind parameters and execute.
		$stmt->bindValue(":name", $this->name);
		$stmt->bindValue(":version", $this->version);
		$stmt->bindValue(":family", $this->family);
		$stmt->bindValue(":icon", $this->icon->get_name());
		$stmt->bindValue(":icon_color", $this->icon->get_color());
		$stmt->execute();

		// Set the ID.
		if (is_null($this->id))
			$this->id = $dbh->lastInsertId();
	}

	/**
	 * Gets the operating system ID.
	 * 
	 * @return int Operating system ID.
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Gets the operating system name.
	 * 
	 * @return string Operating system name.
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Gets the operating system version.
	 * 
	 * @return string Operating system version.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Gets the operating system family.
	 * 
	 * @return string Operating system family.
	 */
	public function get_family() {
		return $this->family;
	}

	/**
	 * Gets the operating system icon.
	 * 
	 * @return Icon Operating system icon.
	 */
	public function get_icon() {
		return $this->icon;
	}

	/**
	 * String representation of this object.
	 * 
	 * @return string String representation of this object.
	 */
	public function as_string() {
		return $this->name . " " . $this->version;
	}

	/**
	 * Gets an HTML flair of the operating system.
	 * 
	 * @param  boolean $use_singlequotes Use single quotes for HTML attributes?
	 * @return string                    HTML flair with icon, color, and
	 *                                   description.
	 */
	public function as_flair($use_singlequotes = false) {
		$str = "<span class=\"badge\" style=\"background-color: " .
			$this->get_icon()->get_color() . ";\">" .
			$this->get_icon()->as_tag(false, $use_singlequotes) . " " .
			$this->as_string() . "</span>";

		// Use single quotes?
		if ($use_singlequotes)
			return str_replace("\"", "'", $str);

		return $str;
	}

	/**
	 * Array representation of this object. Perfect for use in JSON responses.
	 * 
	 * @return array Array representation of this object.
	 */
	public function as_array() {
		return array(
			"id" => $this->id,
			"name" => $this->name,
			"version" => $this->version,
			"family" => $this->family,
			"icon" => $this->icon->as_array()
		);
	}
}

?>
