<?php
    session_start();
    if (!isset($_SESSION['logged_in']))
        $_SESSION['logged_in'] = FALSE;
    if (isset($_POST['lgn']) && isset($_POST['pswd'])) {
        $host_name = 'srv-pleskdb16.ps.kz:3306';
        $db_name = 'redbyteg_users';
        $db_username = 'redby_proger';
        $db_password = 'imgnida1234';
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT * FROM users") or die("Error to open database");
        $v = FALSE;
        while (($row = $result->fetch_assoc()) && !$v) {
            if ($row['username'] == $_POST['lgn'] && $row['pswd'] == $_POST['passwd'])
                $v = TRUE;
        }
        if ($v == TRUE)
            $_SESSION['logged_in'] == TRUE;
        $mysqli->close();
    }
    if (isset($_SESSION['logged_in']))
        if ($_SESSION['logged_in'] == FALSE)
            show_login_form();
    if ($_SESSION['logged_in'])
        echo '1<br>';
    else
        echo '2<br>';
    function show_login_form() {
        echo '
        <div class="to-authorizate">
            Для редактирования необходимо <div class="login-button" id="login_btn">авторизироваться</div>
            <form action="" method="POST" class="login-form" id="lgn_form">
                <input type="text" name="lgn" placeholder="Введите логин">
                <input type="password" name="pswd" placeholder="Введите пароль">
                <input type="submit" name="sbmt" value="Войти">
            </form>
        </div>
        <script>
            login_btn.addEventListener("click", function () {
                lgn_form.classList.toggle("login-form-is-open");
            })
        </script>
        ';
    }
?>