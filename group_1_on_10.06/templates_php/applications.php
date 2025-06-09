<h2>Мои заявки</h2>
<div id="applications-dynamic">
<?php if ($applications): ?>
<div class="order-list fade">
<?php foreach ($applications as $o): ?>
<div class="order-card">
  <div><strong>ID:</strong> <?= htmlspecialchars($o['id']) ?></div>
  <div><strong>Курс:</strong> <?= htmlspecialchars($o['course']) ?></div>
  <div><strong>Дата начала:</strong> <?= htmlspecialchars($o['start_date']) ?></div>
  <div><strong>Оплата:</strong> <?= htmlspecialchars($o['payment']) ?></div>
  <div><strong>Статус:</strong> <?= htmlspecialchars($o['status']) ?></div>
  <div><strong>Отзыв:</strong>
    <?php if ($o['status'] === 'Обучение завершено' && empty($o['review'])): ?>
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
