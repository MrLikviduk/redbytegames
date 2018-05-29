<?php
    $page_name = 'Блог';
    session_start();
    include('elements/connection-info.php');
    $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name) or die("Error to connect to db");
    include('elements/to-authorizate.php');
    include('elements/blog-editor.php');
    include('elements/blog-template.php');
    include('header.php');
    if ($_SESSION['logged_in'] == TRUE)
        show_blog_editor();
    else
        show_login_form();
?>
<?php
    $result = $mysqli->query("SELECT * FROM blog ORDER BY id DESC");
    while($row = $result->fetch_assoc()) {
        $date = explode('-' ,$row['creation_date']);
        $date = $date[2].'.'.$date[1].'.'.$date[0];
        echo show_blog($row['header'], $row['content'], $date, $row['tags'], $row['id']);
    }
?>
<?php include('footer.php'); $mysqli->close(); ?>