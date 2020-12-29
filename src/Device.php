<?php 
/**
 * Device.php
 * An abstraction class to work with a single device in the system.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

require_once(__DIR__ . "/Database.php");

class Device {
	private $id;
	private $mac_addr;
	private $hostname;

	/**
	 * Device class constructor.
	 * 
	 * @param int    $id       Device ID.
	 * @param string $mac_addr MAC address.
	 * @param string $hostname Hostname.
	 */
	public function __construct($id = null, $mac_addr = null, $hostname = null) {
		$this->id = $id;
		$this->mac_addr = $mac_addr;
		$this->hostname = $hostname;
	}

	/**
	 * Gets a list of all the devices available.
	 * 
	 * @return array Array of Device objects.
	 */
	public static function List() {
		$devices = array();

		// Get devices from the database ordered.
		$dbh = Database::connect();
		$query = $dbh->prepare("SELECT id FROM devices");
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);

		// Go through the results creating Device objects.
		foreach ($rows as $device)
			array_push($devices, Device::FromID($device["id"]));

		return $devices;
	}

	/**
	 * Constructs an object with the device information from the database by 
	 * using its ID.
	 * 
	 * @param  int    $id Device ID.
	 * @return Device     Populated Device object with data from database, or
	 *                    NULL if the ID wasn't found.
	 */
	public static function FromID($id) {
		// Get device from database.
		$dbh = Database::connect();
		$query = $dbh->prepare("SELECT * FROM devices WHERE id = :id LIMIT 1");
		$query->bindValue(":id", $id);
		$query->execute();
		$dev = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if the ID was invalid.
		if (empty($dev))
			return null;
		
		// Create a new device object.
		$dev = $dev[0];
		return new Device($dev["id"], $dev["mac_addr"], $dev["hostname"]);
	}

	/**
	 * Constructs an object with the device information from the database by
	 * using its MAC address.
	 * 
	 * @param  string $mac_address Device MAC address.
	 * @return Device              Populated Device object with data from
	 *                             database, or NULL if the MAC address wasn't
	 *                             found.
	 */
	public static function FromMACAddress($mac_addr) {
		// Get device from database.
		$dbh = Database::connect();
		$query = $dbh->prepare("SELECT * FROM devices WHERE mac_addr = :mac_addr LIMIT 1");
		$query->bindValue(":mac_addr", $mac_addr);
		$query->execute();
		$dev = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if the MAC address was invalid.
		if (empty($dev))
			return null;
		
		// Create a new device object.
		$dev = $dev[0];
		return new Device($dev["id"], $dev["mac_addr"], $dev["hostname"]);
	}

	/**
	 * Saves this device object into the database. This will create a new record
	 * if an ID wasn't set previously.
	 */
	public function save() {
		// Get database handle.
		$dbh = Database::connect();
		$stmt = null;

		// Check if we are creating a new device or updating one.
		if (is_null($this->id)) {
			// Creating a new device.
			$stmt = $dbh->prepare("INSERT INTO devices(mac_addr, hostname) VALUES (:mac_addr, :hostname)");
		} else {
			// Update an existing device.
			$stmt = $dbh->prepare("UPDATE devices SET mac_addr = :mac_addr, hostname = :hostname WHERE id = :id");
			$stmt->bindValue(":id", $this->id);
		}

		// Bind parameters and execute.
		$stmt->bindValue(":mac_addr", $this->mac_addr);
		$stmt->bindValue(":hostname", $this->hostname);
		$stmt->execute();

		// Set the device ID.
		if (is_null($this->id))
			$this->id = $dbh->lastInsertId();
	}

	/**
	 * Gets the device ID.
	 * 
	 * @return int Device ID.
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Gets the device MAC address.
	 * 
	 * @return string Device's MAC address.
	 */
	public function get_mac_address() {
		return $this->mac_addr;
	}

	/**
	 * Sets the device hostname.
	 * 
	 * @param string $hostname Device's hostname.
	 */
	public function set_hostname($hostname) {
		$this->hostname = $hostname;
	}

	/**
	 * Gets the device hostname.
	 * 
	 * @return string Device's hostname.
	 */
	public function get_hostname() {
		return $this->hostname;
	}

	/**
	 * Array representation of this object. Perfect for use in JSON responses.
	 * 
	 * @return array Array representation of this object.
	 */
	public function as_array() {
		return array(
			"id" => $this->id,
			"mac_addr" => $this->mac_addr,
			"hostname" => $this->hostname
		);
	}
}

?>
