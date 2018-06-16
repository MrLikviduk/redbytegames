<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/projects-template.php');
    session_start();
    if (!isset($_GET['id']))
        return_404();
    $mysqli = connect_to_database();
    $result = get_by_id($_GET['id'], 'projects') or return_404();
    if (can_do('add_comments')) {
        if (isset($_POST['comment_submit'])) {
            if ($_POST['rating'] >= 1 && $_POST['rating'] <= 5 && strlen($_POST['comment_content']) <= 1023) {
                $date = date('d.m.Y');
                $time = date('H:i');
                $content = $_POST['comment_content'];
                $rating = $_POST['rating'];
                $project_id = $_GET['id'];
                $result = $mysqli->query("SELECT * FROM projects_comments WHERE `user_id` = '".get_id_by_username($_SESSION['login'])."' AND `project_id` = '".((int)$_GET['id'])."'");
                if ($result->num_rows == 0) {
                    $date = $mysqli->real_escape_string($date);
                    $time = $mysqli->real_escape_string($time);
                    $content = $mysqli->real_escape_string($content);
                    $rating = (int)$rating;
                    $project_id = (int)$project_id;
                    $mysqli->query("INSERT INTO projects_comments (id, project_id, `user_id`, `creation_date`, `creation_time`, `content`, `rating`) VALUES (NULL, $project_id, ".get_id_by_username($_SESSION['login']).", '$date', '$time', '$content', '$rating')") or die("ERROR");
                }
                else {
                    $content = $mysqli->real_escape_string($content);
                    $rating = (int)$rating;
                    $mysqli->query("UPDATE projects_comments SET `content` = '$content', `rating` = $rating WHERE `user_id` = '".get_id_by_username($_SESSION['login'])."' AND `project_id` = '".((int)$_GET['id'])."'");
                }
                header("Location: ".$_SERVER['REQUEST_URI']);
            }
        }
    }
    $page_name = $result['name'];
    $is_project = TRUE;
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<div class="box-art-wrapper">
    <img src="/projects_img/<?=$result['box_art_name']?>" alt="<?=$result['name']?>" class="box-art">
</div>
<div class="right-menu">
    <h1 class="project-name">Skater</h1>
    <div class="average-rating">
        <?php
            $result2 = $mysqli->query("SELECT project_id, avg(rating) FROM `projects_comments` WHERE project_id LIKE '".((int)$_GET['id'])."' GROUP BY project_id");
            $row = $result2->fetch_assoc();
            $rating = $row['avg(rating)'];
            $rating = round($rating, 1);
            $rating_for_stars = intval($rating * 10);
            $kol = floor($rating_for_stars / 10);
            $ost = $rating_for_stars % 10;
            if ($ost >= 0 && $ost <= 2) $ost = 0;
            else if ($ost >= 3 && $ost <= 7) $ost = 5;
            else if ($ost >= 8 && $ost <= 9) {
                $ost = 0;
                $kol++;
            }

        ?>
        <div class="rating-stars">
            <?php
                for ($i = 1; $i <= 5; $i++) {
                    echo '<img src="/img/'.($i <= $kol ? 'starfull.png' : ($ost == 5 && $i == $kol + 1 ? 'starhalffull.png' : 'starempty.png')).'" class="rating-star" style="width: 22px; height: 22px;" id="average_star'.($i - 1).'">';
                }
            ?>
        </div>
        <?php
            // echo '('.number_format($rating, 1).')';
        ?>
    </div>
    <div class="tech-params">
        <?php
            $lst = unserialize(base64_decode($result['tech_params']));
            if (!($lst == NULL))
                foreach ($lst as $key => $value) {
                    show_tech_param($key, $value);
                }
        ?>
    </div>
</div>
<script>
    function show_paragraph (value) {
        var par = $('#par' + value);
        var arrow = $('#arrow' + value);
        if (par.css('display') == 'none') {
            par.css('display', 'block');
            arrow.css('transform', 'rotate(180deg)');
        }
        else {
            par.css('display', 'none');
            arrow.css('transform', '');
        }
    }
</script>
<p class="description"><strong>Skater</strong> - казалось бы что игрушка довольно простенькая и ее хватает на пару вечеров, 
но когда очередной раз собираешь команду и слышны голоса товарищей: "Давай опять рванем к той базе, на этот раз мы 
ее разнесем!" хочется снова окунутся в игру. Все это наполнено космической тематикой и напоминает старые приставочные 
игры, когда ты взяв джойстик сидел и залипал перед экраном телевизора, а сейчас твоя мечта сбылась и ты можешь играть с 
друзьями.</p>
<script>
    function resetStars(value) {
        for (var i = 0; i < 5; i++) {
            if (i < value) {
                $('#star' + i).attr('src', '/img/starfull.png');
            }
            else
                $('#star' + i).attr('src', '/img/starempty.png');
        }
    }

    function fillStar (star_id, fill) {
        var star = $('#star' + star_id);
        var value = document.getElementById('rating_input').value;
        if (fill == true) {
            resetStars(star_id + 1);
        }
        else {
            resetStars($('#rating_input').val());
        }
    }

    function chooseStar(star_id) {
        console.log("CHECK");
        document.getElementById('rating_input').value = star_id + 1;
        resetStars(star_id + 1);
    }
</script>
<?php 
    $counter = 0;
    $lst = unserialize(base64_decode($result['paragraphs']));
    if (!($lst == NULL))
        foreach ($lst as $key => $value) {
            show_paragraph($counter, $key, $value);
            $counter++;
        }
    echo '<h1 style="margin-bottom: 10px;">Отзывы</h1>';
?>
<div class="average-rating-stat">
    <div class="average-number">
        <?php echo number_format($rating, 1); ?>
    </div>
    <div class="rating-stars">
        <?php
            for ($i = 1; $i <= 5; $i++) {
                echo '<img src="/img/'.($i <= $kol ? 'starfull.png' : ($ost == 5 && $i == $kol + 1 ? 'starhalffull.png' : 'starempty.png')).'" class="rating-star" style="width: 22px; height: 22px; margin: 0 2px;" id="stat_average_star'.($i - 1).'">';
            }
        ?>
    </div>
    <div class="users-count">
        <img src="/img/users.png" style="display: inline-block; width: 16px; height: 16px;"> 
        <?php 
            $result3 = $mysqli->query("SELECT * FROM projects_comments WHERE `project_id` LIKE ".((int)$_GET['id']));
            echo number_format($result3->num_rows, 0, ".", " ");
        ?>
    </div>
</div>
<?php
    if (can_do('add_comments')) {
        echo '
            <form action="" method="POST" class="comment-editor">
                <label for="rating">Ваша оценка: </label>
                <input type="hidden" name="rating" value="0" id="rating_input">
                <div class="rating-stars">
                ';
                for ($i = 0; $i < 5; $i++) {
                    echo '<img src="/img/starempty.png" class="rating-star" id="star'.$i.'" onmouseover="fillStar('.$i.', true)" onmouseout="fillStar('.$i.', false)" onclick="chooseStar('.$i.')" style="cursor: pointer;">';
                }
                echo '
                </div>
                <label for="comment_content" class="label">Комментарий: </label>
                <textarea name="comment_content" maxlength="1023" class="content" rows="5" id="comment_content'.$result['id'].'"></textarea>
                <input type="submit" name="comment_submit" value="Добавить отзыв" class="submit">
                <input type="hidden" name="project_id" value="'.$result['id'].'">
            </form>
        ';
    }
    else {
        echo '<p>Чтобы оставить отзыв, вам необходимо <a href="/index">авторизоваться</a></p>';
    }
    $result = $mysqli->query("SELECT * FROM projects_comments WHERE `project_id` LIKE '".((int)$_GET['id'])."' ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) {
        show_comment(get_username_by_id($row['user_id']), $row['creation_date'], $row['creation_time'],  $row['content'], $row['rating'], $row['id']);
    }
    
?>

<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>