<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/styles/style.css?v<?=time()?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="/js_scripts/jquery.cookie.js"></script>
    <?php
        if ($page_name == 'О нас')
            echo '<link rel="stylesheet" href="/styles/style-for-about-us.css?v'.time().'">';
        else if ($page_name == 'Блог')
            echo '<link rel="stylesheet" href="/styles/style-for-blog.css?v'.time().'">';
        else if ($page_name == 'Редактор блога' || $page_name == 'Выбор картинки' || $page_name == 'Редактор вакансий')
            echo '<link rel="stylesheet" href="/styles/style-for-editor.css?v'.time().'">';
        else if ($page_name == 'Проекты')
            echo '<link rel="stylesheet" href="/styles/style-for-projects.css?v'.time().'">';
        else if (isset($is_project) && $is_project === TRUE)
            echo '<link rel="stylesheet" href="/styles/style-for-project.css?v'.time().'">';
        else if ($page_name == 'Вакансии')
            echo '<link rel="stylesheet" href="/styles/style-for-vacancy.css?v'.time().'">';
        else if ($page_name == 'Вакансия')
            echo '<link rel="stylesheet" href="/styles/style-for-vacancy-element.css?v'.time().'">';
    ?>
    <title>Red Byte Games - больше, чем игры</title>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<?php
    if ($page_name == 'Главная' || $page_name == 'Регистрация')
        echo '<body style="background: hsl(0, 100%, 30%);">';
    else
        echo '<body>';
?>
    <header>
        <script>
            $(document).ready(function() {
                $(".skitter-large").skitter();
            });
        </script>
        <nav>
            <div class="menu-button" id="btn">
                <div class="burger-element el1" id="el1"></div>
                <div class="burger-element el2" id="el2"></div>
                <div class="burger-element el3" id="el3"></div>
            </div>
            <div class="nav-label"><?php echo $page_name ?></div>
        </nav>
        <div class="nav-menu menu_is-open" id="menu">
            <div style="height: 20px;"></div>
            <a href="/index" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                <div class="nav-element">Главная</div>
            </a>
            <a href="/about-us" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                <div class="nav-element">О нас</div>
            </a>
            <a href="/blogs" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                <div class="nav-element">Блог</div>
            </a>
            <a href="/projects.php" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                <div class="nav-element">Проекты</div>
            </a>
            <a href="/vacancy.php" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                <div class="nav-element">Вакансии</div>
            </a>
            <a href="" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                <div class="nav-element">Тех. поддержка</div>
            </a>
            <div style="height: 10px;"></div>
            <div class="contact">
                <img class="contact-icon" src="/img/mail_icon.png">
                <div class="text">
                    info@redbytegames.ru
                </div>
            </div>
        </div>
        <div class="nav-menu-for-computers">
            <div class="contacts">
                <div class="contact">
                    <img class="contact-icon" src="/img/mail_icon.png">
                    <div class="text">
                        info@redbytegames.ru
                    </div>
                </div>
            </div>
            <div class="nav-elements">
                <a href="/index">
                    <div class="nav-element">Главная</div>
                </a>
                <a href="/about-us">
                    <div class="nav-element">О нас</div>
                </a>
                <a href="/blogs">
                    <div class="nav-element">Блог</div>
                </a>
                <a href="/projects.php">
                    <div class="nav-element">Проекты</div>
                </a>
                <a href="/vacancy.php">
                    <div class="nav-element">Вакансии</div>
                </a>
                <a href="">
                    <div class="nav-element">Тех. поддержка</div>
                </a>
            </div>
        </div>
    </header>
    <script>
        btn.addEventListener('click', function () {
            menu.classList.toggle('menu_is-open');
            el1.classList.toggle('el1-open');
            el2.classList.toggle('el2-open');
            el3.classList.toggle('el3-open');
        })
    </script>
    <main>