<?php
    $page_name = 'Редактор блога';
    session_start();

    if ($_SESSION['logged_in'] != TRUE || !isset($_SESSION['logged_in'])) {
        include($_SERVER['DOCUMENT_ROOT'].'/header.php');
        echo '<div style="margin: 30px auto;">У вас нет прав для просмотра данной страницы</div>';
        include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
        exit();
    }

    if (!isset($date))
        $date = date('Y-m-d');
    if (!isset($_SESSION['id_to_edit_blog']))
        $_SESSION['id_to_edit_blog'] = -1;
    include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
    $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name) or die("Error to connect to db");
    $query = $mysqli->query("SELECT * FROM blog WHERE id LIKE ".$_SESSION['id_to_edit_blog']);
    $to_edit = $query->fetch_assoc();
    if(isset($_POST['header']) && $_POST['header'] != '' && $_POST['content'] != '' && isset($_POST['submit']) && $_SESSION['logged_in'] == TRUE) {
        $header = $_POST['header'];
        $content = $_POST['content'];
        $tags = $_POST['tags'];
        if ($_SESSION['id_to_edit_blog'] == -1)
            $mysqli->query("INSERT INTO blog (id, header, content, creation_date, tags) VALUES (NULL, '$header', '$content', '$date', '$tags')") or die("Error");
        else {
            $t_id = $_SESSION['id_to_edit_blog'];
            $mysqli->query("DELETE FROM blog WHERE id LIKE $t_id") or die("Blin(");
            $date = $to_edit['creation_date'];
            $mysqli->query("INSERT INTO blog (id, header, content, creation_date, tags) VALUES ($t_id, '$header', '$content', '$date', '$tags')") or die("Error");
        }
        $_SESSION['id_to_edit_blog'] = -1;
        header("Location: ".$_SERVER['REQUEST_URI']);
    }
    $mysqli->close();
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<a href="/blog.php" style="margin: 20px auto; display: block;">Назад</a>
<form action="" method="POST" id="edit_form">
    <label for="header">Заголовок: </label>
    <input type="text" name="header" id="header_id" placeholder="Введите название" class="text-box">
    <br>
    <label for="tags">Теги: </label>
    <input type="text" name="tags" id="tags_id" placeholder="Введите теги (через пробел)" class="text-box">
    <br>
    <label for="content">Контент: </label>
    <textarea name="content" id="content_id" placeholder="Введите содержимое блога"></textarea>
    <br>
    <a href="/elements/choose-image-for-blog.php"><div class="insert-image-button">Вставить картинку</div></a>
    <br>
    <input type="submit" name="submit" value="Добавить" class="submit-button">
</form>
<?php 
    if ($_SESSION['id_to_edit_blog'] != -1) {
        echo "
            <script>
                document.getElementById('header_id').value = '".$to_edit['header']."';
                document.getElementById('content_id').innerHTML = '".$to_edit['content']."';
                document.getElementById('tags_id').value = '".$to_edit['tags']."';
            </script>
        ";
    }
    include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
?>