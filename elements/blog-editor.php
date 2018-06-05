<?php
    $page_name = 'Редактор блога';
    session_start();

    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != TRUE) {
        include($_SERVER['DOCUMENT_ROOT'].'/header.php');
        echo '<div style="margin: 30px auto;">У вас нет прав для просмотра данной страницы</div>';
        include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
        exit();
    }

    if (!isset($date))
        $date = date('Y-m-d');
    if (!isset($_SESSION['id_to_edit_blog']))
        $_SESSION['id_to_edit_blog'] = -1;
    include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
    $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name) or die("Error to connect to db");
    $query = $mysqli->query("SELECT * FROM blog WHERE id LIKE ".$_SESSION['id_to_edit_blog']);
    $to_edit = $query->fetch_assoc();
    if(isset($_POST['header']) && $_POST['header'] != '' && $_POST['content'] != '' && isset($_POST['submit']) && $_SESSION['logged_in'] == TRUE) {
        $header = $_POST['header'];
        $content = $_POST['content'];
        $tags = $_POST['tags'];
        if ($_SESSION['id_to_edit_blog'] == -1)
            $mysqli->query("INSERT INTO blog (id, header, content, creation_date, tags) VALUES (NULL, '$header', '$content', '$date', '$tags')") or die("Error");
        else {
            $t_id = $_SESSION['id_to_edit_blog'];
            $mysqli->query("DELETE FROM blog WHERE id LIKE $t_id") or die("Blin(");
            $date = $to_edit['creation_date'];
            $mysqli->query("INSERT INTO blog (id, header, content, creation_date, tags) VALUES ($t_id, '$header', '$content', '$date', '$tags')") or die("Error");
        }
        $_SESSION['id_to_edit_blog'] = -1;
        header("Location: ".$_SERVER['REQUEST_URI']);
    }
    // if (!isset($max_id)) { // Чтобы создать новую картинку с уникальным id
    //     $max_id = 1;
    // }
    // $dir = $_SERVER['DOCUMENT_ROOT'].'/blog_img';
    // $files = [];
    // foreach (scandir($dir) as $key => $value) {
    //     if ($value != '.' && $value != '..') {
    //         array_push($files, $value);
    //         $tmp = explode('.', $value);
    //         if ($tmp[0] >= $max_id)
    //             $max_id = $tmp[0] + 1;
    //     }
    // }
    // if (!isset($file_to_insert))
    //     $file_to_insert = -1;
    // if (isset($_FILES['filename']) && isset($_POST['insert_image'])) {
    //     $check = can_upload($_FILES['filename']);
    //     if ($check === TRUE) {
    //         make_upload($_FILES['filename'], $max_id);
    //         $file_to_insert = $_FILES['filename'];
    //         echo "<script>alert('CHECK')</script>";
    //     }
    // }
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
    <a href="/elements/choose-image-for-blog.php" target="_blank"><div class="insert-image-button">Вставить картинку</div></a>
    <br>
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
                document.getElementById('header_id').value = '".$to_edit['header']."';
                document.getElementById('content_id').innerHTML = '".$to_edit['content']."';
                document.getElementById('tags_id').value = '".$to_edit['tags']."';
            </script>
        ";
    }
    include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
?>