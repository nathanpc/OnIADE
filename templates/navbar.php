<?php

/**
 * Checks if the current page matches our specified name.
 * 
 * @param  string  $check_path Path to be checked against the requested page path.
 * @return boolean             TRUE if the current page name matches.
 */
function is_current_page($check_path) {
	$req_path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

	// Fix root index requests.
	if ($req_path == "/")
		$req_path = "/index.php";

	// Decorate the check path.
	$check_path = "/" . $check_path . ".php";

	return $req_path == $check_path;
}

/**
 * Checks if the current page requesting this template should be marked as
 * active in the navbar items.
 * 
 * @param  string $check_path Path to be checked against the requested page path.
 * @return string             "active" if the page is related to the item or ""
 *                            if it isn't.
 */
function active_navitem($check_path) {
	// Check if the paths match.
	if (is_current_page($check_path))
		return "active";

	return "";
}

?>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	<div class="container">
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<a class="navbar-brand" href="/">
			<img src="/assets/images/logow.png" alt="" height="32"
				class="d-inline-block align-top">
			<?= $config->app->name ?>
		</a>

		<div class="collapse navbar-collapse" id="navbar">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<li class="nav-item">
					<a class="nav-link <?= active_navitem("index") ?>"
						href="/">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= active_navitem("stats") ?>"
						href="/stats.php">Statistics</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= active_navitem("mydevice") ?>"
						href="/mydevice.php">My Device</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?= active_navitem("research") ?>"
						href="/research.php">Research</a>
				</li>
			</ul>
		</div>

		<?php if (is_current_page("stats") || is_current_page("mydevice")) { ?>
			<span class="navbar-text">
				<a href="#" onclick="toggleBuildingBG()" data-bs-toggle="tooltip"
						data-bs-original-title="Toggle background building">
					<i class="far fa-building"></i>
				</a>
			</span>
		<?php } ?>
	</div>
</nav>

<br>
