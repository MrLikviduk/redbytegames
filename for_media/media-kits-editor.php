<?php
    session_start();
    $page_name = 'Редактор китов для прессы';
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    if (!can_do('edit_for_media')) {
        include($_SERVER['DOCUMENT_ROOT'].'/header.php');
        echo '<div style="margin: 30px auto;">'.translate('У вас нет прав для просмотра данной страницы').'</div>';
        include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
        exit();
    }
    $mysqli = connect_to_database();
    if (can_do('edit_for_media')) {
        if (isset($_POST['submit']) && is_legal($_POST['name'], 1, 60) && is_uploaded_file($_FILES['file']['tmp_name'])) {
            if (can_upload($_FILES['file'], 'document')) {
                $date = date('d.m.Y');
                $name = $mysqli->real_escape_string($_POST['name']);
                $temp_array = explode('.', $_FILES['file']['name']);
                $extension = end($temp_array);
                $filename = $name.'.'.$extension;
                make_upload($_FILES['file'], $_POST['name'], 'for_media/kits', $extension);
                $filename = $mysqli->real_escape_string($filename);
                $mysqli->query("INSERT INTO kits (id, `name`, `filename`, creation_date) VALUES (NULL, '$name', '$filename', '$date')") or die("ERROR");
                header("Location: ".$_SERVER['REQUEST_URI']);
            }
        }
    }
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<a href="/for_media/media-kits.php" style="margin: 20px auto; display: block;"><?=translate('Назад')?></a>
<form action="" method="POST" id="edit_form" enctype="multipart/form-data">
    <label for="lang"><?=translate('Язык')?>: </label>
    <select name="lang" id="lang_id" class="text-box">
        <option value="ru"><?=translate('Русский')?></option>
        <option value="en"><?=translate('Английский')?></option>
    </select>
    <br>
    <label for="name"><?=translate('Название')?>: </label>
    <input type="text" name="name" id="name_id" class="text-box" maxlength="60">
    <br>
    <label for="file"><?=translate('Файл')?>: </label>
    <input type="file" name="file"><span style="color: gray"> (<?=translate('доступные форматы')?>: doc, docx, jpg, pdf, png, rar, zip)</span>
    <br>
    <input type="submit" name="submit" value="<?=translate('Добавить')?>" class="submit-button">
</form>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); $mysqli->close(); ?>