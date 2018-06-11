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

    function make_upload($file, $id){	
        // формируем уникальное имя картинки: случайное число и name
        $name = $file['name'];
        $tmp = explode('.', $name);
        $name = $id.'.'.$tmp[count($tmp) - 1];
        copy($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/blog_img/'.$name);
    }

    function can_do($action) { // Может ли юзер делать то, что передано в качестве аргумента
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        if(!isset($_SESSION['login']) || !isset($_SESSION['password'])) {
            $role = 'guest';
        }
        else {
            $result = $mysqli->query("SELECT * FROM users WHERE username LIKE '".$_SESSION['login']."' AND passwd LIKE '".$_SESSION['password']."'");
            if ($result->num_rows < 1) {
                $role = 'guest';
            }
            else {
                $row = $result->fetch_assoc();
                $result2 = $mysqli->query("SELECT * FROM roles WHERE `id` LIKE '".$row['role_id']."'");
                $row2 = $result2->fetch_assoc();
                $role = $row2['role']; 
            }
        }
        if ($role != 'guest') {
            $result = $mysqli->query("SELECT activated FROM users WHERE `username` LIKE'".$_SESSION['login']."'");
            $row = $result->fetch_assoc();
            if ($row['activated'] == 0) {
                $mysqli->close();
                return FALSE;
            }
        }
        $result = $mysqli->query("SELECT * FROM roles WHERE `role` LIKE '$role'");
        $row = $result->fetch_assoc();
        $mysqli->close();
        if ($row[$action] == 0)
            return FALSE;
        return TRUE;
    }

    function username_is_set($username) { // Проверяет, есть ли пользователь в базе данных с таким логином
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT * FROM users WHERE `username` LIKE '$username'") or die("ASHIPKA");
        $mysqli->close();
        if (($result->num_rows) > 0)
            return TRUE;
        else
            return FALSE;
    }

    function email_is_set($email) { // Проверяет, есть ли пользователь в базе данных с таким электронным адресом
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT * FROM users WHERE `email` LIKE '$email'") or die("ASHIPKA");
        $mysqli->close();
        if (($result->num_rows) > 0)
            return TRUE;
        else
            return FALSE;
    }

    function create_user($username, $email, $password, $role) { // Заносит нового пользователя в базу данных
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT * FROM roles WHERE `role` LIKE '$role'") or die("ERROR 1");
        $row = $result->fetch_assoc();
		$role_id = $row['id'];
        $result2 = $mysqli->query("INSERT INTO users (id, username, email, passwd, role_id) VALUES (NULL, '$username', '$email', '$password', $role_id)") or die(
			$username.'<br>'.$email.'<br>'.$password.'<br>'
		);
        $mysqli->close();
        return TRUE;
    }

    function user_is_set($username, $password) { // Проверяет, соответствуют ли логин и пароль одному из пользователей в базе данных
        if (!isset($username) || !isset($password))
            return FALSE;
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT * FROM users WHERE `username` LIKE '$username' AND `passwd` LIKE '$password'");
        $mysqli->close();
        return (($result->num_rows) > 0 ? TRUE : FALSE);
    }

    function get_role($username, $table) { // Возвращает роль пользователя
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT roles.role FROM $table LEFT JOIN roles ON roles.id = $table.role_id WHERE $table.username LIKE '$username' ");
        $mysqli->close();
        $row = $result->fetch_assoc();
        return $row['role'];
    }

    function get_email($username) { // Возвращает электронный адрес пользователя
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT users.email FROM users WHERE username LIKE '$username'");
        $mysqli->close();
        $row = $result->fetch_assoc();
        return $row['email'];
    }

    function add_email_key($username) { // Добавляет ключ для подтверждения почты
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT id FROM users WHERE username LIKE '$username'") or die("ERROR");
        $row = $result->fetch_assoc();
        $id = $row['id'];
        $key = md5(rand(-2147483647, 2147483647));
        $result = $mysqli->query("INSERT INTO email_keys (id, `user_id`, `key`) VALUES (NULL, $id, '$key')") or die("ERROR2");
        $mysqli->close();
    }

    function activate_user($key) { // Активирует аккаунт пользователя по ключу
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT * FROM email_keys WHERE `key` LIKE '$key'") or die("ERROR 1");
        if ($result->num_rows == 0) {
            $mysqli->close();
            return FALSE;
        }
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];
        $result = $mysqli->query("UPDATE users SET `activated` = 1 WHERE id LIKE $user_id") or die("ERROR 2");
        $result = $mysqli->query("DELETE FROM email_keys WHERE `key` LIKE '$key'") or die("ERROR 3");
        $mysqli->close();
    }

    function send_confirm_letter($email) { // Отправляет письмо с кодом подтверждения
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT * FROM users LEFT JOIN email_keys ON email_keys.user_id = users.id WHERE `email` LIKE '$email' ");
        if ($result === FALSE) {
            $mysqli->close();
            return FALSE;
        }
        $mysqli->close();
        $row = $result->fetch_assoc();
        $key = $row['key'];
        $result = mail($email, 'Подтверждение', 'Чтобы подтвердить ваш электронный адрес, перейдите по ссылке: https://redbytegames.ru/index.php?key='.$key, 'From: confirm@redbytegames.ru');
        if ($result === FALSE)
            return FALSE;
        return TRUE;
    }

    function get_field($username, $name, $table = 'users') { // Возвращает значение поля, которое находит по логину
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT * FROM $table WHERE username LIKE '$username'");
        $mysqli->close();
        if ($result === FALSE)
            return FALSE;
        $row = $result->fetch_assoc();
        if (!isset($row[$name]))
            return FALSE;
        return $row[$name];
    }

    function data_is_set($table, $column, $data) {
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT $column FROM $table WHERE $column LIKE '$data'");
        $mysqli->close();
        if ($result === FALSE || $result->num_rows == 0)
            return FALSE;
        return TRUE;
    }

    function delete_data($table, $column, $data) {
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("DELETE FROM $table WHERE $column LIKE '$data'");
        $mysqli->close();
        if ($result === FALSE) {
            return FALSE;
        }
        else
            return TRUE;
    }

    function set_data($table, $unique_column, $unique_data, $column, $data) {
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("UPDATE $table SET $column = '$data' WHERE $unique_column LIKE '$unique_data'");
        $mysqli->close();
        if ($result === FALSE)
            return FALSE;
        return TRUE;
    }

    function add_comment($blog_id, $user_id, $date, $time, $content) {
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("INSERT INTO comments (id, blog_id, `user_id`, creation_date, creation_time, content) VALUES (NULL, $blog_id, $user_id, '$date', '$time', '$content')");
        $mysqli->close();
        if ($result === FALSE)
            return FALSE;
        return TRUE;
    }

    function get_id_by_username($username, $table = 'users') { // Возвращает ид юзера
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT id FROM $table WHERE username LIKE '$username'");
        $mysqli->close();
        if ($result === FALSE || $result->num_rows == 0)
            return FALSE;
        $row = $result->fetch_assoc();
        return $row['id'];
    }

    function get_username_by_id($id, $table = 'users') {
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT username FROM $table WHERE id LIKE '$id'");
        $mysqli->close();
        if ($result === FALSE || $result->num_rows == 0)
            return FALSE;
        $row = $result->fetch_assoc();
        return $row['username'];
    }

    function get_comment_author($id) {
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT users.username FROM comments INNER JOIN users ON users.id = comments.user_id WHERE comments.id LIKE $id");
        $mysqli->close();
        if ($result === FALSE)
            return FALSE;
        $row = $result->fetch_assoc();
        return $row['username'];
    }

    function get_by_id($id, $table) {
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT * FROM `$table` WHERE id LIKE $id");
        if ($result === FALSE)
            return FALSE;
        $row = $result->fetch_assoc();
        return $row;
    }

    function is_own_comment($id, $user_id = NULL) { // Проверяет, принадлежит ли коммент юзеру
        if (!isset($user_id))
            $user_id = get_id_by_username($_SESSION['login']);
        return user_is_set($_SESSION['login'], $_SESSION['password']) && $user_id == get_by_id($id, 'comments')['user_id'];
    }

    function seconds_to_time($s) { // Распределяет секунды по большим величинам
        if ($s < 60)
            return 'менее 1 мин';
        $ans = '';
        if ($s >= 86400) {
            $ans = $ans.(($s - $s % 86400) / 86400).' дн';
            $s = $s % 86400;
        }
        if ($s >= 3600) {
            $ans = $ans.(($s - $s % 3600) / 3600).' ч';
        }
        if ($s >= 60) {
            $ans = $ans.(($s - $s % 60) / 60).' мин';
        }
        return $ans;
    }
?>