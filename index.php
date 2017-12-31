<?php
/*
 * получаем начальные настройки кодировок,
 * переменные с путями скриптов ($pathRoot,$pathBD, $pathConfigs)
 * */
require_once $_SERVER['DOCUMENT_ROOT'] .
	'protected/configs/initial_setup/setup.php';
session_start();

// Разбираем URL на параметры, 1-й контроллер, 2-й action
// например /articles/edit
$urlinfo = $_SERVER['REQUEST_URI'];
$urlparts = explode('/', $urlinfo);

$params = [];

foreach ($urlparts as $v) {
	if ($v != 'index.php' && $v != '') {
		$params[] = $v;
	}
}

// извлекаем из массива параметров контроллер
$controller = isset($params[0]) ? array_shift($params) : 'index';

// извлекаем из массива параметров action
$action = isset($params[0]) ? array_shift($params) : 'index';

// Динамически формируем контроллер
$controller = 'C_' . ucfirst($controller);
// Формируем action
$action = 'action_' . $action;

// Контроллер по-умолчанию
if (!class_exists($controller)) {
	$controller = 'C_Home';
}

$controllerObj = new $controller();
$controllerObj->Request($action, $params);




