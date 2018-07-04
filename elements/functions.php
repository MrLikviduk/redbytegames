<?php

    function can_upload($file){
        // если имя пустое, значит файл не выбран
        if($file['name'] == '')
            return 'Вы не выбрали файл.';
        
        /* если размер файла 0, значит его не пропустили настройки 
        сервера из-за того, что он слишком большой */
        if($file['size'] == 0)
            return 'Файл слишком большой.';
        
        // разбиваем имя файла по точке и получаем массив
        $getMime = explode('.', $file['name']);
        // нас интересует последний элемент массива - расширение
        $mime = strtolower(end($getMime));
        // объявим массив допустимых расширений
        $types = array('jpg', 'png', 'gif', 'bmp', 'jpeg');
        
        // если расширение не входит в список допустимых - return
        if(!in_array($mime, $types))
            return 'Недопустимый тип файла.';
        
        return true;
    }

    function connect_to_database() { // Подключение к базе данных
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        return $mysqli;
    }

    function make_upload($file, $id, $dir_name){	
        $name = $file['name'];
        $tmp = explode('.', $name);
        $name = $id.'.'.$tmp[count($tmp) - 1];
        copy($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/'.$dir_name.'/'.$name);
    }

    function can_do($action) { // (CHECKED) Может ли юзер делать то, что передано в качестве аргумента
        $mysqli = connect_to_database();
        if(!isset($_SESSION['login']) || !isset($_SESSION['password'])) {
            $role = 'guest';
        }
        else {
            $username = $mysqli->real_escape_string($_SESSION['login']);
            $password = $mysqli->real_escape_string($_SESSION['password']);
            $result = $mysqli->query("SELECT * FROM users WHERE username = '".$username."' AND passwd = '".$password."'");
            if ($result->num_rows == 0) {
                $role = 'guest';
            }
            else {
                $row = $result->fetch_assoc();
                $role_id = (int)$row['role_id'];
                $result2 = $mysqli->query("SELECT * FROM roles WHERE `id` = '".$role_id."'");
                $row2 = $result2->fetch_assoc();
                $role = $row2['role']; 
            }
        }
        if ($role != 'guest') {
            $result = $mysqli->query("SELECT activated FROM users WHERE `username` = '".$username."'");
            $row = $result->fetch_assoc();
            if ($row['activated'] == 0) {
                $mysqli->close();
                return FALSE;
            }
        }
        $result = $mysqli->query("SELECT * FROM roles WHERE `role` = '$role'");
        $row = $result->fetch_assoc();
        $mysqli->close();
        if ($row[$action] == 0)
            return FALSE;
        return TRUE;
    }

    function username_is_set($username) { // (CHECKED) Проверяет, есть ли пользователь в базе данных с таким логином
        $mysqli = connect_to_database();
        $username = $mysqli->real_escape_string($username);
        $result = $mysqli->query("SELECT * FROM users WHERE `username` = '$username'") or die("ASHIPKA");
        $mysqli->close();
        if (($result->num_rows) > 0)
            return TRUE;
        else
            return FALSE;
    }

    function email_is_set($email) { // (CHECKED) Проверяет, есть ли пользователь в базе данных с таким электронным адресом
        $mysqli = connect_to_database();
        $email = $mysqli->real_escape_string($email);
        $result = $mysqli->query("SELECT * FROM users WHERE `email` = '$email'") or die("ASHIPKA");
        $mysqli->close();
        if (($result->num_rows) > 0)
            return TRUE;
        else
            return FALSE;
    }

    function create_user($username, $email, $password, $role) { // (CHECKED) Заносит нового пользователя в базу данных
        $mysqli = connect_to_database();
        $role = $mysqli->real_escape_string($role);
        $result = $mysqli->query("SELECT * FROM roles WHERE `role` = '$role'") or die("ERROR 1");
        $row = $result->fetch_assoc();
        $role_id = (int)$row['id'];
        $username = $mysqli->real_escape_string($username);
        $email = $mysqli->real_escape_string($email);
        $password = $mysqli->real_escape_string($password);
        $result2 = $mysqli->query("INSERT INTO users (id, username, email, passwd, role_id) VALUES (NULL, '$username', '$email', '$password', $role_id)") or die(
			$username.'<br>'.$email.'<br>'.$password.'<br>'
		);
        $mysqli->close();
        return TRUE;
    }

    function user_is_set($username, $password) { // (CHECKED) Проверяет, соответствуют ли логин и пароль одному из пользователей в базе данных
        if (!isset($username) || !isset($password))
            return FALSE;
        $mysqli = connect_to_database();
        $username = $mysqli->real_escape_string($username);
        $password = $mysqli->real_escape_string($password);
        $result = $mysqli->query("SELECT * FROM users WHERE `username` = '$username' AND `passwd` = '$password'");
        $mysqli->close();
        return (($result->num_rows) == 1 ? TRUE : FALSE);
    }

    function get_role($username, $table = 'users') { // (CHECKED) Возвращает роль пользователя
        $mysqli = connect_to_database();
        $username = $mysqli->real_escape_string($username);
        $result = $mysqli->query("SELECT roles.role FROM $table LEFT JOIN roles ON roles.id = $table.role_id WHERE $table.username = '$username'") or die("ERROR000");
        $mysqli->close();
        $row = $result->fetch_assoc();
        return $row['role'];
    }

    function get_email($username) { // (CHECKED) Возвращает электронный адрес пользователя
        $mysqli = connect_to_database();
        $username = $mysqli->real_escape_string($username);
        $result = $mysqli->query("SELECT users.email FROM users WHERE username = '$username'");
        $mysqli->close();
        $row = $result->fetch_assoc();
        return $row['email'];
    }

    function add_email_key($username) { // (CHECKED) Добавляет ключ для подтверждения почты
        $mysqli = connect_to_database();
        $username = $mysqli->real_escape_string($username);
        $result = $mysqli->query("SELECT id FROM users WHERE username = '$username'") or die("ERROR");
        $row = $result->fetch_assoc();
        $id = (int)$row['id'];
        $key = md5(rand(-2147483647, 2147483647));
        $result = $mysqli->query("INSERT INTO email_keys (id, `user_id`, `key`) VALUES (NULL, $id, '$key')") or die("ERROR2");
        $mysqli->close();
    }

    function activate_user($key) { // (CHECKED) Активирует аккаунт пользователя по ключу
        $mysqli = connect_to_database();
        $key = $mysqli->real_escape_string($key);
        $result = $mysqli->query("SELECT * FROM email_keys WHERE `key` = '$key'") or die("ERROR 1");
        if ($result->num_rows == 0) {
            $mysqli->close();
            return FALSE;
        }
        $row = $result->fetch_assoc();
        $user_id = (int)$row['user_id'];
        $result = $mysqli->query("UPDATE users SET `activated` = 1 WHERE id = $user_id") or die("ERROR 2");
        $result = $mysqli->query("DELETE FROM email_keys WHERE `key` = '$key'") or die("ERROR 3");
        $mysqli->close();
    }

    function send_confirm_letter($email) { // (CHECKED) Отправляет письмо с кодом подтверждения
        $mysqli = connect_to_database();
        $email = $mysqli->real_escape_string($email);
        $result = $mysqli->query("SELECT * FROM users LEFT JOIN email_keys ON email_keys.user_id = users.id WHERE `email` = '$email'");
        if ($result === FALSE) {
            $mysqli->close();
            return FALSE;
        }
        $row = $result->fetch_assoc();
        $key = $mysqli->real_escape_string($row['key']);
        $mysqli->close();
        $result = mail($email, 'Подтверждение', 'Чтобы подтвердить ваш электронный адрес, перейдите по ссылке: https://redbytegames.ru/authorization.php?key='.$key, 'From: confirm@redbytegames.ru');
        if ($result === FALSE)
            return FALSE;
        return TRUE;
    }

    function get_field($username, $name, $table = 'users') { // (CHECKED) Возвращает значение поля, которое находит по логину
        $mysqli = connect_to_database();
        $username = $mysqli->real_escape_string($username);
        $result = $mysqli->query("SELECT * FROM $table WHERE username = '$username'");
        $mysqli->close();
        if ($result === FALSE)
            return FALSE;
        $row = $result->fetch_assoc();
        if (!isset($row[$name]))
            return FALSE;
        return $row[$name];
    }

    function data_is_set($table, $column, $data) { // (CHECKED)
        $mysqli = connect_to_database();
        $data = $mysqli->real_escape_string($data);
        $result = $mysqli->query("SELECT $column FROM $table WHERE $column = '$data'");
        $mysqli->close();
        if ($result === FALSE || $result->num_rows == 0)
            return FALSE;
        return TRUE;
    }

    function delete_data($table, $column, $data) { // (CHECKED)
        $mysqli = connect_to_database();
        $data = $mysqli->real_escape_string($data);
        $result = $mysqli->query("DELETE FROM $table WHERE $column = '$data'");
        $mysqli->close();
        if ($result === FALSE) {
            return FALSE;
        }
        else
            return TRUE;
    }

    function set_data($table, $unique_column, $unique_data, $column, $data) { // (CHECKED)
        $mysqli = connect_to_database();
        $unique_data = $mysqli->real_escape_string($unique_data);
        $data = $mysqli->real_escape_string($data);
        $result = $mysqli->query("UPDATE $table SET $column = '$data' WHERE $unique_column = '$unique_data'");
        $mysqli->close();
        if ($result === FALSE)
            return FALSE;
        return TRUE;
    }

    function get_data($table, $unique_column, $unique_data) { // (CHECKED)
        $mysqli = connect_to_database();
        $unique_data = $mysqli->real_escape_string($unique_data);
        $result = $mysqli->query("SELECT * FROM `$table` WHERE `$unique_column` = '$unique_data'");
        $mysqli->close();
        if ($result === FALSE || $result->num_rows == 0) return FALSE;
        $row = $result->fetch_assoc();
        return $row;
    }

    function add_comment($blog_id, $user_id, $date, $time, $content) { // (CHECKED)
        $mysqli = connect_to_database();
        $date = $mysqli->real_escape_string($date);
        $time = $mysqli->real_escape_string($time);
        $blog_id = (int)$blog_id;
        $user_id = (int)$user_id;
        $content = $mysqli->real_escape_string($content);
        $result = $mysqli->query("INSERT INTO comments (id, blog_id, `user_id`, creation_date, creation_time, content) VALUES (NULL, $blog_id, $user_id, '$date', '$time', '$content')");
        $mysqli->close();
        if ($result === FALSE)
            return FALSE;
        return TRUE;
    }

    function get_id_by_username($username, $table = 'users') { // (CHECKED) Возвращает ид юзера
        $mysqli = connect_to_database();
        $username = $mysqli->real_escape_string($username);
        $result = $mysqli->query("SELECT id FROM $table WHERE username = '$username'");
        $mysqli->close();
        if ($result === FALSE || $result->num_rows == 0)
            return FALSE;
        $row = $result->fetch_assoc();
        return $row['id'];
    }

    function get_username_by_id($id, $table = 'users') { // (CHECKED)
        $mysqli = connect_to_database();
        $id = (int)$id;
        $result = $mysqli->query("SELECT username FROM $table WHERE id = '$id'");
        $mysqli->close();
        if ($result === FALSE || $result->num_rows == 0)
            return FALSE;
        $row = $result->fetch_assoc();
        return $row['username'];
    }

    function get_comment_author($id) {
        $mysqli = connect_to_database();
        $id = int($id);
        $result = $mysqli->query("SELECT users.username FROM comments INNER JOIN users ON users.id = comments.user_id WHERE comments.id = $id");
        $mysqli->close();
        if ($result === FALSE)
            return FALSE;
        $row = $result->fetch_assoc();
        return $row['username'];
    }

    function get_by_id($id, $table) { // (CHECKED)
        $mysqli = connect_to_database();
        $id = (int)$id;
        $result = $mysqli->query("SELECT * FROM `$table` WHERE id = $id");
        if ($result === FALSE || $result->num_rows == 0)
            return FALSE;
        $row = $result->fetch_assoc();
        return $row;
    }

    function is_own_comment($id, $user_id = NULL) { // (CHECKED) Проверяет, принадлежит ли коммент юзеру
        if (!isset($user_id))
            $user_id = get_id_by_username($_SESSION['login']);
        return user_is_set($_SESSION['login'], $_SESSION['password']) && $user_id == get_by_id($id, 'comments')['user_id'];
    }

    function seconds_to_time($s) { // Распределяет секунды по большим величинам
        if ($s < 60)
            return 'менее 1 мин';
        $ans = '';
        if ($s >= 86400) {
            $ans = $ans.(($s - $s % 86400) / 86400).'дн ';
            $s = $s % 86400;
        }
        if ($s >= 3600) {
            $ans = $ans.(($s - $s % 3600) / 3600).'ч ';
            $s = $s % 3600;
        }
        if ($s >= 60) {
            $ans = $ans.(($s - $s % 60) / 60).'мин ';
            $s = $s % 60;
        }
        return $ans;
    }

    function return_404() { // Возвращает ошибку 404
        $error_404_text = "
            <h1>Not Found</h1>
                The requested document was not found on this server.
            <p>
            </p><hr>
            <address>
                Web Server at redbytegames.ru
            </address>
            ";  
        http_response_code(404);
        echo $error_404_text;
        exit;
    }

    function check_captcha($grr) {
        if (isset($grr) && $grr) {
            $secret = '6LfdRl8UAAAAAGCnYDwaedMso7bqgpRa-AzzSPkS';
            $response = $grr;
            $rsp = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response");
            $arr = json_decode($rsp, TRUE);
            return $arr['success'];
        }
        else
            return FALSE;
    }

    function is_legal($s, $min, $max) {
        return strlen($s) >= $min && strlen($s) <= $max;
    }

    function translate($s) {
        if (!isset($_SESSION['lang']) || $_SESSION['lang'] == 'ru')
            return $s;
        switch($s) {
            case 'Главная':
                return 'Home';
            case 'О нас':
                return 'About us';
            case 'Блог':
                return 'Blog';
            case 'Редактор блога':
                return 'Blog editor';
            case 'Редактор вакансий':
                return 'Vacancy editor';
            case 'Проекты':
                return 'Projects';
            case 'Проект':
                return 'Project';
            case 'Вакансия':
            case 'Вакансии':
                return 'Vacancy';
            case 'Техподдержка':
                return 'Support';
            case 'Выбор языка':
                return 'Choose language';
            case 'Регистрация':
                return 'Registration';
            case 'Авторизация':
                return 'Authorization';
            case 'Войти':
                return 'Log in';
            case 'Выйти':
                return 'Log out';
            case 'Следите за нами':
                return 'Follow us';
            case 'Мы - новоиспеченная компания специализирующаяся на разработке и переиздании мобильных игр. Наша цель - приносить людям самый драйвовый, уникальный и запоминающийся игровой опыт.':
                return 'We are a new company specializing in the development and reissue of mobile games. Our goal is to bring people the most driving, unique and memorable game experience.';
        }
    }
?>