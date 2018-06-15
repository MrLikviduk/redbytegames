<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    $page_name = 'Редактор вакансий';
    $mysqli = connect_to_database();
    session_start();
    if (!can_do('edit_vacancy')) {
        include($_SERVER['DOCUMENT_ROOT'].'/header.php');
        echo '<div style="margin: 30px auto;">У вас нет прав для просмотра данной страницы</div>';
        include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
        exit();
    }
    if (can_do('edit_vacancy')) {
        if (isset($_POST['submit']) && strlen($_POST['name']) != 0 && strlen($_POST['type']) != 0) {
            $name = $_POST['name'];
            $type = $_POST['type'];
            $date = date('d.m.Y');
            $mysqli->query("INSERT INTO vacancy (id, `name`, `type`, `creation_date`) VALUES (NULL, '$name', '$type', '$date')") or die("ERROR");
            header("Location: ".$_SERVER['REQUEST_URI']);
        }
    }
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<a href="/vacancy.php" style="margin: 20px auto; display: block;">Назад</a>
<form action="" method="POST" id="edit_form">
    <label for="name">Заголовок: </label>
    <input type="text" name="name" id="name_id" placeholder="Введите название" class="text-box">
    <br>
    <label for="type">Теги: </label>
    <input type="text" name="type" id="type_id" placeholder="Введите направление" class="text-box">
    <br>
    <input type="submit" name="submit" value="Добавить" class="submit-button">
</form>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php') ?>