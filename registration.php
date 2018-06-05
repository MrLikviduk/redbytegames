<?php
    $page_name = 'Регистрация';
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    if (isset($_POST['submit'])) {
        $min = [
            [$_POST['login'], 8],
            [$_POST['password'], 4],
            [$_POST['email'], 3],
        ];
        $v = true;
        foreach($min as $value)
            if (strlen($value[0]) < $value[1])
                $v = false;
        if ($v == TRUE) {
            if (username_is_set($_POST['login']) == FALSE && email_is_set($_POST['email']) == FALSE && $_POST['password'] == $_POST['confirm_password'] && $_POST['policy'] == 'Yes') {
                create_user($_POST['login'], $_POST['email'], $_POST['password'], 'user');
                header("Location: /index.php");
            }
        }
    }
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<form action="" method="POST" class="registration-form">
    <label for="type">Выберите тип учетной записи: </label>
    <select name="type" onchange="Changed()" id="select-form">
        <option value="user">Пользователь</option>
        <option value="media">Пресса</option>
        <option value="moderator">Модератор</option>
        <option value="owner">Владелец</option>
    </select>
    <div id="form0" style="display: block;">
        <label for="login">Имя пользователя: </label>
        <input type="text" maxlength="32" class="text" placeholder="Введите логин" name="login">
        <label for="email">Электронный адрес</label>
        <input type="email" class="text" name="email" placeholder="Введите электронный адрес">
        <label for="password">Пароль: </label>
        <input type="password" maxlength="32" class="text" placeholder="Введите пароль" name="password">
        <label for="confirm_password">Подтвердите пароль: </label>
        <input type="password" maxlength="32" class="text" placeholder="Подтвердите пароль" name="confirm_password">
        <br>
        <div style=" margin: 8px auto; margin-left: 11px;">
            <input type="checkbox" name="policy" value="Yes">
            <label for="policy" style="display: inline;">Я согласен на хранение и обработку личных данных</label>
        </div>
    </div>
    <div id="form1" style="display: none;">
        Это для прессы!
    </div>
    <div id="form2" style="display: none;">
        Это для модера!
    </div>
    <div id="form3" style="display: none;">
        Это для владельца!
    </div>
    <div class="btn-wrapper">
        <input type="submit" value="Зарегистрироваться" class="submit-btn" name="submit">
    </div>
</form>
<script>
    var s = 0;
    function Changed() {
        document.getElementById('form' + s).style.display = 'none';
        s = document.getElementById('select-form').selectedIndex;
        document.getElementById('form' + s).style.display = 'block';
    }
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php') ?>