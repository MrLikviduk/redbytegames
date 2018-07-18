<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    $page_name = 'Редактор вакансий';
    $mysqli = connect_to_database();
    session_start();
    if (!can_do('edit_vacancy')) {
        include($_SERVER['DOCUMENT_ROOT'].'/header.php');
        echo '<div style="margin: 30px auto;">'.translate('У вас нет прав для просмотра данной страницы').'</div>';
        include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
        exit();
    }
    if (can_do('edit_vacancy')) {
        if (isset($_POST['submit']) && strlen($_POST['name']) != 0 && strlen($_POST['type']) != 0) {
            $name = $mysqli->real_escape_string($_POST['name']);
            $type_id = (int)$_POST['type'];
            $lang = $mysqli->real_escape_string($_POST['lang']);
            if (!isset($_SESSION['id_to_edit_vacancy'])) {
                $date = date('d.m.Y');
                $mysqli->query("INSERT INTO vacancy (id, `name`, `type_id`, `creation_date`, `lang`) VALUES (NULL, '$name', '$type_id', '$date', '$lang')") or die("ERROR");
                header("Location: ".$_SERVER['REQUEST_URI']);
            }
            else {
                $mysqli->query("UPDATE vacancy SET `name` = '$name', `type_id` = '$type_id', `lang` = '$lang' WHERE `id` = ".((int)$_SESSION['id_to_edit_vacancy'])) or die("ERROR");
                unset($_SESSION['id_to_edit_vacancy']);
                header("Location: ".$_SERVER['REQUEST_URI']);
            }
        }
    }
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<a href="/vacancy" style="margin: 20px auto; display: block;">Назад</a>
<form action="" method="POST" id="edit_form">
    <label for="lang"><?=translate('Язык')?>: </label>
    <select name="lang" id="lang_id" class="text-box">
        <option value="ru"><?=translate('Русский')?></option>
        <option value="en"><?=translate('Английский')?></option>
    </select>
    <br>
    <label for="name"><?=translate('Название')?>: </label>
    <input type="text" name="name" id="name_id" class="text-box">
    <br>
    <label for="type"><?=translate('Направление')?>: </label>
    <select name="type" class="text-box" id="type_id">
        <?php
            $result = $mysqli->query("SELECT * FROM vacancy_types ORDER BY `id`");
            while ($row = $result->fetch_assoc()) {
                if ($_SESSION['lang'] == 'ru')
                    $type = $row['russian_name'];
                else if ($_SESSION['lang'] == 'en')
                    $type = $row['english_name'];
                echo '<option '.(isset($_SESSION['id_to_edit_vacancy']) && get_data('vacancy', 'id', $_SESSION['id_to_edit_vacancy'])['type_id'] == $row['id'] ? 'selected' : '').' value="'.$row['id'].'">'.$type.'</option>';
            }
        ?>
    </select>
    <br>
    <input type="submit" name="submit" value="<?=translate('Добавить')?>" class="submit-button">
</form>
<script>
    <?php
        if (isset($_SESSION['id_to_edit_vacancy'])) {
            echo "document.getElementById('name_id').value = '".htmlspecialchars(get_data('vacancy', 'id', $_SESSION['id_to_edit_vacancy'])['name'], ENT_QUOTES, 'UTF-8')."';";
            echo "document.getElementById('lang_id').value = '".htmlspecialchars(get_data('vacancy', 'id', $_SESSION['id_to_edit_vacancy'])['lang'], ENT_QUOTES, 'UTF-8')."';";
        }
    ?>
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); $mysqli->close(); ?>