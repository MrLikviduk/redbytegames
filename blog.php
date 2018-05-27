<?php
    $page_name = 'Блог';
    include('header.php');
    include('elements/to-authorizate.php');
    include('elements/connection-info.php');
    include('elements/blog-template.php');
    $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
    if ($_SESSION['logged_in'] == TRUE) {
        include('blog-editor.php');
    }
    $result = $mysqli->query("SELECT * FROM blogs ORDER BY creation_date") or die("Невозможно загрузить блог");
    while($row = $result->fetch_assoc) {
        echo show_blog($row['name'], $row['content'], $row['creation_date'], $row['tags']);
    }
?>
<?php include('footer.php'); $mysqli->close(); ?>