<?php
    $page_name = 'Редактор блога';
    if(isset($_POST['header']) && $_POST['header'] != '' && $_POST['content'] != '') {
        $date = date('Y-m-d');
        $header = $_POST['header'];
        $content = $_POST['content'];
        $tags = $_POST['tags'];
        $mysqli->query("INSERT INTO blog (id, header, content, creation_date, tags) VALUES (NULL, '$header', '$content', '$date', '$tags')") or die("Error");
        header("Location: ".$_SERVER['REQUEST_URI']);
    }
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<a href="/blog.php" style="margin: 20px auto; display: block;">Назад</a>
<form action="" method="POST" id="edit_form">
    <label for="header">Заголовок: </label>
    <input type="text" name="header" placeholder="Введите название" style="width: 20vw;">
    <br>
    <label for="tags">Теги: </label>
    <input type="text" name="tags" placeholder="Введите теги (через пробел)" style="width: 20vw;">
    <br>
    <label for="content">Контент: </label>
    <textarea name="content" style="width: 40vw; height: 200px;" placeholder="Введите содержимое блога"></textarea>
    <br>
    <input type="submit" name="submit" value="Добавить" class="submit-button">
</form>
<?php 
    include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
?>