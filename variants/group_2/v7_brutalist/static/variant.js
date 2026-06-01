/* ==========================================================================
   Вариант V7 «Neo-Brutalist» — дополнительный интерактив.
   Только то, чего НЕТ в app.js: бесконечная бегущая строка (marquee),
   «дребезг»-наклон карточек на hover (res- курсор) и глитч-бейдж.
   Group-agnostic, guard на каждый селектор, уважает prefers-reduced-motion.
   НЕ дублирует slider/modals/toasts/chips/counters/reveal/burger из app.js.
   ========================================================================== */
(function () {
  'use strict';

  var reduce = window.matchMedia &&
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* --- Бесконечная бегущая строка ([data-marquee]) ---
     Дублируем содержимое и гоняем CSS-анимацию на -50%, чтобы шов был незаметен. */
  var marquees = document.querySelectorAll('[data-marquee]');
  marquees.forEach(function (box) {
    var track = box.querySelector('.nb-marquee-track');
    if (!track || track.dataset.nbReady) return;
    var base = track.innerHTML;
    // Достаточно копий, чтобы перекрыть ширину вьюпорта минимум вдвое.
    track.innerHTML = base + base + base + base;
    track.dataset.nbReady = '1';
    if (reduce) return;
    var px = track.scrollWidth / 2; // половина — это точка бесшовного повтора
    var speed = 60; // px/сек
    var dur = Math.max(8, px / speed);
    track.style.animation = 'nb-scroll ' + dur.toFixed(1) + 's linear infinite';
  });

  if (reduce) return; // дальше — только декоративные эффекты движения

  /* --- «Дребезг»-наклон карточек ([data-tilt]) ---
     Лёгкий тильт по положению курсора, резкий возврат. Без перегруза: rAF-троттл. */
  var tilts = document.querySelectorAll('[data-tilt]');
  if (tilts.length && window.matchMedia('(hover: hover)').matches) {
    tilts.forEach(function (el) {
      var raf = 0;
      var apply = function (rx, ry) {
        el.style.transform = 'translate(-4px,-4px) perspective(700px) rotateX(' +
          rx.toFixed(2) + 'deg) rotateY(' + ry.toFixed(2) + 'deg)';
      };
      el.addEventListener('mousemove', function (e) {
        var r = el.getBoundingClientRect();
        var px = (e.clientX - r.left) / r.width - 0.5;  // -0.5..0.5
        var py = (e.clientY - r.top) / r.height - 0.5;
        if (raf) return;
        raf = window.requestAnimationFrame(function () {
          raf = 0;
          apply(-py * 5, px * 5);
        });
      });
      el.addEventListener('mouseleave', function () {
        if (raf) { window.cancelAnimationFrame(raf); raf = 0; }
        el.style.transform = '';
      });
    });
  }

  /* --- Глитч-бейдж ([data-glitch]) ---
     Кратковременный «щелчок»-сдвиг при наведении. Текст берём из содержимого. */
  var glitches = document.querySelectorAll('[data-glitch]');
  glitches.forEach(function (el) {
    if (!el.getAttribute('data-glitch-text')) {
      el.setAttribute('data-glitch-text', (el.textContent || '').trim());
    }
    var timer = 0;
    el.addEventListener('mouseenter', function () {
      el.classList.add('nb-glitch-on');
      window.clearTimeout(timer);
      timer = window.setTimeout(function () { el.classList.remove('nb-glitch-on'); }, 280);
    });
  });
})();
