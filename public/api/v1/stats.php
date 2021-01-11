<?php
/**
 * stats.php
 * API endpoint for getting statistics.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE;
require __DIR__ . "/../../../vendor/autoload.php";

// Global variables.
$stats = null;

/**
 * Main entry point.
 */
function main() {
	global $stats;

	// Check required parameters and initialize the Statistics object.
	check_required_params();
	$stats = new Statistics();

	switch ($_GET["info"]) {
		case "all":
			get_everything();
			break;
		case "floors":
			get_floors();
			break;
		default:
			check_required_params("info", "invalid");
	}
}

/**
 * Lists all of the available devices.
 */
function get_everything() {
	global $stats;

	// Build the base response.
	$response = array(
		"info" => array(
			"level" => 2,
			"status" => "ok",
			"message" => "Got everything for ya."
		),
		"stats" => $stats->as_array()
	);

	// Send response.
	http_response_code(200);
	header("Content-Type: application/json");
	echo json_encode($response);
}

/**
 * Lists all the floors in details.
 */
function get_floors() {
	global $stats;

	// Build the base response.
	$response = array(
		"info" => array(
			"level" => 2,
			"status" => "ok",
			"message" => "Got everything for ya."
		),
		"floors" => $stats->get_floors_detailed()
	);

	// Send response.
	http_response_code(200);
	header("Content-Type: application/json");
	echo json_encode($response);
}

/**
 * Check if we got the required parameters and automatically fails if they are
 * not present.
 * 
 * @param string $info   Specific info we need to check parameters for.
 * @param string $option Some option for an info.
 */
function check_required_params($info = null, $option = null) {
	header("Content-Type: application/json");

	// Doing the initial checks?
	if (is_null($info)) {
		// User requested with a method that is not supported by us.
		if ($_SERVER["REQUEST_METHOD"] != "GET") {
			http_response_code(400);
			header("Content-Type: application/json");
			echo json_encode(array(
				"info" => array(
					"level" => 0,
					"status" => "error",
					"message" => "Invalid request method " . $_SERVER["REQUEST_METHOD"] . "."
				)
			));

			exit(1);
		}

		// Check if we have the required info parameter.
		if (!(isset($_GET["info"]))) {
			http_response_code(424);
			header("Content-Type: application/json");
			echo json_encode(array(
				"info" => array(
					"level" => 0,
					"status" => "error",
					"message" => "Required parameter 'info' wasn't specified."
				)
			));

			exit(1);
		}

		return;
	}

	if ($info == "info") {
		if ($option == "invalid") {
			// Invalid info parameter.
			http_response_code(400);
			echo json_encode(array(
				"info" => array(
					"level" => 0,
					"status" => "error",
					"message" => "Invalid 'info' value."
				)
			));

			exit(1);
		}
	}
}

main();

?>
