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
	private $floors;
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
		$this->floors = null;
		$this->devices = null;
		$this->types = null;
		$this->models = null;
		$this->oses = null;
		$this->last_entries = null;
	}

	/**
	 * Gets detailed information about a floor.
	 * 
	 * @return array Array with detailed information about each floor.
	 */
	public function get_floors_detailed() {
		$floors = array();

		// Go through floors getting detailed information on each of them.
		foreach ($this->get_floors() as $floor) {
			$info = $floor->as_array();
			$info["entries"] = array();

			// Populate entries.
			$entries = History\Entry::List($floor);
			foreach ($entries as $entry)
				array_push($info["entries"], $entry->as_array());

			// Push this information into the floors array.
			array_push($floors, $info);
		}

		return $floors;
	}

	/**
	 * Gets a list of floors.
	 * 
	 * @return array Array of {@link Floor} objects.
	 */
	public function get_floors() {
		if (is_null($this->floors))
			$this->floors = Floor::List();

		return $this->floors;
	}

	/**
	 * Gets a list of devices.
	 * 
	 * @return array Array of {@link Device} objects.
	 */
	public function get_devices() {
		if (is_null($this->devices))
			$this->devices = Device::List();

		return $this->devices;
	}

	/**
	 * Gets a list of device types.
	 * 
	 * @return array Array of {@link Type} objects.
	 */
	public function get_device_types() {
		if (is_null($this->types))
			$this->types = Device\Type::List();

		return $this->types;
	}

	/**
	 * Gets a list of device models.
	 * 
	 * @return array Array of {@link Model} objects.
	 */
	public function get_device_models() {
		if (is_null($this->models))
			$this->models = Device\Model::List();

		return $this->models;
	}

	/**
	 * Gets a list of device operating systems.
	 * 
	 * @return array Array of {@link OperatingSystem} objects.
	 */
	public function get_device_oses() {
		if (is_null($this->oses))
			$this->oses = Device\OperatingSystem::List();

		return $this->oses;
	}

	/**
	 * Gets the list of last device entries in the history.
	 * 
	 * @return array Array of {@link Entry} objects.
	 */
	public function get_last_entries() {
		if (is_null($this->last_entries))
			$this->last_entries = History\Entry::List();

		return $this->last_entries;
	}

	/**
	 * Array representation of this object. Perfect for use in JSON responses.
	 * 
	 * @return array Array representation of this object.
	 */
	public function as_array() {
		$arr = array(
			"floors" => array(),
			"devices" => array(),
			"device_types" => array(),
			"device_models" => array(),
			"device_oses" => array(),
			"last_entries" => array()
		);

		// This was a nice hack. I'm proud to have wrote it.
		foreach ($arr as $key => $value) {
			// Go through getters adding them as arrays to the master array.
			foreach ([$this, "get_$key"]() as $item) {
				array_push($arr[$key], $item->as_array());
			}
		}

		return $arr;
	}
}

?>
