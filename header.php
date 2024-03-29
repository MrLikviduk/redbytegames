<?php
    if (isset($_POST['exit_from_account'])) {
        unset($_SESSION['login']);
        unset($_SESSION['password']);
        header("Location: ".$_SERVER['REQUEST_URI']);
    }
    $explode_array = explode('/', $_SERVER['SCRIPT_FILENAME']);
    if (!isset($_SESSION['lang']) && end($explode_array) !== 'choose-language.php' && end($explode_array) !== 'choose-language') {
        header('Location: /choose-language');
    }
    if (isset($_POST['change_lang'])) {
        if ($_SESSION['lang'] == 'ru')
            $_SESSION['lang'] = 'en';
        else
            $_SESSION['lang'] = 'ru';
        header("Location: ".$_SERVER['REQUEST_URI']);
    }
    $valid_lang = ['ru', 'en'];
?>
<!DOCTYPE html>
<html lang="<?=(isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en')?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="description" content="We are a new company specializing in the development and reissue of mobile games. Our goal is to bring people the most driving, unique and memorable game experience.">
    <link rel="stylesheet" href="/styles/style.css?v<?=time()?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="/js_scripts/jquery.cookie.js"></script>
    <script src="galleria/galleria-1.5.7.min.js"></script>
    <script src="/ResponsiveSlides/responsiveslides.min.js"></script>
    <link rel="stylesheet" href="/ResponsiveSlides/responsiveslides.css">
    <?php
        if (!function_exists('user_is_set'))
            require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
        switch ($page_name) {
            case 'О нас':
                $special_style = 'style-for-about-us.css';
                break;
            case 'Блог':
            case 'Блог для прессы':
                $special_style = 'style-for-blog.css';
                break;
            case 'Редактор блога':
            case 'Редактор блога для прессы':
            case 'Редактор вакансий':
            case 'Редактор китов для прессы':
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
            case 'Техподдержка':
                $special_style = 'style-for-support.css';
                break;
            case 'Выбор языка':
                $special_style = 'style-for-choose-language.css';
                break;
            case 'Для прессы':
            case 'Киты для прессы':
            case 'Вопрос-Ответ для прессы':
                $special_style = 'style-for-for-media.css';
                break;
        }
    ?>
    <link rel="stylesheet" href="/styles/<?=$special_style?>?v<?=time()?>">
    <title>Red Byte Games | <?=translate($page_name)?></title>
    <script src='https://www.google.com/recaptcha/api.js?hl=<?=(isset($_SESSION['lang']) && in_array($_SESSION['lang'], $valid_lang) ? $_SESSION['lang'] : 'en')?>'></script>
</head>

<?php
    if ($page_name == 'Авторизация' || $page_name == 'Регистрация' || $page_name == 'Выбор языка')
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
            <div class="nav-label"><?php echo translate($page_name) ?></div>
        </nav>
        <div class="nav-menu menu_is-open" id="menu">
            <div style="height: 20px;"></div>
            <a href="/index" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                <div class="nav-element"><?=translate('Главная')?></div>
            </a>
            <a href="/about-us" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                <div class="nav-element"><?=translate('О нас')?></div>
            </a>
            <a href="/blogs" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                <div class="nav-element"><?=translate('Блог')?></div>
            </a>
            <a href="/projects" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                <div class="nav-element"><?=translate('Проекты')?></div>
            </a>
            <?php if (can_do('see_info_for_media')) { ?>
                <a href="/for_media/media-choose" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                    <div class="nav-element"><?=translate('Для прессы')?></div>
                </a>
            <?php } ?>
            <a href="/vacancy" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                <div class="nav-element"><?=translate('Вакансии')?></div>
            </a>
            <a href="/support" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                <div class="nav-element"><?=translate('Техподдержка')?></div>
            </a>
            <div style="height: 10px;"></div>
            <div class="contact">
                <img class="contact-icon" src="/img/mail_icon.png">
                <div class="text">
                    info@redbytegames.ru
                </div>
            </div>
            <?php if (!isset($_SESSION['login']) || !isset($_SESSION['password']) || !user_is_set($_SESSION['login'], $_SESSION['password'])) { ?>
                <a href="/authorization" onclick="menu.classList.toggle('menu_is-open'); el1.classList.toggle('el1-open'); el2.classList.toggle('el2-open'); el3.classList.toggle('el3-open');">
                    <div class="nav-element"><b><?=translate('Войти')?></b></div>
                </a>
            <?php } else { ?>
                <form action="" method="POST" style="display: inline-block;"><button class="nav-element" name="exit_from_account"><b><?=translate('Выйти')?></b></button></form>
            <?php } ?>
            <form action="" method="POST" style="display: inline-block;"><button class="nav-element" name="change_lang" style="display: background: none; border: none;" value="1"><b><?=($_SESSION['lang'] == 'ru' ? 'ENG' : 'RUS')?></b></button></form>
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
                    <div class="nav-element"><?=translate('Главная')?></div>
                </a>
                <a href="/about-us">
                    <div class="nav-element"><?=translate('О нас')?></div>
                </a>
                <a href="/blogs">
                    <div class="nav-element"><?=translate('Блог')?></div>
                </a>
                <a href="/projects">
                    <div class="nav-element"><?=translate('Проекты')?></div>
                </a>
                <?php if (can_do('see_info_for_media')) { ?>
                    <a href="/for_media/media-choose">
                        <div class="nav-element"><?=translate('Для прессы')?></div>
                    </a>
                <?php } ?>
                <a href="/vacancy">
                    <div class="nav-element"><?=translate('Вакансии')?></div>
                </a>
                <a href="/support">
                    <div class="nav-element"><?=translate('Техподдержка')?></div>
                </a>
                <?php if (!isset($_SESSION['login']) || !isset($_SESSION['password']) || !user_is_set($_SESSION['login'], $_SESSION['password'])) { ?>
                    <a href="/authorization">
                        <div class="nav-element"><b><?=translate('Войти')?></b></div>
                    </a>
                <?php } else { ?>
                    <form action="" method="POST" style="display: inline-block;"><button class="nav-element" name="exit_from_account"><b><?=translate('Выйти')?></b></button></form>
                <?php } ?>
                <form action="" method="POST" style="display: inline-block;"><button class="nav-element" name="change_lang" style="display: background: none; border: none;" value="1"><b><?=($_SESSION['lang'] == 'ru' ? 'ENG' : 'RUS')?></b></button></form>
            </div>
        </div>
    </header>
    <script>
        btn.addEventListener('click', function () {
            menu.classList.toggle('menu_is-open');
            el1.classList.toggle('el1-open');
            el2.classList.toggle('el2-open');
            el3.classList.toggle('el3-open');
        });
    </script>
    <main>