<?php
    if(isset($_POST['header']) && $_POST['header'] != '' && $_POST['content'] != '') {
        $date = date('Y-m-d');
        $header = $_POST['header'];
        $content = $_POST['content'];
        $tags = $_POST['tags'];
        $mysqli->query("INSERT INTO blog (id, header, content, creation_date, tags) VALUES (NULL, '$header', '$content', '$date', '$tags')") or die("Error");
        header("Location: ".$_SERVER['REQUEST_URI']);
    }
    function show_blog_editor() {
        echo '
        <form action="" method="POST" style="margin-left: 25vw;" id="edit_form">
            <input type="text" name="header" placeholder="Введите название">
            <input type="text" name="tags" placeholder="Введите теги (через пробел)" style="width: 25vw">
            <br>
            <textarea name="content" style="width: 40vw; height: 200px;">Контент</textarea>
            <br>
            <input type="submit" name="submit">
        </form>
        ';
    }
?>