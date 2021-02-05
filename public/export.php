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

switch ($_GET["format"]) {
	case "json":
		header("Content-Type: application/json");
		echo $exporter->as_json();
		break;
	case "csv":
		header("Content-Type: text/plain");
		echo $exporter->as_csv();
		break;
	default:
		general_error_response();
}

?>
