<?php
/**
 * Statistics.php
 * A simple class to help us compile and calculate statistics on the data we
 * currently have available.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE;
require __DIR__ . "/../vendor/autoload.php";
use OnIADE\Database;
use PDO;

class Statistics {
	private $devices;
	private $types;
	private $models;
	private $oses;
	private $last_entries;

	/**
	 * Statistics object constructor. This will fetch all the basic information
	 * needed from the database.
	 */
	public function __construct() {
		$this->devices = Device::List();
		$this->types = Device\Type::List();
		$this->models = Device\Model::List();
		$this->oses = Device\OperatingSystem::List();
		$this->last_entries = History\Entry::List();
	}

	/**
	 * Gets a list of devices.
	 * 
	 * @return array Array of {@link Device} objects.
	 */
	public function get_devices() {
		return $this->devices;
	}

	/**
	 * Gets a list of device types.
	 * 
	 * @return array Array of {@link Type} objects.
	 */
	public function get_device_types() {
		return $this->types;
	}

	/**
	 * Gets a list of device models.
	 * 
	 * @return array Array of {@link Model} objects.
	 */
	public function get_device_models() {
		return $this->models;
	}

	/**
	 * Gets a list of device operating systems.
	 * 
	 * @return array Array of {@link OperatingSystem} objects.
	 */
	public function get_device_oses() {
		return $this->oses;
	}

	/**
	 * Gets the list of last device entries in the history.
	 * 
	 * @return array Array of {@link Entry} objects.
	 */
	public function get_last_entries() {
		return $this->last_entries;
	}

	/**
	 * Array representation of this object. Perfect for use in JSON responses.
	 * 
	 * @return array Array representation of this object.
	 */
	public function as_array() {
		$arr = array(
			"devices" => $this->devices,
			"types" => $this->types,
			"models" => $this->models,
			"oses" => $this->oses,
			"last_entries" => $this->last_entries
		);

		// TODO: Go through each array and populate them with the actual arrays,
		//       not the objects.

		return $arr;
	}
}

?>
