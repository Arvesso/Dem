<h2>Мои заявки</h2>
<div id="orders-dynamic">
<?php if ($orders): ?>
<table class="fade">
<tr><th>ID</th><th>Дата</th><th>Вес</th><th>Габариты</th><th>Тип</th><th>Откуда</th><th>Куда</th><th>Статус</th><th>Отзыв</th></tr>
<?php foreach ($orders as $o): ?>
<tr>
<td><?= htmlspecialchars($o['id']) ?></td>
<td><?= htmlspecialchars($o['datetime']) ?></td>
<td><?= htmlspecialchars($o['weight']) ?></td>
<td><?= htmlspecialchars($o['size']) ?></td>
<td><?= htmlspecialchars($o['cargo_type']) ?></td>
<td><?= htmlspecialchars($o['from_addr']) ?></td>
<td><?= htmlspecialchars($o['to_addr']) ?></td>
<td><?= htmlspecialchars($o['status']) ?></td>
<td>
    <?php if ($o['status'] === 'Выполнено' && empty($o['review'])): ?>
    <form method="post" action="?action=review&id=<?= $o['id'] ?>">
        <input type="text" name="review" required>
        <button type="submit">Оставить отзыв</button>
    </form>
    <?php else: ?>
        <?= htmlspecialchars($o['review'] ?? '') ?>
    <?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</table>
<?php else: ?>
<p class="fade">Заявок нет</p>
<?php endif; ?>
</div>
