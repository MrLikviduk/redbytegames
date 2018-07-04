<?php
    session_start();
    if (isset($_POST['lang'])) {
        $_SESSION['lang'] = $_POST['lang'];
        header('Location: /index.php');
    }
    $page_name = 'Выбор языка';
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<div class="wrapper">
    <h1>Choose The Language:</h1>
    <form action="" method="post">
        <input type="image" src="/img/en.png" name="lang" value="en">
        <input type="image" src="/img/ru.png" name="lang" value="ru">
    </form>
</div>
<script>
    $('.wrapper').css('top', 'calc(50vh - ' + $('.wrapper').css('height') + ' / 2 - 20px)');
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>