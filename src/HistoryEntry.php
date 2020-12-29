<?php
/**
 * HistoryEntry.php
 * An abstraction class to work with entries in the device history.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

require_once(__DIR__ . "/Database.php");
require_once(__DIR__ . "/Device.php");
require_once(__DIR__ . "/Floor.php");

class HistoryEntry {
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
	 * @param string   $ip_addr  Device's IP address.
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
	 * @return array           Array of HistoryEntry objects found.
	 */
	public static function List($timespan = 1, $floor = null) {
		$devices = array();
		$dbh = Database::connect();

		// Create the query statement.
		$query = null;
		if ($floor == null) {
			$query = $dbh->prepare("SELECT DISTINCT id FROM device_history WHERE dt > NOW() - INTERVAL :ts HOUR ORDER BY dt");
		} else {
			$query = $dbh->prepare("SELECT DISTINCT id FROM device_history WHERE floor_id = :floor_id AND dt > NOW() - INTERVAL :ts HOUR ORDER BY dt");
			$query->bindValue(":floor_id", $floor->get_id());
		}

		// Bind common values and fetch devices.
		$query->bindValue(":ts", $timespan);
		$query->execute();
		$entries = $query->fetchAll(PDO::FETCH_ASSOC);

		// Go through the entries creating HistoryEntry objects.
		foreach ($entries as $entry)
			array_push($devices, HistoryEntry::FromID($entry["id"]));

		return $devices;
	}

	/**
	 * Creates a new history entry object and automatically inserts it into the
	 * database.
	 * 
	 * @param  Device       $device   Device to be added to the history.
	 * @param  Floor        $floor    In which floor said device is?
	 * @param  string       $ip_addr  Device's IP address.
	 * @return HistoryEntry           Populated and stored entry object.
	 */
	public static function Create($device, $floor, $ip_addr) {
		$entry = new HistoryEntry(null, $device, $floor, $ip_addr,
			new DateTime('NOW'));
		$entry->save();

		return $entry;
	}

	/**
	 * Creates an existing history entry object with information from the
	 * database based on its ID.
	 * 
	 * @param  int          $id History entry ID.
	 * @return HistoryEntry     Populated entry object.
	 */
	public static function FromID($id) {
		// Get entry from database.
		$dbh = Database::connect();
		$query = $dbh->prepare("SELECT * FROM device_history WHERE id = :id");
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
		return new HistoryEntry($entry["id"], $device, $floor, $entry["ip_addr"],
			new DateTime($entry["dt"]));
	}

	/**
	 * Saves this entry object into the database. This will create a new record
	 * if an ID wasn't set previously.
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
		$stmt->bindValue(":dt", $this->datetime->format("Y-m-d H:i:s"));
		$stmt->execute();

		// Set the entry ID.
		if (is_null($this->id))
			$this->id = $dbh->lastInsertId();
	}

	/**
	 * Gets the device of this history entry.
	 * 
	 * @return Device Entry's device.
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
	 * @return string Device's IP address.
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
				"iso8601" => date($this->datetime->format("c"))
			)
		);
	}
}


?>
