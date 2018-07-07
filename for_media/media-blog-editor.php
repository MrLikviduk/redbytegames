<?php
    $page_name = 'Редактор блога для прессы';
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    if (!can_do('edit_for_media')) {
        include($_SERVER['DOCUMENT_ROOT'].'/header.php');
        echo '<div style="margin: 30px auto;">'.translate('У вас нет прав для просмотра данной страницы').'</div>';
        include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
        exit();
    }
    if (!isset($date))
        $date = date('Y-m-d');
    if (!isset($_SESSION['id_to_edit_media_blog']))
        $_SESSION['id_to_edit_media_blog'] = -1;
    $mysqli = connect_to_database();
    $query = $mysqli->query("SELECT * FROM blog WHERE id = ".((int)$_SESSION['id_to_edit_media_blog']));
    $to_edit = $query->fetch_assoc();
    if(isset($_POST['header']) && $_POST['header'] != '' && $_POST['content'] != '' && isset($_POST['submit']) && can_do('edit_blog')) {
        $header = $mysqli->real_escape_string($_POST['header']);
        $content = $mysqli->real_escape_string($_POST['content']);
        $tags = $mysqli->real_escape_string($_POST['tags']);
        $lang = $mysqli->real_escape_string($_POST['lang']);
        if ($_SESSION['id_to_edit_media_blog'] == -1)
            $mysqli->query("INSERT INTO blog (id, header, content, creation_date, tags, lang, for_media) VALUES (NULL, '$header', '$content', '$date', '$tags', '$lang', 1)") or die("Error");
        else {
            $t_id = (int)$_SESSION['id_to_edit_media_blog'];
            $date = $mysqli->real_escape_string($to_edit['creation_date']);
            $mysqli->query("UPDATE blog SET header = '$header', content = '$content', creation_date = '$date', tags = '$tags', lang = '$lang' WHERE id = $t_id") or die("Error to edit");
        }
        $_SESSION['id_to_edit_media_blog'] = -1;
        header("Location: ".$_SERVER['REQUEST_URI']);
    }
    $mysqli->close();
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<a href="/for_media/media-blog.php" style="margin: 20px auto; display: block;"><?=translate('Назад')?></a>
<form action="" method="POST" id="edit_form">
    <label for="lang"><?=translate('Язык')?>: </label>
    <select name="lang" id="lang_id" class="text-box">
        <option value="ru"><?=translate('Русский')?></option>
        <option value="en"><?=translate('Английский')?></option>
    </select>
    <br>
    <label for="header"><?=translate('Заголовок')?>: </label>
    <input type="text" name="header" id="header_id" placeholder="<?=translate('Введите заголовок')?>" class="text-box">
    <br>
    <label for="tags"><?=translate('Теги')?>: </label>
    <input type="text" name="tags" id="tags_id" placeholder="<?=translate('Введите теги (через пробел)')?>" class="text-box">
    <br>
    <label for="content"><?=translate('Контент')?>: </label>
    <textarea onchange="Update(this)" onkeydown="Update(this)" onkeypress="Update(this)" onkeyup="Update(this)" onmousedown="Update(this)" name="content" id="content_id" placeholder="<?=translate('Введите содержимое блога')?>"></textarea>
    <br>
    <input type="submit" name="submit" value="<?=translate('Добавить')?>" class="submit-button">
</form>
<script>
    var element = document.getElementById('content_id');
    function Update(input) {
        element.innerHTML = input.value;
    }
</script>
<?php
    if ($_SESSION['id_to_edit_media_blog'] != -1) {
        echo "
            <script>
                document.getElementById('header_id').value = '".htmlspecialchars($to_edit['header'], ENT_QUOTES, 'UTF-8')."';
                document.getElementById('lang_id').value = '".htmlspecialchars($to_edit['lang'], ENT_QUOTES, 'UTF-8')."';
                document.getElementById('content_id').innerHTML = '".htmlspecialchars($to_edit['content'], ENT_QUOTES, 'UTF-8')."';
                document.getElementById('tags_id').value = '".htmlspecialchars($to_edit['tags'], ENT_QUOTES, 'UTF-8')."';
            </script>
        ";
    }
    include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
?>