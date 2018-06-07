<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/styles/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="/js_scripts/jquery.cookie.js"></script>
    <?php
        if ($page_name == 'О нас')
            echo '<link rel="stylesheet" href="/styles/style-for-about-us.css">';
        else if ($page_name == 'Блог')
            echo '<link rel="stylesheet" href="/styles/style-for-blog.css">';
        else if ($page_name == 'Редактор блога' || $page_name == 'Выбор картинки')
            echo '<link rel="stylesheet" href="/styles/style-for-blog-editor.css">';
    ?>
    <title>Red Byte Games - больше, чем игры</title>
</head>

<?php
    if ($page_name == 'Главная' || $page_name == 'Регистрация')
        echo '<body style="background: hsl(0, 100%, 30%);">';
    else
        echo '<body>';
?>
    <header>
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
            <a href="/index.php" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                <div class="nav-element">Главная</div>
            </a>
            <a href="/about-us.php" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                <div class="nav-element">О нас</div>
            </a>
            <a href="/blogs.php" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                <div class="nav-element">Блог</div>
            </a>
            <a href="#contacts" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                <div class="nav-element">Проекты</div>
            </a>
            <a href="#contacts" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                <div class="nav-element">Вакансии</div>
            </a>
            <a href="#contacts" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
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
                <a href="/index.php">
                    <div class="nav-element">Главная</div>
                </a>
                <a href="/about-us.php">
                    <div class="nav-element">О нас</div>
                </a>
                <a href="/blogs.php">
                    <div class="nav-element">Блог</div>
                </a>
                <a href="">
                    <div class="nav-element">Проекты</div>
                </a>
                <a href="">
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