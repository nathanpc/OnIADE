<?php
/**
 * stats.php
 * A nice, detailed, statistics page.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE;
require __DIR__ . "/../vendor/autoload.php";
?>

<?php require(__DIR__ . "/../templates/head.php"); ?>
	<!-- Chart.js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.bundle.min.js" integrity="sha512-SuxO9djzjML6b9w9/I07IWnLnQhgyYVSpHZx0JV97kGBfTIsUYlWflyuW4ypnvhBrslz1yJ3R+S14fdCWmSmSA==" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.css" integrity="sha512-/zs32ZEJh+/EO2N1b0PEdoA10JkdC3zJ8L5FTiQu82LR9S/rOQNfQN7U59U9BC12swNeRAz3HSzIL2vpp4fv3w==" crossorigin="anonymous" />

	<!-- Our statistics library. -->
	<script type="text/javascript" src="/assets/js/stats.js"></script>
	<script type="text/javascript">
		var stats = new Statistics();
	</script>

	<!-- Main Body -->
	<div class="container">
		<!-- Floors -->
		<h3>
			Floors
			<small class="text-muted">How crowded are they?</small>
		</h3>
		<div id="floors">
			<canvas id="floors-occupation" width="400" height="400"></canvas>
		</div>
		<script type="text/javascript">
			stats.populateFloorsDetail();
		</script>
	</div>

<?php require(__DIR__ . "/../templates/footer.php"); ?>
