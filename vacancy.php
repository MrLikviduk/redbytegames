<?php
    include($_SERVER['DOCUMENT_ROOT'].'/elements/vacancy-template.php');
    include($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    $mysqli = connect_to_database();
    session_start();
    $page_name = 'Вакансии';
    if (isset($_SESSION['id_to_edit_vacancy'])) unset($_SESSION['id_to_edit_vacancy']);
    if (can_do('edit_vacancy')) {
        if (isset($_POST['delete_vacancy'])) {
            $mysqli->query("DELETE FROM vacancy WHERE `id` LIKE ".$_POST['delete_vacancy']);
            header("Location: ".$_SERVER['REQUEST_URI']);
        }
        if (isset($_POST['edit_vacancy'])) {
            $_SESSION['id_to_edit_vacancy'] = $_POST['edit_vacancy'];
            header("Location: /elements/vacancy-editor.php");
        }
    }
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
    if (can_do('edit_vacancy'))
        echo '<a href="elements/vacancy-editor.php" style="margin-top: 20px; display: block; text-align: left;">Добавить запись</a>';
    $result = $mysqli->query("SELECT * FROM vacancy");
    echo '<div class="vacancy-wrapper">';
    while($row = $result->fetch_assoc()) {
        show_vacancy($row['name'], get_data('vacancy_types', 'id', $row['type_id'])['name'], $row['creation_date'], $row['id']);
    }
    echo '<div>';
?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>