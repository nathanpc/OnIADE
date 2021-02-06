<?php
/**
 * export.php
 * Where we actually let users export data to use on their own.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE;
require __DIR__ . "/../vendor/autoload.php";
use OnIADE\Utilities\Exporter;

/**
 * Just throws a general error and gives up.
 */
function general_error_response() {
	http_response_code(400);
	die();
}

// Check if we have all the required parameters.
if ((!isset($_GET["format"])) || (!isset($_GET["data"])) ||
		($_SERVER["REQUEST_METHOD"] != "GET"))
	general_error_response();

// Create a new exporter object.
$exporter = new Exporter($_GET["data"]);
if (is_null($exporter))
	general_error_response();

// Check which format we need to export in.
switch ($_GET["format"]) {
	case "json":
		header("Content-Type: application/json");
		echo $exporter->as_json();
		break;
	case "csv":
		header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=\"export.csv\"");
		echo $exporter->as_csv();
		break;
	case "mysql":
		header("Content-Type: application/sql");
		header("Content-Disposition: attachment; filename=\"export.sql\"");
		echo $exporter->as_db_dump([ "floors", "operating_systems",
			"device_types", "device_models", "devices", "request_headers",
			"device_history" ]);
		break;
	default:
		general_error_response();
}

?>
