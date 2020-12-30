<?php
/**
 * Database.php
 * A simple class to help us deal with the application's database.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE;
require __DIR__ . "/../vendor/autoload.php";
use PDO;

class Database {
	/**
	 * Connects to the database and returns a PDO object.
	 * 
	 * @return PDO PDO object of the project database.
	 */
	public static function connect() {
		// Fetch the configuration file.
		$config = require(__DIR__ . "/../config/config.php");
		$config = $config->database;

		// Build a data source name and create PDO.
		$dsn = "mysql:host=" . $config->host . ";dbname=" . $config->dbname;
		return new PDO($dsn, $config->user, $config->password);
	}

	/**
	 * Generates an HTML table with the contents of a specified table in the
	 * database.
	 * 
	 * @param  string $name Queried table name.
	 * @return string       HTML representation of the database table.
	 */
	public static function get_html_table($name) {
		$html = "<table class='table table-striped table-hover'>";
		$dbh = \OnIADE\Database::connect();

		// Get table columns.
		$query = $dbh->prepare("DESCRIBE $name");
		$query->execute();
		$cols = $query->fetchAll(PDO::FETCH_COLUMN);

		// Build the table header.
		$html .= "<thead><tr>";
		foreach ($cols as $col) {
			$html .= "<th scope='col'>$col</th>";
		}
		$html .= "</tr></thead><tbody>";

		// Get table contents.
		$query = $dbh->prepare("SELECT * FROM $name");
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);

		// Build the table rows.
		foreach ($rows as $row) {
			$html .= "<tr>";

			foreach ($cols as $col) {
				// Check for NULLs.
				if (is_null($row[$col]))
					$row[$col] = "<i>NULL</i>";

				// Place the cell into the table.
				$html .= "<td>" . $row[$col] . "</th>";
			}

			$html .= "</tr>";
		}

		// Build the table body.
		$html .= "</tbody></table>";

		return $html;
	}
}

?>
