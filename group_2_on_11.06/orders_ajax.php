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
        $address = htmlspecialchars($o['address']);
        $contact = htmlspecialchars($o['contact']);
        $license_series = htmlspecialchars($o['license_series']);
        $license_number = htmlspecialchars($o['license_number']);
        $license_issue = htmlspecialchars($o['license_issue']);
        $car_make = htmlspecialchars($o['car_make']);
        $car_model = htmlspecialchars($o['car_model']);
        $status = htmlspecialchars($o['status']);
        $reason = htmlspecialchars($o['rejection_reason'] ?? '');

        echo "<div class='order-card'>";
        echo "<div><strong>ID:</strong> $id</div>";
        echo "<div><strong>Дата:</strong> $datetime</div>";
        echo "<div><strong>Адрес:</strong> $address</div>";
        echo "<div><strong>Контакты:</strong> $contact</div>";
        echo "<div><strong>Серия водительского удостоверения:</strong> $license_series</div>";
        echo "<div><strong>Номер водительского удостоверения:</strong> $license_number</div>";
        echo "<div><strong>Дата выдачи водительского удостоверения:</strong> $license_issue</div>";
        echo "<div><strong>Марка:</strong> $car_make</div>";
        echo "<div><strong>Модель:</strong> $car_model</div>";
        echo "<div><strong>Статус:</strong> $status</div>";
        if ($o['status'] === 'Отклонено' && $reason !== '') {
            echo "<div><strong>Причина отказа:</strong> $reason</div>";
    }
        echo "</div>";
    }
    echo "</div>";
} else {
    echo '<p>Заявок нет</p>';
}

