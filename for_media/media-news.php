<?php
    session_start();
    $page_name = 'Новости для прессы';
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    if (!can_do('see_info_for_media')) {
        include($_SERVER['DOCUMENT_ROOT'].'/header.php');
        echo '<div style="margin: 30px auto;">'.translate('У вас нет прав для просмотра данной страницы').'</div>';
        include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
        exit();
    }
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>