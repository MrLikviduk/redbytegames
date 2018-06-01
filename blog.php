<?php
    $page_name = 'Блог';
    session_start();
    include('elements/connection-info.php');
    $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name) or die("Error to connect to db");
    include('elements/to-authorizate.php');
    include('elements/blog-template.php');
    if (!isset($_SESSION['num_of_rows'])) {
        $_SESSION['num_of_rows'] = 0;
    }
    if (isset($_POST['delete_blog']) && $_SESSION['logged_in'] == TRUE) {
        $to_delete = $mysqli->query("DELETE FROM blog WHERE id LIKE ".$_POST['delete_blog']) or die("Error");
        header("Location: ".$_SERVER['REQUEST_URI']);
    }
    if (!isset($blog_notices)) {
        $result = $mysqli->query("SELECT * FROM blog ORDER BY id DESC");
        $array_temp = [];
        while ($row = $result->fetch_assoc()) {
            array_push($array_temp, $row);
        }
        foreach ($array_temp as $key => $value) {
            $blog_notices[($key - ($key % 10)) / 10][$key % 10] = $value;
        }
    }
    if (isset($blog_notices)) {
        if (isset($_POST['show_blogs']) && $_SESSION['num_of_rows'] < count($blog_notices) - 1) {
            $_SESSION['num_of_rows'] += 1;
            header("Location: ".$_SERVER['REQUEST_URI']);
        }
        else if (isset($_POST['hide_blogs']) && $_SESSION['num_of_rows'] > 0) {
            $_SESSION['num_of_rows'] -= 1;
            header("Location: ".$_SERVER['REQUEST_URI']);
        }
    }
    include('header.php');
    if ($_SESSION['logged_in'] == TRUE)
        echo '<a href="elements/blog-editor.php" style="margin-left: 25vw; margin-top: 20px; display: inline-block;">Добавить запись</a>';
    else
        show_login_form();
?>
<?php
    if (isset($blog_notices)) {
        if ($_SESSION['num_of_rows'] > 0) {
            echo '
                <form method="POST" action="">
                    <button name="hide_blogs" class="show-blogs-btn" style="margin-bottom: 0;">Вернуться к предыдущим записям</button>
                </form>
            ';
        }
        for ($j = 0; $j < count($blog_notices[$_SESSION['num_of_rows']]); $j++) {
            $row = $blog_notices[$_SESSION['num_of_rows']][$j];
            $date = explode('-' ,$row['creation_date']);
            $date = $date[2].'.'.$date[1].'.'.$date[0];
            echo show_blog($row['header'], $row['content'], $date, $row['tags'], $row['id']);
        }
        if ($_SESSION['num_of_rows'] < count($blog_notices) - 1) {
            echo '
                <form method="POST" action="">
                    <button name="show_blogs" class="show-blogs-btn" style="margin-top: 0;">Показать еще записи</button>
                </form>
            ';
        }
    }
    
?>
<?php include('footer.php'); $mysqli->close(); ?>