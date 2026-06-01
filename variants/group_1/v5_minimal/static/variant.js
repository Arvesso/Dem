/* ==========================================================================
   Вариант V5 «Minimal / Swiss» — дополнительный деликатный интерактив.
   Не дублирует static/app.js (меню, reveal, счётчики, фильтр, слайдер, тосты).
   Group-agnostic: ничего не предполагает о структуре данных, везде guard.
   Уважает prefers-reduced-motion.
   ========================================================================== */
(function () {
  'use strict';

  var reduce = window.matchMedia &&
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* --- Тонкий индикатор прогресса чтения сверху страницы --- */
  (function readingProgress() {
    if (reduce) return;
    if (document.querySelector('.read-progress')) return; // не дублировать

    var bar = document.createElement('div');
    bar.className = 'read-progress';
    bar.setAttribute('aria-hidden', 'true');
    if (!document.body) return;
    document.body.appendChild(bar);

    var ticking = false;
    var update = function () {
      var doc = document.documentElement;
      var scrollable = (doc.scrollHeight - doc.clientHeight) || 1;
      var ratio = Math.min(Math.max(window.scrollY / scrollable, 0), 1);
      bar.style.width = (ratio * 100).toFixed(2) + '%';
      ticking = false;
    };
    var onScroll = function () {
      if (!ticking) { window.requestAnimationFrame(update); ticking = true; }
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
    update();
  })();

  /* --- Деликатное подчёркивание-«типографика» на интерактивных карточках ---
     Лёгкий hover-эффект: тонкая линия под заголовком карточки появляется
     плавно. Чисто презентационно, только если карточки есть на странице.   */
  (function cardHairline() {
    if (reduce) return;
    var cards = document.querySelectorAll('#catalogGrid .card .card-body h3');
    if (!cards.length) return;

    cards.forEach(function (h) {
      if (h.dataset.v5hl) return; // guard от повторной инициализации
      h.dataset.v5hl = '1';
      h.style.transition = 'letter-spacing .3s ease';
      var card = h.closest('.card');
      if (!card) return;
      card.addEventListener('mouseenter', function () { h.style.letterSpacing = '.005em'; });
      card.addEventListener('mouseleave', function () { h.style.letterSpacing = ''; });
    });
  })();

})();
