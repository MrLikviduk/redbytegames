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
                $result = $mysqli->query("SELECT * FROM projects_comments WHERE `user_id` LIKE '".get_id_by_username($_SESSION['login'])."' AND `project_id` LIKE '".$_GET['id']."'");
                if ($result->num_rows == 0) {
                    $mysqli->query("INSERT INTO projects_comments (id, project_id, `user_id`, `creation_date`, `creation_time`, `content`, `rating`) VALUES (NULL, $project_id, ".get_id_by_username($_SESSION['login']).", '$date', '$time', '$content', '$rating')") or die("ERROR");
                }
                else {
                    $mysqli->query("UPDATE projects_comments SET `content` = '$content', `rating` = $rating WHERE `user_id` LIKE '".get_id_by_username($_SESSION['login'])."' AND `project_id` LIKE '".$_GET['id']."'");
                }
                header("Location: ".$_SERVER['REQUEST_URI']);
            }
        }
    }
    $page_name = $result['name'];
    $is_project = TRUE;
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<img src="/projects_img/<?=$result['box_art_name']?>" alt="<?=$result['name']?>" class="box-art">
<div class="tech-params">
    <?php
        show_tech_param('Название: ', $result['name']);
        $lst = unserialize(base64_decode($result['tech_params']));
        if (!($lst == NULL))
            foreach ($lst as $key => $value) {
                show_tech_param($key, $value);
            }
    ?>
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
    echo '<h1>Отзывы</h1>';
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
                <input type="submit" name="comment_submit" value="Добавить комментарий" class="submit">
                <input type="hidden" name="project_id" value="'.$result['id'].'">
            </form>
        ';
    }
    $result = $mysqli->query("SELECT * FROM projects_comments WHERE `project_id` LIKE '".$_GET['id']."' ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) {
        show_comment(get_username_by_id($row['user_id']), $row['creation_date'], $row['creation_time'],  $row['content'], $row['rating'], $row['id']);
    }
    
?>

<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>