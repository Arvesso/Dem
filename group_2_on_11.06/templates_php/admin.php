<?php
// =============================
// templates_php/admin.php
// панель администратора заявок на тест‑драйв
// =============================
?>
<h2 class="fade">Заявки на тест‑драйв</h2>
<form method="get" action="index.php" class="fade">
    <input type="hidden" name="action" value="admin">
    <label>Статус:
        <select name="status">
            <option value="" <?= !$status_filter ? 'selected' : '' ?>>Все</option>
            <option value="Новая" <?= $status_filter == 'Новая' ? 'selected' : '' ?>>Новая</option>
            <option value="Одобрено" <?= $status_filter == 'Одобрено' ? 'selected' : '' ?>>Одобрено</option>
            <option value="Выполнено" <?= $status_filter == 'Выполнено' ? 'selected' : '' ?>>Выполнено</option>
            <option value="Отклонено" <?= $status_filter == 'Отклонено' ? 'selected' : '' ?>>Отклонено</option>
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
        <div><strong>Дата заявки:</strong> <?= htmlspecialchars($o['datetime']) ?></div>
        <div><strong>Адрес:</strong> <?= htmlspecialchars($o['address']) ?></div>
        <div><strong>Контакты:</strong> <?= htmlspecialchars($o['contact']) ?></div>
        <div><strong>ВУ:</strong> <?= htmlspecialchars($o['license_series'] . ' ' . $o['license_number'] . ' от ' . $o['license_issue']) ?></div>
        <div><strong>Автомобиль:</strong> <?= htmlspecialchars($o['car_make'] . ' ' . $o['car_model']) ?></div>
        <div><strong>Оплата:</strong> <?= htmlspecialchars($o['payment_type']) ?></div>
        <div><strong>Статус:</strong> <?= htmlspecialchars($o['status']) ?></div>
        <div class="actions">
        <form method="post" action="?action=update&id=<?= $o['id'] ?>" style="display:inline">
        <select name="status" class="status-select" data-id="<?= $o['id'] ?>">
            <option value="Одобрено" <?= $o['status']=='Одобрено'?'selected':'' ?>>Одобрено</option>
            <option value="Выполнено" <?= $o['status']=='Выполнено'?'selected':'' ?>>Выполнено</option>
            <option value="Отклонено" <?= $o['status']==='Отклонено'?'selected':'' ?>>Отклонено</option>
        </select>

        <div id="reason-<?= $o['id'] ?>"
            style="display: <?= $o['status']==='Отклонено' ? 'block' : 'none' ?>; margin-top:4px;">
            <lable for="rejection_reason-<?= $o['id'] ?>">Причина отказа: <?= htmlspecialchars($o['rejection_reason'] ?? '') ?></lable><br>
            <input id="rejection_reason-<?= $o['id'] ?>"
                    name="rejection_reason"
                    rows="2"
                    style="width:100%;"></input>
        </div>

        <button type="submit">Обновить</button>
            <button type="submit" name="delete" formaction="?action=delete&id=<?= $o['id'] ?>" formmethod="post">Удалить</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>
<script>
  document.querySelectorAll('.status-select').forEach(function(sel) {
    var id = sel.dataset.id;
    var block = document.getElementById('reason-' + id);
    var ta    = document.getElementById('rejection_reason-' + id);
    sel.addEventListener('change', function() {
      if (sel.value === 'Отклонено') {
        block.style.display = 'block';
        ta.required = true;
      } else {
        block.style.display = 'none';
        ta.required = false;
        ta.value = '';
      }
    });
  });
</script>
</div>
<?php else: ?>
<p class="fade">Нет заявок.</p>
<?php endif; ?>