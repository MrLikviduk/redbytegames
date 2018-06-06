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