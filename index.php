<?php
/*
 * получаем начальные настройки кодировок,
 * переменные с путями скриптов ($pathRoot,$pathBD, $pathConfigs)
 * */
require_once $_SERVER['DOCUMENT_ROOT'] .
	'protected/configs/initial_setup/setup.php';

require($pathRoot . 'protected/v/template/header.php');
require($pathRoot . 'protected/v/template/footer.php');



//Вызываем контроллер
//require ($pathRoot.'protected/c/c_common.php');




