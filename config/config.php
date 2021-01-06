<?php
/**
 * config.php
 * The application configuration file.
 *
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

return (object) array(
	"app" => (object)array(
		"name" => "OnIADE"
	),
	"database" => (object)array(
		"host" => "localhost",
		"dbname" => "oniade",
		"user" => "iade",
		"password" => "password"
	),
	"dev" => (object)array(
		"env" => true,
		"min_floor" => 0,
		"max_floor" => 3
	),
	"host" => (object)array(
		"ip_addr" => "192.168.1.15",
		"mac_addr" => "00:90:F5:E3:99:27"
	),
	"scanner" => (object)array(
		"range" => "192.168.1.0/24",
		"scan_netbios" => true,
		"interval" => 30
	)
);

?>
