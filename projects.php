<?php
    $page_name = 'Проекты';
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/projects-template.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    $mysqli = connect_to_database();
    if (can_do('edit_projects')) {
        if (isset($_POST['name']) && $_POST['name'] != '' && isset($_POST['submit'])) {
            if (is_uploaded_file($_FILES['box_art']['tmp_name'])) {
                $dir = $_SERVER['DOCUMENT_ROOT'].'/projects_img';
                $files = [];
                if (!isset($max_id)) { // Чтобы создать новую картинку с уникальным id
                    $max_id = 1;
                }
                foreach (scandir($dir) as $key => $value) {
                    if ($value != '.' && $value != '..') {
                        array_push($files, $value);
                        $tmp = explode('.', $value);
                        if (is_numeric($tmp[0]) && $tmp[0] >= $max_id)
                            $max_id = $tmp[0] + 1;
                    }
                }
                $box_art_name = $max_id.'.jpg';
                if (can_upload($_FILES['box_art']))
                    make_upload($_FILES['box_art'], $max_id, 'projects_img');
            }
            else
                $box_art_name = 'no_image.jpg';
            $name = $mysqli->real_escape_string($_POST['name']);
            $mysqli->query("INSERT INTO projects (`name`, box_art_name) VALUES ('".$_POST['name']."', '$box_art_name')");
            header("Location: ".$_SERVER['REQUEST_URI']);
        }
    }
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
    if (can_do('edit_projects')) {
        echo '
            <form action="" method="POST" class="add-project" enctype="multipart/form-data">
                <label for="name">Введите название: </label>
                <input type="text" name="name" placeholder="Введите название проекта" class="text">
                <br>
                <label for="box_art">Выберите бокс-арт: </label>
                <input type="file" name="box_art">
                <p></p>
                <input type="submit" value="Добавить проект" name="submit">
            </form>
        ';
    }
    $result = $mysqli->query("SELECT * FROM projects");
    while ($row = $result->fetch_assoc()) {
        show_project($row['name'], $row['box_art_name'], $row['id']);
    }
    $mysqli->close();
?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>