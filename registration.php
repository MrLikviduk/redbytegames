<?php
    $page_name = 'Регистрация';
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    define('LOGIN_MIN', 6);
    define('PASSWORD_MIN', 4);
    define('EMAIL_MIN', 3);
    if (isset($_POST['submit'])) {
        $min = [
            [$_POST['login'], LOGIN_MIN],
            [$_POST['password'], PASSWORD_MIN],
            [$_POST['email'], EMAIL_MIN]
        ];
        $v = true;
        foreach($min as $value)
            if (strlen($value[0]) < $value[1])
                $v = false;
        if ($v == TRUE) {
            if (username_is_set($_POST['login']) == FALSE && email_is_set($_POST['email']) == FALSE && $_POST['password'] == $_POST['confirm_password'] && $_POST['policy'] == 'Yes') {
                create_user($_POST['login'], $_POST['email'], $_POST['password'], 'non_activated');
                $key = md5(rand(-2147483647, 2147483647));
                add_email_key($_POST['login'], $key);
                send_confirm_letter($_POST['email']);
                header("Location: /index.php");
            }
        }
    }
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<div class="reg-wrapper">
    <form action="" method="POST" class="registration-form">
        <label for="type">Выберите тип учетной записи: </label>
        <select name="type" onchange="Changed()" id="select-form">
            <option value="user">Пользователь</option>
            <option value="media">Пресса</option>
            <option value="moderator">Модератор</option>
            <option value="owner">Владелец</option>
        </select>
        <div id="form0" style="display: block;">
            <div class="data-wrapper">
                <label for="login">Имя пользователя: </label>
                <input type="text" maxlength="32" class="text" placeholder="Введите логин" name="login" id="login">
                <?php
                    if (isset($_POST['login'])) {
                        if (strlen($_POST['login']) < LOGIN_MIN)
                            echo '<p class="error-text">Количество символов не может быть меньше '.LOGIN_MIN.'</p>';
                        else if (username_is_set($_POST['login']))
                            echo '<p class="error-text">Это имя пользователя уже занято</p>';
                        else
                            echo "<script>document.getElementById('login').value='".$_POST['login']."'</script>";
                    }
                ?>
                <label for="email">Электронный адрес</label>
                <input type="email" class="text" name="email" placeholder="Введите электронный адрес" id="email">
                <?php
                    if (isset($_POST['email'])) {
                        if (strlen($_POST['email']) < EMAIL_MIN)
                            echo '<p class="error-text">Количество символов не может быть меньше '.EMAIL_MIN.'</p>';
                        else if (email_is_set($_POST['email'])) {
                            echo '<p class="error-text">На этот электронный адрес уже зарегистрирована учетная запись</p>';
                        }
                        else
                            echo "<script>document.getElementById('email').value='".$_POST['email']."'</script>";
                    }
                ?>
                <label for="password">Пароль: </label>
                <input type="password" maxlength="32" class="text" placeholder="Введите пароль" name="password">
                <?php
                    if (isset($_POST['password']))
                        if (strlen($_POST['password']) < EMAIL_MIN)
                            echo '<p class="error-text">Количество символов не может быть меньше '.PASSWORD_MIN.'</p>';
                ?>
                <label for="confirm_password">Подтвердите пароль: </label>
                <input type="password" maxlength="32" class="text" placeholder="Подтвердите пароль" name="confirm_password">
                <?php
                    if (isset($_POST['password']) && isset($_POST['confirm_password']))
                        if ($_POST['password'] != $_POST['confirm_password'])
                            echo '<p class="error-text">Пароли не совпадают</p>';
                ?>
                <br>
                <div style="margin: 8px auto; margin-left: 11px;">
                    <input type="checkbox" name="policy" value="Yes">
                    <label for="policy" style="display: inline;">Я согласен(на) на хранение и обработку личных данных</label>
                </div>
            </div>
        </div>
        <div id="form1" style="display: none;">
            <div class="data-wrapper">
                <label for="employee_login">Имя пользователя: </label>
                <input type="text" maxlength="32" class="text" placeholder="Введите логин" name="employee_login" id="employee_login">
                <label for="employee_password">Пароль: </label>
                <input type="password" maxlength="32" class="text" placeholder="Введите пароль" name="employee_password">
                <label for="employee_email">Электронный адрес: </label>
                <input type="email" class="text" name="employee_email" placeholder="Введите электронный адрес" id="employee_email">
                <label for="employee_name">Имя: </label>
                <input type="text" class="text" name="employee_name" placeholder="Введите имя">
                <label for="employee_surname">Фамилия: </label>
                <input type="text" class="text" name="employee_surname" placeholder="Введите фамилию">
                <label for="employee_otchestvo">Отчество: </label>
                <input type="text" class="text" name="employee_otchestvo" placeholder="Введите отчество">
                <label for="employee_position">Должность: </label>
                <input type="text" class="text" name="employee_position" placeholder="Введите должность">
                <label for="employee_channel">Блог/издание/канал: </label>
                <input type="text" class="text" name="employee_channel" placeholder="Введите блог/издание/канал">
                <label for="employee_link">Ссылка на ресурс: </label>
                <input type="url" class="text" name="employee_link" placeholder="Введите ссылку на ресурс">
                <label for="employee_socnet">Связь в социальных сетях: </label>
                <textarea class="text" rows="4" name="employee_socnet"></textarea>
                <label for="employee_num">Номер телефона: </label>
                <input type="tel" class="text" name="employee_num" placeholder="Введите номер телефона">
                <label for="employee_address">Адрес: </label>
                <textarea class="text" rows="4" name="employee_address"></textarea>
                <div style="margin: 8px auto; margin-left: 11px;">
                    <input type="checkbox" name="passed_interview" value="Yes">
                    <label for="policy" style="display: inline;">Я прошел(ла) собеседование</label>
                </div>
                <div style="margin: 8px auto; margin-left: 11px;">
                    <input type="checkbox" name="employee_policy" value="Yes">
                    <label for="policy" style="display: inline;">Я согласен(на) на хранение и обработку личных данных</label>
                </div>
            </div>
       </div>
        <div class="btn-wrapper">
            <input type="submit" value="Зарегистрироваться" class="submit-btn" name="submit">
        </div>
    </form>
</div>
<script>
    var s = 0;
    function Changed() {
        document.getElementById('form' + s).style.display = 'none';
        s = document.getElementById('select-form').selectedIndex;
        if (s > 0) s = 1;
        document.getElementById('form' + s).style.display = 'block';
    }
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php') ?>