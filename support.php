<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    $mysqli = connect_to_database();
    $page_name="Тех. поддержка";
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<form action="javascript:void(null);" method="POST" id="support_form" onsubmit="call()" class="application-form">
<div class="success-text" id="success_text_id">Ваше сообщение успешно отправлено!</div>
    <label for="fullname">ФИО: </label>
    <input type="text" name="fullname" id="fullname_id" class="text">
    <p></p>
    <label for="email">Почта: </label>
    <input type="email" name="email" id="email_id" class="text">
    <p></p>
    <label for="message">Сообщение: </label>
    <textarea name="message" id="message_id" cols="30" rows="10" class="text"></textarea>
    <p></p>
    <div class="submit-btn-wrapper">
        <div class="g-recaptcha" data-sitekey="6LfdRl8UAAAAAFNp0Aq7VO1Wp7LEm9yaBnXs6-QZ"></div>
        <input type="submit" value="Сообщить о проблеме" class="submit-btn" name="support_submit">
    </div>
</form>
<script type="text/javascript" language="javascript">
    function resetForm() {
        $("#fullname_id").val('');
        $("#email_id").val('');
        $("#message_id").val('');
        $("#success_text_id").css('display', 'block');
    }
    function call() {
 	  var msg = $('#support_form').serialize();
        $.ajax({
          type: 'POST',
          url: '/elements/support-result.php',
          data: msg,
          success: resetForm,
          error:  function(xhr, str){
	    alert('Возникла ошибка: ' + xhr.responseCode);
          }
        });
    }
</script>
<div id="results"></div>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); $mysqli->close(); ?>