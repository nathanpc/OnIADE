<?php
/**
 * debugview.php
 * A little debug page just to check on the database.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE;
require __DIR__ . "/../vendor/autoload.php";
?>

<?php require(__DIR__ . "/../templates/head.php"); ?>

	<!-- Main Body -->
	<div class="container">
		<h3>
			Floors
			<small class="text-muted">The things we stand on</small>
		</h3>
		<?= Database::get_html_table("floors") ?>

		<h3>
			Rooms
			<small class="text-muted">The things we have class in</small>
		</h3>
		<?= Database::get_html_table("rooms") ?>

		<h3>
			Accounts
			<small class="text-muted">People that have signed up</small>
		</h3>
		<?= Database::get_html_table("accounts") ?>

		<h3>
			Device Types
			<small class="text-muted">How diverse are our devices?</small>
		</h3>
		<?= Database::get_html_table("device_types") ?>

		<h3>
			Device Models
			<small class="text-muted">What models of devices do we have?</small>
		</h3>
		<?= Database::get_html_table("device_models") ?>

		<h3>
			Operating Systems
			<small class="text-muted">We all know Linux is the superior one here</small>
		</h3>
		<?= Database::get_html_table("operating_systems") ?>

		<h3>
			Request Headers
			<small class="text-muted">Sweet, sweeet spying</small>
		</h3>
		<?= Database::get_html_table("request_headers") ?>

		<h3>
			Devices
			<small class="text-muted">Everything that has ever passed by us</small>
		</h3>
		<?= Database::get_html_table("devices") ?>
	</div>

<?php require(__DIR__ . "/../templates/footer.php"); ?>
