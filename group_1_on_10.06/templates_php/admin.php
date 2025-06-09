<h2 class="fade">Все заявки</h2>
<form method="get" action="index.php" class="fade">
    <input type="hidden" name="action" value="admin">
    <label>Статус:
        <select name="status">
            <option value="" <?php if (!$status_filter) echo 'selected'; ?>>Все</option>
            <option value="Новая" <?php if ($status_filter=='Новая') echo 'selected'; ?>>Новая</option>
            <option value="Идет обучение" <?php if ($status_filter=='Идет обучение') echo 'selected'; ?>>Идет обучение</option>
            <option value="Обучение завершено" <?php if ($status_filter=='Обучение завершено') echo 'selected'; ?>>Обучение завершено</option>
        </select>
    </label>
    <button type="submit">Фильтр</button>
</form>
<?php if ($applications): ?>
<div class="order-list fade">
<?php foreach ($applications as $o): ?>
<div class="order-card">
  <div><strong>ID:</strong> <?= htmlspecialchars($o['id']) ?></div>
  <div><strong>Пользователь:</strong> <?= htmlspecialchars($o['full_name'] ?? $o['user_id']) ?></div>
  <div><strong>Курс:</strong> <?= htmlspecialchars($o['course']) ?></div>
  <div><strong>Дата начала:</strong> <?= htmlspecialchars($o['start_date']) ?></div>
  <div><strong>Оплата:</strong> <?= htmlspecialchars($o['payment']) ?></div>
  <div><strong>Статус:</strong> <?= htmlspecialchars($o['status']) ?></div>
  <div class="actions">
      <form method="post" action="?action=update&id=<?= $o['id'] ?>" style="display:inline">
          <select name="status">
              <option value="Новая" <?= $o['status']=='Новая'?'selected':'' ?>>Новая</option>
              <option value="Идет обучение" <?= $o['status']=='Идет обучение'?'selected':'' ?>>Идет обучение</option>
              <option value="Обучение завершено" <?= $o['status']=='Обучение завершено'?'selected':'' ?>>Обучение завершено</option>
          </select>
          <button type="submit">Обновить</button>
          <button type="submit" name="delete" formaction="?action=delete&id=<?= $o['id'] ?>" formmethod="post">Удалить</button>
      </form>
  </div>
</div>
<?php endforeach; ?>
</div>
<?php else: ?>
<p class="fade">Заявок нет</p>
<?php endif; ?>
