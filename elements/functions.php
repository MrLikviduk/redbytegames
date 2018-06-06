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
        $result = $mysqli->query("SELECT * FROM roles WHERE `role` LIKE '$role'");
        $row = $result->fetch_assoc();
        $mysqli->close();
        if ($row[$action] == 0)
            return FALSE;
        else
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
    }

    function user_is_set($username, $password) { // Проверяет, соответствуют ли логин и пароль одному из пользователей в базе данных
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT * FROM users WHERE `username` LIKE '$username' AND `passwd` LIKE '$password'");
        $mysqli->close();
        return (($result->num_rows) > 0 ? TRUE : FALSE);
    }

    function get_role($username) { // Возвращает роль пользователя
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT roles.role FROM users LEFT JOIN roles ON roles.id = users.role_id WHERE users.username LIKE '$username' ");
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

    function add_email_key($username, $key) { // Добавляет ключ для подтверждения почты
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT id FROM users WHERE username LIKE '$username'") or die("ERROR");
        $row = $result->fetch_assoc();
        $id = $row['id'];
        $result = $mysqli->query("INSERT INTO email_keys (id, `user_id`, `key`) VALUES (NULL, $id, '$key')") or die("ERROR2");
        $mysqli->close();
    }

    function activate_user($key) {
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT * FROM email_keys WHERE `key` LIKE '$key'") or die("ERROR 1");
        if ($result->num_rows == 0) {
            $mysqli->close();
            return FALSE;
        }
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];
        $result = $mysqli->query("UPDATE users SET role_id = 2 WHERE id LIKE $user_id") or die("ERROR 2");
        $result = $mysqli->query("DELETE FROM email_keys WHERE `key` LIKE '$key'") or die("ERROR 3");
        $mysqli->close();
    }
?>