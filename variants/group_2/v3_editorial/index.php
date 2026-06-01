<?php
require __DIR__ . '/bootstrap.php';

$items = pdo()->query("SELECT * FROM items ORDER BY category, id")->fetchAll();

// Несколько последних отзывов для витрины
$reviews = pdo()->query(
    "SELECT r.rating, r.comment, u.fio, i.title
       FROM reviews r
       JOIN users u   ON u.id = r.user_id
       JOIN requests q ON q.id = r.request_id
       JOIN items i   ON i.id = q.item_id
      ORDER BY r.id DESC LIMIT 6"
)->fetchAll();

$totalRequests = (int) pdo()->query("SELECT COUNT(*) FROM requests")->fetchColumn();
$totalUsers    = (int) pdo()->query("SELECT COUNT(*) FROM users")->fetchColumn();

$PAGE_TITLE = 'Главная';
require __DIR__ . '/includes/header.php';
?>

<!-- ===== HERO / EDITORIAL MASTHEAD ===== -->
<section class="hero ed-hero">
  <div class="hero-bg"></div>
  <div class="container hero-inner">
    <div class="hero-text reveal">
      <span class="hero-badge ed-kicker">✦ <?= e($CONFIG['tagline']) ?></span>
      <h1 class="ed-display" data-lines><?= e($CONFIG['hero_title']) ?></h1>
    </div>

    <aside class="hero-card ed-hero-aside reveal">
      <div class="hero-card-glow"></div>
      <p class="ed-lede"><?= e($CONFIG['hero_text']) ?></p>
      <div class="hero-actions">
        <a href="<?= current_user() ? 'booking.php' : 'register.php' ?>" class="btn btn-primary btn-lg"><?= e($CONFIG['cta']) ?></a>
        <a href="#catalog" class="btn btn-ghost btn-lg">Смотреть каталог</a>
      </div>
      <div class="mini-booking ed-meta-card">
        <div class="mini-row"><span>📍 Локация</span><b>Москва, центр</b></div>
        <div class="mini-row"><span>🏷️ Категории</span><b><?= e(implode(' · ', $CONFIG['categories'])) ?></b></div>
        <div class="mini-row"><span>💳 Оплата</span><b>QR · МИР · офис</b></div>
      </div>
    </aside>
  </div>

  <div class="container">
    <div class="hero-stats ed-figures">
      <div><b data-count="<?= max(6, count($items)) ?>">0</b><span><?= e($CONFIG['stat_items_label']) ?></span></div>
      <div><b data-count="<?= max(120, $totalRequests) ?>">0</b><span><?= e($CONFIG['stat_requests_label']) ?></span></div>
      <div><b data-count="<?= max(340, $totalUsers) ?>">0</b><span><?= e($CONFIG['stat_users_label']) ?></span></div>
    </div>
  </div>
</section>

<!-- ===== КАТАЛОГ ===== -->
<section class="section ed-section" id="catalog">
  <div class="container">
    <div class="section-head ed-head reveal">
      <span class="ed-index">01</span>
      <div class="ed-head-body">
        <h2 class="ed-headline" data-lines><?= e($CONFIG['catalog_title']) ?></h2>
        <p><?= e($CONFIG['catalog_lead']) ?></p>
      </div>
    </div>

    <div class="chips ed-chips reveal" id="catFilter">
      <button class="chip active" data-cat="all">Все</button>
      <?php foreach ($CONFIG['categories'] as $cat): ?>
        <button class="chip" data-cat="<?= e($cat) ?>"><?= e($cat) ?></button>
      <?php endforeach; ?>
    </div>

    <div class="cards-grid ed-grid" id="catalogGrid">
      <?php foreach ($items as $it): ?>
        <article class="card ed-card reveal" data-cat="<?= e($it['category']) ?>">
          <div class="card-media ed-media" data-parallax>
            <img src="static/img/<?= e($it['image']) ?>" alt="<?= e($it['title']) ?>" loading="lazy">
            <span class="card-tag ed-kicker-tag"><?= e($it['category']) ?></span>
          </div>
          <div class="card-body">
            <h3><?= e($it['title']) ?></h3>
            <p><?= e($it['description']) ?></p>
            <div class="card-meta">
              <span>👥 до <?= (int)$it['capacity'] ?> <?= e($it['meta']) ?></span>
              <span class="card-price"><?= money((int)$it['price']) ?> <small><?= e($CONFIG['price_unit']) ?></small></span>
            </div>
            <a href="<?= current_user() ? 'booking.php?item=' . (int)$it['id'] : 'register.php' ?>" class="btn btn-outline btn-block">Выбрать</a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== КАК ЭТО РАБОТАЕТ ===== -->
<section class="section section-alt ed-section" id="how">
  <div class="container">
    <div class="section-head ed-head reveal">
      <span class="ed-index">02</span>
      <div class="ed-head-body">
        <h2 class="ed-headline" data-lines>Как это работает</h2>
        <p><?= e($CONFIG['how_lead']) ?></p>
      </div>
    </div>
    <div class="steps ed-steps">
      <?php foreach ($CONFIG['steps'] as $s): ?>
        <div class="step ed-step reveal">
          <div class="step-num"><?= $s[0] ?></div>
          <h3><?= e($s[1]) ?></h3>
          <p><?= e($s[2]) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== ОТЗЫВЫ ===== -->
<?php if ($reviews): ?>
<section class="section ed-section" id="reviews">
  <div class="container">
    <div class="section-head ed-head reveal">
      <span class="ed-index">03</span>
      <div class="ed-head-body">
        <h2 class="ed-headline" data-lines>Отзывы клиентов</h2>
        <p><?= e($CONFIG['reviews_lead']) ?></p>
      </div>
    </div>
    <div class="reviews-grid ed-reviews">
      <?php foreach ($reviews as $rv): ?>
        <figure class="review ed-quote reveal">
          <div class="stars"><?= str_repeat('★', (int)$rv['rating']) . str_repeat('☆', 5 - (int)$rv['rating']) ?></div>
          <blockquote><?= e($rv['comment']) ?></blockquote>
          <figcaption><b><?= e($rv['fio']) ?></b><span><?= e($rv['title']) ?></span></figcaption>
        </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===== CTA ===== -->
<section class="cta-band ed-cta reveal">
  <div class="container cta-inner">
    <div>
      <h2><?= e($CONFIG['cta_title']) ?></h2>
      <p><?= e($CONFIG['cta_text']) ?></p>
    </div>
    <a href="<?= current_user() ? 'booking.php' : 'register.php' ?>" class="btn btn-light btn-lg"><?= e($CONFIG['cta']) ?></a>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
