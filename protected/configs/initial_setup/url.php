<?php
/**Возврашает пути скриптов, используемых в проекте
 * @return $pathRoot 	- до дириктории проекта
 * @return $pathBD 	- до дириктории базы данных
 * @return $pathConfigs- до дириктории конфигурационных файлов
 * */
$pathRoot 	= $_SERVER['DOCUMENT_ROOT'];
$pathBD 	= $_SERVER['DOCUMENT_ROOT'] . 'protected/m/db/';
$pathConfigs= $_SERVER['DOCUMENT_ROOT'] . 'protected/configs/';
$pathImg	= $_SERVER['DOCUMENT_ROOT'] . 'protected/m/img/';

