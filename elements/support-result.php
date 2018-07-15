<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/constants.php');
    $mysqli = connect_to_database();
    if (is_legal($_POST['fullname'], FULLNAME_MIN, FULLNAME_MAX) && is_legal($_POST['email'], EMAIL_MIN, EMAIL_MAX) && is_legal($_POST['message'], 1, 1023) && check_captcha($_POST['g-recaptcha-response'])) {
        sleep(3);
        $fullname = $mysqli->real_escape_string($_POST['fullname']);
        $email = $mysqli->real_escape_string($_POST['email']);
        $message = $mysqli->real_escape_string($_POST['message']);
        mail('support@redbytegames.ru', 'Новый вопрос', 'ФИО: '.$_POST['fullname']."\r\nПочта: ".$_POST['email']."\r\nСообщение: ".$_POST['message'], "From: support@redbytegames.ru");
        $mysqli->query("INSERT INTO support_requests (id, fullname, `email`, `message`) VALUES (NULL, '$fullname', '$email', '$message')") or die("database error");
        echo 'success';
        exit();
    }
    echo 'incorrect data';
    exit();
    $mysqli->close();
?>