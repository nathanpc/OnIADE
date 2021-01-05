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
 * Gets the current timespan we should be working with.,
 * 
 * @return int Number of hours that we should get data for.
 */
function get_timespan() {
	// Get from GET parameters.
	$ts = 1;
	if (isset($_GET["ts"]))
		$ts = (int)$_GET["ts"];

	return $ts;
}

?>

<?php require(__DIR__ . "/../templates/head.php"); ?>
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
				<?php foreach (History\Entry::List(get_timespan(), $floor) as $entry) { ?>
					<li class="list-group-item">
						<?= $entry->get_device()->get_hostname() ?>
						<span class="flair-spacer"> </span>
						<?= implode("\n", $entry->get_device()->get_flairs()) ?>
					</li>
				<?php } ?>
			</ul>

			<br>
		<?php } ?>
	</div>

<?php require(__DIR__ . "/../templates/footer.php"); ?>
