<h2>Мои заявки</h2>
<div id="orders-dynamic">
<?php if ($orders): ?>
<div class="order-list fade">
<?php foreach ($orders as $o): ?>
<div class="order-card">
  <div><strong>ID:</strong> <?= htmlspecialchars($o['id']) ?></div>
  <div><strong>Дата:</strong> <?= htmlspecialchars($o['datetime']) ?></div>
  <div><strong>Адрес:</strong> <?= htmlspecialchars($o['address']) ?></div>
  <div><strong>Контакты:</strong> <?= htmlspecialchars($o['contact']) ?></div>
  <div><strong>Серия водительского удостоверения:</strong> <?= htmlspecialchars($o['license_series']) ?></div>
  <div><strong>Номер водительского удостоверения:</strong> <?= htmlspecialchars($o['license_number']) ?></div>
  <div><strong>Дата выдачи водительского удостоверения:</strong> <?= htmlspecialchars($o['license_issue']) ?></div>
  <div><strong>Марка:</strong> <?= htmlspecialchars($o['car_make']) ?></div>
  <div><strong>Модель:</strong> <?= htmlspecialchars($o['car_model']) ?></div>
  <div><strong>Статус:</strong> <?= htmlspecialchars($o['status']) ?></div>
  <?php if ($o['status'] === 'Отклонено'): ?>
  <div><strong>Причина отказа: <?= htmlspecialchars($o['rejection_reason']) ?> </strong>
  
  </div>
<?php endif; ?>

  </div>
</div>
<?php endforeach; ?>
</div>
<?php else: ?>
<p class="fade">Заявок нет</p>
<?php endif; ?>
</div>
