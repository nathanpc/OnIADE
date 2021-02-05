<?php require __DIR__ . "/../vendor/autoload.php"; ?>

<div class="card mb-3">
	<div class="card-body">
		<div class="row g-0">
			<div class="col-md-4">
				<img src="<?= $contrib->get_thumbnail_path() ?>">
			</div>

			<div class="col-md-8">
				<div class="card-body">
					<h5 class="card-title"><?= $contrib->get_title() ?></h5>

					<p class="card-text">
						<?= $contrib->get_description() ?>
					</p>

					<p class="card-text">
						<small class="text-muted">
							Submitted <?= $contrib->get_timestamp() ?> by

							<?php if ($contrib->has_personal_website()) { ?>
								<a href="<?= $contrib->get_personal_website() ?>">
									<?= $contrib->get_fullname() ?>
								</a>
							<?php } else { ?>
								<?= $contrib->get_fullname() ?>
							<?php } ?>

							<?php if ($contrib->can_show_email()) { ?>
								&lt;<a href="mailto:<?= $contrib->get_email() ?>"><?= $contrib->get_email() ?></a>&gt;
							<?php } ?>
						</small>
					</p>

					<div class="row justify-content-md-center">
						<div class="col-md-auto">
							<?php if ($contrib->has_url()) { ?>
								<a class="btn btn-primary" role="button"
										href="<?= $contrib->get_url() ?>">
									Website
								</a>
							<?php } ?>

							<?php if ($contrib->has_attachment()) { ?>
								<a class="btn btn-primary" role="button"
										href="<?= $contrib->get_attachment_path() ?>">
									Attachment
								</a>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>