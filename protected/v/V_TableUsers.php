<?php if($i == 1): ?>
<div class="row justify-content-center">
	<table class="table-sm table-striped tbody-hover table-dark">
		<thead>
		<tr>
			<th scope="col">#</th>
			<th scope="col"><a href="/users/sort_login">	Логин 	</a></th>
			<th scope="col">							Email 	</th>
			<th scope="col"><a href="/users/sort_family">Имя 	</a></th>
			<th scope="col"><a href="/users/sort_family">Отчество</a></th>
			<th scope="col"><a href="/users/sort_family">Фамилия </a></th>

			<?php if($trueAdmin): ?>
				<th scope="col"><a href="/users/sort_role">Роль</a></th>
			<?php endif; ?>
		</tr>
		</thead>
		<tbody>
		<?php endif; ?>

		<tr>
			<th scope="row">
				<a href="/users/show_profile/<?= $id ?>"><?= $i ?></a>
				</th>
			<td><?= $login ?></td>
			<td><?= $email ?></td>
			<td><?= $first_name ?></td>
			<td><?= $middle_name ?></td>
			<td><?= $surname ?></td>

			<?php if($trueAdmin): ?>
				<td><?= $role ?></td>
			<?php endif; ?>
			<td>
				<a href="/users/show_profile/<?= $id ?>">
					<img  class="eye" src="/<?= PATH_IMG ?>eye_2.png" alt="Show">
				</a>
			</td>

		</tr>

		<?php if($i == $iEnd): ?>
		</tbody>
	</table>
</div>
<?php endif; ?>
