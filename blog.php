<?php
    $host = 'srv-pleskdb16.ps.kz:3306';
    $login = 'redby_proger';
    $password = 'imgnida1234';
    $database = 'redbyteg_users';
    $mysqli = new mysqli($host, $login, $password, $database) or die("Error");
    $mysqli->close();
    //----------------------------------------------------------
    $page_name = 'Блог';
    //include('header.php'); 
?>
<article>
    <div class="date-and-tags">
        <span class="date">26.05.2018</span>
        <div class="tags">
            <div class="tag">Skater</div>
            <div class="tag">Какой-то тег</div>
        </div>
    </div>
    <h1>Нововведения GitHub</h1>
    <div class="line-after-header"></div>
    <br>
    <p>
        О спикере: Александр Хаёров (@allexx) руководит отделом разработки в компании Ingram Micro Cloud. Ребята в команде Александра считают себя не просто отличными инженерами, а называют себя великой командой voxel джедаями, мастерами оптимизации, гуру 3D и повелителями больших данных! [примечание: по аналогии с названиями должностей в LinkedIn и Medium
        Эта классная команда, готовясь к выступлению на Highload++ 2017, решила развлечь аудиторию и сделать что-то новое и интересное для стенда. Поэтому они запилили игру, о создании которой и пойдет дальше речь.
    </p>
</article>
<?php include('footer.php') ?>