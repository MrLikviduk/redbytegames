<?php
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    $page_name = 'О нас';
    include('header.php');
?>
<section class="some-important-info">
    <h1><?=translate('О нас')?></h1>
    <div class="main-info">
        <p>Мы - новоиспеченная компания специализирующаяся на разработке и переиздании мобильных игр. 
            Наша цель - приносить людям самый драйвовый, уникальный и запоминающийся игровой опыт.</p>
    </div>
</section>
<?php include('footer.php') ?>