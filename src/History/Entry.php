<?php
/**
 * Entry.php
 * An abstraction class to work with entries in the device history.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE\History;
require __DIR__ . "/../../vendor/autoload.php";
use OnIADE\Database;
use OnIADE\Device;
use OnIADE\Floor;
use OnIADE\Utilities\DateTimeUtil;
use PDO;
use DateTime;
use DateInterval;

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
	 * Gets a list of devices that were in the network on a specific floor.
	 * 
	 * @param  Floor   $floor          Want to filter by floor?
	 * @param  boolean $list_ignored   Should we list ignored devices?
	 * @param  boolean $get_everything Dump the whole device history? (CAUTION)
	 * @return array                   Array of Entry objects found.
	 */
	public static function List($floor = null, $list_ignored = false,
			$get_everything = false) {
		$devices = array();
		$dbh = Database::connect();
		$last = Entry::LastEntry();

		// Create timespan before last entry to fetch devices.
		$config = require(__DIR__ . "/../../config/config.php");
		$dt = $last->get_mysql_timestamp($config->scanner->interval / 2);

		// Create the query statement.
		$query = null;
		if ($floor == null) {
			$query = $dbh->prepare("SELECT id FROM device_history WHERE dt > :dt GROUP BY (device_id) ORDER BY dt DESC");
		} else {
			$query = $dbh->prepare("SELECT id FROM device_history WHERE floor_id = :floor_id AND dt > :dt GROUP BY (device_id) ORDER BY dt DESC");
			$query->bindValue(":floor_id", $floor->get_id());
		}

		// Bind common values and fetch devices.
		$query->bindValue(":dt", $dt);
		$query->execute();
		$entries = $query->fetchAll(PDO::FETCH_ASSOC);

		// Go through the entries creating Entry objects.
		foreach ($entries as $entry) {
			$ent = Entry::FromID($entry["id"]);

			// Check if we should skip the entry.
			if (!$list_ignored && $ent->get_device()->is_ignored())
				continue;

			array_push($devices, $ent);
		}

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
		$dbh = Database::connect();
		$query = $dbh->prepare("SELECT * FROM device_history WHERE id = :id LIMIT 1");
		$query->bindValue(":id", $id);
		$query->execute();
		$entry = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if the ID was invalid.
		if (empty($entry))
			return null;

		// Get "support" objects.
		$entry = $entry[0];
		$device = Device::FromID($entry["device_id"]);
		$floor = Floor::FromID($entry["floor_id"]);

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
		$last_dt = $last_entry->get_mysql_timestamp();

		// Get entry from database.
		$dbh = Database::connect();
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
		$device = Device::FromID($entry["device_id"]);
		$floor = Floor::FromID($entry["floor_id"]);

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
		$dbh = Database::connect();
		$query = $dbh->prepare("SELECT * FROM device_history ORDER BY id DESC LIMIT 1");
		$query->execute();
		$entry = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if there are any entries.
		if (empty($entry))
			return null;

		// Get "support" objects.
		$entry = $entry[0];
		$device = Device::FromID($entry["device_id"]);
		$floor = Floor::FromID($entry["floor_id"]);

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
		$dbh = Database::connect();
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
		$stmt->bindValue(":dt", $this->get_mysql_timestamp());
		$stmt->execute();

		// Set the entry ID.
		if (is_null($this->id))
			$this->id = $dbh->lastInsertId();
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
	 * Gets a timestamp that is in the correct format for using in a MySQL
	 * database.
	 * 
	 * @param  int    $subtract Amount of minutes to subtract from the current
	 *                          timestamp before returning it.
	 * @return string           Date and time in MySQL format.
	 */
	public function get_mysql_timestamp($subtract = null) {
		$dt = $this->datetime;
		if (!is_null($subtract))
			$dt->sub(new DateInterval("PT" . ceil($subtract) . "M"));

		return DateTimeUtil::mysql_format($dt);
	}

	/**
	 * Gets a human-readable version of the date and time this entry was
	 * recorded.
	 * 
	 * @return string Human-readable date and time of this entry.
	 */
	public function get_timestamp_elapsed() {
		return DateTimeUtil::since_string($this->datetime);
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
