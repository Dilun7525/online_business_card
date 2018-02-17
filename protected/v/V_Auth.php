<?php
/**загрузка заголовка*/
require_once PATH_TEMPLATE_R. 'setup_head.php';
?>
<body>
<!--Content-->
<div class="container">
	<div class="row  justify-content-around ">
		<div class="inputError <?=$hiddenErrorDiv?>">
			<?=$resultError?>
		</div>
	</div>
	<div class="row  justify-content-around ">
		<div class="marginDiv">
			Войдите или
			<span><a href="/auth/registration">зарегистрируйтесь</a></span>
		</div>
	</div>
	<div class="row  justify-content-around ">
		<form method="post" action="/auth/validate_input_form/registration">
			<div class="form-group">
				<label for="login">Введите имя или email </label>
				<input type="text"
					   class="form-control"
					   id="login"
					   name="login"
					   placeholder="Name or Email">
			</div>
			<div class="form-group">
				<label for="pass">Пароль</label>
				<input type="password"
					   class="form-control"
					   id="pass"
					   name="pass"
					   placeholder="Password">
			</div>
			<div class="form-check">
				<input type="checkbox"
					   class="form-check-input"
					   id="Check"
					   name="rememberUser">
				<label class="form-check-label" for="Check">Запомнить меня</label>
			</div>
			<button type="submit" class="btn btn-primary marginDiv">Войти</button>
		</form>
	</div>
</div>
</body>
</html>