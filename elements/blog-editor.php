<?php
    if(isset($_POST['header'])) {
        $date = date('Y-m-d');
        $header = $_POST['header'];
        $content = $_POST['content'];
        $tags = $_POST['tags'];
        $mysqli->query("INSERT INTO blogs (header, content, creation_date, tags) VALUES ($header, $content, $date, $tags)");
    }
?>
<form action="" method="POST" style="margin-left: 25vw;">
    <input type="text" name="header" placeholder="Введите название">
    <input type="text" name="tags" placeholder="Введите теги" style="width: 25vw">
    <br>
    <textarea name="content" style="width: 40vw; height: 200px;">Контент</textarea>
    <br>
    <input type="submit" name="submit">
</form>