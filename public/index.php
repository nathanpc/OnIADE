<?php
/**
 * index.php
 * Our project's home page.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE;
require __DIR__ . "/../vendor/autoload.php";
use OnIADE\Utilities\UploadHandler;

/**
 * Gets a random X position for a Sim in a buildilg floor.
 * 
 * @return int Random X position in a building floor.
 */
function rand_sim_pos() {
	return rand(320, 915);
}

/**
 * Gets the path to a Sim image based on a Device object.
 * 
 * @param  Device $device Device to be represented by a Sim.
 * @return string         Path to the related Sim image.
 */
function get_sim_image($device) {
	$basepath = "/assets/images/sims";
	$os = $device->get_os();
	$type = $device->get_type();

	// Check if we have a type.
	if (!is_null($type)) {
		if ($type->get_key() == "bot") {
			$path = "$basepath/linux.png";
			if (file_exists(UploadHandler::WEBROOT . $path))
				return $path;
		}
	}

	// Use the generic Sim if we don't have an OS.
	if (is_null($os))
		goto generic_sim;

	// Build a path based on the OS and check if it exists.
	$path = "$basepath/" . strtolower($os->get_name()) . ".png";
	if (file_exists(UploadHandler::WEBROOT . $path))
		return $path;

generic_sim:
	return "$basepath/generic.png";
}

?>

<?php require(__DIR__ . "/../templates/head.php"); ?>

	<div class="container x-scroller">
		<div class="building-container">
			<img id="building" src="/assets/images/iade.png">

			<?php foreach (Floor::List() as $floor) { ?>
				<?php $entries = History\Entry::List($floor, true) ?>

				<?php foreach ($entries as $entry) { ?>
					<?php $device = $entry->get_device(); ?>

					<img class="sim floor<?= $floor->get_number() ?>"
						src="<?= get_sim_image($device) ?>"
						style="left: <?= rand_sim_pos() ?>px"
						data-bs-toggle="popover" data-bs-placement="top"
						data-bs-trigger="hover click" data-bs-html="true"
						title="<?= $device->get_hostname() ?>"
						data-bs-content="<?php require(__DIR__ . "/../templates/device_popover.php"); ?>">
				<?php } ?>
			<?php } ?>
		</div>
	</div>

	<!-- Enable popovers. -->
	<script type="text/javascript">
		window.addEventListener("load", function () {
			var popoverTriggerList = [].slice.call(document.querySelectorAll("[data-bs-toggle='popover']"));
			var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
				return new bootstrap.Popover(popoverTriggerEl);
			});
		});
	</script>

<?php require(__DIR__ . "/../templates/footer.php"); ?>
