<!--Возврашает пути скриптов, используемых в проекте
@return $pathRoot 	- до дириктории проекта
@return $pathBD 	- до дириктории базы данных
-->

<?php
$pathRoot = path_root();
$pathBD = path_bd();

function path_root (){
    return  $_SERVER['DOCUMENT_ROOT'];
}
function path_bd (){
    return  $_SERVER['DOCUMENT_ROOT'].'protected/m/bd/';
}


