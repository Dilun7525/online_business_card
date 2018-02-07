<?php
/**загрузка заголовка*/
require_once $_SERVER['DOCUMENT_ROOT'] . PATH_TEMPLATE . 'setup_head.php';
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
		<form method="post">
			<div class="form-group">
				<label for="login">Логин </label>
				<input type="text"
					   class="form-control"
					   id="login"
					   name="login"
					   value="<?= $login ?>">
			</div>
			<div class="form-group">
				<label for="InputPassword">Пароль</label>
				<input type="password"
					   class="form-control"
					   id="InputPassword"
					   name="pass"
					   value="<?= $pass ?>">
			</div>
			<div class="form-group">
				<label for="email">Email</label>
				<input type="email"
					   class="form-control"
					   id="email"
					   name="email"
					   value="<?= $email ?>">
			</div>

			<div class="form-group">
				<label for="nameUser">Имя</label>
				<input type="text"
					   class="form-control"
					   id="nameUser"
					   name="nameUser"
					   value="<?= $first_name ?>">
			</div>

			<div class="form-group">
				<label for="middleNameUser">Отчество</label>
				<input type="text"
					   class="form-control"
					   id="middleNameUser"
					   name="middleNameUser"
					   value="<?= $middle_name ?>">
			</div>

			<div class="form-group">
				<label for="surnameUser">Фамилия</label>
				<input type="text"
					   class="form-control"
					   id="surnameUser"
					   name="surnameUser"
					   value="<?= $surname ?>">
			</div>

			<?php if($trueAdmin): ?>

				<div class="form-group">
					<label for="role">Роль</label>
					<select class="form-control"
							id="role"
							name="role">
						<?php foreach ($roles as $value): ?>
							<option
								<?php if($value===$role): ?>
									selected
							    <?php endif; ?>
							><?= $value ?></option>
						<?php endforeach; ?>
					</select>
				</div>


				<div class="form-group">
					<p>Правка и создание происходит <br/>через одну и ту же
						форму.<br/>Для создания замените данные.
					</p>
				</div>
			<?php endif; ?>
			<?php if($trueAdmin || $selfIdUsers): ?>
				<button type="submit"
						formaction="/users/edit_profile/<?= $id ?>"
						class="btn btn-primary marginDiv">
					Править
				</button>
			<?php endif; ?>
			<?php if($trueAdmin): ?>
				<button type="submit"
						formaction="/users/delete_profile/<?= $id ?>"
						class="btn btn-primary marginDiv">
					Удалить
				</button>
				<button type="submit"
						formaction="/users/create_profile/<?= $id ?>"
						class="btn btn-primary marginDiv">
					Создать
				</button>
			<?php endif; ?>
		</form>


		</form>
	</div>
</div>
</body>
</html>