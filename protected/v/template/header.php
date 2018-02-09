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

				<?php if ($authorization): ?>
					<img class="user-img" src="/<?= PATH_DB_IMG . $userPhoto ?>"
						 alt="<?=$login ?>">

					<div class="marginDiv">
						<span><a href="/users"><?=	$login ?></a></span>
					</div>

					<a href="/auth/exit_user">
						<img src="/<?= PATH_IMG?>user_exit.png" alt="logo">
					</a>

				<?php else: ?>
					<img src="/<?= PATH_IMG?>user.png" alt="logo">

					<div class="marginDiv">
						<span><a href="/auth/login">Авторизация</a></span>
					</div>

				<?php endif; ?>

			</div>
		</div>
	</div>
</header>

<!--Content-->
<div class="container">
	<div class="row">

