<?php
/**
 * device.php
 * API endpoint for getting information of a device or adding one to the device
 * history.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE;
require __DIR__ . "/../../../vendor/autoload.php";

/**
 * Main entry point.
 */
function main() {
	header("Content-Type: application/json");
	check_required_params();
	
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$device = null;

		// Check if we just want a device list.
		if (!isset($_GET["id"]) && !isset($_GET["macaddr"])) {
			// Just list the devices in the database.
			list_devices();
			return;
		}

		// User wants information about a device.
		check_required_params("get_device");
		if (isset($_GET["id"])) {
			$device = Device::FromID($_GET["id"]);
		} else if (isset($_GET["macaddr"])) {
			$device = Device::FromMACAddress($_GET["macaddr"]);
		}

		// Send device information.
		get_device($device);
	} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// Edge device wants to add a device found in the network to the history.
		check_required_params("add_device_entry");
		add_device_entry();
	}
}

/**
 * Gets a history of the devices in a specific timespan and floor.
 * 
 * @param Floor $floor Floor to filter devices by.
 */
function get_history($floor = null) {
	// Build the base response.
	http_response_code(200);
	$response = array(
		"info" => array(
			"level" => 2,
			"status" => "ok",
			"message" => "Devices history found in database."
		),
		"list" => array()
	);

	// Go through devices adding them as arrays to the response.
	foreach (History\Entry::List($floor) as $entry) {
		array_push($response["list"], $entry->as_array());
	}

	// Send response.
	echo json_encode($response);
}

/**
 * Lists all of the available devices.
 */
function list_devices() {
	// Build the base response.
	http_response_code(200);
	$response = array(
		"info" => array(
			"level" => 2,
			"status" => "ok",
			"message" => "Devices found in database."
		),
		"list" => array()
	);

	// Go through devices adding them as arrays to the response.
	foreach (Device::List() as $device) {
		array_push($response["list"], $device->as_array());
	}

	// Send response.
	echo json_encode($response);
}

/**
 * Sends information about a specific device.
 * 
 * @param Device $device Device to show information about.
 */
function get_device($device = null) {
	// Check if we didn't get a device.
	if (is_null($device)) {
		http_response_code(400);
		echo json_encode(array(
			"info" => array(
				"level" => 0,
				"status" => "error",
				"message" => "Couldn't find the requested device."
			)
		));

		return;
	}

	// Got ya a device.
	http_response_code(200);
	$response = array(
		"info" => array(
			"level" => 2,
			"status" => "ok",
			"message" => "Device found in database."
		),
		"device" => $device->as_array()
	);

	// Send device information.
	echo json_encode($response);
}

/**
 * Adds a device entry to the history and also adds the device to the database
 * if it doesn't exist.
 */
function add_device_entry() {
	$device = null;
	$floor = null;
	
	// Check if the device isn't already in the database.
	$device = Device::FromMACAddress($_POST["macaddr"]);
	if (is_null($device)) {
		// Looks like we have a new one. Add it to the list first.
		$device = new Device(null, $_POST["macaddr"]);
		$device->save();
	}

	// Set hostname to device if available.
	if (isset($_POST["hostname"])) {
		$device->set_hostname($_POST["hostname"]);
		$device->save();
	}

	// Check if the floor exists.
	$floor = Floor::FromNumber($_POST["floor"]);
	if (is_null($floor)) {
		http_response_code(400);
		echo json_encode(array(
			"info" => array(
				"level" => 0,
				"status" => "error",
				"message" => "Couldn't find a floor with a number of '" .
					$_POST["floor"] . "'. Device won't be added to history."
			)
		));

		return;
	}

	// Create a new device entry.
	$entry = History\Entry::Create($device, $floor, $_POST["ipaddr"]);

	http_response_code(200);
	echo json_encode(array(
		"info" => array(
			"level" => 2,
			"status" => "ok",
			"message" => "Device added to history."
		),
		"entry" => $entry->as_array()
	));
}

/**
 * Check if we got the required parameters and automatically fails if they are
 * not present.
 * 
 * @param string $stage Specific stage we need to check parameters for.
 */
function check_required_params($stage = null) {
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if ($stage == "add_device_entry") {
			// We've found a device and want to add it to the history.
			if (!(isset($_POST["macaddr"]) && isset($_POST["floor"]) && isset($_POST["ipaddr"]))) {
				http_response_code(424);
				echo json_encode(array(
					"info" => array(
						"level" => 0,
						"status" => "error",
						"message" => "Required parameter 'macaddr', 'ipaddr', or 'floor' wasn't specified."
					)
				));

				exit(1);
			}
		}
	} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
		if ($stage == "get_device") {
			// We want some information about a device.
			if (!(isset($_GET["id"]) || isset($_GET["macaddr"]))) {
				http_response_code(424);
				echo json_encode(array(
					"info" => array(
						"level" => 0,
						"status" => "error",
						"message" => "Required parameter 'id' or 'macaddr' wasn't specified."
					)
				));

				exit(1);
			}
		}
	} else {
		// User requested with a method that is not supported by us.
		http_response_code(400);
		echo json_encode(array(
			"info" => array(
				"level" => 0,
				"status" => "error",
				"message" => "Invalid request method " . $_SERVER["REQUEST_METHOD"] . "."
			)
		));

		exit(1);
	}
}

main();
?>
