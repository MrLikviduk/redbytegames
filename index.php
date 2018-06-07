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
    if (isset($_GET['key'])) {
        activate_user($_GET['key']);
        header("Location: ".explode('?', $_SERVER['REQUEST_URI'])[0]);
    }
    if (isset($_POST['resend']) && isset($_SESSION['login']) && username_is_set($_SESSION['login']) && get_field($_SESSION['login'], 'activated') === 0) {
        $s = get_email($_SESSION['login']);
        send_confirm_letter($s) or die("ERROR TO RESEND");
        header("Location: ".$_SERVER['REQUEST_URI']);
    }
    include('header.php');
?>
<section class="authorization">
    <?php 
        if (isset($_SESSION['login']) && isset($_SESSION['password']) && user_is_set($_SESSION['login'], $_SESSION['password'])) {
            if (get_field($_SESSION['login'], 'activated') === 0) {
                echo '
                <div class="confirm-email">
                    <h1>Нужно подтвердить свой электронный адрес</h1>
                    <p>
                        На Ваш почтовый ящик <span style="color: lightblue">'.get_email($_SESSION['login']).'</span> было выслано письмо, в котором вам нужно перейти по ссылке для подтверждения Вашего электронного адреса. <br>
                        <form action="" method="POST">
                            <button name="resend">Отправить письмо еще раз</button>
                        </form>
                    </p>
                </div>
                ';
            }
            else
                echo '
                    <div class="confirm-email">
                        <h1>Поздравляем! Ваш почтовый ящик прошел проверку!</h1>
                        <p>
                            Теперь вы можете пользоваться вашей учетной записью.
                        </p>
                    </div>
                ';
        }
        else
            include($_SERVER['DOCUMENT_ROOT'].'/elements/to-authorizate.php');
    ?>
    
</section>
<?php include('footer.php') ?>