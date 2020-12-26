<?php require(__DIR__ . "/../templates/head.php"); ?>

	<!-- Main Body -->
	<div class="container">
		<h3>
			Floors
			<small class="text-muted">The things we stand on</small>
		</h3>

		<?= Database::get_html_table("floors") ?>
	</div>

<?php require(__DIR__ . "/../templates/footer.php"); ?>
