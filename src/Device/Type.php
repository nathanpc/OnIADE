<?php
/**
 * Type.php
 * Abstraction class for a device type.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE\Device;
require __DIR__ . "/../../vendor/autoload.php";
use PDO;

class Type {
	private $id;
	private $key;
	private $name;
	private $subtype;
	private $icon;

	/**
	 * Device type object constructor.
	 * 
	 * @param int     $id       Device type ID.
	 * @param string  $key      Device type key.
	 * @param string  $name     Human-readable device type name.
	 * @param string  $subtype  Device sub-type.
	 * @param Icon    $icon     Device type icon.
	 */
	public function __construct($id = null, $key = null, $name = null,
			$subtype = null, $icon = null) {
		$this->id = $id;
		$this->key = $key;
		$this->name = $name;
		$this->subtype = $subtype;
		$this->icon = $icon;

		// Handle empty icons.
		if (is_null($this->icon))
			$this->icon = new Icon();
	}

	/**
	 * Creates a new device type and automatically saves it to the database.
	 * 
	 * @param  string  $key      Device type key.
	 * @param  string  $name     Human-readable device type name.
	 * @param  string  $subtype  Device sub-type.
	 * @return Type              Populated device type object.
	 */
	public static function Create($key, $name = null, $subtype = null,
			$icon = null) {
		$type = new Type(null, $key, $name, $subtype, $icon);
		$type->save();

		return $type;
	}

	/**
	 * Creates (unless already exists) a new device type from a
	 * \WhichBrowser\Parser object and saves it to the database.
	 * 
	 * @param  \WhichBrowser\Parser $parser       Browser parser.
	 * @param  boolean              $check_exists Check if it has been created
	 *                                            before?
	 * @return Type                               Populated device type object.
	 */
	public static function CreateFromBrowserParser($parser, $check_exists = true) {
		// Get our parameters.
		$key = $parser->device->type;
		$name = ucwords($key);
		$subtype = $parser->device->subtype;

		// Check if it exists before.
		if ($check_exists) {
			$type = Type::FromKey($key);
			if (!is_null($type))
				return $type;
		}

		// Create and return it.
		return Type::Create($key, $name, $subtype);
	}

	/**
	 * Creates and populates this object from the database by providing an ID.
	 * 
	 * @param  int  $id Device type ID.
	 * @return Type     Populated object or NULL if the ID wasn't found.
	 */
	public static function FromID($id) {
		// Get device type from database.
		$dbh = \OnIADE\Database::connect();
		$query = $dbh->prepare("SELECT * FROM device_types WHERE id = :id LIMIT 1");
		$query->bindValue(":id", $id);
		$query->execute();
		$type = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if the ID was invalid.
		if (empty($type))
			return null;
		
		// Create a new device type object.
		$type = $type[0];
		return new Type($type["id"], $type["identifier"], $type["name"],
			$type["subtype"], new Icon($type["icon"], $type["icon_color"]));
	}

	/**
	 * Creates and populates this object from the database by providing a key.
	 * 
	 * @param  string $key Device type key.
	 * @return Type        Populated object or NULL if the device key wasn't
	 *                     found.
	 */
	public static function FromKey($key) {
		// Get device type from database.
		$dbh = \OnIADE\Database::connect();
		$query = $dbh->prepare("SELECT * FROM device_types WHERE identifier = :key LIMIT 1");
		$query->bindValue(":key", $key);
		$query->execute();
		$type = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if the ID was invalid.
		if (empty($type))
			return null;
		
		// Create a new device type object.
		$type = $type[0];
		return new Type($type["id"], $type["identifier"], $type["name"],
			$type["subtype"], new Icon($type["icon"], $type["icon_color"]));
	}

	/**
	 * Saves this device type object into the database. This will create a new
	 * record if an ID wasn't set previously.
	 */
	public function save() {
		// Get database handle.
		$dbh = \OnIADE\Database::connect();
		$stmt = null;

		// Check if we are creating a new device type or updating one.
		if (is_null($this->id)) {
			// Creating a new type.
			$stmt = $dbh->prepare("INSERT INTO device_types(identifier, name, subtype, icon, icon_color) VALUES (:key, :name, :subtype, :icon, :icon_color)");
		} else {
			// Update an existing type.
			$stmt = $dbh->prepare("UPDATE device_types SET identifier = :key, name = :name, subtype = :subtype, icon = :icon, icon_color = :icon_color WHERE id = :id");
			$stmt->bindValue(":id", $this->id);
		}

		// Bind parameters and execute.
		$stmt->bindValue(":key", $this->key);
		$stmt->bindValue(":name", $this->name);
		$stmt->bindValue(":subtype", $this->subtype);
		$stmt->bindValue(":icon", $this->icon->get_name());
		$stmt->bindValue(":icon_color", $this->icon->get_color());
		$stmt->execute();

		// Set the ID.
		if (is_null($this->id))
			$this->id = $dbh->lastInsertId();
	}

	/**
	 * Gets the device type ID.
	 * 
	 * @return int Device type ID.
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Gets the device type key.
	 * 
	 * @return int Device type key.
	 */
	public function get_key() {
		return $this->key;
	}

	/**
	 * Gets the device type name.
	 * 
	 * @return int Device type name.
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Gets the device type subtype.
	 * 
	 * @return int Device type subtype.
	 */
	public function get_subtype() {
		return $this->subtype;
	}

	/**
	 * Gets the device type icon.
	 * 
	 * @return Icon Device type icon.
	 */
	public function get_icon() {
		return $this->icon;
	}

	/**
	 * String representation of this object.
	 * 
	 * @return string String representation of this object.
	 */
	public function as_string() {
		return $this->get_name();
	}

	/**
	 * Array representation of this object. Perfect for use in JSON responses.
	 * 
	 * @return array Array representation of this object.
	 */
	public function as_array() {
		return array(
			"id" => $this->id,
			"key" => $this->key,
			"name" => $this->name,
			"subtype" => $this->subtype,
			"icon" => $this->icon->as_array()
		);
	}
}

?>
