<?php
/**
 * Entry.php
 * An abstraction class to work with entries in the device history.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE\History;
require __DIR__ . "/../../vendor/autoload.php";
use PDO;
use DateTime;

class Entry {
	private $id;
	private $device;
	private $floor;
	private $datetime;
	private $ip_addr;

	/**
	 * Device history entry object constructor.
	 * 
	 * @param int      $id       Entry ID.
	 * @param Device   $device   Device to be added to the history.
	 * @param Floor    $floor    In which floor said device is?
	 * @param string   $ip_addr  Device"s IP address.
	 * @param DateTime $datetime Date and time of the entry.
	 */
	public function __construct($id, $device, $floor, $ip_addr, $datetime) {
		$this->id = $id;
		$this->device = $device;
		$this->ip_addr = $ip_addr;
		$this->floor = $floor;
		$this->datetime = $datetime;
	}

	/**
	 * Gets a list of devices that were in the network within a specific 
	 * timespan and on a specific floor.
	 * 
	 * @param  int   $timespan Timespan in hours.
	 * @param  Floor $floor    Want to filter by floor?
	 * @return array           Array of Entry objects found.
	 */
	public static function List($timespan = 1, $floor = null) {
		$devices = array();
		$dbh = \OnIADE\Database::connect();

		// Create the query statement.
		$query = null;
		if ($floor == null) {
			$query = $dbh->prepare("SELECT DISTINCT id FROM device_history WHERE dt > NOW() - INTERVAL :ts HOUR ORDER BY dt DESC");
		} else {
			$query = $dbh->prepare("SELECT DISTINCT id FROM device_history WHERE floor_id = :floor_id AND dt > NOW() - INTERVAL :ts HOUR ORDER BY dt DESC");
			$query->bindValue(":floor_id", $floor->get_id());
		}

		// Bind common values and fetch devices.
		$query->bindValue(":ts", $timespan);
		$query->execute();
		$entries = $query->fetchAll(PDO::FETCH_ASSOC);

		// Go through the entries creating Entry objects.
		foreach ($entries as $entry)
			array_push($devices, Entry::FromID($entry["id"]));

		return $devices;
	}

	/**
	 * Creates a new history entry object and automatically inserts it into the
	 * database.
	 * 
	 * @param  Device       $device   Device to be added to the history.
	 * @param  Floor        $floor    In which floor said device is?
	 * @param  string       $ip_addr  Device"s IP address.
	 * @return Entry           Populated and stored entry object.
	 */
	public static function Create($device, $floor, $ip_addr) {
		$entry = new Entry(null, $device, $floor, $ip_addr, new DateTime("NOW"));
		$entry->save();

		return $entry;
	}

	/**
	 * Creates an existing history entry object with information from the
	 * database based on its ID.
	 * 
	 * @param  int          $id History entry ID.
	 * @return Entry     Populated entry object.
	 */
	public static function FromID($id) {
		// Get entry from database.
		$dbh = \OnIADE\Database::connect();
		$query = $dbh->prepare("SELECT * FROM device_history WHERE id = :id LIMIT 1");
		$query->bindValue(":id", $id);
		$query->execute();
		$entry = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if the ID was invalid.
		if (empty($entry))
			return null;

		// Get "support" objects.
		$entry = $entry[0];
		$device = \OnIADE\Device::FromID($entry["device_id"]);
		$floor = \OnIADE\Floor::FromID($entry["floor_id"]);

		// Build our entry object.
		return new Entry($entry["id"], $device, $floor,
			$entry["ip_addr"], new DateTime($entry["dt"]));
	}

	/**
	 * Creates an existing history entry object with information from the
	 * database based on a device IP address.
	 * 
	 * @param  int          $ip_addr      History entry ID.
	 * @param  int          $hr_last_seen Maximum number of hours to go back and
	 *                                    search for a valid IP.
	 * @return Entry               Populated entry object.
	 */
	public static function FromIPAddress($ip_addr, $hr_last_seen = 1) {
		// Get last entry to base our Last Seen time frame.
		$last_entry = Entry::LastEntry();
		$last_dt = $last_entry->get_timestamp()->format("Y-m-d H:i:s");

		// Get entry from database.
		$dbh = \OnIADE\Database::connect();
		$query = $dbh->prepare("SELECT * FROM device_history WHERE ip_addr = :ip_addr AND dt > :dt - INTERVAL :ts HOUR ORDER BY dt DESC LIMIT 1");
		$query->bindValue(":ip_addr", $ip_addr);
		$query->bindValue(":dt", $last_dt);
		$query->bindValue(":ts", $hr_last_seen);
		$query->execute();
		$entry = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if the ID was invalid.
		if (empty($entry))
			return null;

		// Get "support" objects.
		$entry = $entry[0];
		$device = \OnIADE\Device::FromID($entry["device_id"]);
		$floor = \OnIADE\Floor::FromID($entry["floor_id"]);

		// Build our entry object.
		return new Entry($entry["id"], $device, $floor,
			$entry["ip_addr"], new DateTime($entry["dt"]));
	}

	/**
	 * Gets the last entry that's available in the database in a pre-populated
	 * Entry object.
	 * 
	 * @return Entry The last entry available in the database.
	 */
	public static function LastEntry() {
		// Get entry from database.
		$dbh = \OnIADE\Database::connect();
		$query = $dbh->prepare("SELECT * FROM device_history ORDER BY id DESC LIMIT 1");
		$query->execute();
		$entry = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if there are any entries.
		if (empty($entry))
			return null;

		// Get "support" objects.
		$entry = $entry[0];
		$device = \OnIADE\Device::FromID($entry["device_id"]);
		$floor = \OnIADE\Floor::FromID($entry["floor_id"]);

		// Build our entry object.
		return new Entry($entry["id"], $device, $floor, $entry["ip_addr"],
			new DateTime($entry["dt"]));
	}

	/**
	 * Saves this entry object into the database. This will create a new record
	 * if an ID wasn"t set previously.
	 */
	public function save() {
		// Get database handle.
		$dbh = \OnIADE\Database::connect();
		$stmt = null;

		// Check if we are creating a new entry or updating one.
		if (is_null($this->id)) {
			// Creating a new entry.
			$stmt = $dbh->prepare("INSERT INTO device_history(device_id, floor_id, ip_addr, dt) VALUES (:device_id, :floor_id, :ip_addr, :dt)");
		} else {
			// Update an existing entry.
			$stmt = $dbh->prepare("UPDATE device_history SET device_id = :device_id, floor_id = :floor_id, ip_addr = :ip_addr, dt = :dt WHERE id = :id");
			$stmt->bindValue(":id", $this->id);
		}

		// Bind parameters and execute.
		$stmt->bindValue(":device_id", $this->device->get_id());
		$stmt->bindValue(":floor_id", $this->floor->get_id());
		$stmt->bindValue(":ip_addr", $this->ip_addr);
		$stmt->bindValue(":dt", $this->datetime->format("Y-m-d H:i:s"));
		$stmt->execute();

		// Set the entry ID.
		if (is_null($this->id))
			$this->id = $dbh->lastInsertId();
	}

	/**
	 * Creates a nice human-readable "X minutes ago"-type string.
	 * @link https://stackoverflow.com/a/18602474/126353 Original Author
	 * 
	 * @param  DateTime $datetime Date and time to compare to now.
	 * @param  boolean  $full     Show a very detailed string down to the seconds?
	 * @return string             Human-readable time passed string.
	 */
	public static function time_since_string($datetime, $full = false) {
		// Get the difference and normalize the data.
		$now = new DateTime;
		$diff = $now->diff($datetime);
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		// Key to human-readable string lookup table.
		$string = array(
			"y" => "year",
			"m" => "month",
			"w" => "week",
			"d" => "day",
			"h" => "hour",
			"i" => "minute",
			"s" => "second",
		);

		// Go through and create a human-readable string.
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . " " . $v . ($diff->$k > 1 ? "s" : "");
			} else {
				unset($string[$k]);
			}
		}

		// Should we just give the user the whole massive string?
		if (!$full)
			$string = array_slice($string, 0, 1);

		return $string ? implode(", ", $string) . " ago" : "just now";
	}

	/**
	 * Gets the device of this history entry.
	 * 
	 * @return Device Entry"s device.
	 */
	public function get_device() {
		return $this->device;
	}

	/**
	 * Gets the floor in which the device is currently located at.
	 * 
	 * @return Floor Floor information.
	 */
	public function get_floor() {
		return $this->floor;
	}

	/**
	 * Gets the device IP address at the time of this entry.
	 * 
	 * @return string Device"s IP address.
	 */
	public function get_ip_addr() {
		return $this->ip_addr;
	}

	/**
	 * Gets the date and time when this entry was recorded.
	 * 
	 * @return DateTime Date and time the entry was recorded.
	 */
	public function get_timestamp() {
		return $this->datetime;
	}

	/**
	 * Gets a human-readable version of the date and time this entry was
	 * recorded.
	 * 
	 * @return string Human-readable date and time of this entry.
	 */
	public function get_timestamp_elapsed() {
		return Entry::time_since_string($this->datetime);
	}

	/**
	 * Array representation of this object. Perfect for use in JSON responses.
	 * 
	 * @return array Array representation of this object.
	 */
	public function as_array() {
		return array(
			"id" => $this->id,
			"device" => $this->device->as_array(),
			"floor" => $this->floor->as_array(),
			"ip_addr" => $this->ip_addr,
			"timestamp" => array(
				"iso8601" => date($this->datetime->format("c")),
				"human_readable" => $this->get_timestamp_elapsed()
			)
		);
	}
}

?>
