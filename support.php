<?php
    session_start();
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    $mysqli = connect_to_database();
    $page_name="Техподдержка";
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<form action="javascript:void(null);" method="POST" id="support_form" onsubmit="call()" class="application-form">
<div class="success-text" id="success_text_id"><?=translate('Ваше сообщение успешно отправлено')?>!</div>
    <label for="fullname"><?=translate('ФИО')?>: </label>
    <input type="text" name="fullname" id="fullname_id" class="text">
    <p></p>
    <label for="email"><?=translate('Почта')?>: </label>
    <input type="email" name="email" id="email_id" class="text">
    <p></p>
    <label for="message"><?=translate('Сообщение')?>: </label>
    <textarea name="message" id="message_id" cols="30" rows="10" class="text"></textarea>
    <p></p>
    <div class="submit-btn-wrapper">
        <div class="g-recaptcha" data-sitekey="6LfdRl8UAAAAAFNp0Aq7VO1Wp7LEm9yaBnXs6-QZ"></div>
        <button type="submit" value="<?=translate('Сообщить о проблеме')?>" class="submit-btn" name="support_submit" id="support_submit"><?=translate('Сообщить о проблеме')?></button>
    </div>
</form>
<script type="text/javascript" language="javascript">
    function resetForm(response) {
        $("#fullname_id").val('');
        $("#email_id").val('');
        $("#message_id").val('');
        $("#success_text_id").css('display', 'block');
        if (response == 'success') {
            $("#success_text_id").html('<?=translate('Ваше сообщение успешно отправлено')?>');
            $("#success_text_id").css('background', 'hsl(120, 80%, 63%)');
        }
        else {
            $("#success_text_id").html('<?=translate('Ошибка: введены неверные данные!')?>');
            $("#success_text_id").css('background', 'hsl(0, 80%, 63%)')
        }
    }
    function call() {
 	  var msg = $('#support_form').serialize();
        $.ajax({
          type: 'POST',
          url: '/elements/support-result.php',
          data: msg,
          beforeSend: function () {
              $("#support_submit").html('<img class="btn-preloader" src="/img/preloader.svg">' + $("#support_submit").html());
          },
          success: function (response) {
              resetForm(response);
          }
        });
    }
</script>
<div id="results"></div>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); $mysqli->close(); ?>