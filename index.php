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
        <form action="" method="post" class="authorization-form">
            <label for="login">Логин: </label>
            <input type="text" placeholder="Введите логин" name="login" class="text">
            <br>
            <label for="password">Пароль: </label>
            <input type="password" placeholder="Введите пароль" name="password" class="text">
            <br>
            <?php 
                if (isset($_POST['login']) && isset($_POST['password'])) {
                    if (username_is_set($_POST['login']) == FALSE) {
                        echo '
                            <p class="error-text">Неверное имя пользователя или<br> пароль</p>
                        ';
                    }
                }
            ?>
            <input type="submit" value="Войти" class="login-btn">
            <br>
            <p class="to-registrate">Впервые на нашем сайте? <a href="/registration.php">Зарегистрируйтесь!</a></p>
        </form>
    </section>
<?php include('footer.php') ?>