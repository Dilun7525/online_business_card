<?php
/*
 * получаем начальные настройки сайта
 * */
require_once $_SERVER['DOCUMENT_ROOT'] .
	'protected/configs/initial_setup/setup.php';
$title = "Home";
$logo = "/protected/m/img/logo.png";
$authorization = true;
$login = "Vasiy";
$fotoUser="/protected/m/img/22.jpg";
?>


<!DOCTYPE html>
<html lang="ru">
<head>
	<!-- Required meta tags -->
	<meta charset="utf8mb4">
	<meta name="viewport"
		  content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title><?= $title ?></title>

	<!-- CSS -->
	<link rel="stylesheet"
		  href="/css/bootstrap.min.css"
	>
	<link rel="stylesheet" href="/css/style.css">
</head>
<body class="cont-bg">
<header class="container">
	<div class="row">
		<div class="col-6 ">
			<div class="row justify-content-start align">
				<a href="/"><img  src="<?= $logo ?>" alt="logo"></a>
			</div>

		</div>
		<div class="col-6">
			<div class="row justify-content-end align-items-center">
				<?php if (!$authorization): ?>
					<img   src="/protected/m/img/user.png"
						 alt="logo">
					<button type="button" class="btn
				btn-outline-dark">Регистрация</button>
				<?php else: ?>
					<img  class="user-img" src="<?=$fotoUser?>" alt="">
					<button type="button" class="btn btn-outline-success"><?= $login?></button>
					<a href="/"><img  src="/protected/m/img/user_exit.png" alt="logo"></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</header>


