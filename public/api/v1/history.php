<?php
/**
 * history.php
 * API endpoint for the device history.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE;
require __DIR__ . "/../../../vendor/autoload.php";

/**
 * Main entry point.
 */
function main() {
	// Check required parameters.
	check_required_params();

	// Get last entries.
	get_last_entries();
}

/**
 * Lists all of the devices from our last network scan.
 */
function get_last_entries() {
	// Check for a specific floor.
	$floor = null;
	if (isset($_GET["floor"]))
		$floor = $_GET["floor"];

	// Get last entries.
	$entries = History\Entry::List($floor);

	// Build the base response.
	$response = array(
		"info" => array(
			"level" => 2,
			"status" => "ok",
			"message" => "Got everything for ya."
		),
		"entries" => array()
	);

	// Go through entries making them regular arrays.
	foreach ($entries as $entry) {
		array_push($response["entries"], $entry->as_array());
	}

	// Send response.
	http_response_code(200);
	header("Content-Type: application/json");
	echo json_encode($response);
}

/**
 * Check if we got the required parameters and automatically fails if they are
 * not present.
 */
function check_required_params() {
	header("Content-Type: application/json");

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
}

main();

?>
