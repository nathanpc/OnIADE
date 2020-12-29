<?php
/**
 * index.php
 * Our project's home page.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

require_once(__DIR__ . "/../src/Floor.php");
require_once(__DIR__ . "/../src/Device.php");
require_once(__DIR__ . "/../src/HistoryEntry.php");

/**
 * Gets the current timespan we should be working with.,
 * 
 * @return int Number of hours that we should get data for.
 */
function get_timespan() {
	$ts = 1;

	// Get from GET parameters.
	if (isset($_GET["ts"]))
		$ts = (int)$_GET["ts"];

	return $ts;
}

/**
 * Gets all of the devices that are currently in a specific floor.
 * 
 * @param  Floor $floor Desired floor to check out.
 * @param  int   $ts    Timespan in hours to get devices.
 * @return array        Array of Device objects currently in the floor.
 */
function get_devices($floor, $ts = 1) {
	$devices = array();

	$dbh = Database::connect();
	$query = $dbh->prepare("SELECT device_id FROM device_history WHERE floor_id = :floor_id");
	$query->bindValue(":floor_id", $floor->get_id());
	$query->execute();
	$entries = $query->fetchAll(PDO::FETCH_ASSOC);

	foreach ($entries as $entry)
		array_push($devices, Device::FromID($entry["device_id"]));

	return $devices;
}

?>

<?php require(__DIR__ . "/../templates/head.php"); ?>
	<script type="text/javascript" src="/assets/js/main.js"></script>

	<!-- Main Body -->
	<div class="container">
		<!-- Timespan Selector -->
		<label for="timespan" id="timespan-label" class="form-label">Showing devices that were on the network for the past <?= get_timespan() ?> hours</label>
		<input type="range" id="timespan" class="form-range" min="1" max="24" step="1" value="<?= get_timespan() ?>" onchange="timespan_update(this.value, true)" oninput="timespan_update(this.value, false)">
		<br>

		<!-- Floors -->
		<?php foreach (Floor::List() as $floor) { ?>
			<h3>
				<?= $floor->get_number() ?>
				<small class="text-muted"><?= $floor->get_name() ?></small>
			</h3>

			<ul class="list-group">
				<?php foreach (get_devices($floor, get_timespan()) as $device) { ?>
					<li class="list-group-item"><?= $device->get_hostname() ?></li>
				<?php } ?>
			</ul>

			<br>
		<?php } ?>
	</div>

<?php require(__DIR__ . "/../templates/footer.php"); ?>
