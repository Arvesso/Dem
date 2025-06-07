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
    echo "<div class='order-list'>";
    foreach ($orders as $o) {
        $id = htmlspecialchars($o['id']);
        $datetime = htmlspecialchars($o['datetime']);
        $weight = htmlspecialchars($o['weight']);
        $size = htmlspecialchars($o['size']);
        $type = htmlspecialchars($o['cargo_type']);
        $from = htmlspecialchars($o['from_addr']);
        $to = htmlspecialchars($o['to_addr']);
        $status = htmlspecialchars($o['status']);
        if ($o['status'] === 'Выполнено' && empty($o['review'])) {
            $review = "<form method='post' action='?action=review&id=$id'><input type='text' name='review' required><button type='submit'>Оставить отзыв</button></form>";
        } else {
            $review = htmlspecialchars($o['review'] ?? '');
        }
        echo "<div class='order-card'>";
        echo "<div><strong>ID:</strong> $id</div>";
        echo "<div><strong>Дата:</strong> $datetime</div>";
        echo "<div><strong>Вес:</strong> $weight</div>";
        echo "<div><strong>Габариты:</strong> $size</div>";
        echo "<div><strong>Тип:</strong> $type</div>";
        echo "<div><strong>Откуда:</strong> $from</div>";
        echo "<div><strong>Куда:</strong> $to</div>";
        echo "<div><strong>Статус:</strong> $status</div>";
        echo "<div><strong>Отзыв:</strong> $review</div>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo '<p>Заявок нет</p>';
}

