<?php
// =============================
// templates_php/create.php
// форма создания заявки на тест‑драйв
// =============================
?>
<h2 class="fade">Новая заявка на тест‑драйв</h2>
<form method="post" action="?action=create" class="fade">
    <p>Желаемая дата и время: <input name="datetime" type="datetime-local" required></p>

    <p>Адрес получения услуги: <input name="address" placeholder="Город, улица, дом" required></p>

    <p>Контактные данные: <input name="contact" placeholder="Телефон или e‑mail" required></p>

    <fieldset>
        <legend>Водительское удостоверение</legend>
        <p>
            Серия: <input name="license_series" pattern="\d{4}" maxlength="4" required>
            Номер: <input name="license_number" pattern="\d{6}" maxlength="6" required>
            Дата выдачи: <input name="license_issue" type="date" required>
        </p>
    </fieldset>

    <p>Автомобиль:
        <select name="car_make" id="car_make" required>
            <option value="">Марка</option>
            <option value="Audi">Audi</option>
            <option value="BMW">BMW</option>
            <option value="Mercedes">Mercedes</option>
        </select>
        <select name="car_model" id="car_model" required>
            <option value="">Модель</option>
        </select>
    </p>

    <p><button type="submit">Отправить</button></p>
</form>
<script>
const models = {
    "Audi": ["A4", "Q3"],
    "BMW": ["3 Series", "X1"],
    "Mercedes": ["C‑Class", "GLA"]
};

document.getElementById('car_make').addEventListener('change', function () {
    const make = this.value;
    const modelSelect = document.getElementById('car_model');
    modelSelect.innerHTML = '<option value="">Модель</option>';
    if (models[make]) {
        models[make].forEach(m => {
            const o = document.createElement('option');
            o.value = m;
            o.textContent = m;
            modelSelect.appendChild(o);
        });
    }
});
</script>
