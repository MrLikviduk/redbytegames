<?php
    session_start();
    $page_name = 'Для прессы';
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    if (!can_do('see_info_for_media')) {
        include($_SERVER['DOCUMENT_ROOT'].'/header.php');
        echo '<div style="margin: 30px auto;">'.translate('У вас нет прав для просмотра данной страницы').'</div>';
        include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
        exit();
    }
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<div class="choose-menu">
    <a href="/for_media/media-blog.php">
        <div class="choose-element">
            <?=translate('Блог')?>
        </div>
    </a>
    <a href="/for_media/media-question-answer.php">
        <div class="choose-element">
            <?=translate('Вопрос-Ответ')?>
        </div>
    </a>
    <a href="/for_media/media-kits.php">
        <div class="choose-element">
            <?=translate('Киты')?>
        </div>
    </a>
</div>
<script>
    $('.choose-menu').css('top', 'calc(50vh - ' + $('.choose-menu').css('height') + ' / 2 - 20px)');
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>