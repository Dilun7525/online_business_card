<!--
@return  Возврашает путь до проекта
-->
<?php
$pathRoot = path_root();

function path_root (){
    return  $_SERVER['DOCUMENT_ROOT'];
}


