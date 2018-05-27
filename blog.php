<?php
    $page_name = 'Блог';
    session_start();
    include('elements/connection-info.php');
    $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name) or die("Error to connect to db");
    include('header.php');
    include('elements/to-authorizate.php');
    include('elements/blog-template.php');
    if ($_SESSION['logged_in'] == TRUE) {
        include('elements/blog-editor.php');
    }
    $result = $mysqli->query("SELECT * FROM blog ORDER BY id DESC");
    while($row = $result->fetch_assoc()) {
        $date = $row['creation_date'];
        $date = substr($date, 8) + '.' + substr($date, 5, 2) + '.' + substr($date, 0, 2);
        echo show_blog($row['header'], $row['content'], $date, $row['tags']);
    }
?>
<?php include('footer.php'); $mysqli->close(); ?>