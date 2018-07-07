<?php
    $page_name = 'Блог';
    session_start();
    include('elements/functions.php');
    include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
    $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name) or die("Error to connect to db");
    include('elements/blog-template.php');
    if (isset($_SESSION['id_to_edit_blog'])) unset($_SESSION['id_to_edit_blog']);
    if (!isset($_SESSION['num_of_rows'])) {
        $_SESSION['num_of_rows'] = 0;
    }
    if (!isset($_SESSION['id_to_edit_blog']))
        $_SESSION['id_to_edit_blog'] = -1;
    if (isset($_POST['delete_blog']) && can_do('edit_blog')) {
        $id = (int)$_POST['delete_blog'];
        $to_delete = $mysqli->query("DELETE FROM blog WHERE id = $id") or die("Error");
        header("Location: ".$_SERVER['REQUEST_URI']);
    }
    if (isset($_POST['edit_blog']) && can_do('edit_blog')) {
        $_SESSION['id_to_edit_blog'] = $_POST['edit_blog'];
        header('Location: /elements/blog-editor.php');
    }
    if (!isset($blog_notices)) {
        $result = $mysqli->query("SELECT * FROM blog WHERE lang = '".$mysqli->real_escape_string($_SESSION['lang'])."' AND for_media = 0 ORDER BY id DESC");
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
    if (isset($_POST['delete_comment'])) {
        if ((user_is_set($_SESSION['login'], $_SESSION['password']) && get_id_by_username($_SESSION['login']) == get_by_id($_POST['delete_comment'], 'comments')['user_id']) || can_do('delete_comments')) {
            $comment = get_by_id($_POST['delete_comment'], 'comments');
            $blog_id = $comment['blog_id'];
            $comment_id = (int)$_POST['delete_comment'];
            $mysqli->query("DELETE FROM comments WHERE id = ".$comment_id);
            header("Location: ".(explode('#', $_SERVER['REQUEST_URI'])[0]).'#fcn'.$blog_id);
        }
    }
    if (isset($_POST['edit_comment'])) {
        if (is_own_comment($_POST['edit_comment'])) {
            $comment = get_by_id($_POST['edit_comment'], 'comments');
            $_SESSION['id_to_edit_comment'] = $comment['id'];
            $blog_id = $comment['blog_id'];
            header("Location: ".(explode('#', $_SERVER['REQUEST_URI'])[0]).'#fcn'.$blog_id);
        }
    }
    if (isset($_POST['ban_user'])) {
        if (can_do('ban_users')) {
            $comment = get_by_id($_POST['ban_user'], 'comments');
            $blog_id = $comment['blog_id'];
            if ($_POST['days'] >= 0 && $_POST['hours'] >= 0)
                $ban_time = $_POST['days'] * 86400 + $_POST['hours'] * 3600;
            else
                $ban_time = 0;
            $user_id = (int)get_by_id($_POST['ban_user'], 'comments')['user_id'];
            $mysqli->query("DELETE FROM comments WHERE `user_id` = $user_id");
            set_data('users', 'id', $user_id, 'banned', 1);
            set_data('users', 'id', $user_id, 'unban_time', intval(date('U')) + $ban_time);
            header("Location: ".(explode('#', $_SERVER['REQUEST_URI'])[0]).'#fcn'.$blog_id);
        }
    }
    if (user_is_set($_SESSION['login'], $_SESSION['password']) && get_field($_SESSION['login'], 'banned', 'users') == '1' && intval(date('U')) > intval(get_field($_SESSION['login'], 'unban_time', 'users'))) {
        set_data('users', 'username', $_SESSION['login'], 'banned', 0);
        set_data('users', 'username', $_SESSION['login'], 'unban_time', NULL);
    }
    if (isset($_POST['comment_submit']) && strlen($_POST['comment_content']) > 0 && strlen($_POST['comment_content']) < 1024 && can_do('add_comments') && get_field($_SESSION['login'], 'banned') == '0') {
        $user_id = get_id_by_username($_SESSION['login']);
        $blog_id = $_POST['blog_id'];
        if (isset($_SESSION['id_to_edit_comment']) && get_by_id($_SESSION['id_to_edit_comment'], 'comments')['blog_id'] == $_POST['blog_id'] && user_is_set($_SESSION['login'], $_SESSION['password']) && get_id_by_username($_SESSION['login']) == get_by_id($_SESSION['id_to_edit_comment'], 'comments')['user_id']) {
            $comment_content = $mysqli->real_escape_string($_POST['comment_content']);
            $id_to_edit_comment = (int) $_SESSION['id_to_edit_comment'];
            $mysqli->query("UPDATE comments SET content = '".$comment_content."' WHERE id = ".$id_to_edit_comment);
            unset($_SESSION['id_to_edit_comment']);
            header("Location: ".(explode('#', $_SERVER['REQUEST_URI'])[0]).'#fcn'.$_POST['blog_id']);
        }
        else {
            add_comment($blog_id, $user_id, date('d.m.Y'), date('H:i'), $_POST['comment_content']);
            header("Location: ".(explode('#', $_SERVER['REQUEST_URI'])[0]).'#fcn'.$_POST['blog_id']);
        }
    }
    include('header.php');
    if (can_do('edit_blog'))
        echo '<a href="elements/blog-editor.php" style="margin-top: 20px; display: inline-block;">'.translate('Добавить запись').'</a>';
?>
<script>
    function showOrHideComments(element_id, count) {
        var s = count > 1000 ? count : '1000+'
        document.getElementById('show_or_hide_comments' + element_id).innerHTML = (document.getElementById('comments' + element_id).style.display == 'none' ? '<?=translate('Скрыть комментарии')?>' : ('<?=translate('Показать комментарии')?>' + s));
        document.getElementById('comments' + element_id).style.display = (document.getElementById('comments' + element_id).style.display == 'block' ? 'none' : 'block');
    }
</script>
<?php
    if (isset($blog_notices)) {
        if ($_SESSION['num_of_rows'] > 0) {
            echo '
                <form method="POST" action="">
                    <button name="hide_blogs" class="show-blogs-btn" style="margin-bottom: 0;">'.translate('Вернуться к предыдущим записям').'</button>
                </form>
            ';
        }
        for ($j = 0; $j < count($blog_notices[$_SESSION['num_of_rows']]); $j++) {
            $row = $blog_notices[$_SESSION['num_of_rows']][$j];
            $date = explode('-' ,$row['creation_date']);
            $date = $date[2].'.'.$date[1].'.'.$date[0];
            echo show_blog($row['header'], $row['content'], $date, $row['tags'], $row['id']);
            echo '<div id="fcn'.$row['id'].'" style="position: relative; top: -70px;"></div>';
            if (can_do('add_comments')) {
                echo '
                    <form action="" method="POST" class="comment-editor">
                        <label for="comment_content" class="label">'.translate('Комментарий').': </label>
                        <textarea name="comment_content" '.(get_field($_SESSION['login'], 'banned') == '1' ? 'disabled' : '').' maxlength="1023" class="content" rows="5" id="comment_content'.$row['id'].'">'.(get_field($_SESSION['login'], 'banned') == '1' ? translate('Вы не можете оставлять комментарии, так как были заблокированы модератором.').PHP_EOL.translate('Оставшееся время до разблокировки').': '.seconds_to_time(intval(get_field($_SESSION['login'], 'unban_time', 'users')) - intval(date('U'))) : '').'</textarea>
                        <input type="submit" '.(get_field($_SESSION['login'], 'banned') == '1' ? 'disabled' : '').' name="comment_submit" value="'.translate('Добавить комментарий').'" class="submit">
                        <input type="hidden" name="blog_id" value="'.$row['id'].'">
                    </form>
                ';
            }
            $comments_result = $mysqli->query("SELECT * FROM comments WHERE blog_id = ".((int)$row['id'])." ORDER BY id DESC");
            $comments_count = $comments_result->num_rows;
            if ($comments_count > 0) {
                echo '
                    <div id="show_or_hide_comments'.$row['id'].'" class="show-comments-btn" onclick="showOrHideComments('.$row['id'].', '.$comments_count.')">'.translate('Показать комментарии').'</div>
                ';
                echo '<div style="display: none;" id="comments'.$row['id'].'">';
                while ($comments = $comments_result->fetch_assoc()) {
                    show_comment(get_username_by_id($comments['user_id']), $comments['creation_date'], $comments['creation_time'], $comments['content'], $comments['id']);
                }
                echo '</div>';
            }
        }
        if ($_SESSION['num_of_rows'] < count($blog_notices) - 1) {
            echo '
                <form method="POST" action="">
                    <button name="show_blogs" class="show-blogs-btn" style="margin-top: 0;">'.translate('Показать еще записи').'</button>
                </form>
            ';
        }
    }
?>

<?php include('footer.php'); $mysqli->close(); ?>