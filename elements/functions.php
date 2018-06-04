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

    function can_do($action) { // Может ли юзер делать то, что передано в качестве аргумента
        include($_SERVER['DOCUMENT_ROOT'].'/elements/connection-info.php');
        $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name);
        $mysqli->close();
    } 
    
    function make_upload($file, $id){	
        // формируем уникальное имя картинки: случайное число и name
        $name = $file['name'];
        $tmp = explode('.', $name);
        $name = $id.'.'.$tmp[count($tmp) - 1];
        copy($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'].'/blog_img/'.$name);
    }
?>