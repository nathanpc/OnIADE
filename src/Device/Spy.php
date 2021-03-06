<?php
/**
 * Spy.php
 * Time to act like Facebook and Google! YAY!
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE\Device;
require __DIR__ . "/../../vendor/autoload.php";
use OnIADE\History;

class Spy {
	private $ip;
	private $hist_entry;
	private $headers;

	/**
	 * Device spy object constructor.
	 * 
	 * @param string  $ip      IP of the device we are going to spy on.
	 * @param boolean $prevent True if we don't want to spy on this one.
	 */
	public function __construct($ip = null, $prevent = false) {
		// Get device IP.
		$this->ip = $ip;
		if (is_null($ip))
			$this->ip = Spy::get_client_ip();

		// Get history entry.
		$this->hist_entry = null;
		if (!$prevent)
			$this->hist_entry = History\Entry::FromIPAddress($this->ip);

		// Get request headers.
		$this->headers = null;
		if (!$prevent && !is_null($this->hist_entry))
			$this->headers = RequestHeaders::Create(
				$this->hist_entry->get_device(),
				getallheaders());
	}

	/**
	 * Gets the client's User-Agent header.
	 * 
	 * @return string Client's User-Agent header.
	 */
	public static function get_user_agent() {
		return $_SERVER["HTTP_USER_AGENT"];
	}

	/**
	 * Tries to get the most accurate client IP address possible.
	 * @link https://stackoverflow.com/a/41382472/126353 Original Author
	 * 
	 * @return string Client's IP address.
	 */
	public static function get_client_ip() {
		$ip = null;

		if (isset($_SERVER["HTTP_CLIENT_IP"]))
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		else if(isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		else if(isset($_SERVER["HTTP_X_FORWARDED"]))
			$ip = $_SERVER["HTTP_X_FORWARDED"];
		else if(isset($_SERVER["HTTP_X_CLUSTER_CLIENT_IP"]))
			$ip = $_SERVER["HTTP_X_CLUSTER_CLIENT_IP"];
		else if(isset($_SERVER["HTTP_FORWARDED_FOR"]))
			$ip = $_SERVER["HTTP_FORWARDED_FOR"];
		else if(isset($_SERVER["HTTP_FORWARDED"]))
			$ip = $_SERVER["HTTP_FORWARDED"];
		else if(isset($_SERVER["REMOTE_ADDR"]))
			$ip = $_SERVER["REMOTE_ADDR"];

		// If we are running in a development environment make sure to fix the
		// IP address if we are accessing from the host machine.
		$config = require(__DIR__ . "/../../config/config.php");
		if ($config->dev->env && ($ip == "127.0.0.1"))
			$ip = $config->host->ip_addr;

		return $ip;
	}

	/**
	 * Checks if a device is currently spyable.
	 * 
	 * @return boolean True if the device is spyable.
	 */
	public function is_spyable() {
		return !is_null($this->hist_entry);
	}

	/**
	 * Gets the device IP address.
	 * 
	 * @return string Device IP address.
	 */
	public function get_ip_addr() {
		return $this->ip;
	}

	/**
	 * Gets the last history entry of this device.
	 * 
	 * @return History\Entry Device's history entry.
	 */
	public function get_history_entry() {
		return $this->hist_entry;
	}

	/**
	 * Gets detailed information about the device.
	 * 
	 * @return RequestHeaders Detailed information about the device.
	 */
	public function get_request_headers() {
		return $this->headers;
	}
}

?>
