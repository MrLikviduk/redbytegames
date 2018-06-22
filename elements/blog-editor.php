<?php
    $page_name = 'Редактор блога';
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    if (!can_do('edit_blog')) {
        include($_SERVER['DOCUMENT_ROOT'].'/header.php');
        echo '<div style="margin: 30px auto;">У вас нет прав для просмотра данной страницы</div>';
        include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
        exit();
    }

    if (!isset($date))
        $date = date('Y-m-d');
    if (!isset($_SESSION['id_to_edit_blog']))
        $_SESSION['id_to_edit_blog'] = -1;
    $mysqli = connect_to_database();
    $query = $mysqli->query("SELECT * FROM blog WHERE id = ".((int)$_SESSION['id_to_edit_blog']));
    $to_edit = $query->fetch_assoc();
    if(isset($_POST['header']) && $_POST['header'] != '' && $_POST['content'] != '' && isset($_POST['submit']) && can_do('edit_blog')) {
        $header = $mysqli->real_escape_string($_POST['header']);
        $content = $mysqli->real_escape_string($_POST['content']);
        $tags = $mysqli->real_escape_string($_POST['tags']);
        if ($_SESSION['id_to_edit_blog'] == -1)
            $mysqli->query("INSERT INTO blog (id, header, content, creation_date, tags) VALUES (NULL, '$header', '$content', '$date', '$tags')") or die("Error");
        else {
            $t_id = (int)$_SESSION['id_to_edit_blog'];
            $date = $mysqli->real_escape_string($to_edit['creation_date']);
            $mysqli->query("UPDATE blog SET header = '$header', content = '$content', creation_date = '$date', tags = '$tags' WHERE id = $t_id") or die("Error to edit");
        }
        $_SESSION['id_to_edit_blog'] = -1;
        header("Location: ".$_SERVER['REQUEST_URI']);
    }
    $mysqli->close();
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<a href="/blogs.php" style="margin: 20px auto; display: block;">Назад</a>
<form action="" method="POST" id="edit_form">
    <label for="header">Заголовок: </label>
    <input type="text" name="header" id="header_id" placeholder="Введите название" class="text-box">
    <br>
    <label for="tags">Теги: </label>
    <input type="text" name="tags" id="tags_id" placeholder="Введите теги (через пробел)" class="text-box">
    <br>
    <label for="content">Контент: </label>
    <textarea onchange="Update(this)" onkeydown="Update(this)" onkeypress="Update(this)" onkeyup="Update(this)" onmousedown="Update(this)" name="content" id="content_id" placeholder="Введите содержимое блога"></textarea>
    <br>
    <!-- <a href="/elements/choose-image-for-blog.php" target="_blank"><div class="insert-image-button">Вставить картинку</div></a>
    <br> -->
    <input type="submit" name="submit" value="Добавить" class="submit-button">
</form>
<!-- <form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="filename">
    <button name="insert_image">Добавить картинку</button>
</form> -->
<script>
    var element = document.getElementById('content_id');
    function Update(input) {
        element.innerHTML = input.value;
    }
</script>
<?php
    if ($_SESSION['id_to_edit_blog'] != -1) {
        echo "
            <script>
                document.getElementById('header_id').value = '".htmlspecialchars($to_edit['header'], ENT_QUOTES, 'UTF-8')."';
                document.getElementById('content_id').innerHTML = '".htmlspecialchars($to_edit['content'], ENT_QUOTES, 'UTF-8')."';
                document.getElementById('tags_id').value = '".htmlspecialchars($to_edit['tags'], ENT_QUOTES, 'UTF-8')."';
            </script>
        ";
    }
    include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
?>