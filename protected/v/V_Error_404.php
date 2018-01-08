<?php
/**получаем начальные настройки сайта
 */
require_once PATH_ROOT . PATH_CONFIGS . "initial_setup/setup.php";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport"
		  content="with=device-width, initial-scale=1, shrink-to-fit=no">

	<title><?= $title ?></title>

	<!-- CSS -->
	<!--<link rel="stylesheet"
		  href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
		  integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb"
		  crossorigin="anonymous">-->
	<link rel="stylesheet" href="/css/style.css">
	<link rel="stylesheet" href="/css/bootstrap.min.css">

</head>
<body class="bg-404">

<!--Content-->
<div class="container">
	<div class="row  justify-content-around ">
		<a href="home" class="btn-404-margin">
			<div class="row align-items-center justify-content-around btn-404">
				Бежим отсюда
			</div>
		</a>
	</div>
</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="/protected/m/js/bootstrap.js"></script>
<script src="/protected/m/js/bootstrap.bundle.js"></script>
<script src="/protected/m/js/popper.js"></script>

</body>
</html>