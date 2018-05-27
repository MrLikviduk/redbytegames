<div class="to-authorizate">
    Для редактирования необходимо <div class="login-button" id="login_btn">авторизироваться</div>
    <form action="" method="POST" class="login-form" id="lgn_form">
        <input type="text" name="lgn" placeholder="Введите логин">
        <input type="password" name="pswd" placeholder="Введите пароль">
        <input type="submit" name="sbmt" value="Войти">
    </form>
</div>
<script>
    login_btn.addEventListener('click', function () {
        lgn_form.classList.toggle('login-form-is-open');
    })
</script>