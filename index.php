<?php
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    $page_name = 'Главная';
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<ul class="rslides">
    <li><img src="/latest_news_img/1.jpg" alt=""></li>
    <li><img src="/latest_news_img/2.jpg" alt=""></li>
    <li><img src="/latest_news_img/3.jpg" alt=""></li>
</ul>
<script>
    $(function() {
        $(".rslides").responsiveSlides();
    });
    $(".rslides").responsiveSlides({
        pager: true
    });
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php') ?>