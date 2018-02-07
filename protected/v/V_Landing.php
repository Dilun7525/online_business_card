<?php
/**загрузка заголовка*/
require_once $_SERVER['DOCUMENT_ROOT'] . 'protected/v/template/setup_head.php';?>
<!--// todo  Сначала три блока, для отработки JS.-->
<body >
<!--Content-->
<div class="container-fluid ">
	<div class="card-deck size">
		<div class="layer card1"></div>
		<div class="layer card2"></div>
		<div class="layer card3"></div>
	</div>
</div>


<?php
/**загрузка скриптов*/
//require_once PATH_VIEW. 'template/setup_footer_script.php';?>
<script src="<?=PATH_MODEL?>js/card_deck.js"> </script>
</body>
</html>



