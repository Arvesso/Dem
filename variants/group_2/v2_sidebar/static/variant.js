/* ==========================================================================
   Вариант V2 «Sidebar Dashboard» — дополнительный интерактив.
   Не дублирует app.js (бургер, reveal, счётчики, чипы, слайдер, модалки,
   тосты, маску даты, шапку, кнопку «наверх»). Group-agnostic, с guard'ами.
   ========================================================================== */
(function () {
  'use strict';

  var reduce = window.matchMedia &&
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* --- Scrollspy: подсветка активного пункта бокового меню --- */
  var nav = document.getElementById('mainNav');
  if (nav) {
    var links = Array.prototype.slice.call(nav.querySelectorAll('a[href*="#"]'));
    var map = [];
    links.forEach(function (a) {
      var hash = (a.getAttribute('href') || '').split('#')[1];
      if (!hash) return;
      var sec = document.getElementById(hash);
      if (sec) map.push({ link: a, sec: sec });
    });

    if (map.length && 'IntersectionObserver' in window) {
      var setActive = function (link) {
        map.forEach(function (m) { m.link.classList.remove('is-active'); });
        if (link) link.classList.add('is-active');
      };
      var spy = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
          if (en.isIntersecting) {
            var hit = map.filter(function (m) { return m.sec === en.target; })[0];
            if (hit) setActive(hit.link);
          }
        });
      }, { rootMargin: '-45% 0px -50% 0px', threshold: 0 });
      map.forEach(function (m) { spy.observe(m.sec); });
    }
  }

  /* --- Лёгкий pointer-glow на дашборд-панелях (только если есть мышь) --- */
  if (!reduce && window.matchMedia && window.matchMedia('(pointer: fine)').matches) {
    var panels = document.querySelectorAll('.v2-metric, .card, .hero-inner');
    Array.prototype.forEach.call(panels, function (el) {
      el.addEventListener('pointermove', function (e) {
        var r = el.getBoundingClientRect();
        if (!r.width || !r.height) return;
        el.style.setProperty('--mx', ((e.clientX - r.left) / r.width * 100) + '%');
        el.style.setProperty('--my', ((e.clientY - r.top) / r.height * 100) + '%');
      });
    });
  }

  /* --- Плавный скролл по якорям бокового меню без скачка хэша --- */
  if (!reduce) {
    document.querySelectorAll('a[href*="index.php#"], a[href^="#"]').forEach(function (a) {
      a.addEventListener('click', function (e) {
        var hash = (a.getAttribute('href') || '').split('#')[1];
        if (!hash) return;
        var target = document.getElementById(hash);
        if (!target) return;
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });
  }
})();
