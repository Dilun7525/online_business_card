<?php
/**загрузка заголовка*/
require_once $_SERVER['DOCUMENT_ROOT'] . 'protected/v/template/setup_head.php';?>

<body> <!--class="cont-bg"-->
<header class="container">
	<div class="row  align-items-center no-gutters">
		<div class="col-6">
			<div class="row justify-content-start">
				<a href="/"><img src="/<?= PATH_IMG . $logo ?>" alt="logo"></a>
			</div>
		</div>
		<div class="col-6">
			<div class="row justify-content-end align-items-center">
				<?php if (!$authorization): ?>
					<img src="/<?= PATH_IMG?>user.png" alt="logo">
					<button type="button" class="btn
				btn-outline-dark">Регистрация
					</button>
				<?php else: ?>
					<img class="user-img" src="/<?= PATH_DB_IMG . $fotoUser ?>"
						 alt="">
					<button type="button"
							class="btn btn-outline-success"><?= $login ?></button>
					<a href="/"><img src="/<?= PATH_IMG?>user_exit.png"
									 alt="logo"></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</header>

<!--Content-->
<div class="container">
	<div class="row">

