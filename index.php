<?php
    $page_name = 'Главная';
    // include('elements/connection-info.php');
    // $mysqli = new mysqli($host_name, $db_username, $db_password, $db_name) or die("Error to connect to db");
    // $mysqli->query("TRUNCATE TABLE blog") or die("Не получилось(");
    // $mysqli->close();
    include('header.php');
?>
    <section class="main-section">
        <div class="pictures">
            <div style="width: 100vw; height: 100vh; background: hsla(0, 20%, 20%, 0.8); position: relative">
                <div style="position: absolute; top: 30%;">
                    <h1 class="main-header">Red Byte Games</h1>
                    <hr style="border: none; background: white; width: 250px; height: 2px;">
                    <p class="main-paragraph">Мы создаем больше, чем игры</p>
                </div>
            </div>
        </div>
    </section>
<?php include('footer.php') ?>