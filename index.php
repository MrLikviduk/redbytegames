<?php
    $page_name = 'Главная';
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    if (isset($_POST['login']) && isset($_POST['password'])) {
        if (username_is_set($_POST['login']) == TRUE) {
            $_SESSION['login'] = $_POST['login'];
            $_SESSION['password'] = $_POST['password'];
            header("Location: ".$_SERVER['REQUEST_URI']);
        }
    }
    include('header.php');
?>
<section class="authorization">
    <?php 
        if (isset($_SESSION['login']) && isset($_SESSION['password']) && user_is_set($_SESSION['login'], $_SESSION['password']))
            echo '<div class="main-header">Добро пожаловать, '.$_SESSION['login'].'</div>';
        else
            include($_SERVER['DOCUMENT_ROOT'].'/elements/to-authorizate.php');
    ?>
</section>
<?php include('footer.php') ?>