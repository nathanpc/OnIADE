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
			<small class="text-muted">What sizes do we have?</small>
		</h3>
		<?= Database::get_html_table("device_types") ?>

		<h3>
			Operating Systems
			<small class="text-muted">We all know Linux is superior</small>
		</h3>
		<?= Database::get_html_table("operating_systems") ?>

		<h3>
			Devices
			<small class="text-muted">Everything that has ever passed by us</small>
		</h3>
		<?= Database::get_html_table("devices") ?>
	</div>

<?php require(__DIR__ . "/../templates/footer.php"); ?>
