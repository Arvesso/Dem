<h2>Админ вход</h2>
<form method="post" action="?action=admin">
    <p>Логин: <input name="username" required></p>
    <p>Пароль: <input type="password" name="password" required></p>
    <p><button type="submit">Войти</button></p>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
</form>
