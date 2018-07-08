<?php
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    $mysqli = connect_to_database();
    if (can_do('see_info_for_media')) {
        if (isset($_POST['question'])) {
            if (is_legal($_POST['question'], 1, 300)) {
                $question = $mysqli->real_escape_string($_POST['question']);
                $date = date('d.m.Y');
                $mysqli->query("INSERT INTO questions_answers (id, question, creation_date) VALUES (NULL, '$question', '$date')") or die("Ошибка при запросе в базу данных!");
                $mysqli->close();
                echo translate('Ваш вопрос успешно отправлен!'); exit();
            }
            else
                echo translate('Ошибка: введены неверные данные!'); exit();
        }
        if (can_do('answer_questions_for_media')) {
            if (isset($_POST['answer'])) {
                if (is_legal($_POST['answer'], 1, 300)) {
                    $answer = $mysqli->real_escape_string($_POST['answer']);
                    $id = (int)$_POST['qa_id'];
                    $mysqli->query("UPDATE questions_answers SET answer = '$answer' WHERE id = $id") or die("DATABASESSEESES");
                    $mysqli->close();
                    echo htmlspecialchars($_POST['answer'], ENT_QUOTES, 'UTF-8'); exit();
                }
                else
                    echo translate('Ошибка: введены неверные данные!'); exit();
            }
        }
        if (can_do('delete_questions_for_media')) {
            if (isset($_POST['id_to_delete_q'])) {
                $id = (int)$_POST['id_to_delete_q'];
                $mysqli->query("DELETE FROM questions_answers WHERE id = $id") or die("Не получилось :(");
                $mysqli->close();
                die("Все ок :)");
            }
        }
    }
    
?>