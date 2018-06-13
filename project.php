<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/projects-template.php');
    if (!isset($_GET['id']))
        return_404();
    $mysqli = connect_to_database();
    $result = get_by_id($_GET['id'], 'projects') or return_404();
    $page_name = $result['name'];
    $is_project = TRUE;
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<img src="/projects_img/<?=$result['box_art_name']?>" alt="<?=$result['name']?>" class="box-art">
<div class="tech-params">
    <?php
        show_tech_param('Название: ', $result['name']);
        show_tech_param('Дата релиза: ', $result['release_date']);
        show_tech_param('Рейтинг: ', $result['rating']);
        $lst = unserialize(base64_decode($result['tech_params']));
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
<?php 
    $counter = 0;
    $lst = unserialize(base64_decode($result['paragraphs']));
    foreach ($lst as $key => $value) {
        show_paragraph($counter, $key, $value);
        $counter++;
    }
?>

<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); ?>