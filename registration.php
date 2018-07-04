<?php
    $page_name = 'Регистрация';
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/constants.php');
    $db['login'] = 'username';
    $db['password'] = 'passwd';
    $db['email'] = 'email';
    $db['fullname'] = 'fullname';
    $db['position'] = 'position';
    $db['channel'] = 'channel';
    $db['link'] = 'link_to_resource';
    $db['socnet'] = 'socnet';
    $db['telnum'] = 'telephone_number';
    $db['address'] = 'address';
    $db['fraction'] = 'fraction';
    $db['passedinterview'] = 'passed_interview';

    function input_text($type, $name, $label, $min, $max, $num_of_rows = 1, $type_of_input = 'text') {
        $type_name = $type.'_'.$name;
        if ($num_of_rows < 1)
            return FALSE;
        echo '<label for="'.$type_name.'">'.$label.'</label>';
        if ($num_of_rows > 1) {
            echo '
                <textarea class="text" maxlength="'.$max.'" rows="'.$num_of_rows.'" name="'.$type_name.'" id="'.$type_name.'"></textarea>
            ';
        }
        else {
            echo '
                <input type="'.$type_of_input.'" maxlength="'.$max.'" class="text" name="'.$type_name.'" id="'.$type_name.'">
            ';
        }
        echo '<p class="error-text" style="display: none;" id="'.$type_name.'_error_text">Количество символов не должно быть меньше '.$min.'</p>';
        echo "
            <script>
                $('#".$type_name."').keyup(changed_input);
                $('#".$type_name."').change(changed_input);
                function changed_input () {
                    var value = $('#".$type_name."').val();
                    var error = $('#".$type_name."_error_text');
                    if ('".$name."' == 'confirm_password') {
                        if ($('#".$type.'_'.'password'."').val() != value) {
                            error.html('Пароли не совпадают');
                            error.css('display', 'block');
                        }
                        else {
                            error.css('display', 'none');
                        }
                    }
                    else if (value.length == 0 && ".$min." > 0) {
                        error.html('Поле не должно быть пустым');
                        error.css('display', 'block');
                    }
                    else if (value.length < ".$min.") {
                        error.html('Количество символов не должно быть меньше ".$min."');
                        error.css('display', 'block');
                    }
                    else
                        error.css('display', 'none');
                }
            </script>
        ";
    }
    function input_checkbox($type, $name, $label) {
        echo '
            <div style="margin: 8px auto; margin-left: 11px;">
                <input type="checkbox" name="'.$type.'_'.$name.'" value="1">
                <label for="'.$type.'_'.$name.'" style="display: inline;">'.$label.'</label>
            </div>
        ';
    }

    if (isset($_POST['submit']) && $_POST['type'] == 'user' && check_captcha($_POST['g-recaptcha-response'])) {
        $min = [
            [$_POST['user_login'], LOGIN_MIN],
            [$_POST['user_password'], PASSWORD_MIN],
            [$_POST['user_email'], EMAIL_MIN]
        ];
        $v = true;
        foreach($min as $value)
            if (strlen($value[0]) < $value[1])
                $v = false;
        if ($v == TRUE) {
            if (username_is_set($_POST['user_login']) == FALSE && email_is_set($_POST['user_email']) == FALSE && $_POST['user_password'] == $_POST['user_confirm_password'] && $_POST['user_policy'] == '1') {
                create_user($_POST['user_login'], $_POST['user_email'], $_POST['user_password'], 'user');
                add_email_key($_POST['user_login']);
                send_confirm_letter($_POST['user_email']);
                header("Location: /authorization.php");
            }
        }
    }
    else if (isset($_POST['submit']) && check_captcha($_POST['g-recaptcha-response'])) {
        $type = $_POST['type'];
        foreach ($_POST as $key => $value) {
            if (explode('_', $key)[0] == $type)
                $lst[$key] = $value;
        }
        if (!email_is_set($lst[$type.'_email']) && data_is_set('non_activated_employees', 'username', $lst[$type.'_login']) && data_is_set('non_activated_employees', 'passwd', $lst[$type.'_password']) && get_role($lst[$type.'_login'], 'non_activated_employees') == $type && $_POST[$type.'_policy'] == '1') {
            $role = $type;
            delete_data('non_activated_employees', 'username', $lst[$type.'_login']);
            create_user($lst[$type.'_login'], $lst[$type.'_email'], $lst[$type.'_password'], $role) or die("ERROR1");
            foreach ($lst as $key => $value) {
                set_data('users', 'username', $lst[$type.'_login'], $db[explode('_', $key)[1]], $value);
            }
            add_email_key($lst[$type.'_login']);
            send_confirm_letter($lst[$type.'_email']);
            header("Location: /authorization.php");
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
                <?php 
                    input_text('user', 'login', 'Логин: ', LOGIN_MIN, LOGIN_MAX);
                    input_text('user', 'email', 'Электронный адрес: ', EMAIL_MIN, EMAIL_MAX, 1, 'email');
                    input_text('user', 'password', 'Пароль: ', PASSWORD_MIN, PASSWORD_MAX, 1, 'password');
                    input_text('user', 'confirm_password', 'Подтвердите пароль: ', PASSWORD_MIN, PASSWORD_MAX, 1, 'password');
                    input_checkbox('user', 'policy', 'Я согласен(на) на хранение и обработку личных данных');
                ?>
            </div>
        </div>
        <div id="form1" style="display: none;">
            <div class="data-wrapper">
                <?php 
                    input_text('media', 'login', 'Логин: ', LOGIN_MIN, LOGIN_MAX);
                    input_text('media', 'password', 'Пароль: ', PASSWORD_MIN, PASSWORD_MAX, 1, 'password');
                    input_text('media', 'email', 'Электронная почта: ', EMAIL_MIN, EMAIL_MAX, 1, 'email');
                    input_text('media', 'fullname', 'ФИО: ', FULLNAME_MIN, FULLNAME_MAX);
                    input_text('media', 'channel', 'Блог/издание/канал: ', CHANNEL_MIN, CHANNEL_MAX);
                    input_text('media', 'position', 'Утвержденная должность: ', POSITION_MIN, POSITION_MAX);
                    input_text('media', 'link', 'Ссылка на ресурс: ', LINK_MIN, LINK_MAX, 1, 'url');
                    input_text('media', 'socnet', 'Связь в социальных сетях: ', SOCNET_MIN, SOCNET_MAX, 4);
                    input_text('media', 'telnum', 'Номер телефона: ', TELNUM_MIN, TELNUM_MAX, 1, 'tel');
                    input_text('media', 'address', 'Адрес: ', ADDRESS_MIN, ADDRESS_MAX, 4);
                    input_checkbox('media', 'policy', 'Я согласен(на) на хранение и обработку личных данных');
                ?>
            </div>
       </div>
       <div id="form2" style="display: none;">
            <div class="data-wrapper">
                <?php 
                    input_text('moderator', 'login', 'Логин: ', LOGIN_MIN, LOGIN_MAX, 1);
                    input_text('moderator', 'password', 'Пароль: ', PASSWORD_MIN, PASSWORD_MAX, 1, 'password');
                    input_text('moderator', 'email', 'Электронная почта: ', EMAIL_MIN, EMAIL_MAX, 1);
                    input_text('moderator', 'fullname', 'ФИО: ', FULLNAME_MIN, FULLNAME_MAX);
                    input_text('moderator', 'position', 'Утвержденная должность: ', POSITION_MIN, POSITION_MAX);
                    input_checkbox('moderator', 'passedinterview', 'Я прошел(ла) собеседование');
                    input_checkbox('moderator', 'policy', 'Я согласен(на) на хранение и обработку личных данных');
                ?>
            </div>
       </div>
       <div id="form3" style="display: none;">
            <div class="data-wrapper">
                <?php 
                    input_text('owner', 'login', 'Логин: ', LOGIN_MIN, LOGIN_MAX, 1);
                    input_text('owner', 'password', 'Пароль: ', PASSWORD_MIN, PASSWORD_MAX, 1, 'password');
                    input_text('owner', 'email', 'Электронная почта: ', EMAIL_MIN, EMAIL_MAX, 1);
                    input_text('owner', 'fullname', 'ФИО: ', FULLNAME_MIN, FULLNAME_MAX);
                    input_text('owner', 'position', 'Утвержденная должность: ', POSITION_MIN, POSITION_MAX);
                    input_text('owner', 'fraction', 'Доля акций: ', FRACTION_MIN, FRACTION_MAX);
                    input_checkbox('owner', 'policy', 'Я согласен(на) на хранение и обработку личных данных');
                ?>
            </div>
       </div>
       <div class="g-recaptcha" data-sitekey="6LfdRl8UAAAAAFNp0Aq7VO1Wp7LEm9yaBnXs6-QZ"></div>
        <div class="btn-wrapper">
            <input type="submit" value="Зарегистрироваться" class="submit-btn" name="submit">
        </div>
    </form>
</div>
<script>
    $.cookie('s', 0);
    function Changed() {
        document.getElementById('form' + $.cookie('s')).style.display = 'none';
        $.cookie('s', document.getElementById('select-form').selectedIndex);
        document.getElementById('form' + $.cookie('s')).style.display = 'block';
    }
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php') ?>