<?php
/*
 * получаем начальные настройки кодировок,
 * переменные с путями скриптов ($pathRoot,$pathBD)
 * */
require_once $_SERVER['DOCUMENT_ROOT'] . '/protected/m/initial_setup/setup.php';

require ($pathRoot.'protected/m/bd/bd.php');

$bd=new bd($pathBD.'bd_install.txt');
$bd->connect();
$bd->disconnect();

$bd=new bd($pathBD.'bd_install.txt');
$bd->connect();
$bd->disconnect();

$bd=new bd($pathBD.'bd_install.txt');
$bd->connect();
$bd->disconnect();

//Вызываем контроллер
//require ($pathRoot.'protected/c/c_common.php');
