<?php 
/**
 * Device.php
 * An abstraction class to work with a single device in the system.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE;
require __DIR__ . "/../vendor/autoload.php";
use OnIADE\Utilities\DateTimeUtil;
use OnIADE\History;
use PDO;
use DateTime;

class Device {
	private $id;
	private $mac_addr;
	private $hostname;
	private $type;
	private $model;
	private $os;
	private $ignored;

	/**
	 * Device class constructor.
	 * 
	 * @param int                    $id       Device ID.
	 * @param string                 $mac_addr MAC address.
	 * @param string                 $hostname Hostname.
	 * @param boolean                $ignored  Ignore this device when listing?
	 * @param Device\Type            $type     Device type.
	 * @param Device\Model           $model    Device model.
	 * @param Device\OperatingSystem $os       Device operating system.
	 */
	public function __construct($id = null, $mac_addr = null, $hostname = null,
			$ignored = false, $type = null, $model = null, $os = null) {
		$this->id = $id;
		$this->mac_addr = $mac_addr;
		$this->hostname = $hostname;
		$this->ignored = $ignored;
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
			(bool)$dev["ignored"], Device\Type::FromID($dev["type_id"]),
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
			(bool)$dev["ignored"], Device\Type::FromID($dev["type_id"]),
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
			$stmt = $dbh->prepare("INSERT INTO devices(mac_addr, hostname, type_id, model_id, os_id, ignored) VALUES (:mac_addr, :hostname, :type_id, :model_id, :os_id, :ignored)");
		} else {
			// Update an existing device.
			$stmt = $dbh->prepare("UPDATE devices SET mac_addr = :mac_addr, hostname = :hostname, type_id = :type_id, model_id = :model_id, os_id = :os_id, ignored = :ignored WHERE id = :id");
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
		$stmt->bindValue(":ignored", (int)$this->ignored);
		$stmt->execute();

		// Set the device ID.
		if (is_null($this->id))
			$this->id = $dbh->lastInsertId();
	}

	/**
	 * Gets the amount of time a device has been online on the network for a
	 * given period.
	 * 
	 * @param  string $period Period to calculate the time for. (This can be:
	 *                        "today", "week", "month", "year", "ever")
	 * @return int            Number of minutes a device has been online.
	 */
	public function get_time_online($period = "today") {
		$config = require(__DIR__ . "/../config/config.php");
		$dbh = Database::connect();
		$last = History\Entry::LastEntry();

		// Get the past timestamp from last entry.
		$dt = $last->get_timestamp();
		switch ($period) {
			case "today":
				$dt->modify("today");
				break;
			case "week":
				$dt->modify("Sunday this week");
				break;
			case "month":
				$dt->modify("first day of");
				break;
			case "year":
				$dt->modify("first day of January");
				break;
			case "ever":
				$dt->modify("first day of March 2020");
				break;
			default:
				return null;
		}

		// Prepare and execute query.
		$query = $dbh->prepare("SELECT COUNT(*) FROM device_history WHERE device_id = :device_id AND dt > :dt");
		$query->bindValue(":device_id", $this->get_id());
		$query->bindValue(":dt", DateTimeUtil::mysql_format($dt));
		$query->execute();

		// Return time online.
		return (int)($query->fetchColumn()) * $config->scanner->interval;
	}

	/**
	 * Gets the amount of time a device has been online on the network for the
	 * current day.
	 * 
	 * @return int Number of minutes a device has been online today.
	 */
	public function get_time_online_today() {
		return $this->get_time_online("today");
	}

	/**
	 * Gets a list of HTML device flairs.
	 * 
	 * @return array Array of HTML device flairs.
	 */
	public function get_flairs() {
		$flairs = array();

		// Device type.
		if (!is_null($this->type))
			array_push($flairs, $this->type->as_flair());

		// Operating system.
		if (!is_null($this->os))
			array_push($flairs, $this->os->as_flair());

		return $flairs;
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
	 * Sets the ignored parameter.
	 * 
	 * @param boolean $ignored Should we ignore this device when listing?
	 */
	public function set_ignored($ignored) {
		$this->ignored = $ignored;
	}

	/**
	 * Checks if this device should be ignored when listing.
	 * 
	 * @return boolean Should this device be ignored when listing?
	 */
	public function is_ignored() {
		return $this->ignored;
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
			"ignored" => (bool)$this->ignored,
			"time_online" => array(
				"today" => $this->get_time_online("today"),
				"week" => $this->get_time_online("week"),
				"month" => $this->get_time_online("month"),
				"year" => $this->get_time_online("year"),
				"ever" => $this->get_time_online("ever")
			),
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
