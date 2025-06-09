<h2 class="fade">Регистрация</h2>
<form method="post" action="?action=register" class="fade">
    <p>Логин: <input name="username" required pattern="[А-Яа-яЁё]{6,}"></p>
    <p>Пароль: <input type="password" name="password" required minlength="6"></p>
    <p>ФИО: <input name="full_name" required pattern="[А-Яа-яЁё ]+"></p>
    <p>Телефон: <input name="phone" placeholder="+7(999)-999-99-99" required pattern="\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}"></p>
    <p>Email: <input name="email" type="email" required></p>
    <p><button type="submit">Зарегистрироваться</button></p>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
</form>
