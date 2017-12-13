<?php
//получаем адрес root папки сайта
require_once $_SERVER['DOCUMENT_ROOT'] . '/protected/model/initial_setup/url.php';

include $pathRoot . 'protected/view/template/header.php';
include $pathRoot . 'protected/model/local.php';
?>

<div class="container">
    <div class="row">
        <div class="col-1 "></div>
        <div class="col">

            <?php include $pathRoot . 'protected/model/bd/bdr-articles.php';?>
            <?php include $pathRoot . 'protected/view/form-article.php';?>
            <?php include $pathRoot . 'protected/view/form-edit.php';?>
            <?php include $pathRoot . 'protected/view/form-add.php';?>
            <?php include $pathRoot . 'protected/view/form-del.php';?>

        </div>
        <div class="col-1 "></div>
    </div>
</div>

<?php include $pathRoot . 'protected/view/template/footer.php';?>



