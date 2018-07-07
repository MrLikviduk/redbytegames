<?php
    function show_kit($name, $extension, $date) { ?>
        <div class="kit">
            <div class="date"><?=htmlspecialchars($date, ENT_QUOTES, 'UTF-8')?></div>
            <img src="/img/extension_img/<?=htmlspecialchars($extension, ENT_QUOTES, 'UTF-8')?>.png" alt="<?=htmlspecialchars($extension, ENT_QUOTES, 'UTF-8')?>">
            <div class="name"><?=htmlspecialchars($name, ENT_QUOTES, 'UTF-8')?></div>
            <button class="download">DOWNLOAD</button>
        </div>
    <?php }
?>