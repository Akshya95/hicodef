/**
 * HICODEF Partner Carousel — Progressive Enhancement
 * PHP already renders partner cards server-side.
 * This JS clones them for infinite scroll and handles arrows.
 * Zero localStorage. Zero AJAX. All data from the DOM.
 */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    var track  = document.getElementById('hicodef-partner-track');
    var prev   = document.getElementById('hicodef-partner-prev');
    var next   = document.getElementById('hicodef-partner-next');
    var dotsEl = document.getElementById('hicodef-partner-dots');
    var section= document.getElementById('hicodef-partner-carousel');

    if ( ! track ) return;

    var cards   = track.querySelectorAll('.hicodef-partner-card');
    var count   = cards.length;
    if ( count === 0 ) return;

    var CARD_W  = 200;  // px — card width + margin
    var pos     = 0;
    var autoT   = null;

    // Clone cards twice for seamless loop
    var clone1 = track.innerHTML;
    track.innerHTML = clone1 + clone1 + clone1;

    // Dots (one per real card, max 8)
    if ( dotsEl ) {
      var dotCount = Math.min( count, 8 );
      for ( var i = 0; i < dotCount; i++ ) {
        var d = document.createElement('button');
        d.className = 'partner-dot' + (i === 0 ? ' on' : '');
        d.setAttribute('aria-label', 'Partner ' + (i + 1));
        dotsEl.appendChild(d);
      }
    }

    function setPos(p, animate) {
      var total = count * CARD_W;
      // Seamless wrap
      if ( p >= total * 2 ) p -= total;
      if ( p < 0 ) p += total;
      pos = p;
      track.style.transition = animate ? 'transform .5s cubic-bezier(.4,0,.2,1)' : 'none';
      track.style.transform  = 'translateX(-' + pos + 'px)';

      // Update dots
      if ( dotsEl ) {
        var dotIdx = Math.round( pos / CARD_W ) % Math.min(count, 8);
        dotsEl.querySelectorAll('.partner-dot').forEach(function(d, i) {
          d.classList.toggle('on', i === dotIdx);
        });
      }
    }

    function tick() {
      setPos(pos + 1, false);
    }

    function stepBy(dir) {
      stopAuto();
      setPos(pos + dir * CARD_W, true);
      setTimeout(startAuto, 2000);
    }

    function startAuto() {
      stopAuto();
      autoT = setInterval(tick, 28);
    }

    function stopAuto() {
      clearInterval(autoT);
    }

    // Set initial position to the middle clone so we can go both directions
    setPos(count * CARD_W, false);
    startAuto();

    if ( prev ) prev.addEventListener('click', function () { stepBy(-1); });
    if ( next ) next.addEventListener('click', function () { stepBy(1);  });

    if ( section ) {
      section.addEventListener('mouseenter', stopAuto);
      section.addEventListener('mouseleave', startAuto);
    }
  });
})();
