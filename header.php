<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/styles/style.css?v<?=time()?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="/js_scripts/jquery.cookie.js"></script>
    <script src="galleria/galleria-1.5.7.min.js"></script>
    <?php
        switch ($page_name) {
            case 'О нас':
                $special_style = 'style-for-about-us.css';
                break;
            case 'Блог':
                $special_style = 'style-for-blog.css';
                break;
            case 'Редактор блога':
            case 'Редактор вакансий':
                $special_style = 'style-for-editor.css';
                break;
            case 'Проекты':
                $special_style = 'style-for-projects.css';
                break;
            case 'Проект':
                $special_style = 'style-for-project.css';
                break;
            case 'Вакансии':
                $special_style = 'style-for-vacancy.css';
                break;
            case 'Вакансия':
                $special_style = 'style-for-vacancy-element.css';
                break;
            case 'Тех. поддержка':
                $special_style = 'style-for-support.css';
                break;
        }
    ?>
    <link rel="stylesheet" href="/styles/<?=$special_style?>?v<?=time()?>">
    <title>Red Byte Games - больше, чем игры</title>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<?php
    if ($page_name == 'Авторизация' || $page_name == 'Регистрация')
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
            <a href="/support.php" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                <div class="nav-element">Тех. поддержка</div>
            </a>
            <div style="height: 10px;"></div>
            <div class="contact">
                <img class="contact-icon" src="/img/mail_icon.png">
                <div class="text">
                    info@redbytegames.ru
                </div>
            </div>
            <a href="/authorization.php" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                <div class="nav-element"><b>Войти</b></div>
            </a>
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
                <a href="/support.php">
                    <div class="nav-element">Тех. поддержка</div>
                </a>
                <a href="/authorization.php">
                    <div class="nav-element"><b>Войти</b></div>
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