<h2>Все заявки</h2>
<?php if ($orders): ?>
<table>
<tr><th>ID</th><th>Пользователь</th><th>Дата</th><th>Вес</th><th>Габариты</th><th>Тип</th><th>Откуда</th><th>Куда</th><th></th></tr>
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
<td>
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
