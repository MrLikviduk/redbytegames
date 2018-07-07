<?php
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    $mysqli = connect_to_database();
    if (can_do('see_info_for_media')) {
        if (isset($_POST['submit']) && is_legal($_POST['question'], 1, 300)) {
            $question = $mysqli->real_escape_string($_POST['question']);
            $date = date('d.m.Y');
            $mysqli->query("INSERT INTO questions_answers (id, question, creation_date) VALUES (NULL, '$question', '$date')") or die("Ошибка при запросе в базу данных!");
            $mysqli->close();
            die("Ваше сообщение успешно отправлено!")
        }
        else
            die("Ошибка: введены неверные данные!");
    }
    else
        die("Ошибка: недостаточно прав!")
?>