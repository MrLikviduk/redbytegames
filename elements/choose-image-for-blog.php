<?php 
    $page_name = 'Выбор картинки';
    session_start();

    function can_upload($file){
        // если имя пустое, значит файл не выбран
        if($file['name'] == '')
            return 'Вы не выбрали файл.';
        
        /* если размер файла 0, значит его не пропустили настройки 
        сервера из-за того, что он слишком большой */
        if($file['size'] == 0)
            return 'Файл слишком большой.';
        
        // разбиваем имя файла по точке и получаем массив
        $getMime = explode('.', $file['name']);
        // нас интересует последний элемент массива - расширение
        $mime = strtolower(end($getMime));
        // объявим массив допустимых расширений
        $types = array('jpg', 'png', 'gif', 'bmp', 'jpeg');
        
        // если расширение не входит в список допустимых - return
        if(!in_array($mime, $types))
            return 'Недопустимый тип файла.';
        
        return true;
    }
    
    function make_upload($file){	
        // формируем уникальное имя картинки: случайное число и name
        $name = mt_rand(0, 10000) . $file['name'];
        copy($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/blog_img/' . $name);
    }

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
    foreach (scandir($dir) as $key => $value) {
        if ($value != '.' && $value != '..')
            array_push($files, $value);
    }
    if (isset($_FILES['filename'])) {
        $check = can_upload($_FILES['filename']);
        if ($check === TRUE) {
            make_upload($_FILES['filename']);
        }
        header("Location: ".$_SERVER['REQUEST_URI']);
    }
    
    if (!isset($pictures)) {
        $pictures = [];
        $result = $files;
        foreach ($files as $key => $value) {
            $pictures[($key - ($key % 9)) / 9][$key % 9] = $value;
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
                <img src="'.'/blog_img/'.$value.'" class="picture">
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