<?php
/**загрузка заголовка*/
require_once PATH_TEMPLATE_R. 'setup_head.php';
?>
<body>
<!--Content-->
<div class="container">
	<div class="row  justify-content-around ">
		<div class="inputError <?= $hiddenErrorDiv ?>">
			<?= $resultError ?>
		</div>
	</div>
	<div class="row  justify-content-around ">
		<div class="marginDiv">
			Зарегистрируйтесь или
			<span><a href="/auth/login">войдите</a></span>
		</div>
	</div>
	<div class="row  justify-content-around ">
		<form action="/auth/validate_register_form" method="post">
			<div class="form-group">
				<label for="login">Введите логин </label>
				<input type="text"
					   class="form-control"
					   id="login"
					   name="login"
					   placeholder="Login">
			</div>
			<div class="form-group">
				<label for="InputPassword">Введите пароль</label>
				<input type="password"
					   class="form-control"
					   id="InputPassword"
					   name="pass"
					   placeholder="Password">
			</div>
			<div class="form-group">
				<label for="email">Введите email</label>
				<input type="email"
					   class="form-control"
					   id="email"
					   name="email"
					   placeholder="name@example.ru">
			</div>

			<div class="form-group">
				<label for="nameUser">Введите Имя</label>
				<input type="text"
					   class="form-control"
					   id="nameUser"
					   name="nameUser"
					   placeholder="Имя">
			</div>

			<div class="form-group">
				<label for="middleNameUser">Введите Отчество</label>
				<input type="text"
					   class="form-control"
					   id="middleNameUser"
					   name="middleNameUser"
					   placeholder="Отчество">
			</div>

			<div class="form-group">
				<label for="surnameUser">Введите Фамилию</label>
				<input type="text"
					   class="form-control"
					   id="surnameUser"
					   name="surnameUser"
					   placeholder="Фамилия">
			</div>

			<div class="form-group">
				<input type="checkbox"
					   class="form-check-input"
					   id="Check"
					   name="rememberUser">
				<label class="form-check-label" for="Check">Запомнить меня
				</label>
			</div>

			<button type="submit" class="btn btn-primary marginDiv">
				Зарегистрироваться и войти
			</button>

		</form>
	</div>
</div>
</body>
</html>