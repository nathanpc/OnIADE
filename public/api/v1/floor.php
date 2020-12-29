<?php
/**
 * floor.php
 * API endpoint for getting information about floors.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

require_once(__DIR__ . "/../../../src/Floor.php");

// Set the content type.
header("Content-Type: application/json");

/**
 * Check if we got the required parameters and automatically fails if they are
 * not present.
 */
function check_required_params($stage = null) {
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		if ($stage == "specific_floor") {
			// Getting a specific floor.
			if (!(isset($_GET["id"]) || isset($_GET["number"]))) {
				http_response_code(424);
				echo json_encode(array(
					"info" => array(
						"level" => 0,
						"status" => "error",
						"message" => "Required parameter 'id' or 'number' wasn't specified."
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

check_required_params();
if ($_SERVER["REQUEST_METHOD"] == "GET") {
	// Check if the user wants a list of the floors.
	if (!isset($_GET["id"]) && !isset($_GET["number"])) {
		// Build the base response.
		http_response_code(200);
		$response = array(
			"info" => array(
				"level" => 2,
				"status" => "ok",
				"message" => "Floor found in database."
			),
			"list" => array()
		);

		// Go through floors adding them as arrays to the response.
		foreach (Floor::List() as $floor) {
			array_push($response["list"], $floor->as_array());
		}

		// Send response.
		echo json_encode($response);
		return;
	}

	// User wants information about a floor.
	check_required_params("specific_floor");
	$floor = null;
	if (isset($_GET["id"])) {
		$floor = Floor::FromID($_GET["id"]);
	} else if (isset($_GET["number"])) {
		$floor = Floor::FromNumber($_GET["number"]);
	}

	// Check if we didn't get a floor.
	if (is_null($floor)) {
		http_response_code(400);
		echo json_encode(array(
			"info" => array(
				"level" => 0,
				"status" => "error",
				"message" => "Couldn't find the requested floor."
			)
		));

		return;
	}

	// Got ya a floor.
	http_response_code(200);
	$response = array(
		"info" => array(
			"level" => 2,
			"status" => "ok",
			"message" => "Floor found in database."
		),
		"floor" => $floor->as_array()
	);
	echo json_encode($response);
}

?>
