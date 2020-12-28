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
 * Gets all of the available floors in ascending order for us.
 * 
 * @return array Array with all the floors as Floor objects.
 */
function get_floors() {
	$floors = array();

	$dbh = Database::connect();
	$query = $dbh->prepare("SELECT id FROM floors ORDER BY number ASC");
	$query->execute();
	$rows = $query->fetchAll(PDO::FETCH_ASSOC);

	foreach ($rows as $floor)
		array_push($floors, Floor::FromID($floor["id"]));

	return $floors;
}

/**
 * Gets all of the devices that are currently in a specific floor.
 * 
 * @param  Floor $floor Desired floor to check out.
 * @return array        Array of Device objects currently in the floor.
 */
function get_devices($floor) {
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

// Get our floors.
$floors = get_floors();
?>

<?php require(__DIR__ . "/../templates/head.php"); ?>

	<!-- Main Body -->
	<div class="container">
		<?php foreach ($floors as $floor) { ?>
			<h3>
				<?= $floor->get_number() ?>
				<small class="text-muted"><?= $floor->get_name() ?></small>
			</h3>

			<ul class="list-group">
				<?php foreach (get_devices($floor) as $device) { ?>
					<li class="list-group-item"><?= $device->get_hostname() ?></li>
				<?php } ?>
			</ul>

			<br>
		<?php } ?>
	</div>

<?php require(__DIR__ . "/../templates/footer.php"); ?>
