<?php
/**
 * Model.php
 * Abstraction class for a device model.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE\Device;
require __DIR__ . "/../../vendor/autoload.php";
use OnIADE\Database;
use PDO;

class Model {
	private $id;
	private $type;
	private $manufacturer;
	private $name;

	/**
	 * Device request headers object constructor.
	 * 
	 * @param int    $id           Device request headers ID.
	 * @param string $manufacturer Device manufacturer.
	 * @param string $name         Model name.
	 * @param Type   $type         Type of this device.
	 */
	public function __construct($id = null, $manufacturer = null, $name = null, $type = null) {
		$this->id = $id;
		$this->manufacturer = $manufacturer;
		$this->name = $name;
		$this->type = $type;
	}

	/**
	 * Gets a list of all the device models available.
	 * 
	 * @return array Array of Model objects.
	 */
	public static function List() {
		$models = array();

		// Get models from the database ordered.
		$dbh = Database::connect();
		$query = $dbh->prepare("SELECT id FROM device_models");
		$query->execute();
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);

		// Go through the results creating Model objects.
		foreach ($rows as $model)
			array_push($models, Model::FromID($model["id"]));

		return $models;
	}

	/**
	 * Creates a new device model and automatically saves it to the database.
	 * 
	 * @param  string $manufacturer Device manufacturer.
	 * @param  string $name         Model name.
	 * @param  Type   $type         Type of this device.
	 * @return Model                Populated device model object.
	 */
	public static function Create($manufacturer = null, $name = null, $type = null) {
		$model = new Model(null, $manufacturer, $name, $type);
		$model->save();

		return $model;
	}

	/**
	 * Creates (unless already exists) a new device model from a
	 * \WhichBrowser\Parser object and saves it to the database.
	 * 
	 * @param  \WhichBrowser\Parser $parser       Browser parser.
	 * @param  boolean              $propagate    Propagate creation to objects
	 *                                            associated with this one?
	 *                                            ({@see Type})
	 * @param  boolean              $check_exists Check if it has been created
	 *                                            before?
	 * @return Model                              Populated device model object.
	 */
	public static function CreateFromBrowserParser($parser, $propagate = true,
			$check_exists = true) {
		// Get our parameters.
		$manufacturer = $parser->device->manufacturer;
		$name = $parser->device->model;
		$type = Type::FromKey($parser->device->type);

		// Should we propagate our changes to device types.
		if ($propagate) {
			$type = Type::CreateFromBrowserParser($parser);
		}

		// Check if it exists before.
		if ($check_exists) {
			$model = Model::FromDetails($manufacturer, $name, $type);
			if (!is_null($model))
				return $model;
		}

		// Create and return it.
		return Model::Create($manufacturer, $name, $type);
	}

	/**
	 * Creates and populates this object from the database by providing an ID.
	 * 
	 * @param  int   $id Device model ID.
	 * @return Model     Populated object or NULL if the ID wasn't found.
	 */
	public static function FromID($id) {
		// Get device model from database.
		$dbh = Database::connect();
		$query = $dbh->prepare("SELECT * FROM device_models WHERE id = :id LIMIT 1");
		$query->bindValue(":id", $id);
		$query->execute();
		$model = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if the ID was invalid.
		if (empty($model))
			return null;
		
		// Create a new device model object.
		$model = $model[0];
		return new Model($model["id"], $model["manufacturer"], $model["name"],
			Type::FromID($model["type_id"]));
	}

	/**
	 * Creates and populates this object from the database by providing full
	 * information about it.
	 * 
	 * @param  string $manufacturer Device manufacturer.
	 * @param  string $name         Model name.
	 * @param  Type   $type         Type of this device.
	 * @return Model                Populated object or NULL if it wasn't found.
	 */
	public static function FromDetails($manufacturer, $name, $type) {
		// Build database query statement.
		$dbh = Database::connect();
		$query = $dbh->prepare("SELECT * FROM device_models WHERE (manufacturer <=> :manufacturer) AND (name <=> :name) AND (type_id <=> :type_id) LIMIT 1");

		// Get type ID.
		$type_id = null;
		if (!is_null($type))
			$type_id = $type->get_id();

		// Bind parameters and execute.
		$query->bindValue(":manufacturer", $manufacturer);
		$query->bindValue(":name", $name);
		$query->bindValue(":type_id", $type->get_id());
		$query->execute();
		$model = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if the information was invalid.
		if (empty($model))
			return null;
		
		// Create a new device model object.
		$model = $model[0];
		return new Model($model["id"], $model["manufacturer"], $model["name"],
			Type::FromID($model["type_id"]));
	}

	/**
	 * Checks if a device model exists in the database already.
	 * 
	 * @param  string  $manufacturer Device manufacturer.
	 * @param  string  $name         Model name.
	 * @param  Type    $type         Type of this device.
	 * @return boolean               Does the device exist?
	 */
	public static function Exists($manufacturer, $name, $type) {
		$model = Model::FromDetails($manufacturer, $name, $type);
		return !is_null($model);
	}

	/**
	 * Saves this device model object into the database. This will create a new
	 * record if an ID wasn't set previously.
	 */
	public function save() {
		// Get database handle.
		$dbh = Database::connect();
		$stmt = null;

		// Check if we are creating a new device model or updating one.
		if (is_null($this->id)) {
			// Creating a new model.
			$stmt = $dbh->prepare("INSERT INTO device_models(manufacturer, name, type_id) VALUES (:manufacturer, :name, :type_id)");
		} else {
			// Update an existing model.
			$stmt = $dbh->prepare("UPDATE device_models SET manufacturer = :manufacturer, name = :name, type_id = :type_id WHERE id = :id");
			$stmt->bindValue(":id", $this->id);
		}

		// Get type ID.
		$type_id = null;
		if (!is_null($this->type))
			$type_id = $this->type->get_id();

		// Bind parameters and execute.
		$stmt->bindValue(":manufacturer", $this->manufacturer);
		$stmt->bindValue(":name", $this->name);
		$stmt->bindValue(":type_id", $type_id);
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

	/**
	 * Gets the device type.
	 * 
	 * @return Type Device type.
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * Gets the device manufacturer.
	 * 
	 * @return Type Device manufacturer.
	 */
	public function get_manufacturer() {
		return $this->manufacturer;
	}

	/**
	 * Gets the device name.
	 * 
	 * @return Type Device name.
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * String representation of this object.
	 * 
	 * @return string String representation of this object.
	 */
	public function as_string() {
		return $this->manufacturer . " " . $this->name;
	}

	/**
	 * Array representation of this object. Perfect for use in JSON responses.
	 * 
	 * @return array Array representation of this object.
	 */
	public function as_array() {
		return array(
			"id" => $this->id,
			"manufacturer" => $this->manufacturer,
			"name" => $this->name,
			"type" => $this->type->as_array()
		);
	}
}

?>
