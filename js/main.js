/**
 * Compassion NGO Theme - main.js
 */
(function () {
  'use strict';

  // -------------------------------------------------------
  // Mobile Menu Toggle
  // -------------------------------------------------------
  const toggle = document.querySelector('.menu-toggle');
  const nav    = document.querySelector('.main-navigation');

  if (toggle && nav) {
    toggle.addEventListener('click', function () {
      const expanded = this.getAttribute('aria-expanded') === 'true';
      this.setAttribute('aria-expanded', String(!expanded));
      nav.classList.toggle('is-open');
    });

    // Close on outside click
    document.addEventListener('click', function (e) {
      if (!nav.contains(e.target) && !toggle.contains(e.target)) {
        nav.classList.remove('is-open');
        toggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // -------------------------------------------------------
  // Sticky Header Shadow
  // -------------------------------------------------------
  const header = document.querySelector('.site-header');
  if (header) {
    const onScroll = () => {
      header.style.boxShadow = window.scrollY > 10
        ? '0 2px 16px rgba(0,0,0,0.08)'
        : 'none';
    };
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  // -------------------------------------------------------
  // Animate Stats on Scroll (Intersection Observer)
  // -------------------------------------------------------
  const statsBar = document.querySelector('.stats-bar');
  if (statsBar) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          animateStats();
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.3 });
    observer.observe(statsBar);
  }

  function animateStats() {
    document.querySelectorAll('.stat-number').forEach(el => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(12px)';
      el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
      // Stagger
      setTimeout(() => {
        el.style.opacity = '1';
        el.style.transform = 'translateY(0)';
      }, 100 * [...document.querySelectorAll('.stat-number')].indexOf(el));
    });
  }

  // -------------------------------------------------------
  // Smooth Reveal on Scroll
  // -------------------------------------------------------
  const revealEls = document.querySelectorAll('.card, .program-card, .team-card, .post');

  if (revealEls.length && 'IntersectionObserver' in window) {
    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });

    revealEls.forEach((el, i) => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(20px)';
      el.style.transition = `opacity 0.45s ease ${i * 0.07}s, transform 0.45s ease ${i * 0.07}s`;
      revealObserver.observe(el);
    });
  }

  // -------------------------------------------------------
  // Progress Bar Animate
  // -------------------------------------------------------
  document.querySelectorAll('.progress-fill').forEach(fill => {
    const target = fill.style.width;
    fill.style.width = '0';
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          fill.style.transition = 'width 1s ease';
          fill.style.width = target;
          obs.unobserve(e.target);
        }
      });
    }, { threshold: 0.5 });
    obs.observe(fill);
  });

})();

// ── Pill Header: scroll shadow ──
(function () {
  var wrap = document.querySelector('.site-header-wrap');
  if (!wrap) return;
  window.addEventListener('scroll', function () {
    wrap.classList.toggle('scrolled', window.scrollY > 12);
  }, { passive: true });
})();

// ── Pill Header: mobile toggle ──
(function () {
  var btn = document.getElementById('mobile-toggle');
  var nav = document.querySelector('.header-nav');
  if (!btn || !nav) return;

  btn.addEventListener('click', function () {
    var expanded = this.getAttribute('aria-expanded') === 'true';
    this.setAttribute('aria-expanded', String(!expanded));
    nav.classList.toggle('is-open', !expanded);
  });

  // Close on outside click
  document.addEventListener('click', function (e) {
    if (!nav.contains(e.target) && !btn.contains(e.target)) {
      nav.classList.remove('is-open');
      btn.setAttribute('aria-expanded', 'false');
    }
  });
})();

// ── Pill Header: search toggle ──
(function () {
  var btn = document.getElementById('header-search-toggle');
  var bar = document.getElementById('header-search-bar');
  if (!btn || !bar) return;

  btn.addEventListener('click', function () {
    var hidden = bar.hasAttribute('hidden');
    if (hidden) {
      bar.removeAttribute('hidden');
      bar.querySelector('input') && bar.querySelector('input').focus();
    } else {
      bar.setAttribute('hidden', '');
    }
  });
})();
