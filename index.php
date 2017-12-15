<?php
/*
 * получаем начальные настройки кодировок,
 * переменные с путями скриптов ($pathRoot,$pathBD, $pathConfigs)
 * */
require_once $_SERVER['DOCUMENT_ROOT'] . '/protected/configs/initial_setup/setup.php';

require($pathRoot . 'protected/m/db/db.php');

$dataBase1 = db::getInstance($pathConfigs . 'db_install.txt');
$dataBase1->connect();
$object=[
	'test1'=>"O'kipling<rger>\||",
	'test2'=>"boergergeok's"
];
	$where='id=1';
	$tr = htmlspecialchars("O'kipling<rger>||");
	echo "tr= ".$tr."<br>";
	$query = 'SELECT * FROM test_table WHERE test1='.$tr;
$dataBase1->select($query) ;



$dataBase1->disconnect();


$dataBase2 = db::getInstance($pathConfigs . 'db_install.txt');
$dataBase2->connect();
$dataBase2->disconnect();

//Вызываем контроллер
//require ($pathRoot.'protected/c/c_common.php');




