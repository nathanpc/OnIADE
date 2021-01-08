<?php
/**
 * index.php
 * Our project's home page.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE;
require __DIR__ . "/../vendor/autoload.php";
?>

<?php require(__DIR__ . "/../templates/head.php"); ?>
	<!-- Main Body -->
	<div class="container">
		<!-- Floors -->
		<?php foreach (Floor::List() as $floor) { ?>
			<?php $entries = History\Entry::List($floor) ?>

			<h3>
				<?= count($entries) ?>
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
