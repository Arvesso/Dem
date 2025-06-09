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

$stmt = $db->prepare('SELECT * FROM applications WHERE user_id = ?');
$stmt->execute([$_SESSION['user_id']]);
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($applications) {
    echo "<div class='order-list'>";
    foreach ($applications as $o) {
        $id = htmlspecialchars($o['id']);
        $course = htmlspecialchars($o['course']);
        $date = htmlspecialchars($o['start_date']);
        $payment = htmlspecialchars($o['payment']);
        $status = htmlspecialchars($o['status']);
        if ($o['status'] === 'Обучение завершено' && empty($o['review'])) {
            $review = "<form method='post' action='?action=review&id=$id'><input type='text' name='review' required><button type='submit'>Оставить отзыв</button></form>";
        } else {
            $review = htmlspecialchars($o['review'] ?? '');
        }
        echo "<div class='order-card'>";
        echo "<div><strong>ID:</strong> $id</div>";
        echo "<div><strong>Курс:</strong> $course</div>";
        echo "<div><strong>Дата начала:</strong> $date</div>";
        echo "<div><strong>Оплата:</strong> $payment</div>";
        echo "<div><strong>Статус:</strong> $status</div>";
        echo "<div><strong>Отзыв:</strong> $review</div>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo '<p>Заявок нет</p>';
}

