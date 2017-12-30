<?php
/**Возврашает пути скриптов, используемых в проекте
 * @return "PATH_ROOT" 		- проекта
 * @return "PATH_CONFIGS"	- конфигурационных файлов
 * @return "PATH_MODEL"		- модели
 * @return "PATH_VIEW"		- шаблонов
 * @return "PATH_CONTROLLER"- контролеров
 * @return "PATH_BD" 		- базы данных
 * @return "PATH_IMG"		- изображений
 * @return "PATH_BD_IMG"	- изображений для работы с БД
 * */
define("PATH_ROOT",		 	$_SERVER['DOCUMENT_ROOT']."/");
define("PATH_CONFIGS",	 	'protected/configs/');
define("PATH_MODEL",	 	"protected/m/");
define("PATH_VIEW", 	 	"protected/v/");
define("PATH_CONTROLLER",	"protected/c/");
define("PATH_BD",		 	"protected/m/db/");
define("PATH_BD_IMG",	 	"protected/m/db/img/");
define("PATH_IMG",	 	 	"protected/m/img/");



