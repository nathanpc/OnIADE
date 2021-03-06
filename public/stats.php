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
	<div id="bg-container" class="container building-bg">
		<script type="text/javascript">loadBuildingBGSettings();</script>

		<!-- Online Time -->
		<h3>
			Online Time
			<small class="text-muted">How long have people been online today?</small>
		</h3>
		<div id="online-time">
			<div class="container">
				<div class="row">
					<div class="col">
						<canvas id="desktop-oses-time"></canvas>
					</div>
					<div class="col">
						<canvas id="mobile-oses-time"></canvas>
					</div>
				</div>
			</div>
		</div>
		<br>

		<!-- Operating Systems -->
		<h3>
			Operating Systems
			<small class="text-muted">What's the preference of our users?</small>
		</h3>
		<div id="oses">
			<div class="container">
				<div class="row">
					<div class="col">
						<canvas id="desktop-oses"></canvas>
					</div>
					<div class="col">
						<canvas id="mobile-oses"></canvas>
					</div>
				</div>
			</div>
		</div>
		<script type="text/javascript">
			stats.populateOSDetail();
		</script>
		<br>
		
		<!-- Floors -->
		<h3>
			Floors
			<small class="text-muted">How crowded are they?</small>
		</h3>
		<div id="floors">
			<canvas id="floors-occupation"></canvas>
		</div>
		<script type="text/javascript">
			stats.populateFloorsDetail();
		</script>
		<br>
	</div>

<?php require(__DIR__ . "/../templates/footer.php"); ?>
