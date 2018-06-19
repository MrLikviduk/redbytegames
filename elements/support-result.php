<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/constants.php');
    $mysqli = connect_to_database();
    if (isset($_POST['support_submit'])) {
        if (is_legal($_POST['fullname'], FULLNAME_MIN, FULLNAME_MAX) && is_legal($_POST['email'], EMAIL_MIN, EMAIL_MAX) && is_legal($_POST['message'], 1, 1023) && check_captcha($_POST['g-recaptcha-response'])) {
            $fullname = $mysqli->real_escape_string($_POST['fullname']);
            $email = $mysqli->real_escape_string($_POST['email']);
            $message = $mysqli->real_escape_string($_POST['message']);
            $mysqli->query("INSERT INTO support_requests (id, fullname, `email`, `message`) VALUES (NULL, '$fullname', '$email', '$message')") or die("ERROR");
        }
    }
    $mysqli->close();
?>