<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Грузовозофф</title>
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body>
<nav>
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="?action=orders">Мои заявки</a>
        <a href="?action=create">Новая заявка</a>
        <a href="?action=logout">Выход</a>
    <?php else: ?>
        <a href="?action=login">Вход</a>
        <a href="?action=register">Регистрация</a>
    <?php endif; ?>
    <a href="?action=admin">Админ</a>
</nav>
<div class="container">
<?php echo $content; ?>
</div>
<script src="static/js/form.js"></script>
</body>
</html>
