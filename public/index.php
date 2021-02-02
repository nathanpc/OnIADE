<?php
/**
 * index.php
 * Our project's home page.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE;
require __DIR__ . "/../vendor/autoload.php";

/**
 * Gets a random X position for a Sim in a buildilg floor.
 * 
 * @return int Random X position in a building floor.
 */
function rand_sim_pos() {
	return rand(320, 915);
}

?>

<?php require(__DIR__ . "/../templates/head.php"); ?>

	<div class="container x-scroller">
		<div class="building-container">
			<img id="building" src="/assets/images/iade.png">

			<?php foreach (Floor::List() as $floor) { ?>
				<?php $entries = History\Entry::List($floor) ?>

				<?php foreach ($entries as $entry) { ?>
					<?php $device = $entry->get_device(); ?>

					<img class="sim floor<?= $floor->get_number() ?>"
						src="/assets/images/sim.png"
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
