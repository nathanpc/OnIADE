<?php
/**
 * mydevice.php
 * Shows information about the device you're currently accessing us from.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE;
require __DIR__ . "/../vendor/autoload.php";
?>

<?php require(__DIR__ . "/../templates/head.php"); ?>
<?php
	$entry = $spy->get_history_entry();
	$device = null;
	$floor = null;

	if (!is_null($entry)) {
		$device = $entry->get_device();
		$floor = $entry->get_floor();
	}
?>

	<!-- Main Body -->
	<div class="container">
		<?php if (!$spy->is_spyable()) { ?>
			<div style="text-align: center;">
				<h1>Device not found</h1>
				<p>Sorry but we couldn't find your device in the database. Looks like you've reached us so fast that our server was unable to track you previously. Please wait a couple of minutes for our highly motivated, peaceful, and privacy-loving robots to find your device and add it to our database.</p>
				<br>
				<p><i>Your IP address is: <?= $spy->get_ip_addr() ?></i></p>
			</div>

			<br>
		<?php } else { ?>
			<h3>
				Device Information
				<small class="text-muted">Some information we have about your current device</small>
			</h3>
			<dl class="row">
				<dt class="col-sm-2">Device Number</dt>
				<dd class="col-sm-9"><?= $device->get_id() ?></dd>

				<dt class="col-sm-2">Hostname</dt>
				<dd class="col-sm-9"><?= $device->get_hostname() ?></dd>

				<dt class="col-sm-2">MAC Address</dt>
				<dd class="col-sm-9"><?= $device->get_mac_address() ?></dd>
				
				<dt class="col-sm-2">Last IP Address</dt>
				<dd class="col-sm-9"><?= $entry->get_ip_addr() ?></dd>

				<dt class="col-sm-2">Type</dt>
				<dd class="col-sm-9"><?= $device->get_type()->as_flair() ?></dd>

				<?php if (!is_null($device->get_model()->get_manufacturer())) { ?>
					<dt class="col-sm-2">Manufacturer</dt>
					<dd class="col-sm-9"><?= $device->get_model()->get_manufacturer() ?></dd>
				<?php } ?>

				<?php if (!is_null($device->get_model()->get_name())) { ?>
					<dt class="col-sm-2">Model</dt>
					<dd class="col-sm-9"><?= $device->get_model()->get_name() ?></dd>
				<?php } ?>
				
				<?php if (!is_null($device->get_os())) { ?>
					<dt class="col-sm-2">Operating System</dt>
					<dd class="col-sm-9"><?= $device->get_os()->as_flair() ?></dd>
				<?php } ?>
			</dl>

			<h3>
				Location
				<small class="text-muted">Last known location we have from you</small>
			</h3>
			<dl class="row">
				<dt class="col-sm-2">Floor Number</dt>
				<dd class="col-sm-9"><?= $floor->get_number() ?></dd>
				
				<dt class="col-sm-2">Floor Name</dt>
				<dd class="col-sm-9"><?= $floor->get_name() ?></dd>
				
				<dt class="col-sm-2">Last Seen</dt>
				<dd class="col-sm-9"><?= $entry->get_timestamp_elapsed() ?></dd>
			</dl>
		<?php } ?>
	</div>

<?php require(__DIR__ . "/../templates/footer.php"); ?>
