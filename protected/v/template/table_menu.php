<?php
/**
 * @param $linkimgMenu			- ссылка на другую страницу
 * @param $imgMenu				- имя файла с изображением
 * @param $descriptionImgMenu	- описание изображения
 */
?>
<div class="col-12 col-sm-6 col-md-4">
	<div class="row justify-content-center">
		<div class="focus pic">
			<a href="<?= $linkimgMenu?>">
				<img class="img-size" src="/<?=PATH_DB_IMG.$imgFile?>"
					 alt="<?=$descriptionImgMenu?>">
			</a>
		</div>
	</div>
</div>
