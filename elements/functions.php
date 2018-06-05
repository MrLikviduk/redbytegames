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
        
        if(!isset($_SESSION['username']) || !isset($_SESSION['password']))
            $role = 'guest';
        else {
            $result = $mysqli->query("SELECT * FROM users WHERE username LIKE '".$_SESSION['username']."' AND passwd LIKE '".$_SESSION['password']."'");
            if ($result->num_rows < 1)
                $role = 'guest';
            else {
                $row = $result->fetch_row();
                $result2 = $mysqli->query("SELECT * FROM roles WHERE `id` LIKE '".$row['role_id']."'");
                $row2 = $result2->fetch_row();
                $role = $row2['role']; 
            }
        }
        $result = $mysqli->query("SELECT * FROM roles WHERE `role` LIKE $role");
        $row = $result->fetch_row();
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
        $result = $mysqli->query("SELECT * FROM users WHERE `email` LIKE 'email'") or die("ASHIPKA");
        $mysqli->close();
        if (($result->num_rows) > 0)
            return TRUE;
        else
            return FALSE;
    }

    function create_user($username, $email, $password, $role) {
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $result = $mysqli->query("SELECT * FROM roles WHERE `role` LIKE '$role'") or die("ERROR 1");
        $row = $result->fetch_row();
        $role_id = $row['id'];
        $result = $mysqli->query("INSERT INTO users (id, username, email, passwd, role_id) VALUES (NULL, '$username', '$email', '$password', $role_id)") or die($role_id);
        $mysqli->close();
    }
?>