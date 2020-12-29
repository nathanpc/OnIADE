<?php
/**
 * DeviceSpy.php
 * Time to act like Facebook and Google! YAY!
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

require_once(__DIR__ . "/HistoryEntry.php");

class DeviceSpy {
	private $ip;
	private $ua;
	private $device;

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
		$config = require(__DIR__ . "/../config/config.php");
		if ($config->dev->env && ($ip == "127.0.0.1"))
			$ip = $config->dev->host_ip;

		return $ip;
	}
}

?>
