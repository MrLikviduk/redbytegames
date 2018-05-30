<?php
    $page_name = 'Редактор блога';
    session_start();
    if ($_SESSION['logged_in'] != TRUE || !isset($_SESSION['logged_in'])) {
        include($_SERVER['DOCUMENT_ROOT'].'/header.php');
        echo '<div style="margin-left: 25vw">У вас нет прав для просмотра данной страницы</div>';
        include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
        exit();
    }
    include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
    $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name) or die("Error to connect to db");
    if(isset($_POST['header']) && $_POST['header'] != '' && $_POST['content'] != '') {
        $date = date('Y-m-d');
        $header = $_POST['header'];
        $content = $_POST['content'];
        $tags = $_POST['tags'];
        $mysqli->query("INSERT INTO blog (id, header, content, creation_date, tags) VALUES (NULL, '$header', '$content', '$date', '$tags')") or die("Error");
        header("Location: ".$_SERVER['REQUEST_URI']);
    }
    $mysqli->close();
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