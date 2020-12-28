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
		$query = $dbh->prepare("SELECT * FROM devices WHERE id = :id");
		$query->bindParam(":id", $id);
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
	 *                             database, or NULL if the ID wasn't found.
	 */
	public static function FromMACAddress($mac_addr) {
		// Get device from database.
		$dbh = Database::connect();
		$query = $dbh->prepare("SELECT * FROM devices WHERE mac_addr = :mac_addr");
		$query->bindParam(":mac_addr", $mac_addr);
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

		// Check if we are creating a new device or updating one.
		if (is_null($this->id)) {
			// Creating a new device.
			$stmt = $dbh->prepare("INSERT INTO devices(mac_addr, hostname) VALUES (:mac_addr, :hostname)");
		} else {
			// Update an existing device.
			$stmt = $dbh->prepare("UPDATE devices SET mac_addr = :mac_addr, hostname = :hostname WHERE id = :id");
			$stmt->bindParam(":id", $this->id);
		}

		// Bind parameters and execute.
		$stmt->bindParam(":mac_addr", $this->mac_addr);
		$stmt->bindParam(":hostname", $this->hostname);
		$stmt->execute();

		// Set the device ID.
		if (is_null($this->id))
			$this->id = $pdo->lastInsertId();
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
