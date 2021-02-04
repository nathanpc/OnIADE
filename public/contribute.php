<?php
/**
 * contribute.php
 * Page that accepts contributions.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE;
require __DIR__ . "/../vendor/autoload.php";

// Let's make sure that the user has requested this page via POST only.
if ($_SERVER["REQUEST_METHOD"] != "POST") {
	http_response_code(404);
	die();
}

// Create a new contribution.
$contrib = Contribution::Create($_POST["name"], $_POST["website"],
	$_POST["email"], $_POST["title"], $_POST["contribwebsite"],
	$_POST["description"], isset($_POST["publicemail"]), $_FILES["thumbnail"],
	$_FILES["attachment"]);

?>

<?php require(__DIR__ . "/../templates/head.php"); ?>

	<!-- Main Body -->
	<div class="container">
		<?php if (is_null($contrib)) { ?>
			<div class="row justify-content-md-center">
				<div class="col-md-9">
					<div style="text-align: center;">
						<h1>Error submitting contribution</h1>
						<p>Sorry but because of some issue in your upload the submission failed. Go back and check if all of the fields were entered correctly, and most importantly if your thumbnail is actually an image and if your attachment isn't too big.</p>
					</div>
				</div>
			</div>

			<br>
		<?php } else { ?>
			<h1>It works!</h1>
		<?php } ?>

		<pre><code><?php var_dump($_POST); ?></code></pre>
	</div>

<?php require(__DIR__ . "/../templates/footer.php"); ?>
