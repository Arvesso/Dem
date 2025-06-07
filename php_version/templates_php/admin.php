<h2 class="fade">Все заявки</h2>
<form method="get" action="index.php" class="fade">
    <input type="hidden" name="action" value="admin">
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
<div class="order-list fade">
<?php foreach ($orders as $o): ?>
<div class="order-card">
  <div><strong>ID:</strong> <?= htmlspecialchars($o['id']) ?></div>
  <div><strong>Пользователь:</strong> <?= htmlspecialchars($o['full_name'] ?? $o['user_id']) ?></div>
  <div><strong>Дата:</strong> <?= htmlspecialchars($o['datetime']) ?></div>
  <div><strong>Вес:</strong> <?= htmlspecialchars($o['weight']) ?></div>
  <div><strong>Габариты:</strong> <?= htmlspecialchars($o['size']) ?></div>
  <div><strong>Тип:</strong> <?= htmlspecialchars($o['cargo_type']) ?></div>
  <div><strong>Откуда:</strong> <?= htmlspecialchars($o['from_addr']) ?></div>
  <div><strong>Куда:</strong> <?= htmlspecialchars($o['to_addr']) ?></div>
  <div><strong>Статус:</strong> <?= htmlspecialchars($o['status']) ?></div>
  <div class="actions">
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
  </div>
</div>
<?php endforeach; ?>
</div>
<?php else: ?>
<p class="fade">Заявок нет</p>
<?php endif; ?>
