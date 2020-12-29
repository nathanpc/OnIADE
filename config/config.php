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
		"host_ip" => "192.168.1.15"
	)
);

?>
