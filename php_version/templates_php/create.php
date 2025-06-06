<h2>Новая заявка</h2>
<form method="post" action="?action=create">
    <p>Дата и время: <input name="datetime" type="datetime-local" required></p>
    <p>Вес (кг): <input name="weight" type="number" min="0" step="0.01" required></p>
    <p>Габариты: <input name="size" required></p>
    <p>Тип груза: <input name="cargo_type" required></p>
    <p>Адрес отправления: <input name="from_addr" required></p>
    <p>Адрес доставки: <input name="to_addr" required></p>
    <p><button type="submit">Отправить</button></p>
</form>
