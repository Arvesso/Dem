<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Грузовозофф</title>
    <link rel="stylesheet" href="static/css/style.css">
</head>
<body>
<nav>
    <div class="links">
        <?php if (isset($_SESSION['user_id']) || ($_SESSION['admin'] ?? false)): ?>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="?action=orders">Мои заявки</a>
                <a href="?action=create">Новая заявка</a>
            <?php endif; ?>
            <a href="?action=logout">Выход</a>
        <?php else: ?>
            <a href="?action=login">Вход</a>
            <a href="?action=register">Регистрация</a>
        <?php endif; ?>
        <a href="?action=admin">Админ</a>
    </div>
    <button id="theme-toggle" title="Сменить тему">🌙</button>
</nav>
<div class="container">
<?php echo $content; ?>
</div>
<script src="static/js/form.js"></script>
</body>
</html>
