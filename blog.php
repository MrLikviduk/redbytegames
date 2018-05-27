<?php
    $page_name = 'Блог';
    session_start();
    include('elements/connection-info.php');
    $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
    include('header.php');
    include('elements/to-authorizate.php');
    include('elements/blog-template.php');
    if ($_SESSION['logged_in'] == TRUE) {
        include('elements/blog-editor.php');
    }
    $result = $mysqli->query("SELECT * FROM blog ORDER BY creation_date");
    while($row = $result->fetch_assoc()) {
        echo show_blog($row['header'], $row['content'], $row['creation_date'], $row['tags']);
    }
?>
<?php include('footer.php'); $mysqli->close(); ?>