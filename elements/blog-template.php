<?php
    function show_blog($header, $content, $date, $tags, $id) {
        require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
        echo '
        <article>
            ';
        if (can_do('edit_blog')) {
            echo '<form action="" method="post">
                    <button class="icon-blog edit" name="edit_blog" value="'.$id.'"></button>
                    <button class="icon-blog delete" name="delete_blog" value="'.$id.'" onclick="return confirm(\'Вы уверены, что хотите удалить запись из блога?\')"></button>
                </form>
                ';
            
        }
        echo '
            <div class="date-and-tags">
                <span class="date">'.$date.'</span>
                <div class="between-date-and-tags"></div>
                <span class="date">Теги: </span>    ';
        foreach (explode(' ', $tags) as $value) {
            if (trim($value) != '')
                echo '<div class="tag">'.htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8').'</div>';
        }
        echo '
            </div>
            <h1>'.htmlspecialchars($header, ENT_QUOTES, 'UTF-8').'</h1>
            <div class="line-after-header"></div>
            <br>
            <p>
                '.htmlspecialchars($content, ENT_QUOTES, 'UTF-8').'
            </p>
        </article>
        ';
    }

    function show_comment($name, $date, $time, $content, $id) {
        require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
        echo '
            <div class="comment">
                <div class="top">
                    <div class="name">'.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').'</div>
                    <div class="date-and-time">'.$date.' в '.$time.'</div>
                </div>
                <div class="content">
                    '.htmlspecialchars($content, ENT_QUOTES, 'UTF-8').'
                </div>
                ';
                if (is_own_comment($id) || can_do('delete_comments'))
                        echo '<form action="" method="POST" class="panel">';
                if (is_own_comment($id)) {
                    echo '
                        <button class="btn" name="edit_comment" value="'.$id.'">Редактировать</button>
                    ';
                }
                if (is_own_comment($id) || can_do('delete_comments')) {
                    echo '
                        <button class="btn" name="delete_comment" value="'.$id.'" onclick="return confirm(\'Вы действительно хотите удалить комментарий?\')">Удалить</button>
                    ';
                }
                if (can_do('ban_users') && !is_own_comment($id)) {
                    echo '
                        <button class="btn" name="ban_user" value="'.$id.'" onclick="return confirm(\'Вы действительно хотите заблокировать пользователя?\')">Заблокировать</button> на <input type="number" name="days" style="width: 50px" value="0" min="0" max="10000"> дней <input type="number" name="hours" style="width: 50px" value="0" min="0" max="23"> часов
                    ';
                }
                if (is_own_comment($id) || can_do('delete_comments')) {
                    echo '</form>';
                }
            echo '</div>';
            if ($_SESSION['id_to_edit_comment'] == $id)
                echo "<script>
                    document.getElementById('comment_content".get_by_id($_SESSION['id_to_edit_comment'], 'comments')['blog_id']."').innerHTML = '".htmlspecialchars(get_by_id($_SESSION['id_to_edit_comment'], 'comments')['content'], ENT_QUOTES, 'UTF-8')."';
                </script>";
    }
?>