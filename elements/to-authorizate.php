<form action="" method="post" class="authorization-form">
    <label for="login"><?=translate('Логин')?>: </label>
    <input type="text" name="login" class="text">
    <br>
    <label for="password"><?=translate('Пароль')?>: </label>
    <input type="password" name="password" class="text">
    <br>
    <?php 
        if (isset($_POST['login']) && isset($_POST['password'])) {
            if (user_is_set($_POST['login'], $_POST['password']) == FALSE) {
                echo '
                    <p class="error-text">'.translate('Неверное имя пользователя или пароль').'</p>
                ';
            }
        }
    ?>
    <input type="submit" value="<?=translate('Войти')?>" class="login-btn">
    <br>
    <p class="to-registrate"><?=translate('Впервые на нашем сайте?')?> <a href="/registration.php"><?=translate('Зарегистрируйтесь')?>!</a></p>
</form>