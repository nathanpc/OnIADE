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
 * Counts the number of entries in an {@link Entry} array that aren't ignored.
 * 
 * @param  array $entries Array of {@link Entry}.
 * @return int            Number of counted entries.
 */
function count_valid_entries($entries) {
	$count = 0;

	// Go through the entries looking for non-ignored ones.
	foreach ($entries as $entry) {
		if (!$entry->get_device()->is_ignored())
			$count++;
	}

	return $count;
}

?>

<?php require(__DIR__ . "/../templates/head.php"); ?>
	<!-- Main Body -->
	<div class="container">
		<!-- Floors -->
		<?php foreach (Floor::List() as $floor) { ?>
			<?php $entries = History\Entry::List($floor) ?>

			<h3>
				<?= count_valid_entries($entries) ?>
				<small class="text-muted"><?= $floor->get_name() ?></small>
			</h3>

			<ul class="list-group">
				<?php foreach ($entries as $entry) { ?>
					<?php if (!$entry->get_device()->is_ignored()) { ?>
						<li class="list-group-item">
							<?= $entry->get_device()->get_hostname() ?>
							<span class="flair-spacer"> </span>
							<?= implode("\n", $entry->get_device()->get_flairs()) ?>
						</li>
					<?php } ?>
				<?php } ?>
			</ul>

			<br>
		<?php } ?>
	</div>

<?php require(__DIR__ . "/../templates/footer.php"); ?>
