<?php
    function show_project($name, $pct_name, $id) {
        echo '
            <a href="/project.php?id='.$id.'">
                <div class="project" style="background-image: url(/projects_img/'.$pct_name.')">
                    <div class="pct-cover">
                        <h2>'.$name.'</h2>
                    </div>
                </div>
            </a>
        ';
    }
    
    function show_tech_param($key, $value) {
        echo '
            <div class="property-key">'.$key.'</div>
            <div class="property-value">'.$value.'</div>
            <div style="height: 10px;"></div>
        ';
    }

    function show_paragraph($val, $name, $content) {
        echo '
            <h2 class="paragraph-name" onclick="show_paragraph('.$val.')"><div class="show-paragraph" id="arrow'.$val.'"></div>'.$name.'</h2>
            <hr>
            <p class="paragraph" id="par'.$val.'" style="display: none;">
                '.$content.'
            </p>
        ';
    }

    function show_comment($name, $date, $time, $content, $rating, $id) {
        echo '
            <div class="comment">
                <div class="top">
                    <div class="name">'.$name.'</div>
                    <div class="date-and-time">'.$date.' в '.$time.'</div>
                    <div class="rating">Оценка: <div class="rating-stars">';
                for ($i = 1; $i <= 5; $i++) {
                    echo '<img src="/img/'.($rating >= $i ? 'starfull.png' : 'starempty.png').'" class="rating-star">';
                }    
                echo '</div></div>
                </div>';
                if ($content != NULL && content != '')
                echo '
                    <div class="content">
                        '.$content.'
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