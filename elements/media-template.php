<?php
    function show_kit($name, $extension, $date, $id) { ?>
        <div class="kit">
            <div class="date"><?=htmlspecialchars($date, ENT_QUOTES, 'UTF-8')?></div>
            <img src="/img/extension_img/<?=htmlspecialchars($extension, ENT_QUOTES, 'UTF-8')?>.png" alt="<?=htmlspecialchars($extension, ENT_QUOTES, 'UTF-8')?>">
            <div class="name"><?=htmlspecialchars($name, ENT_QUOTES, 'UTF-8')?></div>
            <a href="/for_media/kits/<?=$name.'.'.$extension?>"><button class="download">DOWNLOAD</button></a>
            <?php if (can_do('edit_for_media')) { ?>
                <form action="" method="POST">
                    <button name="delete_file" value="<?=$id?>" onclick="return confirm('<?=translate('Вы действительно хотите удалить файл?')?>')"><?=translate('Удалить')?></button>
                </form>
            <?php } ?>
        </div>
    <?php }
?>