<?php
/**
 * research.php
 * Researcher's corner.
 * 
 * @author Nathan Campos <nathan@innoveworkshop.com>
 */

namespace OnIADE;
require __DIR__ . "/../vendor/autoload.php";

?>

<?php require(__DIR__ . "/../templates/head.php"); ?>

	<!-- Main Body -->
	<div class="container">
		<!-- A little explanation. -->
		<div id="explanation">
			<h3>
				Researcher's Corner
				<small class="text-muted">We are gathering data, you want it</small>
			</h3>

			<p>We've been gathering all this really nice data, and we think we're doing a pretty OK job at displaying and analyzing it, but we also know that there's a lot of really intelligent people out there, people like you, that want to use this data in their research, or maybe think there are better correlations to be shown in our platform. This is the place to congregate, get some of this sweet, sweet, data and also collaborate on making this an even better platform.</p>
		</div>

		<!-- Data export. -->
		<div id="export">
			<h3>
				Data Export
				<small class="text-muted">Grab everything you want</small>
			</h3>

			<div class="row row-cols-1 row-cols-md-4">
				<div class="col">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title">XML</h5>
							<h6 class="card-subtitle mb-2 text-muted">Extensible Markup Language</h6>
							<p class="card-text">The most obiquitous way to exchange data. This option guarantees compatibility with any programming language. It's specially useful if you want to use <a href="https://www.mathworks.com/products/matlab.html">MATLAB</a> or <a href="https://www.gnu.org/software/octave/index">GNU Octave</a>.</p>
						</div>
						<ul class="list-group list-group-flush">
							<li class="list-group-item"><a href="#">Unique Devices</a></li>
							<li class="list-group-item"><a href="#">Device History</a></li>
							<li class="list-group-item"><a href="#">Everything</a></li>
						</ul>
					</div>
				</div>
				<div class="col">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title">JSON</h5>
							<h6 class="card-subtitle mb-2 text-muted">JavaScript Object Notation</h6>
							<p class="card-text">A modern way to interact with our data using programming languages. This option will give you a lot more flexibility if you want to use things like <a href="https://www.python.org/">Python</a> or <a href="https://www.r-project.org/">R</a> to work with our data.</p>
						</div>
						<ul class="list-group list-group-flush">
							<li class="list-group-item"><a href="#">Unique Devices</a></li>
							<li class="list-group-item"><a href="#">Device History</a></li>
							<li class="list-group-item"><a href="#">Everything</a></li>
						</ul>
					</div>
				</div>
				<div class="col">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title">CSV</h5>
							<h6 class="card-subtitle mb-2 text-muted">
								Comma Separated Values
							</h6>
							<p class="card-text">The easiest way to use our data. You can open this file in software packages like Excel and <a href="https://www.libreoffice.org/">LibreOffice Calc</a> and easily interact with the data yourself without any programming required.</p>
						</div>
						<ul class="list-group list-group-flush">
							<li class="list-group-item"><a href="#">Unique Devices</a></li>
							<li class="list-group-item"><a href="#">Device History</a></li>
						</ul>
					</div>
				</div>
				<div class="col">
					<div class="card h-100">
						<div class="card-body">
							<h5 class="card-title">MySQL</h5>
							<h6 class="card-subtitle mb-2 text-muted">
								Database Dump
							</h6>
							<p class="card-text">A partial dump of our <a href="https://www.mysql.com/">MySQL</a> database, only with the important information for research, and no sensitive data.</p>

							<p class="card-text">This option has the highest barrier of entry, but will give you the most flexibility out of all the options in here.</p>
						</div>
						<ul class="list-group list-group-flush">
							<li class="list-group-item"><a href="#">Everything</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<br>

		<!-- Open Source! -->
		<div id="opensource">
			<h3>
				Open Source
				<small class="text-muted">We want your contributions</small>
			</h3>

			<p>We are fully commited to the <a href="https://en.wikipedia.org/wiki/Open-source-software_movement">open source movement</a>, having licensed the entirety of this web application under the <a href="https://opensource.org/licenses/MIT">MIT license</a>, and if you're a programmer and found our project insteresting, you are more than welcome to join our community and start contributing to make this web application even better!</p>

			<p>You can find the codebase in our <a href="https://github.com/nathanpc/OnIADE">GitHub repository</a> with all of the instructions needed to setup a development environment in your own machine. If you have any questions or inquiries about the project please <a href="https://github.com/nathanpc/OnIADE/issues/new/choose">open an issue</a>, but if you just want to chat a bit or maybe you want some help in your own project feel free to contact <a href="https://nathancampos.me/">@nathanpc</a>, he's always eager to help out.</p>
		</div>

		<!-- Contribute too! -->
		<div id="contribute">
			<h3>
				Contribute Your Way
				<small class="text-muted">Have you done something interesting with our data? Show us!</small>
			</h3>

			<p>Whether you're a designer, a researcher, or someone that really enjoys data, you can contribute to make our platform even better! Researchers and data lovers can use our data to discover new and interesting correlations that should be included in the web application so that everyone can see them as well, and designers can find new and better ways to transform this data into beautiful visualizations that captivate our audience and brings meaning to the data that we are gathering.</p>

			<p>You can contribute your work simply by submitting it via the following form and it'll be publicly displayed in our contributions board where other contributors will be able to view it and improve on it further. We might even integrate it into our platform so that everyone can see it.</p>

			<form class="row g-3">
				<div class="col-lg-3">
					<div class="form-floating">
						<input type="text" class="form-control" id="name" required>
						<label for="name">Full Name</label>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="form-floating">
						<input type="url" class="form-control" id="website">
						<label for="website">Personal Website</label>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="form-floating">
						<input type="email" class="form-control" id="email" required>
						<label for="email">Email Address</label>
					</div>
				</div>
				<div class="col-lg-3 align-self-center">
					<div class="form-check form-switch">
						<input class="form-check-input" type="checkbox" id="publicemail" checked>
						<label class="form-check-label" for="publicemail">Let others contact you via email</label>
					</div>
				</div>

				<div class="col-lg-5">
					<div class="form-floating">
						<input type="text" class="form-control" id="title" required>
						<label for="title">Contribution Title</label>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="form-floating">
						<input type="url" class="form-control" id="contribwebsite">
						<label for="contribwebsite">Contribution Website</label>
					</div>
				</div>

				<div class="col-lg-9">
					<div class="form-floating">
						<textarea class="form-control" id="description" aria-label="Brief description of your contribution." style="height: 100px" required></textarea>
						<label for="description">Brief Description</label>
					</div>
				</div>

				<div class="col-lg-5 attach-col">
					<div>
						<label for="thumbnail" class="form-label upload-label">Thumbnail</label>
						<input class="form-control" id="thumbnail" type="file">
					</div>
				</div>
				<div class="col-lg-4 attach-col">
					<div>
						<label for="attachment" class="form-label upload-label">Attachment</label>
						<input class="form-control" id="attachment" type="file">
					</div>
				</div>

				<div class="col-12">
					<button type="submit" class="btn btn-primary">Submit Contribution</button>
				</div>
			</form>
		</div>
		<br>

		<!-- Contributions Board -->
		<div id="contributions">
			<h3>
				Contributions
				<small class="text-muted">What people have done with our data so far</small>
			</h3>

			<p>Showcasing what our wonderful users have contributed so far:</p>

			<div class="contrib-container">
				<div class="contrib-entry">
					<div class="row justify-content-md-center">
						<div class="col-md-7">
							<h4>Some interesting contribution title</h4>
							<small class="author">
								Submitted 2 days ago by <a href="#">Nathan Campos</a> &lt;<a href="mailto:hi@nathancampos.me">hi@nathancampos.me</a>&gt;
							</small>

							<p class="description">Whether you're a designer, a researcher, or someone that really enjoys data, you can contribute to make our platform even better! Researchers and data lovers can use our data to discover new and interesting correlations that should be included in the web application so that everyone can see them as well, and designers can find new and better ways to transform this data into beautiful visualizations that captivate our audience and brings meaning to the data that we are gathering.</p>
						</div>

						<div class="col-md-3">
							<img src="/assets/images/iade.png">
						</div>
					</div>

					<br>

					<div class="row justify-content-md-center">
						<div class="col-md-auto">
							<a class="btn btn-primary" href="#" role="button">Website</a>
							<a class="btn btn-primary" href="#" role="button">Attachment</a>
						</div>
					</div>

					<div class="row justify-content-md-center">
						<div class="col-md-10">
							<hr>
						</div>
					</div>
				</div>

				<div class="contrib-entry">
					<div class="row justify-content-md-center">
						<div class="col-md-7">
							<h4>Some interesting contribution title</h4>
							<small class="author">
								Submitted 2 days ago by <a href="#">Nathan Campos</a> &lt;<a href="mailto:hi@nathancampos.me">hi@nathancampos.me</a>&gt;
							</small>

							<p class="description">Whether you're a designer, a researcher, or someone that really enjoys data, you can contribute to make our platform even better! Researchers and data lovers can use our data to discover new and interesting correlations that should be included in the web application so that everyone can see them as well, and designers can find new and better ways to transform this data into beautiful visualizations that captivate our audience and brings meaning to the data that we are gathering.</p>
						</div>

						<div class="col-md-3">
							<img src="/assets/images/iade.png">
						</div>
					</div>

					<br>

					<div class="row justify-content-md-center">
						<div class="col-md-auto">
							<a class="btn btn-primary" href="#" role="button">Website</a>
							<a class="btn btn-primary" href="#" role="button">Attachment</a>
						</div>
					</div>
				</div>

				<br>
			</div>
		</div>
	</div>

<?php require(__DIR__ . "/../templates/footer.php"); ?>
