<!--
Файл функций
-->
<?php
/*функция подключения шаблона по переданному пути и массиву переменных
использует функцию extract()- извлечение переменных с именами в key
ob_start();
ob_get_clean();
конструкция использования буфера обмена
*/

function setTemplate ($fileName, $vars = array()){
    extract($vars);
    ob_start();
    include $fileName;
    return ob_get_clean();
}