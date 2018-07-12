<?php
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/blog-template.php');
    $mysqli = connect_to_database();
    if (isset($_POST['comment_content']) && is_legal($_POST['comment_content'], 1, 1023) && can_do('add_comments') && get_field($_SESSION['login'], 'banned') == '0') {
        $user_id = get_id_by_username($_SESSION['login']);
        $blog_id = $_POST['blog_id'];
        if (isset($_SESSION['id_to_edit_comment']) && get_by_id($_SESSION['id_to_edit_comment'], 'comments')['blog_id'] == $_POST['blog_id'] && user_is_set($_SESSION['login'], $_SESSION['password']) && get_id_by_username($_SESSION['login']) == get_by_id($_SESSION['id_to_edit_comment'], 'comments')['user_id']) {
            $comment_content = $mysqli->real_escape_string($_POST['comment_content']);
            $id_to_edit_comment = (int) $_SESSION['id_to_edit_comment'];
            $mysqli->query("UPDATE comments SET content = '$comment_content' WHERE id = $id_to_edit_comment");
            $id = (int) $_SESSION['id_to_edit_comment'];
            unset($_SESSION['id_to_edit_comment']);
        }
        else {
            add_comment($blog_id, $user_id, date('d.m.Y'), date('H:i'), $_POST['comment_content']);
        }
        $result = $mysqli->query("SELECT * FROM comments WHERE user_id = $user_id and blog_id = $blog_id ORDER BY id DESC");
        $comment = $result->fetch_assoc();
        if (!isset($id)) $id = (int)$comment['id'];
        else {
            $result = $mysqli->query("SELECT * FROM comments WHERE id = $id");
            $comment = $result->fetch_assoc();
        }
        show_comment(get_by_id($comment['user_id'], 'users')['username'], $comment['creation_date'], $comment['creation_time'], $comment['content'], $id);
    }
    if (isset($_POST['delete_comment'])) {
        $comment = get_by_id($_POST['delete_comment'], 'comments');
        $user_id = $comment['user_id'];
        $username = get_username_by_id($username);
        $role = get_role($username);
        if (((user_is_set($_SESSION['login'], $_SESSION['password']) && get_id_by_username($_SESSION['login']) == get_by_id($_POST['delete_comment'], 'comments')['user_id'])) || (can_do('delete_comments') && $role != 'owner')) {
            $blog_id = $comment['blog_id'];
            $comment_id = (int)$_POST['delete_comment'];
            $mysqli->query("DELETE FROM comments WHERE id = $comment_id");
            echo 'success';
            exit();
        }
        else die("fail");
    }
?>