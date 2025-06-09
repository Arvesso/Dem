<h2 class="fade">Новая заявка</h2>
<form method="post" action="?action=create" class="fade">
    <p>Курс:
        <select name="course" id="course">
            <option value="Основы алгоритмизации и программирования">Основы алгоритмизации и программирования</option>
            <option value="Основы веб-дизайна">Основы веб-дизайна</option>
            <option value="Основы проектирования баз данных">Основы проектирования баз данных</option>
        </select>
    </p>
    <p>Дата начала обучения: <input name="start_date" type="date" required></p>
    <p>Способ оплаты:
        <select name="payment">
            <option value="Наличные">Наличные</option>
            <option value="Перевод на банковский счет">Перевод на банковский счет</option>
        </select>
    </p>
    <p><button type="submit">Отправить</button></p>
</form>
