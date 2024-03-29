<?php
    function show_blog($header, $content, $date, $tags, $id) {
        require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
        echo '
        <article>
            ';
        if (can_do('edit_blog')) {
            echo '<form action="" method="post">
                    <button class="icon-blog edit" name="edit_blog" value="'.$id.'"></button>
                    <button class="icon-blog delete" name="delete_blog" value="'.$id.'" onclick="return confirm(\''.translate('Вы уверены, что хотите удалить запись из блога?').'\')"></button>
                </form>
                ';
            
        }
        echo '
            <div class="date-and-tags">
                <span class="date">'.$date.'</span>
                <div class="between-date-and-tags"></div>
                <span class="date">'.translate('Теги').': </span>    ';
        foreach (explode(' ', $tags) as $value) {
            if (trim($value) != '')
                echo '<div class="tag">'.htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8').'</div>';
        }
        echo '
            </div>
            <h1>'.htmlspecialchars($header, ENT_QUOTES, 'UTF-8').'</h1>
            <div class="line-after-header"></div>';
            echo '<br>
            <p>
                '.htmlspecialchars($content, ENT_QUOTES, 'UTF-8').'
            </p>
        </article>
        ';
    }

    function show_comment($name, $date, $time, $content, $id, $for_media = FALSE) {
        require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
        echo '
            <div class="comment" id="comment'.$id.'">
                <div class="top">
                    <div class="name">'.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').'</div>
                    <div class="date-and-time">'.$date.' '.$time.'</div>
                </div>
                <div class="content">
                    '.htmlspecialchars($content, ENT_QUOTES, 'UTF-8').'
                </div>
                ';
                $role = get_role($name);
                if (is_own_comment($id) || ((can_do('delete_comments') || can_do('ban_users')) && $role != 'owner'))
                        echo '<form action="" method="POST" class="panel">';
                if (is_own_comment($id)) {
                    echo '
                        <button class="btn" name="edit_comment" value="'.$id.'">'.translate('Редактировать').'</button>
                    ';
                } 
                if (is_own_comment($id) || (can_do('delete_comments') && $role != 'owner')) {
                    echo '
                        <div class="btn" name="delete_comment" onclick="delete_comment('.$id.')">'.translate('Удалить').'</div>
                    ';
                }
                if ((can_do('ban_users') && $role != 'owner') && !is_own_comment($id)) {
                    echo '
                        <div class="btn" value="'.$id.'" onclick="show_block_form('.$id.')" id="ban_user_btn'.$id.'">'.translate('Заблокировать').'</div>
                    ';
                }
                if (is_own_comment($id) || ((can_do('delete_comments') || can_do('ban_users')) && $role != 'owner')) {
                    echo '</form>';
                }
            echo '</div>';
            if (($for_media ? $_SESSION['id_to_edit_comment'] : $_SESSION['id_to_edit_comment']) == $id)
                echo "<script>
                    document.getElementById('comment_content".get_by_id(($for_media ? $_SESSION['id_to_edit_comment'] : $_SESSION['id_to_edit_comment']), 'comments')['blog_id']."').innerHTML = '".htmlspecialchars(get_by_id(($for_media ? $_SESSION['id_to_edit_comment'] : $_SESSION['id_to_edit_comment']), 'comments')['content'], ENT_QUOTES, 'UTF-8')."';
                </script>";
    }
?>