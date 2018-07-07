<?php
    function show_kit($name, $extansion, $date) { ?>
        <div class="kit">
            <div class="date"><?=htmlspecialchars($date, ENT_QUOTES, 'UTF-8')?></div>
            <img src="/img/extension_img/<?=htmlspecialchars($extansion, ENT_QUOTES, 'UTF-8')?>.png" alt="<?=htmlspecialchars($extansion, ENT_QUOTES, 'UTF-8')?>?>">
            <div class="name"><?=htmlspecialchars($name, ENT_QUOTES, 'UTF-8')?></div>
            <button class="download">DOWNLOAD</button>
        </div>
    <?php }
?>