<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/functions.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/vacancy-template.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/elements/constants.php');
    session_start();
    if (!isset($_GET['id']))
        return_404();
    $mysqli = connect_to_database();
    $lists = unserialize(base64_decode($result['lists']));
    $result = get_by_id($_GET['id'], 'vacancy') or return_404();
    if (can_do('edit_vacancy')) {
        if (isset($_POST['lst_submit'])) {
            $content = $_POST['input_text'];
            if (strlen($content) > 0 && strlen($content) < 1024) {
                array_push($lists[$_POST['lst_name']], $content);
                $id = (int)$_GET['id'];
                $mysqli->query("UPDATE vacancy SET lists = '".base64_encode(serialize($lists))."' WHERE id = $id");
                header("Location: ".$_SERVER['REQUEST_URI']);
            }
        }
    }
    if (isset($_POST['request_submit'])) {
        $name = $mysqli->real_escape_string($_POST['name']);
        $email = $mysqli->real_escape_string($_POST['email']);
        $resume = $mysqli->real_escape_string($_POST['resume']);
        $vacancy_id = (int)$_GET['id'];
        $result = $mysqli->query("SELECT * FROM vacancy_requests WHERE `email` LIKE '$email'");
        if (strlen($name) >= NAME_MIN && strlen($name) <= NAME_MAX && strlen($email) >= EMAIL_MIN && strlen($email) <= EMAIL_MAX && strlen($resume) >= LINK_MIN && strlen($resume) <= LINK_MAX) {
            $kol = $result->num_rows;
            if ($kol > 0) {
                $mysqli->query("UPDATE vacancy_requests SET `name` = '$name', `resume` = '$resume', `vacancy_id` = '$vacancy_id' WHERE `email` LIKE '$email'") or die("ERROR1");
            }
            else {
                $mysqli->query("INSERT INTO vacancy_requests (id, vacancy_id, `name`, email, `resume`) VALUES (NULL, $vacancy_id, '$name', '$email', '$resume')") or die("ERROR2");
            }
        }
        header("Location: ".$_SERVER['REQUEST_URI']);
    }
    $page_name = 'Вакансия';
    include($_SERVER['DOCUMENT_ROOT'].'/header.php');
?>
<div class="vacancy-name">Программист C#</div>
<?php
    show_vacancy_list('responsibilities', 'Обязанности:', $lists['responsibilities']);
    show_vacancy_list('required', 'Обязанности:', $lists['required']);
    show_vacancy_list('desired', 'Обязанности:', $lists['desired']);
?>
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