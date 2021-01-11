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
	<!-- Main Body -->
	<div class="container">
		<pre><code><?php var_dump($stats); ?></code></pre>
	</div>

<?php require(__DIR__ . "/../templates/footer.php"); ?>
