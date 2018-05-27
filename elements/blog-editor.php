<?php
    if(isset($_POST['header'])) {
        $result = $mysqli->query("SELECT * FROM blog");
        $v = FALSE;
        while ($row = $result->fetch_assoc()) {
            if ($row['header'] == $_POST['header'])
                $v = TRUE;
        }
        if (!$v) {
            $date = date('Y-m-d');
            $header = $_POST['header'];
            $content = $_POST['content'];
            $tags = $_POST['tags'];
            $mysqli->query("INSERT INTO blog (id, header, content, creation_date, tags) VALUES (NULL, '$header', '$content', '$date', '$tags')") or die("Error");
            unset($_POST['header']);
        }
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