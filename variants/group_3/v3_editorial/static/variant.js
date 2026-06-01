/* ==========================================================================
   Вариант 3 «Editorial / Big Type» — дополнительный интерактив.
   Только то, чего НЕТ в app.js: построчное появление заголовков и лёгкий
   параллакс изображений каталога. Group-agnostic, guard на каждый селектор,
   уважает prefers-reduced-motion. НЕ дублирует slider/modals/toasts/chips/
   counters/date-mask/reveal-наблюдатель из app.js.
   ========================================================================== */
(function () {
  'use strict';

  var reduce = window.matchMedia &&
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (reduce) return; // оставляем статичный, доступный вид

  /* --- Построчное появление крупных заголовков ([data-lines]) --- */
  var heads = document.querySelectorAll('[data-lines]');
  if (heads.length) {
    heads.forEach(function (el) {
      if (el.dataset.linesReady) return;
      var words = (el.textContent || '').split(/\s+/).filter(Boolean);
      if (!words.length) return;
      el.textContent = '';
      words.forEach(function (w, i) {
        var span = document.createElement('span');
        span.className = 'ed-word';
        span.textContent = w;
        span.style.transitionDelay = (i * 55) + 'ms';
        el.appendChild(span);
        el.appendChild(document.createTextNode(' '));
      });
      el.dataset.linesReady = '1';
    });

    if ('IntersectionObserver' in window) {
      var hio = new IntersectionObserver(function (entries) {
        entries.forEach(function (en) {
          if (en.isIntersecting) {
            en.target.classList.add('ed-words-in');
            hio.unobserve(en.target);
          }
        });
      }, { threshold: 0.2 });
      heads.forEach(function (el) { hio.observe(el); });
    } else {
      heads.forEach(function (el) { el.classList.add('ed-words-in'); });
    }
  }

  /* --- Лёгкий параллакс изображений каталога ([data-parallax]) --- */
  var media = document.querySelectorAll('[data-parallax] img');
  if (media.length) {
    var ticking = false;
    var update = function () {
      var vh = window.innerHeight || document.documentElement.clientHeight;
      media.forEach(function (img) {
        var box = img.parentElement;
        if (!box) return;
        var rect = box.getBoundingClientRect();
        if (rect.bottom < 0 || rect.top > vh) return;
        var progress = (rect.top + rect.height / 2 - vh / 2) / vh; // -0.5..0.5
        var shift = Math.max(-14, Math.min(14, progress * -22));
        img.style.transform = 'scale(1.12) translate3d(0,' + shift.toFixed(2) + 'px,0)';
      });
      ticking = false;
    };
    var onScroll = function () {
      if (!ticking) { ticking = true; window.requestAnimationFrame(update); }
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
    update();
  }
})();
