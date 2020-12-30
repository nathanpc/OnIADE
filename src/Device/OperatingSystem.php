<?php
/**
 * OperatingSystem.php
 * Abstraction class for a device operating system.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE\Device;
require __DIR__ . "/../../vendor/autoload.php";
use PDO;

class OperatingSystem {
	private $id;
	private $name;
	private $version;
	private $family;

	/**
	 * Device operating system object constructor.
	 * 
	 * @param int    $id      Device operating system ID.
	 * @param string $name    Its proper name.
	 * @param string $version OS version.
	 * @param string $family  OS family.
	 */
	public function __construct($id = null, $name = null, $version = null,
			$family = null) {
		$this->id = $id;
		$this->name = $name;
		$this->version = $version;
		$this->family = $family;
	}

	/**
	 * Creates a new device OS and automatically saves it to the database.
	 * 
	 * @param  string          $name    Its proper name.
	 * @param  string          $version OS version.
	 * @param  string          $family  OS family.
	 * @return OperatingSystem          Populated device OS object.
	 */
	public static function Create($name = null, $version = null, $family = null) {
		$os = new OperatingSystem(null, $name, $version, $family);
		$os->save();

		return $os;
	}

	/**
	 * Creates (unless already exists) a new device operating system from a
	 * \WhichBrowser\Parser object and saves it to the database.
	 * 
	 * @param  \WhichBrowser\Parser $parser       Browser parser.
	 * @param  boolean              $check_exists Check if it has been created
	 *                                            before?
	 * @return OperatingSystem                    Populated device OS object.
	 */
	public static function CreateFromBrowserParser($parser, $check_exists = true) {
		// Get our parameters.
		$name = $parser->os->name;
		$version = $parser->os->version->alias;
		$family = $parser->os->family;

		// Check if it exists before.
		if ($check_exists) {
			$os = OperatingSystem::FromDetails($name, $version, $family);
			if (!is_null($os))
				return $os;
		}

		// Create and return it.
		return OperatingSystem::Create($name, $version, $family);
	}

	/**
	 * Creates and populates this object from the database by providing an ID.
	 * 
	 * @param  int             $id Device operating system ID.
	 * @return OperatingSystem     Populated object or NULL if the ID wasn't
	 *                             found.
	 */
	public static function FromID($id) {
		// Get device OS from database.
		$dbh = \OnIADE\Database::connect();
		$query = $dbh->prepare("SELECT * FROM operating_systems WHERE id = :id LIMIT 1");
		$query->bindValue(":id", $id);
		$query->execute();
		$os = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if the ID was invalid.
		if (empty($os))
			return null;
		
		// Create a new device OS object.
		$os = $os[0];
		return new OperatingSystem($os["id"], $os["name"], $os["version"], $os["family"]);
	}

	/**
	 * Creates and populates this object from the database by providing full
	 * information about it.
	 * 
	 * @param  string          $name    Operating system name.
	 * @param  string          $version OS version.
	 * @param  string          $family  OS family.
	 * @return OperatingSystem          Populated object or NULL if it wasn't
	 *                                  found.
	 */
	public static function FromDetails($name, $version, $family) {
		// Build database query statement.
		$dbh = \OnIADE\Database::connect();
		$query = $dbh->prepare("SELECT * FROM operating_systems WHERE (name = :name) AND (version = :version) AND (family <=> :family) LIMIT 1");

		// Bind parameters and execute.
		$query->bindValue(":name", $name);
		$query->bindValue(":version", $version);
		$query->bindValue(":family", $family);
		$query->execute();
		$os = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if the information was invalid.
		if (empty($os))
			return null;
		
		// Create a new device OS object.
		$os = $os[0];
		return new OperatingSystem($os["id"], $os["name"], $os["version"], $os["family"]);
	}

	/**
	 * Checks if a device operating system exists in the database already.
	 * 
	 * @param  string  $name    Operating system name.
	 * @param  string  $version OS version.
	 * @param  string  $family  OS family.
	 * @return boolean          Does the operating system exist?
	 */
	public static function Exists($name, $version, $family) {
		$os = OperatingSystem::FromDetails($name, $version, $family);
		return !is_null($os);
	}

	/**
	 * Saves this device operating system object into the database. This will
	 * create a new record if an ID wasn't set previously.
	 */
	public function save() {
		// Get database handle.
		$dbh = \OnIADE\Database::connect();
		$stmt = null;

		// Check if we are creating a new device model or updating one.
		if (is_null($this->id)) {
			// Creating a new model.
			$stmt = $dbh->prepare("INSERT INTO operating_systems(name, version, family) VALUES (:name, :version, :family)");
		} else {
			// Update an existing model.
			$stmt = $dbh->prepare("UPDATE operating_systems SET name = :name, version = :version, family = :family WHERE id = :id");
			$stmt->bindValue(":id", $this->id);
		}

		// Bind parameters and execute.
		$stmt->bindValue(":name", $this->name);
		$stmt->bindValue(":version", $this->version);
		$stmt->bindValue(":family", $this->family);
		$stmt->execute();

		// Set the ID.
		if (is_null($this->id))
			$this->id = $dbh->lastInsertId();
	}

	/**
	 * Gets the device model ID.
	 * 
	 * @return int Device model ID.
	 */
	public function get_id() {
		return $this->id;
	}
}

?>
