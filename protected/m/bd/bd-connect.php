<?php
/* получаем начальные настройки кодировок,
 * переменную с путем до корня сайта ($pathRoot)*/
require_once $_SERVER['DOCUMENT_ROOT'] . '/protected/m/initial_setup/setup.php';
require $pathRoot.'protected/m/bd/bd-install.php';
$ii=0;
// подключаемся к серверу c проверкой на наличие открытого соединения
if (!$openConnect){
	$link = mysqli_connect($host, $user, $password, $database)
	or die("Ошибка подключения к БД" . mysqli_error($link));
	$openConnect = true;
	mysqli_query($link,"SET NAMES utf8mb4");
	mysqli_set_charset($link,'utf8mb4');

	echo "В БД вошли_".++$ii."<br>";

}else{
	require $pathRoot.'protected/m/bd/bd-disconnect.php';
	echo "Из БД вышли"."<br>";
	$openConnect = false;

}




/*mysqli_query($link,"SET CHARACTER SET 'utf8';");
mysqli_query($link,"SET SESSION collation_connection = 'utf8_general_ci';");
*/
