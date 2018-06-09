<?php
    function show_blog($header, $content, $date, $tags, $id) {
        require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
        echo '
        <article>
            ';
        if (can_do('edit_blog')) {
            echo '<form action="" method="post">
                    <button class="icon-blog edit" name="edit_blog" value="'.$id.'"></button>
                    <button class="icon-blog delete" name="delete_blog" value="'.$id.'"></button>
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
                echo '<div class="tag">'.trim($value).'</div>';
        }
        echo '
            </div>
            <h1>'.$header.'</h1>
            <div class="line-after-header"></div>
            <br>
            <p>
                '.$content.'
            </p>
        </article>
        ';
    }

    function show_comment($name, $date, $time, $content, $id) {
        echo '
            <div class="comment">
                <div class="top">
                    <div class="name">'.$name.'</div>
                    <div class="date-and-time">'.$date.' в '.$time.'</div>
                </div>
                <div class="content" id="comment_content'.$id.'">
                    '.$content.'
                </div>
                ';
                $v = user_is_set($_SESSION['login'], $_SESSION['password']) && get_id_by_username($_SESSION['login']) == get_by_id($id, 'comments')['user_id'];
                if ($v || can_do('delete_comments'))
                        echo '<form action="" method="POST" class="panel">';
                if ($v) {
                    echo '
                        <button class="btn" name="edit_comment" value="'.$id.'">Редактировать</button>
                    ';
                }
                if ($v || can_do('delete_comments')) {
                    echo '
                        <button class="btn" name="delete_comment" value="'.$id.'">Удалить</button>
                    ';
                }
                if ($v || can_do('delete_comments')) {
                    echo '</form>';
                }
            echo '</div>';
            if ($_SESSION['id_to_edit_comment'] == $id)
                echo "<script>
                    document.getElementById('comment_content".$_SESSION['id_to_edit_comment']."').innerHTML = '".get_by_id($_SESSION['id_to_edit_comment'], 'comments')['content']."';
                </script>";
    }
?>