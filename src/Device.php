<?php 
/**
 * Device.php
 * An abstraction class to work with a single device in the system.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE;
require __DIR__ . "/../vendor/autoload.php";
use PDO;

class Device {
	private $id;
	private $mac_addr;
	private $hostname;
	private $type;
	private $model;
	private $os;

	/**
	 * Device class constructor.
	 * 
	 * @param int                    $id       Device ID.
	 * @param string                 $mac_addr MAC address.
	 * @param string                 $hostname Hostname.
	 * @param Device\Type            $type     Device type.
	 * @param Device\Model           $model    Device model.
	 * @param Device\OperatingSystem $os       Device operating system.
	 */
	public function __construct($id = null, $mac_addr = null, $hostname = null,
			$type = null, $model = null, $os = null) {
		$this->id = $id;
		$this->mac_addr = $mac_addr;
		$this->hostname = $hostname;
		$this->type = $type;
		$this->model = $model;
		$this->os = $os;
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
		return new Device($dev["id"], $dev["mac_addr"], $dev["hostname"], 
			Device\Type::FromID($dev["type_id"]),
			Device\Model::FromID($dev["model_id"]),
			Device\OperatingSystem::FromID($dev["os_id"]));
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
		return new Device($dev["id"], $dev["mac_addr"], $dev["hostname"], 
			Device\Type::FromID($dev["type_id"]),
			Device\Model::FromID($dev["model_id"]),
			Device\OperatingSystem::FromID($dev["os_id"]));
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
			$stmt = $dbh->prepare("INSERT INTO devices(mac_addr, hostname, type_id, model_id, os_id) VALUES (:mac_addr, :hostname, :type_id, :model_id, :os_id)");
		} else {
			// Update an existing device.
			$stmt = $dbh->prepare("UPDATE devices SET mac_addr = :mac_addr, hostname = :hostname, type_id = :type_id, model_id = :model_id, os_id = :os_id WHERE id = :id");
			$stmt->bindValue(":id", $this->id);
		}

		// Device type.
		$type_id = null;
		if (!is_null($this->type))
			$type_id = $this->type->get_id();

		// Device model.
		$model_id = null;
		if (!is_null($this->model))
			$model_id = $this->model->get_id();

		// Device operating system.
		$os_id = null;
		if (!is_null($this->os))
			$os_id = $this->os->get_id();

		// Bind parameters and execute.
		$stmt->bindValue(":mac_addr", $this->mac_addr);
		$stmt->bindValue(":hostname", $this->hostname);
		$stmt->bindValue(":type_id", $type_id);
		$stmt->bindValue(":model_id", $model_id);
		$stmt->bindValue(":os_id", $os_id);
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
	 * Gets the device type.
	 * 
	 * @return Device\Type Device type object.
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * Sets the device type.
	 * 
	 * @param Device\Type $type Device type object.
	 */
	public function set_type($type) {
		$this->type = $type;
	}

	/**
	 * Gets the device model.
	 * 
	 * @return Device\Model Device model object.
	 */
	public function get_model() {
		return $this->model;
	}

	/**
	 * Sets the device model.
	 * 
	 * @param Device\Model $model Device model object.
	 */
	public function set_model($model) {
		$this->model = $model;
	}

	/**
	 * Gets the device operating system.
	 * 
	 * @return Device\OperatingSystem Device operating system object.
	 */
	public function get_os() {
		return $this->os;
	}

	/**
	 * Sets the device operating system.
	 * 
	 * @return Device\OperatingSystem $os Device operating system object.
	 */
	public function set_os($os) {
		$this->os = $os;
	}

	/**
	 * Array representation of this object. Perfect for use in JSON responses.
	 * 
	 * @return array Array representation of this object.
	 */
	public function as_array() {
		$arr = array(
			"id" => $this->id,
			"mac_addr" => $this->mac_addr,
			"hostname" => $this->hostname,
			"type" => null,
			"model" => null,
			"os" => null
		);

		// Device type.
		if (!is_null($this->type))
			$arr["type"] = $this->type->as_array();

		// Device model.
		if (!is_null($this->model))
			$arr["model"] = $this->model->as_array();

		// Operating system.
		if (!is_null($this->os))
			$arr["os"] = $this->os->as_array();

		return $arr;
	}
}

?>
