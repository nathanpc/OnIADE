<?php require __DIR__ . "/../vendor/autoload.php"; ?>

<ul class='device-poplist'>
	<li>Device Number: <?= $device->get_id() ?></li>
	<li>MAC Address: <?= $device->get_mac_address() ?></li>
	<li>Last IP Address: <?= $entry->get_ip_addr() ?></li>

	<?php if ((!is_null($device->get_type())) || 
			(!is_null($device->get_os()))) { ?>
		<li><br></li>
	<?php } ?>

	<?php if (!is_null($device->get_type())) { ?>
		<li>Type: <?= $device->get_type()->as_text(true) ?></li>
	<?php } ?>

	<?php if (!is_null($device->get_os())) { ?>
		<li>Operating System: <?= $device->get_os()->as_text(true) ?></li>
	<?php } ?>

	<?php if (!is_null($device->get_model())) { ?>
		<?php if ((!is_null($device->get_model()->get_manufacturer())) ||
				(!is_null($device->get_model()->get_name()))) { ?>
			<li><br></li>
		<?php } ?>

		<?php if (!is_null($device->get_model()->get_manufacturer())) { ?>
			<li>Manufacturer: <?= $device->get_model()->get_manufacturer() ?></li>
		<?php } ?>

		<?php if (!is_null($device->get_model()->get_name())) { ?>
			<li>Model: <?= $device->get_model()->get_name() ?></li>
		<?php } ?>
	<?php } ?>

	<br>

	<li>This user has been online for the past <b><?= $device->get_time_online_today() ?></b> minutes.</b>
</ul>