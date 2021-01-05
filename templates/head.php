<?php $config = require(__DIR__ . "/../config/config.php"); ?>
<?php require __DIR__ . "/../vendor/autoload.php"; ?>
<?php $spy = new \OnIADE\Device\Spy(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title><?= $config->app->name ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Bootstrap -->
	<link async href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
	<script defer async src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

	<!-- Font Awesome -->
	<script src="https://kit.fontawesome.com/ae0b599810.js" crossorigin="anonymous"></script>

	<!-- Custom stuff -->
	<script type="text/javascript" src="/assets/js/main.js"></script>
</head>
<body>
	<?php require __DIR__ . "/navbar.php"; ?>
