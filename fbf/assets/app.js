/* ==========================================================================
   Future Bridge Foundation — Shared App Logic
   Theme (light/dark), language (EN/BN), mobile nav, reveal-on-scroll,
   dynamic card rendering, and a small lightbox. No localStorage is used —
   state lives in memory for the current visit (see README note).
   ========================================================================== */

/* ---------------- Bilingual helper ----------------
   Builds two inline spans; CSS shows only the one matching <html lang="">.
------------------------------------------------------ */
function bi(en, bn) {
  return `<span data-lang="en">${en}</span><span data-lang="bn">${bn}</span>`;
}

/* ---------------- Theme ---------------- */
function initTheme() {
  const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  if (prefersDark) document.documentElement.classList.add('dark');
  document.querySelectorAll('[data-theme-toggle]').forEach(btn => {
    btn.addEventListener('click', () => {
      document.documentElement.classList.toggle('dark');
      syncThemeIcons();
    });
  });
  syncThemeIcons();
}
function syncThemeIcons() {
  const isDark = document.documentElement.classList.contains('dark');
  document.querySelectorAll('[data-theme-icon-sun]').forEach(el => el.classList.toggle('hidden', isDark));
  document.querySelectorAll('[data-theme-icon-moon]').forEach(el => el.classList.toggle('hidden', !isDark));
}

/* ---------------- Language ---------------- */
function initLang() {
  document.documentElement.lang = document.documentElement.lang || 'en';
  applyPlaceholders();
  document.querySelectorAll('[data-lang-toggle]').forEach(btn => {
    btn.addEventListener('click', () => {
      document.documentElement.lang = document.documentElement.lang === 'bn' ? 'en' : 'bn';
      applyPlaceholders();
      syncLangLabels();
    });
  });
  syncLangLabels();
}
function applyPlaceholders() {
  const lang = document.documentElement.lang;
  document.querySelectorAll('[data-ph-en]').forEach(el => {
    el.setAttribute('placeholder', lang === 'bn' ? el.getAttribute('data-ph-bn') : el.getAttribute('data-ph-en'));
  });
}
function syncLangLabels() {
  const isBn = document.documentElement.lang === 'bn';
  document.querySelectorAll('[data-lang-label]').forEach(el => {
    el.textContent = isBn ? 'EN' : 'বাং';
  });
}

/* ---------------- Mobile menu ---------------- */
function initMobileMenu() {
  const btn = document.getElementById('menu-btn');
  const menu = document.getElementById('mobile-menu');
  if (!btn || !menu) return;
  btn.addEventListener('click', () => {
    const open = menu.classList.toggle('open');
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
}

/* ---------------- Scroll reveal ---------------- */
function initReveal() {
  const items = document.querySelectorAll('.fb-reveal');
  if (!items.length) return;
  if (!('IntersectionObserver' in window)) {
    items.forEach(el => el.classList.add('is-visible'));
    return;
  }
  const io = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        io.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12 });
  items.forEach(el => io.observe(el));
}

/* ---------------- Project cards (home + related) ---------------- */
function projectCardHTML(p) {
  return `
  <a href="project.html?id=${p.id}" class="fb-card fb-reveal group block rounded-2xl overflow-hidden bg-[var(--surface)] border border-[var(--border)]">
    <div class="relative h-48 overflow-hidden">
      <img src="${p.cover}" alt="${p.en.title}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
      <span class="absolute top-3 left-3 font-mono text-[11px] uppercase tracking-wider px-2 py-1 rounded-full bg-[var(--surface)]/90 text-[var(--ink)]">${bi('Project', 'প্রকল্প')}</span>
    </div>
    <div class="p-5">
      <h3 class="font-display text-xl font-semibold mb-2">${bi(p.en.title, p.bn.title)}</h3>
      <p class="text-sm text-[var(--ink-soft)] leading-relaxed mb-4">${bi(p.en.short, p.bn.short)}</p>
      <span class="inline-flex items-center gap-1 text-sm font-semibold text-[var(--primary)]">
        ${bi('Read more', 'বিস্তারিত')} <span aria-hidden="true">&rarr;</span>
      </span>
    </div>
  </a>`;
}
function renderProjectCards(containerId, opts = {}) {
  const el = document.getElementById(containerId);
  if (!el) return;
  let list = PROJECTS;
  if (opts.excludeId) list = list.filter(p => p.id !== opts.excludeId);
  if (opts.limit) list = list.slice(0, opts.limit);
  el.innerHTML = list.map(projectCardHTML).join('');
  initReveal();
}

/* ---------------- Footer project links ---------------- */
function renderFooterLinks(containerId) {
  const el = document.getElementById(containerId);
  if (!el) return;
  el.innerHTML = PROJECTS.map(p => `
    <li><a href="project.html?id=${p.id}" class="hover:text-[var(--accent)] transition-colors">${bi(p.en.title, p.bn.title)}</a></li>
  `).join('');
}

/* ---------------- Video cards ---------------- */
function videoCardHTML(v) {
  return `
  <div class="fb-card fb-reveal rounded-2xl overflow-hidden bg-[var(--surface)] border border-[var(--border)]">
    <div class="aspect-video">
      <iframe class="w-full h-full" src="https://www.youtube.com/embed/${v.youtubeId}" title="${v.en.title}" frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
    </div>
    <div class="p-4">
      <h3 class="font-display text-lg font-semibold mb-1">${bi(v.en.title, v.bn.title)}</h3>
      <p class="text-sm text-[var(--ink-soft)]">${bi(v.en.desc, v.bn.desc)}</p>
    </div>
  </div>`;
}
function renderVideos(containerId, opts = {}) {
  const el = document.getElementById(containerId);
  if (!el) return;
  let list = VIDEOS;
  if (opts.limit) list = list.slice(0, opts.limit);
  el.innerHTML = list.map(videoCardHTML).join('');
  initReveal();
}

/* ---------------- Gallery ---------------- */
function galleryItemHTML(g, i) {
  return `
  <button type="button" data-gallery-index="${i}" class="fb-card fb-reveal group relative block w-full rounded-xl overflow-hidden border border-[var(--border)]">
    <img src="${g.img}" alt="${g.en}" class="w-full h-full object-cover aspect-square group-hover:scale-105 transition-transform duration-500">
    <span class="absolute inset-x-0 bottom-0 p-2 text-xs bg-gradient-to-t from-black/70 to-transparent text-white text-left">${bi(g.en, g.bn)}</span>
  </button>`;
}
let _galleryData = [];
function renderGallery(containerId, opts = {}) {
  const el = document.getElementById(containerId);
  if (!el) return;
  let list = GALLERY;
  if (opts.projectId) list = list.filter(g => g.projectId === opts.projectId);
  if (opts.limit) list = list.slice(0, opts.limit);
  _galleryData = list;
  el.innerHTML = list.map(galleryItemHTML).join('');
  el.querySelectorAll('[data-gallery-index]').forEach(btn => {
    btn.addEventListener('click', () => openLightbox(parseInt(btn.getAttribute('data-gallery-index'), 10)));
  });
  initReveal();
}

/* ---------------- Lightbox ---------------- */
let _lightboxIndex = 0;
function ensureLightbox() {
  if (document.getElementById('fb-lightbox')) return;
  const div = document.createElement('div');
  div.id = 'fb-lightbox';
  div.innerHTML = `
    <button type="button" aria-label="Close" data-lb-close class="absolute top-5 right-6 text-white text-3xl leading-none">&times;</button>
    <button type="button" aria-label="Previous" data-lb-prev class="absolute left-3 md:left-8 top-1/2 -translate-y-1/2 text-white text-4xl">&#8249;</button>
    <figure class="text-center">
      <img data-lb-img src="" alt="">
      <figcaption data-lb-caption class="text-white/80 mt-3 text-sm"></figcaption>
    </figure>
    <button type="button" aria-label="Next" data-lb-next class="absolute right-3 md:right-8 top-1/2 -translate-y-1/2 text-white text-4xl">&#8250;</button>
  `;
  document.body.appendChild(div);
  div.querySelector('[data-lb-close]').addEventListener('click', closeLightbox);
  div.addEventListener('click', (e) => { if (e.target === div) closeLightbox(); });
  div.querySelector('[data-lb-prev]').addEventListener('click', () => stepLightbox(-1));
  div.querySelector('[data-lb-next]').addEventListener('click', () => stepLightbox(1));
  document.addEventListener('keydown', (e) => {
    if (!div.classList.contains('open')) return;
    if (e.key === 'Escape') closeLightbox();
    if (e.key === 'ArrowLeft') stepLightbox(-1);
    if (e.key === 'ArrowRight') stepLightbox(1);
  });
}
function openLightbox(index) {
  ensureLightbox();
  _lightboxIndex = index;
  paintLightbox();
  document.getElementById('fb-lightbox').classList.add('open');
}
function stepLightbox(dir) {
  _lightboxIndex = (_lightboxIndex + dir + _galleryData.length) % _galleryData.length;
  paintLightbox();
}
function paintLightbox() {
  const g = _galleryData[_lightboxIndex];
  const box = document.getElementById('fb-lightbox');
  const isBn = document.documentElement.lang === 'bn';
  box.querySelector('[data-lb-img]').src = g.img;
  box.querySelector('[data-lb-img]').alt = isBn ? g.bn : g.en;
  box.querySelector('[data-lb-caption]').textContent = isBn ? g.bn : g.en;
}
function closeLightbox() {
  const box = document.getElementById('fb-lightbox');
  if (box) box.classList.remove('open');
}

/* ---------------- Boot ---------------- */
document.addEventListener('DOMContentLoaded', () => {
  initTheme();
  initLang();
  initMobileMenu();
  initReveal();
});
