<?php
session_start();

$config = require __DIR__ . '/db_config.php';
$db = new PDO($config['dsn'], $config['user'], $config['password']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Initialize tables
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE,
    password_hash VARCHAR(255),
    full_name VARCHAR(255),
    phone VARCHAR(255),
    email VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
$db->exec("CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    course VARCHAR(255),
    start_date DATE,
    payment VARCHAR(255),
    status VARCHAR(255) DEFAULT 'Новая',
    review TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// add new columns if database already existed
$cols = [];
$res = $db->query("SHOW COLUMNS FROM applications");
foreach ($res as $row) { $cols[] = $row['Field']; }
if (!in_array('status', $cols)) {
    $db->exec("ALTER TABLE applications ADD COLUMN status VARCHAR(255) DEFAULT 'Новая'");
}
if (!in_array('review', $cols)) {
    $db->exec("ALTER TABLE applications ADD COLUMN review TEXT");
}
if (!in_array('course', $cols)) {
    $db->exec("ALTER TABLE applications ADD COLUMN course VARCHAR(255)");
}
if (!in_array('start_date', $cols)) {
    $db->exec("ALTER TABLE applications ADD COLUMN start_date DATE");
}
if (!in_array('payment', $cols)) {
    $db->exec("ALTER TABLE applications ADD COLUMN payment VARCHAR(255)");
}

function render($template, $vars = []) {
    extract($vars);
    ob_start();
    include __DIR__ . "/templates_php/$template.php";
    $content = ob_get_clean();
    include __DIR__ . "/templates_php/base.php";
}

function logged_in() {
    return isset($_SESSION['user_id']);
}

$action = $_GET['action'] ?? 'index';

switch ($action) {
    case 'register':
        handle_register($db);
        break;
    case 'login':
        handle_login($db);
        break;
    case 'logout':
        session_destroy();
        header('Location: ?action=login');
        break;
    case 'applications':
        if (!logged_in()) { header('Location: ?action=login'); exit; }
        $stmt = $db->prepare('SELECT * FROM applications WHERE user_id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        render('applications', ['applications' => $applications]);
        break;
    case 'create':
        if (!logged_in()) { header('Location: ?action=login'); exit; }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $db->prepare('INSERT INTO applications (user_id, course, start_date, payment, status) VALUES (?,?,?,?,?)');
            $stmt->execute([
                $_SESSION['user_id'],
                $_POST['course'],
                $_POST['start_date'],
                $_POST['payment'],
                'Новая'
            ]);
            header('Location: ?action=applications');
            exit;
        }
        render('create');
        break;
    case 'review':
        if (!logged_in()) { header('Location: ?action=login'); exit; }
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $db->prepare('SELECT * FROM applications WHERE id = ?');
        $stmt->execute([$id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($order && $order['user_id'] == $_SESSION['user_id'] && $order['status'] === 'Обучение завершено') {
            $stmt = $db->prepare('UPDATE applications SET review = ? WHERE id = ?');
            $stmt->execute([$_POST['review'], $id]);
        }
        header('Location: ?action=applications');
        break;
    case 'admin':
        handle_admin($db);
        break;
    case 'update':
        if (!($_SESSION['admin'] ?? false)) { header('Location: ?action=admin'); exit; }
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $db->prepare('UPDATE applications SET status = ? WHERE id = ?');
        $stmt->execute([$_POST['status'], $id]);
        header('Location: ?action=admin');
        break;
    case 'delete':
        if (!($_SESSION['admin'] ?? false)) { header('Location: ?action=admin'); exit; }
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $db->prepare('DELETE FROM applications WHERE id = ?');
        $stmt->execute([$id]);
        header('Location: ?action=admin');
        break;
    default:
        if (logged_in()) {
            header('Location: ?action=applications');
        } else {
            header('Location: ?action=login');
        }
}

function handle_register($db) {
    $error = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $full_name = $_POST['full_name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];

        $USER_RE = '/^[\x{0400}-\x{04FF}]{6,}$/u';
        $PHONE_RE = '/^\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}$/';
        $EMAIL_RE = '/^[^@]+@[^@]+\.[^@]+$/';
        $NAME_RE = '/^[\x{0400}-\x{04FF} ]+$/u';

        if (!preg_match($USER_RE, $username)) {
            $error = 'Неверный логин';
        } elseif (strlen($password) < 6) {
            $error = 'Пароль слишком короткий';
        } elseif (!preg_match($NAME_RE, $full_name)) {
            $error = 'Неверное имя';
        } elseif (!preg_match($PHONE_RE, $phone)) {
            $error = 'Неверный телефон';
        } elseif (!preg_match($EMAIL_RE, $email)) {
            $error = 'Неверный email';
        } else {
            try {
                $stmt = $db->prepare('INSERT INTO users (username, password_hash, full_name, phone, email) VALUES (?,?,?,?,?)');
                $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $full_name, $phone, $email]);
                header('Location: ?action=login');
                exit;
            } catch (PDOException $e) {
                $error = 'Логин занят';
            }
        }
    }
    render('register', ['error' => $error]);
}

function handle_login($db) {
    $error = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($username === 'admin' && $password === 'education') {
            $_SESSION['admin'] = true;
            header('Location: ?action=admin');
            exit;
        }

        $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: ?action=applications');
            exit;
        } else {
            $error = 'Неверный логин или пароль';
        }
    }
    render('login', ['error' => $error]);
}

function handle_admin($db) {
    if (!($_SESSION['admin'] ?? false)) {
        header('Location: ?action=login');
        return;
    }
    $status_filter = $_GET['status'] ?? null;
    if ($status_filter) {
        $stmt = $db->prepare('SELECT applications.*, users.full_name FROM applications LEFT JOIN users ON applications.user_id = users.id WHERE status = ?');
        $stmt->execute([$status_filter]);
        $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $applications = $db->query('SELECT applications.*, users.full_name FROM applications LEFT JOIN users ON applications.user_id = users.id')->fetchAll(PDO::FETCH_ASSOC);
    }
    render('admin', ['applications' => $applications, 'status_filter' => $status_filter]);
}
?>
