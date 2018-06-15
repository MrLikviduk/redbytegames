<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/constants.php');
    session_start();
    if (!isset($_GET['id']))
        return_404();
    $mysqli = connect_to_database();
    $result = get_by_id($_GET['id'], 'vacancy') or return_404();
    // $responsibilities = unserialize(base64_decode($result['responsibilities']));
    // $required = unserialize(base64_decode($result['required']));
    // $desired = unserialize(base64_decode($result['desired']));
    if (isset($_POST['request_submit'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $resume = $_POST['resume'];
        $vacancy_id = $_GET['id'];
        $result = $mysqli->query("SELECT * FROM vacancy_requests WHERE `email` LIKE '$email'");
        if (strlen($name) >= NAME_MIN && strlen($name) <= NAME_MAX && strlen($email) >= EMAIL_MIN && strlen($email) <= EMAIL_MAX && strlen($resume) >= LINK_MIN && strlen($resume) <= LINK_MAX) {
            $kol = $result->num_rows;
            if ($kol > 0) {
                $mysqli->query("UPDATE vacancy_requests SET `name` = '$name', `resume` = '$resume', `vacancy_id` = '$vacancy_id' WHERE `email` LIKE '$email'") or die("ERROR1");
            }
            else {
                $mysqli->query("INSERT INTO vacancy_requests (id, vacancy_id, `name`, email, requests) VALUES (NULL, $vacancy_id, '$name', '$email', '$resume')") or die("ERROR2");
            }
        }

    }
    $page_name = 'Вакансия';
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<div class="vacancy-name">Программист C#</div>
<div class="list">
    <div class="list-header">Обязанности:</div>
    <ul>
        <li>Работать по 20 часов в сутки</li>
        <li>Спать по 3-4 часа</li>
    </ul>
</div>
<div class="list">
    <div class="list-header">Квалификация:</div>
    <ul>
        <li>Работать по 20 часов в сутки</li>
        <li>Спать по 3-4 часа</li>
    </ul>
</div>
<div class="list">
    <div class="list-header">Обязанности:</div>
    <ul>
        <li>Работать по 20 часов в сутки</li>
        <li>Спать по 3-4 часа</li>
    </ul>
</div>
<h3 style="max-width: 400px; margin: 40px 0;">Заинтересованы? Заполните анкету ниже, наши менеджеры вам ответят в ближайшее время!</h3>
<form action="" method="POST" class="application-form">
    <label for="name">Введите имя: </label>
    <input type="text" name="name" class="text" maxlength="<?=NAME_MAX?>">
    <p></p>
    <label for="email">Введите электронный адрес: </label>
    <input type="email" name="email" class="text" maxlength="<?=EMAIL_MAX?>">
    <p></p>
    <label for="resume">Введите ссылку на резюме: </label>
    <input type="url" name="resume" class="text" maxlength="<?=LINK_MAX?>">
    <p></p>
    <div style="text-align: right;"><input type="submit" value="Отправить" class="submit-btn" name="request_submit"></div>
</form>
<?php include($_SERVER['DOCUMENT_ROOT'].'/footer.php'); $mysqli->close(); ?>