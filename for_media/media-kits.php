<?php
    session_start();
    $page_name = 'Киты для прессы';
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/media-template.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    if (!can_do('see_info_for_media')) {
        include($_SERVER['DOCUMENT_ROOT'].'/header.php');
        echo '<div style="margin: 30px auto;">'.translate('У вас нет прав для просмотра данной страницы').'</div>';
        include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
        exit();
    }
    $mysqli = connect_to_database();
    if (can_do('edit_for_media')) {
        if (isset($_POST['delete_file'])) {
            $id = (int)$_POST['delete_file'];
            $kit = get_by_id($id, 'kits');
            unlink($_SERVER['DOCUMENT_ROOT'].'/for_media/kits/'.$kit['filename']);
            $mysqli->query("DELETE FROM kits WHERE id = $id");
            header("Location: ".$_SERVER['REQUEST_URI']);
        }
    }
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<script>
    var main = $('main');
    main.css('text-align', 'center');
    main.css('padding', '0 15vw');
    if (window.matchMedia('(max-width: 1212px)').matches) {
        main.css('padding', '0 10vw');
    }
</script>
<?php
    if (can_do('edit_for_media'))
        echo '<a href="/for_media/media-kits-editor.php" style="margin-top: 20px; display: block; text-align: left;">'.translate('Добавить файл').'</a>';
    $result = $mysqli->query("SELECT * FROM kits ORDER BY id DESC");
    while($row = $result->fetch_assoc()) {
        $filename = $row['filename'];
        $temp_array = explode('.', $filename);
        $extension = end($temp_array);
        show_kit($row['name'], $extension, $row['creation_date'], $row['id']);
    }

?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); $mysqli->close(); ?>