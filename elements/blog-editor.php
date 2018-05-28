<?php
    function setRandomSessid() {
	    $_SESSION['sessid'] = md5(date('d.m.Y H:i:s').rand(1, 1000000));
    }
    setRandomSessid();
    if(isset($_POST['header']) && $_POST['sessid'] == $_SESSION['sessid']) {
        $date = date('Y-m-d');
        $header = $_POST['header'];
        $content = $_POST['content'];
        $tags = $_POST['tags'];
        $mysqli->query("INSERT INTO blog (id, header, content, creation_date, tags) VALUES (NULL, '$header', '$content', '$date', '$tags')") or die("Error");
    }
?>
<form action="" method="POST" style="margin-left: 25vw;">
    <input type="text" name="header" placeholder="Введите название">
    <input type="text" name="tags" placeholder="Введите теги" style="width: 25vw">
    <br>
    <textarea name="content" style="width: 40vw; height: 200px;">Контент</textarea>
    <br>
    <input type='hidden' name='sessid' value='<?=$_SESSION['sessid']?>'>
    <input type="submit" name="submit">
</form>