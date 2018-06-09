<?php
    $page_name = 'Блог';
    session_start();
    include('elements/functions.php');
    include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
    $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name) or die("Error to connect to db");
    include('elements/blog-template.php');
    if (!isset($_SESSION['num_of_rows'])) {
        $_SESSION['num_of_rows'] = 0;
    }
    if (!isset($_SESSION['id_to_edit_blog']))
        $_SESSION['id_to_edit_blog'] = -1;
    if (isset($_POST['delete_blog']) && can_do('edit_blog')) {
        $to_delete = $mysqli->query("DELETE FROM blog WHERE id LIKE ".$_POST['delete_blog']) or die("Error");
        header("Location: ".$_SERVER['REQUEST_URI']);
    }
    if (isset($_POST['edit_blog']) && can_do('edit_blog')) {
        $_SESSION['id_to_edit_blog'] = $_POST['edit_blog'];
        header('Location: /elements/blog-editor.php');
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
    if (isset($_POST['show_or_hide_comments'])) {
        $t_id = $_POST['show_or_hide_comments'];
        if (!isset($_SESSION['show_or_hide_comments'][$t_id])) $_SESSION['show_or_hide_comments'][$t_id] = FALSE;
        $_SESSION['show_or_hide_comments'][$t_id] = !$_SESSION['show_or_hide_comments'][$t_id];
        header("Location: ".(explode('#', $_SERVER['REQUEST_URI'])[0]).'#fcn'.$t_id);

    }
    if (isset($_POST['comment_submit']) && strlen($_POST['comment_content']) > 0 && strlen($_POST['comment_content']) < 1024 && can_do('add_comments')) {
        $user_id = get_id_by_username($_SESSION['login']);
        $blog_id = $_POST['blog_id'];
        add_comment($blog_id, $user_id, date('d.m.Y'), date('H:i'), $_POST['comment_content']);
        header("Location: ".(explode('#', $_SERVER['REQUEST_URI'])[0]).'#fcn'.$_POST['blog_id']);
    }
    include('header.php');
    if (can_do('edit_blog'))
        echo '<a href="elements/blog-editor.php" style="margin-top: 20px; display: inline-block;">Добавить запись</a>';
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
            echo '<div id="fcn'.$row['id'].'" style="position: relative; top: -70px;"></div>';
            if (can_do('add_comments')) {
                echo '
                    <form action="" method="POST" class="comment-editor">
                        <label for="comment_content" class="label">Комментарий: </label>
                        <textarea name="comment_content" maxlength="1023" class="content" rows="5"></textarea>
                        <input type="submit" name="comment_submit" value="Добавить комментарий" class="submit">
                        <input type="hidden" name="blog_id" value="'.$row['id'].'">
                    </form>
                ';
            }
            $comments_result = $mysqli->query("SELECT * FROM comments WHERE blog_id LIKE ".$row['id']." ORDER BY id DESC");
            if ($comments_result->num_rows > 0) {
                echo '
                    <form action="" method="POST">
                        <button name="show_or_hide_comments'.$row['id'].'" class="show-comments-btn" value="'.$row['id'].'">Показать комментарии</button>
                    </form>
                ';
            }
            echo '<div style="display: none;" id="comments'.$row['id'].'">';
            while ($comments = $comments_result->fetch_assoc()) {
                show_comment(get_username_by_id($comments['user_id']), $comments['creation_date'], $comments['creation_time'], $comments['content']);
            }
            echo '</div>';
            echo "
                <script>
                    show_or_hide_comments".$row['id'].".addEventListener('click', function () {
                        getElementById('comments".$row['id']."').style.display = (getElementById('comments".$row['id']."').style.display == 'block' ? 'none' : 'block');
                    })
                </script>
            ";
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