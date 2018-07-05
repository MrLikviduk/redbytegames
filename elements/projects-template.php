<?php
    function show_project($name, $pct_name, $id) {
        echo '
            <a href="/project.php?id='.$id.'">
                <div class="project" style="background-image: url(/projects_img/'.$pct_name.')">
                    <div class="pct-cover">
                        <h2>'.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').'</h2>
                    </div>';
        if (can_do('edit_projects')) { ?>
            <form action="" method="POST" style="display: inline-block;">
                <button name="delete_project" value="<?=$id?>" style="margin: 0 5px;" onclick="return confirm('<?=translate('Вы действительно хотите удалить проект?')?>')"><?=translate('Удалить')?></button>
            </form>
        <?php }
        echo '</div>
        </a>
    ';
    }
    
    function show_tech_param($key, $value) {
        require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
        ?>
            <div class="property-key"><?=htmlspecialchars($key, ENT_QUOTES, 'UTF-8')?></div>
            <div class="property-value"><?=htmlspecialchars($value, ENT_QUOTES, 'UTF-8')?></div>
            <?php if (can_do('edit_projects')) { ?>
            <form action="" method="POST" style="display: inline-block">
                <button name="delete_tech_param" value="<?=$key?>" style="margin: 0 5px;" onclick="return confirm('<?=translate('Вы действительно хотите удалить параметр?')?>')"><?=translate('Удалить')?></button>
            </form>
            <?php } ?>
            <br>
 <?php   }

    function show_paragraph($val, $name, $content) {
        require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
     ?>
            <h2 class="paragraph-name" onclick="show_paragraph(<?=$val?>)"><div class="show-paragraph" id="arrow<?=$val?>"></div><?=htmlspecialchars($name, ENT_QUOTES, 'UTF-8')?></h2>
            <?php if (can_do('edit_projects')) { ?>
                <form action="" method="post" style="display: inline-block;">
                    <button name="delete_p" value="<?=$name?>" style="margin: 0 5px;" onclick="return confirm('<?=translate('Вы действительно хотите удалить параграф?')?>')"><?=translate('Удалить')?></button>
                </form>
            <?php } ?>
            <hr>
            <p class="paragraph" id="par<?=$val?>" style="display: none;">
                <?=htmlspecialchars($content, ENT_QUOTES, 'UTF-8')?>
            </p>
    <?php }

    function show_comment($name, $date, $time, $content, $rating, $id) {
        echo '
            <div class="comment">
                <div class="top">
                    <div class="name">'.htmlspecialchars($name, ENT_QUOTES, 'UTF-8').'</div>
                    <div class="date-and-time">'.$date.' '.$time.'</div>
                    <div class="rating">'.translate('Оценка').': <div class="rating-stars">';
                for ($i = 1; $i <= 5; $i++) {
                    echo '<img src="/img/'.($rating >= $i ? 'starfull.png' : 'starempty.png').'" class="rating-star">';
                }    
                echo '</div></div>
                </div>';
                if ($content != NULL && $content != '')
                echo '
                    <div class="content">
                        '.htmlspecialchars($content, ENT_QUOTES, 'UTF-8').'
                    </div>
                ';
                echo '
                </div>
                ';
    }
?>