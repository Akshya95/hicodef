/**
 * HICODEF Slider — Progressive Enhancement
 * PHP already renders slides server-side.
 * This JS only adds: transitions, arrows, dots, autoplay, counter.
 * All data comes from the DOM — zero localStorage, zero AJAX.
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var slides  = document.querySelectorAll('.hicodef-slide');
    var dotsEl  = document.getElementById('hicodef-slider-dots');
    var counterEl = document.getElementById('hicodef-slider-counter');
    var prev    = document.getElementById('hicodef-slider-prev');
    var next    = document.getElementById('hicodef-slider-next');

    if ( ! slides.length ) return;

    var cur   = 0;
    var total = slides.length;
    var timer = null;

    function goTo(n) {
      slides[cur].classList.remove('active');
      cur = ( n + total ) % total;
      slides[cur].classList.add('active');
      updateUI();
    }

    function updateUI() {
      if ( counterEl ) counterEl.textContent = total > 1 ? (cur + 1) + ' / ' + total : '';
      if ( dotsEl ) {
        dotsEl.querySelectorAll('.slider-dot').forEach(function (d, i) {
          d.classList.toggle('on', i === cur);
        });
      }
    }

    function resetTimer() {
      clearInterval(timer);
      if ( total > 1 ) timer = setInterval(function () { goTo(cur + 1); }, 5500);
    }

    // Build dots
    if ( dotsEl && total > 1 ) {
      for (var i = 0; i < total; i++) {
        (function(idx){
          var btn = document.createElement('button');
          btn.className = 'slider-dot' + (idx === 0 ? ' on' : '');
          btn.setAttribute('aria-label', 'Slide ' + (idx + 1));
          btn.addEventListener('click', function () { goTo(idx); resetTimer(); });
          dotsEl.appendChild(btn);
        })(i);
      }
    }

    // Arrows
    if ( prev ) prev.addEventListener('click', function () { goTo(cur - 1); resetTimer(); });
    if ( next ) next.addEventListener('click', function () { goTo(cur + 1); resetTimer(); });

    // Keyboard
    document.addEventListener('keydown', function (e) {
      if ( e.key === 'ArrowLeft'  ) { goTo(cur - 1); resetTimer(); }
      if ( e.key === 'ArrowRight' ) { goTo(cur + 1); resetTimer(); }
    });

    // Pause on hover
    var wrap = document.getElementById('hicodef-slider-section');
    if ( wrap ) {
      wrap.addEventListener('mouseenter', function () { clearInterval(timer); });
      wrap.addEventListener('mouseleave', resetTimer);
    }

    updateUI();
    resetTimer();
  });
})();
