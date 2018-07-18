<?php
    session_start();
    if (isset($_POST['lang'])) {
        $_SESSION['lang'] = $_POST['lang'];
        header('Location: /index');
    }
    $page_name = 'Выбор языка';
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<div class="wrapper">
    <h1>Choose The Language:</h1>
    <form action="" method="post">
        <button type="submit" name="lang" value="en"><img src="/img/en.png" alt="en"></button>
        <button type="submit" name="lang" value="ru"><img src="/img/ru.png" alt="ru"></button>
    </form>
</div>
<script>
    $('.wrapper').css('top', 'calc(50vh - ' + $('.wrapper').css('height') + ' / 2 - 20px)');
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>