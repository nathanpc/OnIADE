<?php
/**
 * stats.php
 * A nice, detailed, statistics page.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE;
require __DIR__ . "/../vendor/autoload.php";

$stats = new Statistics();
?>

<?php require(__DIR__ . "/../templates/head.php"); ?>
	<script type="text/javascript" src="/assets/js/stats.js"></script>
	<script type="text/javascript">
		var stats = new Statistics(<?= json_encode($stats->as_array()) ?>);
	</script>

	<!-- Main Body -->
	<div class="container">
		<!-- Floors -->
		<h3>
			Floors
			<small class="text-muted">How crowded are they?</small>
		</h3>
		<div id="floors">
		</div>
		<script type="text/javascript">
			stats.populateFloorsDetail();
		</script>
	</div>

<?php require(__DIR__ . "/../templates/footer.php"); ?>
