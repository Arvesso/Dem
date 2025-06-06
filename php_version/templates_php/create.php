<h2>Новая заявка</h2>
<form method="post" action="?action=create">
    <p>Дата и время: <input name="datetime" type="datetime-local" required></p>
    <p>Вес (кг): <input name="weight" type="number" min="0" step="0.01" required></p>
    <p>Габариты: <input name="size" required></p>
    <p>Тип груза:
        <select name="cargo_type" id="cargo_type">
            <option value="Хрупкое">Хрупкое</option>
            <option value="Скоропортящееся">Скоропортящееся</option>
            <option value="Требуется рефрижератор">Требуется рефрижератор</option>
            <option value="Животные">Животные</option>
            <option value="Жидкость">Жидкость</option>
            <option value="Мебель">Мебель</option>
            <option value="Мусор">Мусор</option>
        </select>
    </p>
    <p>Адрес отправления: <input name="from_addr" required></p>
    <p>Адрес доставки: <input name="to_addr" required></p>
    <p><button type="submit">Отправить</button></p>
</form>
