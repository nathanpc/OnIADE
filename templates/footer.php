	<!-- Footer -->
	<footer>
		<div class="container">
			<hr>
			<div class="container">
				<div class="row">
					<div class="col">
						<?= $config->app->name ?> &#169; 2020-<?= date("Y") ?> <a href="https://innoveworkshop.com/">Innove Workshop</a>
					</div>

					<div class="col" style="text-align: right;">
						Designed and built by <a href="https://nathancampos.me/">@nathanpc</a> and <a href="https://www.instagram.com/digobraga8/">@digobraga8</a>
					</div>
				</div>

				<br>
			</div>
		</div>
	</footer>

	<!-- Enable tooltips. -->
	<script type="text/javascript">
		window.addEventListener("load", function () {
			var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
			var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
				return new bootstrap.Tooltip(tooltipTriggerEl);
			});
		});
	</script>
</body>
</html>
