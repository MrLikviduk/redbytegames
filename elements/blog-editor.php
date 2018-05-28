<?php
    if(isset($_POST['header']) ) {
        $date = date('Y-m-d');
        $header = $_POST['header'];
        $content = $_POST['content'];
        $tags = $_POST['tags'];
        $mysqli->query("INSERT INTO blog (id, header, content, creation_date, tags) VALUES (NULL, '$header', '$content', '$date', '$tags')") or die("Error");
        header("Refresh: 0");
    }
    function show_blog_editor() {
        echo '
        <form action="" method="POST" style="margin-left: 25vw;" id="edit_form">
            <input type="text" name="header" placeholder="Введите название">
            <input type="text" name="tags" placeholder="Введите теги" style="width: 25vw">
            <br>
            <textarea name="content" style="width: 40vw; height: 200px;">Контент</textarea>
            <br>
            <input type="submit" name="submit">
        </form>
        ';
    }
?>