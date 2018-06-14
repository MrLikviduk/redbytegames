<?php 
    $page_name = 'Выбор картинки';
    session_start();


    if (!isset($_SESSION['num_of_rows_pictures'])) {
        $_SESSION['num_of_rows_pictures'] = 0;
    }

    if ($_SESSION['logged_in'] != TRUE || !isset($_SESSION['logged_in'])) {
        include($_SERVER['DOCUMENT_ROOT'].'/header.php');
        echo '<div style="margin: 30px auto;">У вас нет прав для просмотра данной страницы</div>';
        include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
        exit();
    }
    $dir = $_SERVER['DOCUMENT_ROOT'].'/blog_img';
    $files = [];
    if (!isset($max_id)) { // Чтобы создать новую картинку с уникальным id
        $max_id = 1;
    }
    foreach (scandir($dir) as $key => $value) {
        if ($value != '.' && $value != '..') {
            array_push($files, $value);
            $tmp = explode('.', $value);
            if ($tmp[0] >= $max_id)
                $max_id = $tmp[0] + 1;
        }
    }
    
    if (!isset($pictures)) {
        $pictures = [];
        $result = $files;
        foreach ($files as $key => $value) {
            $pictures[($key - ($key % 12)) / 12][$key % 12] = $value;
        }
    }

    if (isset($pictures)) {
        if (isset($_POST['show_pictures']) && $_SESSION['num_of_rows_pictures'] < count($pictures) - 1) {
            $_SESSION['num_of_rows_pictures'] += 1;
            header("Location: ".$_SERVER['REQUEST_URI']);
        }
        else if (isset($_POST['hide_pictures']) && $_SESSION['num_of_rows_pictures'] > 0) {
            $_SESSION['num_of_rows_pictures'] -= 1;
            header("Location: ".$_SERVER['REQUEST_URI']);
        }
    }

    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<a href="/elements/blog-editor.php" style="margin: 20px auto; display: block;">Назад</a>
<form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="filename">
    <input type="submit" value="Добавить картинку">
</form>
<div class="pictures-to-choose-image">
<?php
    if (isset($pictures)) {
        if ($_SESSION['num_of_rows_pictures'] > 0) {
            echo '
                <form method="POST" action="">
                    <button name="hide_pictures" class="show-pictures-btn" style="margin-bottom: 0;">Вернуться к предыдущим картинкам</button>
                </form>
            ';
        }
        foreach ($pictures[$_SESSION['num_of_rows_pictures']] as $key => $value) {
            echo '
                <a href="/elements/blog-editor.php?image='.$value.'?content='.$_GET['content'].'">
                    <img src="'.'/blog_img/'.$value.'" class="picture">
                </a>
            ';
        }
        if ($_SESSION['num_of_rows_pictures'] < count($pictures) - 1) {
            echo '
                <form method="POST" action="">
                    <button name="show_pictures" class="show-pictures-btn" style="margin-top: 0;">Показать еще картинки</button>
                </form>
            ';
        }
    }
?>
</div>
<?php   
    include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); 
?>