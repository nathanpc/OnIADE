<?php
/**
 * Exporter.php
 * A way to let us export data from the database to some of the most common
 * data exchange formats available.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE\Utilities;
require __DIR__ . "/../../vendor/autoload.php";
use OnIADE\Database;
use OnIADE\Floor;
use OnIADE\Device;
use OnIADE\History;
use OnIADE\Contribution;

class Exporter {
	private $floors;
	private $devices;
	private $types;
	private $models;
	private $oses;
	private $history;

	/**
	 * Exporter object constructor. Just gets us a nice clean slate to work
	 * with.
	 */
	public function __construct() {
		$this->floors = null;
		$this->devices = null;
		$this->types = null;
		$this->models = null;
		$this->oses = null;
		$this->history = null;
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
	 * Gets the list of device entries in the history.
	 * 
	 * @return array Array of {@link Entry} objects.
	 */
	public function get_device_history() {
		if (is_null($this->history))
			$this->history = History\Entry::List(null, true, true);

		return $this->history;
	}

	/**
	 * Exports the data as JSON.
	 * 
	 * @return string JSON data.
	 */
	public function as_json() {
		return json_encode($this->as_array());
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
			"device_history" => array()
		);

		// Populate the array and return.
		$this->populate_array($arr);
		return $arr;
	}

	/**
	 * Populates a specially crafted array with information from the database.
	 * 
	 * @param assoc Array to be populated with data.
	 */
	private function populate_array(&$arr) {
		// This was a nice hack. I'm proud to have wrote it.
		foreach ($arr as $key => $value) {
			// Go through getters adding them as arrays to the master array.
			foreach ([$this, "get_$key"]() as $item) {
				array_push($arr[$key], $item->as_array());
			}
		}
	}
}

?>
