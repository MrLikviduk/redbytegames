<?php
    $page_name = 'Проекты';
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/projects-template.php');
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
    show_project('Skater', '/projects_img/no_image.jpg');
?>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>