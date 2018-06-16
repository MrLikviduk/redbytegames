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
    function show_vacancy_list($name, $label, $lst) {
        echo '
        <div class="list">
            <div class="list-header">'.$label.'</div>
            <ul>';
                if ($lst !== NULL) foreach ($lst as $key => $value) {
                    echo '<li>'.$value.'</li>';
                }
                if (can_do('edit_vacancy')) echo '
                <form action="" method="POST">
                    <input type="hidden" name="lst_name" value="'.$name.'">
                    <ul><input type="text" name="input_text" maxlength="1023"> <input type="submit" name="lst_submit" value="Добавить"></ul>
                </form>';
                echo '
            </ul>
        </div>
        ';
    }
?>