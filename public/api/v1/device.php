<?php
/**
 * device.php
 * API endpoint for getting information of a device or adding one to the device
 * history.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

require_once(__DIR__ . "/../../../src/Device.php");

// Set the content type.
header("Content-Type: application/json");

/**
 * Check if we got the required parameters and automatically fails if they are
 * not present.
 */
function check_required_params() {
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// We've found a device and want to add it to the history.
		if (!isset($_POST["macaddr"])) {
			http_response_code(424);
			echo json_encode(array(
				"info" => array(
					"level" => 0,
					"status" => "error",
					"message" => "Required parameter 'macaddr' wasn't specified."
				)
			));

			exit(1);
		}
	} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
		// We want some information about a device.
		if (!isset($_GET["id"])) {
			http_response_code(424);
			echo json_encode(array(
				"info" => array(
					"level" => 0,
					"status" => "error",
					"message" => "Required parameter 'id' wasn't specified."
				)
			));

			exit(1);
		}
	} else {
		// User requested with a method that is not supported by us.
		http_response_code(400);
		echo json_encode(array(
			"info" => array(
				"level" => 0,
				"status" => "error",
				"message" => "Invalid request method " .
					$_SERVER["REQUEST_METHOD"] . "."
			)
		));

		exit(1);
	}
}

// Check if we have everything we need.
check_required_params();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
	// User wants information about a device.
	$device = Device::FromID($_GET["id"]);

	// Check if we didn't get a device.
	if (is_null($device)) {
		http_response_code(400);
		echo json_encode(array(
			"info" => array(
				"level" => 0,
				"status" => "error",
				"message" => "Couldn't find a device with ID " . $_GET["id"] . "."
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
	echo json_encode($response);
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
	// Edge device wants to add a device found in the network to the history.
	http_response_code(200);
	echo json_encode(array(
		"info" => array(
			"level" => 2,
			"status" => "ok",
			"message" => "Device added to history."
		)
	));
}

?>
