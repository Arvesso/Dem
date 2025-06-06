<?php
session_start();

$db = new PDO('sqlite:' . __DIR__ . '/../gruzovozoff/gruzovozoff.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Initialize tables
$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE,
    password_hash TEXT,
    full_name TEXT,
    phone TEXT,
    email TEXT
)");
$db->exec("CREATE TABLE IF NOT EXISTS orders (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    datetime TEXT,
    weight TEXT,
    size TEXT,
    cargo_type TEXT,
    from_addr TEXT,
    to_addr TEXT
)");

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
    case 'orders':
        if (!logged_in()) { header('Location: ?action=login'); exit; }
        $stmt = $db->prepare('SELECT * FROM orders WHERE user_id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        render('orders', ['orders' => $orders]);
        break;
    case 'create':
        if (!logged_in()) { header('Location: ?action=login'); exit; }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $stmt = $db->prepare('INSERT INTO orders (user_id, datetime, weight, size, cargo_type, from_addr, to_addr) VALUES (?,?,?,?,?,?,?)');
            $stmt->execute([
                $_SESSION['user_id'],
                $_POST['datetime'],
                $_POST['weight'],
                $_POST['size'],
                $_POST['cargo_type'],
                $_POST['from_addr'],
                $_POST['to_addr']
            ]);
            header('Location: ?action=orders');
            exit;
        }
        render('create');
        break;
    case 'admin':
        handle_admin($db);
        break;
    case 'delete':
        if (!($_SESSION['admin'] ?? false)) { header('Location: ?action=admin'); exit; }
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $db->prepare('DELETE FROM orders WHERE id = ?');
        $stmt->execute([$id]);
        header('Location: ?action=admin');
        break;
    default:
        if (logged_in()) {
            header('Location: ?action=orders');
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
        $stmt = $db->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: ?action=orders');
            exit;
        } else {
            $error = 'Неверный логин или пароль';
        }
    }
    render('login', ['error' => $error]);
}

function handle_admin($db) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !($_SESSION['admin'] ?? false)) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if ($username === 'admin' && $password === 'gruzovik2024') {
            $_SESSION['admin'] = true;
        } else {
            render('admin_login', ['error' => 'Неверные данные']);
            return;
        }
    }
    if (!($_SESSION['admin'] ?? false)) {
        render('admin_login');
        return;
    }
    $orders = $db->query('SELECT * FROM orders')->fetchAll(PDO::FETCH_ASSOC);
    render('admin', ['orders' => $orders]);
}
?>
