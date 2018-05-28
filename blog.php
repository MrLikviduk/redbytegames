<?php
    $page_name = 'Блог';
    session_start();
    include('elements/connection-info.php');
    $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name) or die("Error to connect to db");
    include('elements/to-authorizate.php')
    if ($_SESSION['logged_in'] == TRUE) {
        include('elements/blog-editor.php');
    };
    include('elements/blog-template.php');
    include('header.php');
?>
<form action="" method="POST" style="margin-left: 25vw;" id="edit_form">
    <input type="text" name="header" placeholder="Введите название">
    <input type="text" name="tags" placeholder="Введите теги" style="width: 25vw">
    <br>
    <textarea name="content" style="width: 40vw; height: 200px;">Контент</textarea>
    <br>
    <input type="submit" name="submit">
</form>
<?php
    $result = $mysqli->query("SELECT * FROM blog ORDER BY id DESC");
    while($row = $result->fetch_assoc()) {
        $date = explode('-' ,$row['creation_date']);
        $date = $date[2].'.'.$date[1].'.'.$date[0];
        echo show_blog($row['header'], $row['content'], $date, $row['tags'], $row['id']);
    }
?>
<?php include('footer.php'); $mysqli->close(); ?>