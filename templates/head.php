<?php $config = require(__DIR__ . "/../config/config.php"); ?>
<?php require __DIR__ . "/../vendor/autoload.php"; ?>
<?php $spy = new \OnIADE\Device\Spy(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title><?= $config->app->name ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="apple-touch-icon-precomposed" sizes="57x57" href="/assets/favicons/apple-touch-icon-57x57.png" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="/assets/favicons/apple-touch-icon-114x114.png" />
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="/assets/favicons/apple-touch-icon-72x72.png" />
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/assets/favicons/apple-touch-icon-144x144.png" />
	<link rel="apple-touch-icon-precomposed" sizes="60x60" href="/assets/favicons/apple-touch-icon-60x60.png" />
	<link rel="apple-touch-icon-precomposed" sizes="120x120" href="/assets/favicons/apple-touch-icon-120x120.png" />
	<link rel="apple-touch-icon-precomposed" sizes="76x76" href="/assets/favicons/apple-touch-icon-76x76.png" />
	<link rel="apple-touch-icon-precomposed" sizes="152x152" href="/assets/favicons/apple-touch-icon-152x152.png" />
	<link rel="icon" type="image/png" href="/assets/favicons/favicon-196x196.png" sizes="196x196" />
	<link rel="icon" type="image/png" href="/assets/favicons/favicon-96x96.png" sizes="96x96" />
	<link rel="icon" type="image/png" href="/assets/favicons/favicon-32x32.png" sizes="32x32" />
	<link rel="icon" type="image/png" href="/assets/favicons/favicon-16x16.png" sizes="16x16" />
	<link rel="icon" type="image/png" href="/assets/favicons/favicon-128.png" sizes="128x128" />
	<meta name="application-name" content="&nbsp;"/>
	<meta name="msapplication-TileColor" content="#FFFFFF" />
	<meta name="msapplication-TileImage" content="/assets/favicons/mstile-144x144.png" />
	<meta name="msapplication-square70x70logo" content="/assets/favicons/mstile-70x70.png" />
	<meta name="msapplication-square150x150logo" content="/assets/favicons/mstile-150x150.png" />
	<meta name="msapplication-wide310x150logo" content="/assets/favicons/mstile-310x150.png" />
	<meta name="msapplication-square310x310logo" content="/assets/favicons/mstile-310x310.png" />

	<!-- Bootstrap -->
	<link async href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
	<script defer async src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

	<!-- Font Awesome -->
	<script src="https://kit.fontawesome.com/ae0b599810.js" crossorigin="anonymous"></script>

	<!-- Custom stuff -->
	<link href="/assets/css/default.css" rel="stylesheet">
	<script type="text/javascript" src="/assets/js/http.js"></script>
	<script type="text/javascript" src="/assets/js/bgtoggler.js"></script>
</head>
<body>
	<?php require __DIR__ . "/navbar.php"; ?>
