<?php
    session_start();
    $page_name = 'Вопрос-Ответ для прессы';
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/media-template.php');
    if (!can_do('see_info_for_media')) {
        include($_SERVER['DOCUMENT_ROOT'].'/header.php');
        echo '<div style="margin: 30px auto;">'.translate('У вас нет прав для просмотра данной страницы').'</div>';
        include($_SERVER['DOCUMENT_ROOT'].'/footer.php');
        exit();
    }
    $mysqli = connect_to_database();
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<script>
    var main = $('main');
    main.css('padding', '0 5vw');
    if (window.matchMedia('(min-width: 800px)').matches) {
        main.css('padding', '0 15vw');
    }
    if (window.matchMedia('(min-width: 1200px)').matches) {
        main.css('padding', '0 25vw');
    }
</script>
<form action="javascript:void(null);" method="POST" id="question_form" onsubmit="call()" style="margin-top: 20px;">
    <label for="question"><?=translate('Задать вопрос')?>: </label>
    <br>
    <textarea name="question" id="question_id" rows="10" style="width: 100%;" maxlength="300"></textarea>
    <br>
    <span id="success_text_id" style="display: none;"></span>
    <input type="submit" value="<?=translate('Отправить')?>" style="padding: 10px;" name="submit">
</form>
<?php
    if (can_do('answer_questions_for_media'))
        $result = $mysqli->query("SELECT * FROM questions_answers WHERE lang = '".$mysqli->real_escape_string($_SESSION['lang'])."' ORDER BY id DESC");
    else
        $result = $mysqli->query("SELECT * FROM questions_answers WHERE answer IS NOT NULL AND lang = '".$mysqli->real_escape_string($_SESSION['lang'])."' ORDER BY id DESC");
    while ($row = $result->fetch_assoc()) {
        show_qa($row['question'], $row['answer'], $row['creation_date'], $row['id']);
    }
?>
<script type="text/javascript" language="javascript">
    function resetForm(response) {
        $("#question_id").val('');
        $("#success_text_id").css('display', 'block');
        $("#success_text_id").html(response);
    }
    function call() {
 	  var msg = $("#question_form").serialize();
        $.ajax({
          type: 'POST',
          url: '/for_media/media-question-answer-result.php',
          data: msg,
          success: resetForm,
          error:  function(xhr, str){
	    alert('Возникла ошибка: ' + xhr.responseCode);
          }
        });
    }
    function call_answer(id) {
        var msg = $("#answer_form" + id).serialize();
        $.ajax({
            type: 'POST',
            url: '/for_media/media-question-answer-result.php',
            data: msg,
            success: function(response) {
                var answer = $('#answer' + id);
                answer.html(response);
                $('#answer_id' + id).html('');
                $('#answer_id' + id).val('');
            },
            error:  function(xhr, str){
	    alert('Возникла ошибка: ' + xhr.responseCode);
          }
        });
    }
    function call_delete(id) {
        var msg = $("#delete_form" + id).serialize();
        $.ajax({
            type: 'POST',
            url: '/for_media/media-question-answer-result.php',
            data: msg,
            success: function() {
                $("#qa" + id).remove();
            },
            error:  function(xhr, str){
	    alert('Возникла ошибка: ' + xhr.responseCode);
          }
        });
    }
</script>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); $mysqli->close(); ?>