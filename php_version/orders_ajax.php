<?php
session_start();
$config = require __DIR__ . '/db_config.php';
$db = new PDO($config['dsn'], $config['user'], $config['password']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo 'Не авторизовано';
    exit;
}

$stmt = $db->prepare('SELECT * FROM orders WHERE user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($orders) {
    echo "<table><tr><th>ID</th><th>Дата</th><th>Вес</th><th>Габариты</th><th>Тип</th><th>Откуда</th><th>Куда</th><th>Статус</th><th>Отзыв</th></tr>";
    foreach ($orders as $o) {
        $id = htmlspecialchars($o['id']);
        $datetime = htmlspecialchars($o['datetime']);
        $weight = htmlspecialchars($o['weight']);
        $size = htmlspecialchars($o['size']);
        $type = htmlspecialchars($o['cargo_type']);
        $from = htmlspecialchars($o['from_addr']);
        $to = htmlspecialchars($o['to_addr']);
        $status = htmlspecialchars($o['status']);
        $review = '';
        if ($o['status'] === 'Выполнено' && empty($o['review'])) {
            $review = "<form method='post' action='?action=review&id=$id'><input type='text' name='review' required><button type='submit'>Оставить отзыв</button></form>";
        } else {
            $review = htmlspecialchars($o['review'] ?? '');
        }
        echo "<tr><td>$id</td><td>$datetime</td><td>$weight</td><td>$size</td><td>$type</td><td>$from</td><td>$to</td><td>$status</td><td>$review</td></tr>";
    }
    echo "</table>";
} else {
    echo '<p>Заявок нет</p>';
}

