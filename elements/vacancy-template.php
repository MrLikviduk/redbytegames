<?php
    function show_vacancy($name, $type, $date, $id) {
        require_once($_SERVER['DOCUMENT_ROOT'],'/elements/functions.php');
        echo '
            <div class="vacancy">
                <div class="type">
                    '.htmlspecialchars($type, ENT_QUOTES, 'UTF-8').'
                </div>
                <a href="/vacancy-element.php?id='.$id.'">
                    <div class="name">
                        '.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').'
                    </div>
                </a>
                <div class="date">
                    '.translate('Опубликовано').': '.$date.'
                </div>
                ';
                if (can_do('edit_vacancy')) echo '
                <form action="" method="POST">
                    <button class="btn" name="delete_vacancy" value="'.$id.'" onclick="return confirm(\''.translate('Вы действительно хотите удалить вакансию?').'\') ">'.translate('Удалить').'</button>
                    <button class="btn" name="edit_vacancy" value="'.$id.'">'.translate('Редактировать').'</button>
                </form>
                ';
            echo '</div>';
    }
    function show_vacancy_list($name, $label, $lst) {
        require_once($_SERVER['DOCUMENT_ROOT'],'/elements/functions.php');
        echo '
        <div class="list">
            <div class="list-header">'.htmlspecialchars($label, ENT_QUOTES, 'UTF-8').':</div>
            <ul>';
                if ($lst !== NULL) foreach ($lst as $key => $value) {
                    echo '<li>'.$value;
                    if (can_do('edit_vacancy'))
                        echo '<form action="" method="POST" style="display: inline-block;"><input type="hidden" name="lst_name" value="'.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').'"><button name="delete_lst_element" value="'.htmlspecialchars($key, ENT_QUOTES, 'UTF-8').'" style="margin: 0 5px;">'.translate('Удалить').'</button></form>';
                    echo '</li>';
                }
                if (can_do('edit_vacancy')) echo '
                <form action="" method="POST">
                    <input type="hidden" name="lst_name" value="'.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').'">
                    <ul><input type="text" name="input_text" maxlength="1023"> <input type="submit" name="lst_submit" value="'.translate('Добавить').'"></ul>
                </form>';
                echo '
            </ul>
        </div>
        ';
    }
?>