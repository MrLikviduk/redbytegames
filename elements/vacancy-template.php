<?php
    function show_vacancy($name, $type, $date, $id) {
        echo '
            <div class="vacancy">
                <div class="type">
                    '.$type.'
                </div>
                <a href="/vacancy-element.php?id='.$id.'">
                    <div class="name">
                        '.$name.'
                    </div>
                </a>
                <div class="date">
                    Опубликовано: '.$date.'
                </div>
                ';
                if (can_do('edit_vacancy')) echo '
                <form action="" method="POST">
                    <button class="btn" name="delete_vacancy" value="'.$id.'" onclicked="return confirm(\'Вы действительно хотите удалить вакансию?\') ">Удалить</button>
                    <button class="btn" name="edit_vacancy" value="'.$id.'">Редактировать</button>
                </form>
                ';
            echo '</div>';
    }
?>