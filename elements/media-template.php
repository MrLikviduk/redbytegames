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
    function show_qa($question, $answer, $date, $id) { // QA - Question Answer ?>
        <div class="qa-block">
            <div class="question"><?=htmlspecialchars($question, ENT_QUOTES, 'UTF-8')?></div>
            <div class="date"><?=htmlspecialchars($date, ENT_QUOTES, 'UTF-8')?></div>
            <div class="answer" id="answer<?=$id?>"><?=htmlspecialchars($answer, ENT_QUOTES, 'UTF-8')?></div>
            <?php if (can_do('answer_questions_for_media')) { ?>
                <form action="javascript:void(null);" method="POST" id="answer_form<?=$id?>" onsubmit="call_answer(<?=$id?>)" style="margin: 10px 0;">
                    <label for="answer"><?=translate('Ответить на вопрос')?>: </label>
                    <br>
                    <textarea name="answer" id="answer_id<?=$id?>" rows="6" style="width: 100%;" maxlength="300"></textarea>
                    <br>
                    <input type="hidden" name="qa_id" value=<?=$id?>>
                    <input type="submit" value="<?=translate('Ответить')?>" style="padding: 10px;" name="submit">
                </form>
            <?php } ?>
        </div>
    <?php }
?>