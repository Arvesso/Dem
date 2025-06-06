<h2>Все заявки</h2>
<form method="get" action="?action=admin">
    <label>Статус:
        <select name="status">
            <option value="" <?php if (!$status_filter) echo 'selected'; ?>>Все</option>
            <option value="Новая" <?php if ($status_filter=='Новая') echo 'selected'; ?>>Новая</option>
            <option value="В работе" <?php if ($status_filter=='В работе') echo 'selected'; ?>>В работе</option>
            <option value="Выполнено" <?php if ($status_filter=='Выполнено') echo 'selected'; ?>>Выполнено</option>
            <option value="Отменена" <?php if ($status_filter=='Отменена') echo 'selected'; ?>>Отменена</option>
        </select>
    </label>
    <button type="submit">Фильтр</button>
</form>
<?php if ($orders): ?>
<table>
<tr><th>ID</th><th>Пользователь</th><th>Дата</th><th>Вес</th><th>Габариты</th><th>Тип</th><th>Откуда</th><th>Куда</th><th>Статус</th><th>Действия</th></tr>
<?php foreach ($orders as $o): ?>
<tr>
<td><?= htmlspecialchars($o['id']) ?></td>
<td><?= htmlspecialchars($o['user_id']) ?></td>
<td><?= htmlspecialchars($o['datetime']) ?></td>
<td><?= htmlspecialchars($o['weight']) ?></td>
<td><?= htmlspecialchars($o['size']) ?></td>
<td><?= htmlspecialchars($o['cargo_type']) ?></td>
<td><?= htmlspecialchars($o['from_addr']) ?></td>
<td><?= htmlspecialchars($o['to_addr']) ?></td>
<td><?= htmlspecialchars($o['status']) ?></td>
<td>
    <form method="post" action="?action=update&id=<?= $o['id'] ?>" style="display:inline">
        <select name="status">
            <option value="Новая" <?= $o['status']=='Новая'?'selected':'' ?>>Новая</option>
            <option value="В работе" <?= $o['status']=='В работе'?'selected':'' ?>>В работе</option>
            <option value="Выполнено" <?= $o['status']=='Выполнено'?'selected':'' ?>>Выполнено</option>
            <option value="Отменена" <?= $o['status']=='Отменена'?'selected':'' ?>>Отменена</option>
        </select>
        <button type="submit">Обновить</button>
    </form>
    <form method="post" action="?action=delete&id=<?= $o['id'] ?>" style="display:inline">
        <button type="submit">Удалить</button>
    </form>
</td>
</tr>
<?php endforeach; ?>
</table>
<?php else: ?>
<p>Заявок нет</p>
<?php endif; ?>
