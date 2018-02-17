<?php
/**Возврашает пути скриптов, используемых в проекте
 * @return "PATH_ROOT" 		- проекта
 * @return "PATH_CONFIGS"	- конфигурационных файлов
 * @return "PATH_SETUP"		- первоначальные настройки
 * @return "PATH_CONTROLLER"- контролеров
 * @return "PATH_MODEL"		- модели
 * @return "PATH_IMG"		- изображений
 * @return "PATH_BD_IMG"	- изображений для работы с БД
 * @return "PATH_JS"		- JavaScript
 * @return "PATH_VIEW"		- шаблонов
 * @return "PATH_TEMPLATE"	- шаблонов heder & footer
 * @return "PATH_BD" 		- базы данных
 * @return "..._R" 			- путь абсолютный
 * */

substr($_SERVER['DOCUMENT_ROOT'],-1)=="/"
	?define("PATH_ROOT",	$_SERVER['DOCUMENT_ROOT'])
	:define("PATH_ROOT",	$_SERVER['DOCUMENT_ROOT']."/");


define("PATH_CONFIGS",	 	'/protected/configs/');
define("PATH_SETUP",	 	'/protected/configs/initial_setup/');
define("PATH_CONTROLLER",	"/protected/c/");
define("PATH_MODEL",	 	"/protected/m/");
define("PATH_IMG",	 	 	"/protected/m/img/");
define("PATH_DB_IMG",	 	"/protected/m/img/db/");
define("PATH_JS",	 		"/protected/m/js/");
define("PATH_VIEW", 	 	"/protected/v/");
define("PATH_TEMPLATE", 	"/protected/v/template/");

//Абсолютные пути
define("PATH_CONFIGS_R",	 PATH_ROOT . "protected/configs/");
define("PATH_SETUP_R",	 	 PATH_ROOT . "protected/configs/initial_setup/");
define("PATH_CONTROLLER_R",	 PATH_ROOT . "protected/c/");
define("PATH_MODEL_R",	 	 PATH_ROOT . "protected/m/");
define("PATH_IMG_R",	 	 PATH_ROOT . "protected/m/img/");
define("PATH_DB_IMG_R",	 	 PATH_ROOT . "protected/m/img/db/");
define("PATH_JS_R",	 		 PATH_ROOT . "protected/m/js/");
define("PATH_VIEW_R", 	 	 PATH_ROOT . "protected/v/");
define("PATH_TEMPLATE_R", 	 PATH_ROOT . "protected/v/template/");

