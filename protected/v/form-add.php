<!--
Обработчик:
url: '../protected/model/bd/bdr-article.php',
-->
</br>
<h3>Добавление статьи</h3>
<form method="POST"
      id="form-add"
      action="javascript:void(null);"
      onsubmit="call('form-add','../protected/model/bd/bdr-add-entry.php','resAdd')">
    <label for="addArticle"> Article: </label>
    <input id="addArticle" name="addArticle" value="" type="text">
    </br>
    <label for="addAutor"> Autor: </label>
    <textarea id="addAutor" name="addAutor" value="" rows="10" >

    </textarea>
   <!-- <input id="addAutor" name="addAutor" value="" type="text">-->
    </br>
    <input value="Send" type="submit">
</form>

<div id="resAdd"></div>