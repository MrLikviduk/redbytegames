<?php
    session_start();
    $page_name = 'Вопрос-Ответ для прессы';
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/media-template.php');
    if (!can_do('see_info_for_media')) {
        include($_SERVER['DOCUMENT_ROOT'].'/header.php');
        echo '<div style="margin: 30px auto;">'.translate('У вас нет прав для просмотра данной страницы').'</div>';
        include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
        exit();
    }
    $mysqli = connect_to_database();
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<script>
    var main = $('main');
    main.css('padding', '0 5vw');
    if (window.matchMedia('(min-width: 800px)').matches) {
        main.css('padding', '0 15vw');
    }
    if (window.matchMedia('(min-width: 1200px)').matches) {
        main.css('padding', '0 25vw');
    }
</script>
<?php
    $result = $mysqli->query("SELECT * FROM questions_answers ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) {
        show_qa($row['question'], $row['answer'], $row['date']);
    }
?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); $mysqli->close(); ?>