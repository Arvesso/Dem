/* ==========================================================================
   Вариант V8 «Scroll-Snap / Magazine» — дополнительный интерактив.
   ТОЛЬКО V8-специфика: прогресс скролла обёртки лендинга, активная точка
   секции, стрелки горизонтального скроллера каталога.
   Базовый функционал (бургер, reveal, счётчики, фильтр-чипы, слайдер,
   модалки, тосты, маска даты, кнопка «наверх») — в app.js, НЕ дублируем.
   IIFE, group-agnostic, guard на каждом шаге, уважает prefers-reduced-motion.
   ========================================================================== */
(function () {
  'use strict';

  var deck = document.getElementById('v8Deck');
  // Если обёртки лендинга нет (внутренняя страница) — ничего не делаем.
  if (!deck) return;

  var reduce = window.matchMedia &&
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* --- Прогресс скролла лендинг-обёртки --- */
  var progressBar = (function () {
    var wrap = document.getElementById('v8Progress');
    return wrap ? wrap.querySelector('span') : null;
  })();

  /* --- Точки-навигация секций --- */
  var dots = Array.prototype.slice.call(document.querySelectorAll('#v8Dots .v8-dot'));
  var sections = dots
    .map(function (d) {
      var id = d.getAttribute('data-target');
      return id ? document.getElementById(id) : null;
    })
    .filter(Boolean);

  // Плавный переход к секции по клику на точку (скроллим ОБЁРТКУ, не window).
  dots.forEach(function (dot) {
    dot.addEventListener('click', function (ev) {
      var id = dot.getAttribute('data-target');
      var target = id ? document.getElementById(id) : null;
      if (!target) return;
      ev.preventDefault();
      try {
        deck.scrollTo({ top: target.offsetTop, behavior: reduce ? 'auto' : 'smooth' });
      } catch (e) {
        deck.scrollTop = target.offsetTop;
      }
    });
  });

  var setActiveDot = function (idx) {
    dots.forEach(function (d, i) { d.classList.toggle('is-active', i === idx); });
  };

  var updateProgress = function () {
    if (!progressBar) return;
    var max = deck.scrollHeight - deck.clientHeight;
    var ratio = max > 0 ? deck.scrollTop / max : 0;
    progressBar.style.height = (Math.max(0, Math.min(1, ratio)) * 100) + '%';
  };

  // Активная точка: наиболее видимая секция в обёртке.
  var io = null;
  if ('IntersectionObserver' in window && sections.length) {
    var visible = {};
    io = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) {
        var i = sections.indexOf(en.target);
        if (i < 0) return;
        visible[i] = en.isIntersecting ? en.intersectionRatio : 0;
      });
      var best = -1, bestRatio = 0;
      Object.keys(visible).forEach(function (k) {
        if (visible[k] > bestRatio) { bestRatio = visible[k]; best = +k; }
      });
      if (best >= 0) setActiveDot(best);
    }, { root: deck, threshold: [0.25, 0.5, 0.75] });
    sections.forEach(function (s) { io.observe(s); });
  }

  // Прогресс — на скролл обёртки (rAF-throttle, без дубля логики app.js).
  var ticking = false;
  deck.addEventListener('scroll', function () {
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(function () {
      updateProgress();
      ticking = false;
    });
  }, { passive: true });
  updateProgress();
  setActiveDot(0);

  /* --- Стрелки горизонтального скроллера каталога --- */
  var rail = document.getElementById('catalogGrid');
  var prevBtn = document.getElementById('v8RailPrev');
  var nextBtn = document.getElementById('v8RailNext');

  if (rail && (prevBtn || nextBtn)) {
    var stepBy = function () {
      // Шаг ≈ ширина одной видимой карточки + gap.
      var first = rail.querySelector('.card:not(.hide)');
      if (first) {
        var styles = window.getComputedStyle(rail);
        var gap = parseFloat(styles.columnGap || styles.gap || '0') || 0;
        return first.getBoundingClientRect().width + gap;
      }
      return Math.round(rail.clientWidth * 0.8);
    };

    var scrollRail = function (dir) {
      try {
        rail.scrollBy({ left: dir * stepBy(), behavior: reduce ? 'auto' : 'smooth' });
      } catch (e) {
        rail.scrollLeft += dir * stepBy();
      }
    };

    if (prevBtn) prevBtn.addEventListener('click', function () { scrollRail(-1); });
    if (nextBtn) nextBtn.addEventListener('click', function () { scrollRail(1); });

    // Деактивация стрелок на краях.
    var syncRailBtns = function () {
      var maxLeft = rail.scrollWidth - rail.clientWidth - 1;
      var scrollable = maxLeft > 2;
      if (prevBtn) prevBtn.disabled = !scrollable || rail.scrollLeft <= 1;
      if (nextBtn) nextBtn.disabled = !scrollable || rail.scrollLeft >= maxLeft;
    };

    var railTicking = false;
    rail.addEventListener('scroll', function () {
      if (railTicking) return;
      railTicking = true;
      window.requestAnimationFrame(function () {
        syncRailBtns();
        railTicking = false;
      });
    }, { passive: true });

    if ('ResizeObserver' in window) {
      var ro = new ResizeObserver(syncRailBtns);
      ro.observe(rail);
    } else {
      window.addEventListener('resize', syncRailBtns, { passive: true });
    }

    // Фильтр-чипы управляются app.js; после клика — пересчитать края стрелок.
    var chips = document.querySelectorAll('#catFilter .chip');
    chips.forEach(function (c) {
      c.addEventListener('click', function () {
        window.requestAnimationFrame(syncRailBtns);
      });
    });

    syncRailBtns();
  }
})();
