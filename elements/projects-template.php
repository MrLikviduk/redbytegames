<?php
    function show_project($name, $pct_name, $id) {
        echo '
            <a href="/project.php?id='.$id.'">
                <div class="project" style="background-image: url(/projects_img/'.$pct_name.')">
                    <div class="pct-cover">
                        <h2>'.htmlspecialchars($name).'</h2>
                    </div>
                </div>
            </a>
        ';
    }
    
    function show_tech_param($key, $value) {
        require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
        ?>
            <div class="property-key"><?=htmlspecialchars($key)?></div>
            <div class="property-value"><?=htmlspecialchars($value)?></div>
            <?php if (can_do('edit_projects')) { ?>
            <form action="" method="POST" style="display: inline-block">
                <button name="delete_tech_param" value="<?=$key?>" style="margin: 0 5px;" onclick="return confirm('Вы действительно хотите удалить параметр?')">Удалить</button>
            </form>
            <?php } ?>
            <br>
 <?php   }

    function show_paragraph($val, $name, $content) {
        require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
     ?>
            <h2 class="paragraph-name" onclick="show_paragraph(<?=$val?>)"><div class="show-paragraph" id="arrow<?=$val?>"></div><?=htmlspecialchars($name)?></h2>
            <?php if (can_do('edit_projects')) { ?>
                <form action="" method="post" style="display: inline-block;">
                    <button name="delete_p" value="<?=$name?>" style="margin: 0 5px;" onclick="return confirm('Вы действительно хотите удалить параграф?')">Удалить</button>
                </form>
            <?php } ?>
            <hr>
            <p class="paragraph" id="par<?=$val?>" style="display: none;">
                <?=htmlspecialchars($content)?>
            </p>
    <?php }

    function show_comment($name, $date, $time, $content, $rating, $id) {
        echo '
            <div class="comment">
                <div class="top">
                    <div class="name">'.htmlspecialchars($name).'</div>
                    <div class="date-and-time">'.$date.' в '.$time.'</div>
                    <div class="rating">Оценка: <div class="rating-stars">';
                for ($i = 1; $i <= 5; $i++) {
                    echo '<img src="/img/'.($rating >= $i ? 'starfull.png' : 'starempty.png').'" class="rating-star">';
                }    
                echo '</div></div>
                </div>';
                if ($content != NULL && $content != '')
                echo '
                    <div class="content">
                        '.htmlspecialchars($content).'
                    </div>
                ';
                echo '
                </div>
                ';
            //     if (is_own_comment($id) || can_do('delete_comments'))
            //             echo '<form action="" method="POST" class="panel">';
            //     if (is_own_comment($id)) {
            //         echo '
            //             <button class="btn" name="edit_comment" value="'.$id.'">Редактировать</button>
            //         ';
            //     }
            //     if (is_own_comment($id) || can_do('delete_comments')) {
            //         echo '
            //             <button class="btn" name="delete_comment" value="'.$id.'" onclick="return confirm(\'Вы действительно хотите удалить комментарий?\')">Удалить</button>
            //         ';
            //     }
            //     if (can_do('ban_users') && !is_own_comment($id)) {
            //         echo '
            //             <button class="btn" name="ban_user" value="'.$id.'" onclick="return confirm(\'Вы действительно хотите заблокировать пользователя?\')">Заблокировать</button> на <input type="number" name="days" style="width: 50px" value="0" min="0" max="10000"> дней <input type="number" name="hours" style="width: 50px" value="0" min="0" max="23"> часов
            //         ';
            //     }
            //     if (is_own_comment($id) || can_do('delete_comments')) {
            //         echo '</form>';
            //     }
            // echo '</div>';
            // if ($_SESSION['id_to_edit_comment'] == $id)
            //     echo "<script>
            //         document.getElementById('comment_content".get_by_id($_SESSION['id_to_edit_comment'], 'comments')['blog_id']."').innerHTML = '".get_by_id($_SESSION['id_to_edit_comment'], 'comments')['content']."';
            //     </script>";
    }
?>