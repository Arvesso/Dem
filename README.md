# Грузовозофф

Простой портал для оформления заявок на грузоперевозки. Реализован на Flask с использованием SQLite.

## Запуск

```
python3 -m venv venv
source venv/bin/activate
pip install flask flask_sqlalchemy werkzeug
python gruzovozoff/app.py
```

После запуска приложение будет доступно на `http://localhost:5000`.

## Запуск PHP версии

```
php -S localhost:8000 -t php_version
```

Файл `php_version/db_config.php` содержит параметры подключения к MySQL. Перед
запуском создайте базу данных и при необходимости измените логин/пароль.

После запуска PHP версия будет доступна на `http://localhost:8000`.
