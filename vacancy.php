<?php
    include($_SERVER['DOCUMENT_ROOT'].'/elements/vacancy-template.php');
    include($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    $mysqli = connect_to_database();
    session_start();
    $page_name = 'Вакансии';
    if (can_do('edit_vacancy')) {
        if (isset($_POST['delete_vacancy'])) {
            $mysqli->query("DELETE FROM vacancy WHERE `id` LIKE ".$_POST['delete_vacancy']);
            header("Location: ".$_SERVER['REQUEST_URI']);
        }
    }
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
    if (can_do('edit_vacancy'))
        echo '<a href="elements/vacancy-editor.php" style="margin-top: 20px; display: block; text-align: left;">Добавить запись</a>';
    $result = $mysqli->query("SELECT * FROM vacancy");
    echo '<div class="vacancy-wrapper">';
    while($row = $result->fetch_assoc()) {
        show_vacancy($row['name'], $row['type'], $row['creation_date'], $row['id']);
    }
    echo '<div>';
?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>