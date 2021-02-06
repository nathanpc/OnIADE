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
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use OnIADE\Database;
use OnIADE\Floor;
use OnIADE\Device;
use OnIADE\History;
use OnIADE\Contribution;

class Exporter {
	const CSV_KEY_SEPARATOR = "#";

	private $floors;
	private $devices;
	private $types;
	private $models;
	private $oses;
	private $history;
	private $scope;

	/**
	 * Exporter object constructor. Just gets us a nice clean slate to work
	 * with.
	 */
	public function __construct($scope = "everything") {
		$this->floors = null;
		$this->devices = null;
		$this->types = null;
		$this->models = null;
		$this->oses = null;
		$this->history = null;
		$this->scope = $scope;
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
	 * Exports the data as CSV.
	 * 
	 * @return string CSV data.
	 */
	public function as_csv() {
		$arr = $this->as_array();
		$buffer = "";
		$headers = array();

		// Check if we can export a CSV from this data.
		if ((count($arr) > 1) || empty($arr))
			return null;
		$arr = reset($arr);

		// Go through array trying to find the most complete example of an item.
		foreach ($arr as $item) {
			$tmp_headers = $this->get_csv_header_fields($item);
			if (count($tmp_headers) > count($headers))
				$headers = $tmp_headers;
		}

		// Build document header.
		foreach ($headers as $field) {
			$buffer .= "\"$field\",";
		}
		$buffer .= "\n";

		// Build the rows in the document.
		foreach ($arr as $item) {
			foreach ($headers as $field) {
				$buffer .= "\"" . 
					$this->get_csv_field_from_path($item, $field) . "\",";
			}

			$buffer .= "\n";
		}

		return $buffer;
	}

	/**
	 * Exports a database dump as plain SQL.
	 * 
	 * @param  array  $tables  Tables to dump from the database.
	 * @param  int    $timeout Command execution timeout in seconds.
	 * @return string          SQL database dump.
	 */
	public function as_db_dump($tables, $timeout = 20) {
		// Get application configuration and build the command array.
		$config = require(__DIR__ . "/../../config/config.php");
		$command = array_merge([ $config->database->binpath . "mysqldump", "-u",
			$config->database->user, "-p" . $config->database->password,
			$config->database->dbname ], $tables);

		// Setup the command to dump the database.
		$process = new Process($command);
		$process->setTimeout($timeout);

		try {
			// Run MySQL dump and return our nice dump.
			$process->mustRun();
			return $process->getOutput();
		} catch (ProcessTimedOutException $e) {
			return "ERROR: Database dump command took too long to finish.";
		} catch (ProcessFailedException $e) {
			return "ERROR: Failed to run the database dump command.\n\n" .
				$e->getMessage();
		}
	}

	/**
	 * Array representation of this object. Perfect for use in JSON responses.
	 * 
	 * @return array Array representation of this object.
	 */
	public function as_array() {
		$arr = array();

		switch ($this->scope) {
			case "everything":
				$arr = array(
					"floors" => array(),
					"devices" => array(),
					"device_types" => array(),
					"device_models" => array(),
					"device_oses" => array(),
					"device_history" => array()
				);
				break;
			case "uniquedevs":
				$arr = array(
					"devices" => array()
				);
				break;
			case "history":
				$arr = array(
					"device_history" => array()
				);
				break;
		}

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

	/**
	 * Gets a list with CSV header fields.
	 * 
	 * @param  assoc  $item   An item to have its headers extracted.
	 * @param  string $prefix The prefix the header should have.
	 * @return array          List of headers.
	 */
	private function get_csv_header_fields($item, $prefix = null) {
		$fields = array();

		// Go through the associative array getting its fields.
		foreach ($item as $key => $value) {
			if (is_array($item[$key])) {
				// Make sure we have a valid next prefix for really nested data.
				$next_prefix = $key;
				if (!is_null($prefix))
					$next_prefix = $prefix . Exporter::CSV_KEY_SEPARATOR . $key;

				// Yay! Recursion!
				$fields = array_merge($fields,
					$this->get_csv_header_fields($item[$key], $next_prefix));
				continue;
			}

			if (is_null($prefix)) {
				array_push($fields, $key);
			} else {
				array_push($fields, $prefix . Exporter::CSV_KEY_SEPARATOR . $key);
			}
		}

		return $fields;
	}

	/**
	 * Gets a CSV string field from an associative array path separated by
	 * CSV_KEY_SEPARATOR.
	 * 
	 * @param  assoc  $item  Item to have it's field found.
	 * @param  string $apath Associative array path separated by
	 *                       CSV_KEY_SEPARATOR.
	 * @return string        CSV field string. Empty string if no item found.
	 */
	private function get_csv_field_from_path($item, $apath) {
		$element = $item;
		$fields = explode(Exporter::CSV_KEY_SEPARATOR, $apath);

		// Go through nested associative arrays to find the field.
		foreach ($fields as $index) {
			// Check if it exists.
			if (!isset($element[$index]))
				return "";

			$element = $element[$index];
		}

		// Escape double-quotes inside field.
		return str_replace("\"", "\"\"", $element);
	}
}

?>
