<?php
/**
 * RequestHeaders.php
 * Abstraction class for a device request headers.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE\Device;
require __DIR__ . "/../../vendor/autoload.php";
use PDO;

class RequestHeaders {
	private $id;
	private $headers;
	private $parser;
	private $device;

	/**
	 * Device request headers object constructor.
	 * 
	 * @param int    $id      Device request headers ID.
	 * @param Device $device  Device that this request originated from.
	 * @param array  $headers Request headers (from {@link getallheaders()})
	 */
	public function __construct($id = null, $device = null, $headers = null) {
		$this->id = $id;
		$this->device = $device;
		$this->headers = $headers;
		$this->parser = new \WhichBrowser\Parser($headers);
	}

	/**
	 * Creates (unless already exists) a new device request headers object and
	 * saves it to the database.
	 * 
	 * @param  Device         $device       Device that this request originated
	 *                                      from.
	 * @param  array          $headers      Request headers (from 
	 *                                      {@link getallheaders()})
	 * @param  boolean        $propagate    Propagate creation to objects
	 *                                      associated with this one?
	 *                                      ({@see Model}, {@see Type},
	 *                                      {@see OperatingSystem})
	 * @param  boolean        $check_exists Check if it has been created before?
	 * @return RequestHeaders               Populated object or NULL if the ID
	 *                                      wasn't found.
	 */
	public static function Create($device, $headers, $propagate = true,
			$check_exists = true) {
		$req = null;

		// Check if it exists before doing anything.
		if ($check_exists) {
			$req = RequestHeaders::FromDevice($device);
			if (!is_null($req))
				$req->set_headers($headers);
		}

		// Create a new object if needed and save our changes so far.
		if (is_null($req))
			$req = new RequestHeaders(null, $device, $headers);
		$req->save();

		// Create all the object is we need to propagate changes.
		if ($propagate) {
			OperatingSystem::CreateFromBrowserParser($req->get_browser_parser());
			Model::CreateFromBrowserParser($req->get_browser_parser(), $propagate);
		}

		return $req;
	}

	/**
	 * Creates and populates this object from the database by providing an ID.
	 * 
	 * @param  int            $id Device request headers ID.
	 * @return RequestHeaders     Populated object or NULL if the ID wasn't
	 *                            found.
	 */
	public static function FromID($id) {
		// Get device request headers from database.
		$dbh = \OnIADE\Database::connect();
		$query = $dbh->prepare("SELECT * FROM request_headers WHERE id = :id LIMIT 1");
		$query->bindValue(":id", $id);
		$query->execute();
		$req = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if the ID was invalid.
		if (empty($req))
			return null;
		
		// Create a new device request headers object.
		$req = $req[0];
		return new RequestHeaders($req["id"], Device::FromID($req["device_id"]),
			json_decode($req["json"]));
	}

	/**
	 * Creates and populates this object from an existing Device object.
	 * 
	 * @param  Device         $device Device object.
	 * @return RequestHeaders         Populated object or NULL if the device
	 *                                didn't belong to any requests.
	 */
	public static function FromDevice($device) {
		// Get device request headers from database.
		$dbh = \OnIADE\Database::connect();
		$query = $dbh->prepare("SELECT * FROM request_headers WHERE device_id = :device_id LIMIT 1");
		$query->bindValue(":device_id", $device->get_id());
		$query->execute();
		$req = $query->fetchAll(PDO::FETCH_ASSOC);

		// Check if the device ID was invalid.
		if (empty($req))
			return null;
		
		// Create a new device request headers object.
		$req = $req[0];
		return new RequestHeaders($req["id"], $device,
			json_decode($req["json"], true));
	}

	/**
	 * Saves this device request headers object into the database. This will
	 * create a new record if an ID wasn't set previously.
	 */
	public function save() {
		// Get database handle.
		$dbh = \OnIADE\Database::connect();
		$stmt = null;

		// Check if we are creating a new device request headers or updating one.
		if (is_null($this->id)) {
			// Creating a new request headers.
			$stmt = $dbh->prepare("INSERT INTO request_headers(json, device_id) VALUES (:json, :device_id)");
		} else {
			// Update an existing request headers.
			$stmt = $dbh->prepare("UPDATE request_headers SET json = :json, device_id = :device_id WHERE id = :id");
			$stmt->bindValue(":id", $this->id);
		}

		// Bind parameters and execute.
		$stmt->bindValue(":json", json_encode($this->headers));
		$stmt->bindValue(":device_id", $this->device->get_id());
		$stmt->execute();

		// Set the ID.
		if (is_null($this->id))
			$this->id = $dbh->lastInsertId();
	}

	/**
	 * Gets the device request headers ID.
	 * 
	 * @return int Device request headers ID.
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Gets the original request headers that generated this device request headers.
	 * 
	 * @return array Request headers (like {@link getallheaders()})
	 */
	public function get_headers() {
		return $this->headers;
	}

	/**
	 * Sets the original request headers that generated this device request headers.
	 * 
	 * @param array $headers Request headers (from {@link getallheaders()})
	 */
	public function set_headers($headers) {
		$this->headers = $headers;
	}

	/**
	 * Gets the \WhichBrowser\Parser object for this device request headers.
	 * @link https://github.com/WhichBrowser/Parser-PHP
	 * 
	 * @return \WhichBrowser\Parser Browser parser object.
	 */
	public function get_browser_parser() {
		return $this->parser;
	}
}

?>
