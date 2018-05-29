<?php
    if (!isset($_SESSION['logged_in']))
        $_SESSION['logged_in'] = FALSE;
    if (isset($_POST['lgn']) && isset($_POST['passwd'])) {
        include('connection-info.php');
        $result = $mysqli->query("SELECT * FROM users");
        $v = FALSE;
        while ($row = $result->fetch_assoc()) {
            if ($row['username'] == $_POST['lgn'] && $row['passwd'] == $_POST['passwd'])
                $v = TRUE;
        }
        if ($v == TRUE) // Соответствуют ли введенные данные одному из аккаунтов
            $_SESSION['logged_in'] = TRUE;
    }
    function show_login_form() {
        echo '
        <div class="to-authorizate">
            Для редактирования необходимо <div class="login-button" id="login_btn">авторизироваться</div>
            <form action="" method="POST" class="login-form" id="lgn_form">
                <input type="text" name="lgn" placeholder="Введите логин">
                <input type="password" name="passwd" placeholder="Введите пароль">
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