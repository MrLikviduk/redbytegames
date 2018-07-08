<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    if (isset($_POST['login'])) {
        $mysqli = connect_to_database();
        $login = $mysqli->real_escape_string($_POST['login']);
        $result = $mysqli->query("SELECT * FROM users WHERE username = '$login'");
        if ($result->num_rows == 0) {
            echo 'success';
            exit();
        }
        else {
            echo 'fail';
            exit();
        }
        $mysqli->close();
    }
?>