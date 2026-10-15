/* Animasi scroll + sentuhan interaktif untuk website publik.
   Vanilla JS, tanpa dependensi. Kalau JS mati, konten tetap tampil
   (lihat "jaring pengaman" di <head>). */
(function () {
  var html = document.documentElement;
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* --- target elemen yang di-reveal --- */
  var SELECTOR = [
    '.hero', '.page-title', 'section.sec', 'footer',
    '.grid3 > *', '.grid4 > *', '.steps > *', '.chips > *',
    '.stats > *', '.masonry > *', '.contact > *', '.tech > *', '.prow > *'
  ].join(',');

  var items = Array.prototype.slice.call(document.querySelectorAll(SELECTOR));

  /* --- stagger: beri jeda bertingkat untuk anak grid --- */
  items.forEach(function (el) {
    var parent = el.parentElement;
    if (!parent) return;
    var siblings = Array.prototype.filter.call(parent.children, function (c) {
      return c.matches(SELECTOR);
    });
    var i = siblings.indexOf(el);
    if (i > 0) el.style.setProperty('--d', Math.min(i, 8) * 70 + 'ms');
  });

  /* --- reveal saat masuk viewport --- */
  if (reduce || !('IntersectionObserver' in window)) {
    items.forEach(function (el) { el.classList.add('is-visible'); });
  } else {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          e.target.classList.add('is-visible');
          io.unobserve(e.target);
        }
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

    items.forEach(function (el) { io.observe(el); });
  }

  /* --- bar progres scroll --- */
  var progress = document.createElement('div');
  progress.className = 'scroll-progress';
  document.body.appendChild(progress);

  var nav = document.querySelector('.nav');
  var portrait = document.querySelector('.portrait');

  function render() {
    var max = html.scrollHeight - html.clientHeight;
    var y = window.pageYOffset || html.scrollTop || 0;

    progress.style.width = (max > 0 ? (y / max) * 100 : 0) + '%';

    if (nav) nav.classList.toggle('scrolled', y > 8);

    if (portrait && !reduce && y < 700) {
      portrait.style.transform = 'translateY(' + (y * -0.04) + 'px)';
    }
  }

  var ticking = false;
  window.addEventListener('scroll', function () {
    if (ticking) return;
    ticking = true;
    window.requestAnimationFrame(function () { render(); ticking = false; });
  }, { passive: true });
  window.addEventListener('resize', render);

  render();

  /* tandai site.js sudah jalan -> batalkan jaring pengaman di <head> */
  html.classList.add('reveal-ready');
})();
