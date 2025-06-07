<h2>Мои заявки</h2>
<div id="orders-dynamic">
<?php if ($orders): ?>
<div class="order-list fade">
<?php foreach ($orders as $o): ?>
<div class="order-card">
  <div><strong>ID:</strong> <?= htmlspecialchars($o['id']) ?></div>
  <div><strong>Дата:</strong> <?= htmlspecialchars($o['datetime']) ?></div>
  <div><strong>Вес:</strong> <?= htmlspecialchars($o['weight']) ?></div>
  <div><strong>Габариты:</strong> <?= htmlspecialchars($o['size']) ?></div>
  <div><strong>Тип:</strong> <?= htmlspecialchars($o['cargo_type']) ?></div>
  <div><strong>Откуда:</strong> <?= htmlspecialchars($o['from_addr']) ?></div>
  <div><strong>Куда:</strong> <?= htmlspecialchars($o['to_addr']) ?></div>
  <div><strong>Статус:</strong> <?= htmlspecialchars($o['status']) ?></div>
  <div><strong>Отзыв:</strong>
    <?php if ($o['status'] === 'Выполнено' && empty($o['review'])): ?>
    <form method="post" action="?action=review&id=<?= $o['id'] ?>">
        <input type="text" name="review" required>
        <button type="submit">Оставить отзыв</button>
    </form>
    <?php else: ?>
        <?= htmlspecialchars($o['review'] ?? '') ?>
    <?php endif; ?>
  </div>
</div>
<?php endforeach; ?>
</div>
<?php else: ?>
<p class="fade">Заявок нет</p>
<?php endif; ?>
</div>
