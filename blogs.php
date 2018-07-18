<?php
    $page_name = 'Блог';
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/blog-template.php');
    $mysqli = connect_to_database();
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
        header('Location: /elements/blog-editor');
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
    if (isset($_POST['edit_comment'])) {
        if (is_own_comment($_POST['edit_comment'])) {
            $comment = get_by_id($_POST['edit_comment'], 'comments');
            $_SESSION['id_to_edit_comment'] = $comment['id'];
            $blog_id = $comment['blog_id'];
            header("Location: ".(explode('#', $_SERVER['REQUEST_URI'])[0]).'#fcn'.$blog_id);
        }
    }
    if (isset($_POST['block_form_submit'])) {
        $comment = get_by_id($_POST['block_form_comment_id'], 'comments') or die("ERROR0");
        $user_id = $comment['user_id'];
        $username = get_username_by_id($user_id);
        $role = get_role($username);
        if (can_do('ban_users') && $role != 'owner') {
            $blog_id = $comment['blog_id'];
            $days = (int)$_POST['block_form_days'];
            $hours = (int)$_POST['block_form_hours'];
            if ($_POST['block_form_days'] >= 0 && $_POST['block_form_hours'] >= 0)
                $ban_time = $days * 86400 + $hours * 3600;
            else
                $ban_time = 0;
            if (is_legal($_POST['block_form_comment'], 0, 300))
                $ban_comment = $_POST['block_form_comment'];
            else
                $ban_comment = '';
            $user_id = get_by_id($_POST['block_form_comment_id'], 'comments')['user_id'];
            $mysqli->query("DELETE FROM comments WHERE `user_id` = $user_id") or die("ERROR111");
            set_data('users', 'id', $user_id, 'banned', 1) or die("ERROR1");
            set_data('users', 'id', $user_id, 'unban_time', intval(date('U')) + $ban_time) or die("ERROR1");
            set_data('users', 'id', $user_id, 'ban_comment', $ban_comment) or die("ERROR1");
            header("Location: ".(explode('#', $_SERVER['REQUEST_URI'])[0]).'#fcn'.$blog_id);
        }
    }
    if (user_is_set($_SESSION['login'], $_SESSION['password']) && get_field($_SESSION['login'], 'banned', 'users') == '1' && intval(date('U')) > intval(get_field($_SESSION['login'], 'unban_time', 'users'))) {
        set_data('users', 'username', $_SESSION['login'], 'banned', 0);
        set_data('users', 'username', $_SESSION['login'], 'unban_time', NULL);
    }
    include('header.php');
    if (can_do('edit_blog'))
        echo '<a href="elements/blog-editor" style="margin-top: 20px; display: inline-block;">'.translate('Добавить запись').'</a>';
?>
<script>
    function showOrHideComments(element_id, count) {
        var s = count > 1000 ? '1000+' : count;
        document.getElementById('show_or_hide_comments' + element_id).innerHTML = (document.getElementById('comments' + element_id).style.display == 'none' ? '<?=translate('Скрыть комментарии')?>' : ('<?=translate('Показать комментарии')?>' + ' (' + s + ')'));
        document.getElementById('comments' + element_id).style.display = (document.getElementById('comments' + element_id).style.display == 'block' ? 'none' : 'block');
    }
    function add_comment(id, comment_id = -1) {
        var msg = $("#comment_form" + id).serialize();
        $.ajax({
            type: 'POST',
            url: '/elements/blog-result.php',
            data: msg,
            success: function (response) {
                if (comment_id == -1) {
                    if (response != 'comments_limit') {
                        $("#comments" + id).html(response + $("#comments" + id).html());
                        var count = $("#comments" + id + " .comment").length;
                        if ($("#comments" + id).css('display') == 'none') {
                            showOrHideComments(id, count);
                        }
                    }
                    else {
                        $("#comments_limit_text" + id).css('display', 'block');
                        return;
                    }
                }
                else
                    $("#comment" + comment_id).replaceWith(response);
                $("#comment_content" + id).val('');
                $("#comments_limit_text" + id).css('display', 'none');
            }
        });
    }
    function delete_comment(id) {
        if (!confirm('<?=translate('Вы действительно хотите удалить комментарий?')?>')) return; 
        $.ajax({
            type: 'POST',
            url: '/elements/blog-result.php',
            data: {
                delete_comment: id
            },
            success: function (response) {
                if (response == 'success') {
                    $("#comment" + id).remove();
                }
            }
        });
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
                    <form action="javascript:void(null);" method="POST" class="comment-editor" id="comment_form'.$row['id'].'" onsubmit="add_comment('.$row['id'].', '.(isset($_SESSION['id_to_edit_comment']) && get_by_id($_SESSION['id_to_edit_comment'], 'comments')['blog_id'] == $row['id'] ? $_SESSION['id_to_edit_comment'] : -1).')">
                        <label for="comment_content" class="label">'.translate('Комментарий').': </label>
                        <textarea name="comment_content" '.(get_field($_SESSION['login'], 'banned') == '1' ? 'disabled' : '').' maxlength="1023" class="content" rows="5" id="comment_content'.$row['id'].'">'.(get_field($_SESSION['login'], 'banned') == '1' ? translate('Вы не можете оставлять комментарии, так как были заблокированы модератором.').PHP_EOL.translate('Оставшееся время до разблокировки').': '.seconds_to_time(intval(get_field($_SESSION['login'], 'unban_time', 'users')) - intval(date('U'))).(get_field($_SESSION['login'], 'ban_comment') != '' ? PHP_EOL.translate('Комментарий модератора').': '.htmlspecialchars(get_field($_SESSION['login'], 'ban_comment'), ENT_QUOTES, 'UTF-8') : '') : '').'</textarea>
                        <p id="comments_limit_text'.$row['id'].'" style="margin: 0; color: red; font-size: 0.9rem; display: none">Превышен лимит комментариев в минуту. Повторите попытку позже...</p>
                        <input type="submit" '.(get_field($_SESSION['login'], 'banned') == '1' ? 'disabled' : '').' name="comment_submit" value="'.translate('Добавить комментарий').'" class="submit">
                        <input type="hidden" name="blog_id" value="'.$row['id'].'">
                    </form>
                ';
            }
            $comments_result = $mysqli->query("SELECT * FROM comments WHERE blog_id = ".((int)$row['id'])." ORDER BY id DESC");
            $comments_count = $comments_result->num_rows;
            if ($comments_count > 0) {
                echo '
                    <div id="show_or_hide_comments'.$row['id'].'" class="show-comments-btn" onclick="showOrHideComments('.$row['id'].', '.$comments_count.')">'.translate('Показать комментарии').' ('.($comments_count > 1000 ? '1000+' : $comments_count).')</div>
                ';
            }
            echo '<div style="display: '.($comments_count > 0 ? 'none' : 'block').';" id="comments'.$row['id'].'">';
            while ($comments = $comments_result->fetch_assoc()) {
                show_comment(get_username_by_id($comments['user_id']), $comments['creation_date'], $comments['creation_time'], $comments['content'], $comments['id']);
            }
            echo '</div>';
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
<script>
    function show_block_form(id) {
        $("#block_form_wrapper").css('display', 'block');
        $("#block_form_comment_id").val($("#ban_user_btn" + id).attr('value'));
    }
    function hide_block_form() {
        $("#block_form_wrapper").css('display', 'none');
    }
</script>
<div class="block-form-wrapper" style="display: none;" id="block_form_wrapper">
    <form action="" method="post" class="block-form" id="block_form">
        <input type="hidden" id="block_form_comment_id" name="block_form_comment_id">
        <label for="ban_days"><?=translate('Выберите время блокировки')?>:</label><br>
        <input type="number" name="block_form_days" id="block_form_days" class="text" style="width: 50px" value="0" min="0"> <?=translate('дней')?> <input type="number" name="block_form_hours" id="block_form_hours" class="text" style="width: 50px" value="0" min="0"> <?=translate('часов')?> <br>
        <label for="ban_comment"><?=translate('Введите комментарий')?>:</label><br>
        <textarea id="block_form_comment" name="block_form_comment" rows="10" class="text" style="width: 250px;" maxlength="300"></textarea>
        <input type="submit" name="block_form_submit" id="block_form_submit" class="submit-btn" style="margin-left: 10px" value="<?=translate('Заблокировать')?>">
        <button type="button" class="submit-btn" style="margin-left: 10px" onclick="hide_block_form()"><?=translate('Отмена')?></button>
    </form>
</div>
<?php include('footer.php'); $mysqli->close(); ?>