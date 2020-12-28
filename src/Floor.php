<?php
/**
 * Floor.php
 * An abstraction class to work with floors in the database.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

require_once(__DIR__ . "/Database.php");

class Floor {
	private $id;
	private $number;
	private $name;

	/**
	 * Floor object constructor.
	 * 
	 * @param int    $id     Floor ID.
	 * @param int    $number Floor number.
	 * @param string $name   Floor name.
	 */
	public function __construct($id = null, $number = null, $name = null) {
		$this->id = $id;
		$this->number = $number;
		$this->name = $name;
	}

	/**
	 * Constructs an object with the floor information from the database by 
	 * using its ID.
	 * 
	 * @param  int    $id Floor ID.
	 * @return Floor      Populated Floor object with data from database, or
	 *                    NULL if the ID wasn't found.
	 */
	public static function FromID($id) {
		// Get floor from database.
		$dbh = Database::connect();
		$query = $dbh->prepare("SELECT * FROM floors WHERE id = :id");
		$query->bindValue(":id", $id);
		$query->execute();
		$floor = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if the ID was invalid.
		if (empty($floor))
			return null;
		
		// Create a new floor object.
		$floor = $floor[0];
		return new Floor($floor["id"], $floor["number"], $floor["name"]);
	}

	/**
	 * Constructs an object with the floor information from the database by
	 * using its floor number.
	 * 
	 * @param  string $number Floor number.
	 * @return Floor          Populated Floor object with data from database, or
	 *                        NULL if the floor number wasn't found.
	 */
	public static function FromNumber($number) {
		// Get device from database.
		$dbh = Database::connect();
		$query = $dbh->prepare("SELECT * FROM floors WHERE number = :number");
		$query->bindValue(":number", $number);
		$query->execute();
		$floor = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if the floor number was invalid.
		if (empty($floor))
			return null;
		
		// Create a new Floor object.
		$floor = $floor[0];
		return new Floor($floor["id"], $floor["number"], $floor["name"]);
	}

	/**
	 * Gets the floor ID.
	 * 
	 * @return int Floor ID.
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Gets the floor number.
	 * 
	 * @return int Floor number.
	 */
	public function get_number() {
		return $this->number;
	}

	/**
	 * Gets the floor name.
	 * 
	 * @return string Floor name.
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Array representation of this object. Perfect for use in JSON responses.
	 * 
	 * @return array Array representation of this object.
	 */
	public function as_array() {
		return array(
			"id" => $this->id,
			"number" => $this->number,
			"name" => $this->name
		);
	}
}

?>
