// Phase 20 checks: dark/light theme, scroll progress bar, scroll reveal, header over the hero, photo bands.
//   shots       — Home, What We Do, a ministry page, Our Impact, About, Donate: light + dark × 390 + 1440
//                 (full page, reveal blocks shown) → out/phase20/shots
//   theme       — the saved choice applies on the next page load and on the FIRST frame (no flash): checked from a
//                 requestAnimationFrame registered before any page script, both ways (device dark + saved light,
//                 device light + saved dark); the toggle flips data-theme, aria-pressed, aria-label and the saved value;
//                 the inline theme script comes before the first stylesheet in <head>
//   progress    — scaleX 0 at the top, 1 at the bottom; hidden on a page too short to scroll; under the admin bar
//                 when logged in (32px at 1440, 46px at 390) — needs WP_USER / WP_PASS, else skipped
//   reveal      — nothing hidden with JavaScript off or with reduced motion; nothing on the first screen hidden;
//                 nothing left hidden after scrolling to the bottom
//   header      — transparent at the top of hero pages, frosted after 80px; nav text contrast measured on the pixels
//                 behind it (text hidden for the shot) in both states and both themes
//   bands       — photo bands get their photo only near the screen; the photo file is ≤ 250 KB
//   page health — no horizontal scroll, no console errors, CLS < 0.1 (whole page scrolled), per page/width/theme
// usage: node phase20_check.js [outfile.json]   env: WP_URL (default http://127.0.0.1:8899), CHROME_PATH, WP_USER, WP_PASS
const fs = require('fs');
const path = require('path');
const { chromium } = require('playwright-core');
const { PNG } = require('pngjs');
const B = (process.env.WP_URL || 'http://127.0.0.1:8899').replace(/\/$/, '');
const OUT = process.argv[2] || path.join(__dirname, 'out', 'phase20.json');
const SHOTS = path.join(path.dirname(OUT), 'phase20', 'shots');
const PAGES = ['/', '/what-we-do/', '/what-we-do/bible-distribution/', '/our-impact/', '/about/', '/donate/'];
const HERO_PAGES = ['/', '/what-we-do/bible-distribution/', '/our-impact/', '/about/', '/donate/'];
const results = { pages: {}, theme: {}, progress: {}, reveal: {}, header: {}, bands: {}, failures: [] };
const fail = (area, msg) => { results.failures.push(area + ': ' + msg); console.error('FAIL', area, msg); };

const lum = (r, g, b) => { const f = (c) => { c /= 255; return c <= 0.03928 ? c / 12.92 : Math.pow((c + 0.055) / 1.055, 2.4); }; return 0.2126 * f(r) + 0.7152 * f(g) + 0.0722 * f(b); };
const ratio = (a, b) => (Math.max(a, b) + 0.05) / (Math.min(a, b) + 0.05);
const rgb = (s) => (s.match(/[\d.]+/g) || [0, 0, 0]).slice(0, 3).map(Number);

async function context(b, theme, w, extra = {}) {
  const ctx = await b.newContext({ viewport: { width: w, height: w < 600 ? 844 : 900 }, colorScheme: theme === 'dark' ? 'dark' : 'light', ...extra });
  await ctx.addInitScript((t) => {
    try {
      localStorage.setItem('krm-newsletter', JSON.stringify({ dismissed: Date.now() }));
      if (t) localStorage.setItem('krm-theme', t);
    } catch (e) {}
  }, theme);
  return ctx;
}
async function scrollThrough(p) {
  await p.evaluate(async () => {
    for (let y = 0; y <= document.documentElement.scrollHeight; y += Math.round(innerHeight * 0.6)) { scrollTo(0, y); await new Promise((r) => setTimeout(r, 90)); }
    // The page can grow while it scrolls (lazy images, content): keep going until the real bottom is reached.
    for (let i = 0; i < 8 && innerHeight + scrollY < document.documentElement.scrollHeight - 2; i++) { scrollTo(0, document.documentElement.scrollHeight); await new Promise((r) => setTimeout(r, 300)); }
    await new Promise((r) => setTimeout(r, 300));
  });
}

(async () => {
  const b = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });
  fs.mkdirSync(SHOTS, { recursive: true });

  // ---------- page health + screenshots
  for (const theme of ['light', 'dark']) {
    for (const w of [390, 1440]) {
      const ctx = await context(b, theme, w);
      await ctx.addInitScript(() => {
        window.__cls = 0;
        try { new PerformanceObserver((l) => { for (const e of l.getEntries()) if (!e.hadRecentInput) window.__cls += e.value; }).observe({ type: 'layout-shift', buffered: true }); } catch (e) {}
      });
      for (const u of PAGES) {
        const p = await ctx.newPage();
        const errors = [];
        p.on('pageerror', (e) => errors.push(e.message));
        p.on('console', (m) => { if (m.type() === 'error') errors.push(m.text()); });
        await p.goto(B + u, { waitUntil: 'load' });
        await p.waitForTimeout(300);
        await scrollThrough(p);
        await p.waitForTimeout(800);
        const r = await p.evaluate(() => ({
          theme: document.documentElement.getAttribute('data-theme'),
          overflow: document.documentElement.scrollWidth - innerWidth,
          cls: Math.round(window.__cls * 1000) / 1000,
          pendingAfterScroll: document.querySelectorAll('.reveal-pending').length,
        }));
        r.errors = errors;
        await p.evaluate(() => scrollTo(0, 0));
        await p.waitForTimeout(400);
        await p.screenshot({ path: path.join(SHOTS, (u.replace(/\//g, '_').replace(/^_|_$/g, '') || 'home') + '-' + theme + '-' + w + '.png'), fullPage: true });
        results.pages[u + ' ' + theme + ' ' + w] = r;
        if (r.theme !== theme) fail('theme', `${u} ${w}: data-theme ${r.theme}, expected ${theme}`);
        if (r.overflow > 0) fail('overflow', `${u} ${theme} ${w}: ${r.overflow}px`);
        if (r.cls >= 0.1) fail('cls', `${u} ${theme} ${w}: ${r.cls}`);
        if (errors.length) fail('console', `${u} ${theme} ${w}: ${errors.join(' | ')}`);
        if (r.pendingAfterScroll) fail('reveal', `${u} ${theme} ${w}: ${r.pendingAfterScroll} blocks still hidden after scrolling to the bottom`);
        console.error(u, theme, w, JSON.stringify(r));
        await p.close();
      }
      await ctx.close();
    }
  }

  // ---------- theme: first frame, persistence, toggle
  {
    const firstFrame = () => {
      window.__first = null;
      requestAnimationFrame(() => {
        const h = document.documentElement;
        window.__first = { theme: h.getAttribute('data-theme'), bg: getComputedStyle(document.body || h).backgroundColor };
      });
    };
    for (const [device, saved] of [['dark', 'light'], ['light', 'dark']]) {
      const ctx = await b.newContext({ viewport: { width: 1440, height: 900 }, colorScheme: device });
      await ctx.addInitScript((t) => { try { localStorage.setItem('krm-newsletter', JSON.stringify({ dismissed: Date.now() })); localStorage.setItem('krm-theme', t); } catch (e) {} }, saved);
      await ctx.addInitScript(firstFrame);
      const p = await ctx.newPage();
      const out = [];
      for (const u of ['/about/', '/donate/']) {
        await p.goto(B + u, { waitUntil: 'load' });
        await p.waitForTimeout(200);
        out.push(await p.evaluate(() => window.__first));
      }
      const want = saved === 'dark' ? [10, 18, 48] : [255, 255, 255];
      results.theme['device ' + device + ', saved ' + saved] = out;
      out.forEach((f, i) => {
        const got = f && rgb(f.bg);
        if (!f || f.theme !== saved || got.join() !== want.join()) fail('theme', `device ${device}, saved ${saved}, page ${i}: first frame ${JSON.stringify(f)}`);
      });
      await ctx.close();
    }
    // toggle: device light, no saved choice → click → dark saved → next page dark on its first frame
    const ctx = await b.newContext({ viewport: { width: 1440, height: 900 }, colorScheme: 'light' });
    await ctx.addInitScript(() => { try { localStorage.setItem('krm-newsletter', JSON.stringify({ dismissed: Date.now() })); } catch (e) {} });
    await ctx.addInitScript(firstFrame);
    const p = await ctx.newPage();
    await p.goto(B + '/about/', { waitUntil: 'load' });
    const before = await p.evaluate(() => { const t = document.querySelector('.header-inner > [data-theme-toggle]'); return { theme: document.documentElement.getAttribute('data-theme'), pressed: t.getAttribute('aria-pressed'), label: t.getAttribute('aria-label'), size: [t.offsetWidth, t.offsetHeight] }; });
    await p.click('.header-inner > [data-theme-toggle]');
    const after = await p.evaluate(() => { const t = document.querySelector('.header-inner > [data-theme-toggle]'); return { theme: document.documentElement.getAttribute('data-theme'), pressed: t.getAttribute('aria-pressed'), label: t.getAttribute('aria-label'), saved: localStorage.getItem('krm-theme'), metas: [...document.querySelectorAll('meta[name="theme-color"]')].map((m) => m.content) }; });
    await p.goto(B + '/our-impact/', { waitUntil: 'load' });
    const next = await p.evaluate(() => window.__first);
    const head = await (await p.request.get(B + '/our-impact/')).text();
    const iScript = head.indexOf('localStorage.getItem("krm-theme")');
    const iCss = Math.min(...['<link rel=\'stylesheet\'', '<link rel="stylesheet"', '<style'].map((t) => { const i = head.indexOf(t); return i < 0 ? Infinity : i; }));
    results.theme.toggle = { before, after, nextPageFirstFrame: next, scriptBeforeCss: iScript > 0 && iScript < iCss };
    if (before.pressed !== 'false' || before.label !== 'Switch to dark mode') fail('toggle', 'initial state ' + JSON.stringify(before));
    if (before.size[0] < 44 || before.size[1] < 44) fail('toggle', 'tap target ' + before.size);
    if (after.theme !== 'dark' || after.pressed !== 'true' || after.label !== 'Switch to light mode' || after.saved !== 'dark') fail('toggle', 'after click ' + JSON.stringify(after));
    if (!next || next.theme !== 'dark') fail('toggle', 'next page first frame ' + JSON.stringify(next));
    if (!results.theme.toggle.scriptBeforeCss) fail('theme', 'inline theme script is not before the first stylesheet');
    // mobile menu toggle
    await p.setViewportSize({ width: 390, height: 844 });
    await p.goto(B + '/about/', { waitUntil: 'load' });
    await p.click('.menu-toggle');
    await p.click('.menu-theme [data-theme-toggle]');
    results.theme.mobileMenuToggle = await p.evaluate(() => document.documentElement.getAttribute('data-theme'));
    if (results.theme.mobileMenuToggle !== 'light') fail('toggle', 'mobile menu toggle did not switch back to light');
    await ctx.close();
  }

  // ---------- progress bar
  {
    const ctx = await context(b, 'light', 1440);
    const p = await ctx.newPage();
    await p.goto(B + '/about/', { waitUntil: 'load' });
    await p.waitForTimeout(300);
    const scale = () => p.evaluate(() => { const m = getComputedStyle(document.querySelector('.krm-progress')).transform; const v = m === 'none' ? 1 : Number(m.match(/matrix\(([^,]+)/)[1]); return Math.round(v * 1000) / 1000; });
    const top = await scale();
    await p.evaluate(() => scrollTo(0, document.documentElement.scrollHeight));
    await p.waitForTimeout(300);
    const bottom = await scale();
    const box = await p.evaluate(() => { const e = document.querySelector('.krm-progress'); const r = e.getBoundingClientRect(); return { top: r.top, height: r.height, z: +getComputedStyle(e).zIndex, headerZ: +getComputedStyle(document.querySelector('.site-header')).zIndex, ariaHidden: e.getAttribute('aria-hidden') }; });
    results.progress = { top, bottom, box };
    if (top !== 0) fail('progress', 'not 0 at the top: ' + top);
    if (bottom !== 1) fail('progress', 'not 1 at the bottom: ' + bottom);
    if (box.top !== 0 || box.height !== 3 || box.z <= box.headerZ || box.ariaHidden !== 'true') fail('progress', 'box ' + JSON.stringify(box));
    // a page too short to scroll
    await p.setViewportSize({ width: 1440, height: 3000 });
    await p.goto(B + '/nope-404-phase20/', { waitUntil: 'load' });
    await p.waitForTimeout(300);
    results.progress.shortPage = await p.evaluate(() => ({ scrollable: document.documentElement.scrollHeight - innerHeight, hidden: getComputedStyle(document.querySelector('.krm-progress')).visibility === 'hidden' }));
    if (results.progress.shortPage.scrollable < 48 && !results.progress.shortPage.hidden) fail('progress', 'shown on a page too short to scroll');
    await ctx.close();
    if (process.env.WP_USER && process.env.WP_PASS) {
      results.progress.adminBar = {};
      for (const w of [1440, 390]) {
        const c = await context(b, 'light', w);
        const q = await c.newPage();
        await q.goto(B + '/wp-login.php');
        await q.fill('#user_login', process.env.WP_USER);
        await q.fill('#user_pass', process.env.WP_PASS);
        await Promise.all([q.waitForNavigation(), q.click('#wp-submit')]);
        await q.goto(B + '/about/', { waitUntil: 'load' });
        await q.evaluate(() => scrollTo(0, 600));
        await q.waitForTimeout(300);
        const r = await q.evaluate(() => ({ bar: document.querySelector('.krm-progress').getBoundingClientRect().top, adminBar: document.getElementById('wpadminbar') ? document.getElementById('wpadminbar').offsetHeight : null }));
        results.progress.adminBar[w] = r;
        const want = w === 1440 ? 32 : 46;
        if (r.adminBar !== want || r.bar !== want) fail('progress', `admin bar at ${w}: ${JSON.stringify(r)}, expected ${want}`);
        await c.close();
      }
    } else {
      results.progress.adminBar = 'skipped (set WP_USER and WP_PASS)';
    }
  }

  // ---------- reveal: no JS, reduced motion, first screen
  {
    const invisible = () => [...document.querySelectorAll('main h2, main p, main li, main article, main img')].filter((e) => {
      const r = e.getBoundingClientRect(); if (!r.width || !r.height) return false;
      for (let x = e; x && x !== document.body; x = x.parentElement) { const s = getComputedStyle(x); if (+s.opacity < 0.99 && !x.closest('[aria-hidden="true"]')) return true; }
      return false;
    }).length;
    for (const [name, opts] of [['no JavaScript', { javaScriptEnabled: false }], ['reduced motion', { reducedMotion: 'reduce' }]]) {
      const ctx = await context(b, 'light', 1440, opts);
      const p = await ctx.newPage();
      const out = {};
      for (const u of PAGES) {
        await p.goto(B + u, { waitUntil: 'load' });
        await p.waitForTimeout(300);
        out[u] = await p.evaluate(invisible);
        if (out[u]) fail('reveal', `${name}: ${out[u]} elements not fully visible on ${u}`);
      }
      results.reveal[name] = out;
      await ctx.close();
    }
    const ctx = await context(b, 'light', 1440);
    const p = await ctx.newPage();
    const out = {};
    for (const u of PAGES) {
      await p.goto(B + u, { waitUntil: 'load' });
      await p.waitForTimeout(300);
      out[u] = await p.evaluate(() => ({
        pending: document.querySelectorAll('.reveal-pending').length,
        pendingOnFirstScreen: [...document.querySelectorAll('.reveal-pending')].filter((e) => e.getBoundingClientRect().top < innerHeight).length,
      }));
      if (out[u].pendingOnFirstScreen) fail('reveal', `${u}: ${out[u].pendingOnFirstScreen} blocks hidden on the first screen`);
    }
    results.reveal.normal = out;
    await ctx.close();
  }

  // ---------- header over the hero
  async function headerContrast(p) {
    const box = await p.evaluate(() => {
      const h = document.querySelector('.site-header'); const r = h.getBoundingClientRect();
      const link = document.querySelector('.menu > li > a') || document.querySelector('.menu-toggle');
      const vis = link && getComputedStyle(link).display !== 'none' && link.getBoundingClientRect().width > 0 ? link : document.querySelector('.menu-toggle');
      return { x: r.left, y: r.top, w: r.width, h: r.height, color: getComputedStyle(vis).color, bg: getComputedStyle(h).backgroundColor, scrolled: h.classList.contains('is-scrolled') };
    });
    await p.addStyleTag({ content: '.site-header *{color:transparent!important;border-color:transparent!important;box-shadow:none!important} .site-header img{opacity:0!important}', });
    await p.waitForTimeout(350);
    const png = PNG.sync.read(await p.screenshot({ clip: { x: box.x, y: box.y, width: box.w, height: box.h } }));
    const ls = [];
    for (let i = 0; i < png.data.length; i += 4 * 7) ls.push(lum(png.data[i], png.data[i + 1], png.data[i + 2]));
    ls.sort((a, b) => a - b);
    const fg = lum(...rgb(box.color));
    // Worst case of the 90% of background pixels closest to the text colour (ignores the brightest/darkest 10%).
    const worst = fg > 0.5 ? ls[Math.floor(ls.length * 0.9)] : ls[Math.floor(ls.length * 0.1)];
    return { scrolled: box.scrolled, headerBg: box.bg, text: box.color, contrast: Math.round(ratio(fg, worst) * 100) / 100 };
  }
  for (const theme of ['light', 'dark']) {
    for (const w of [1440, 390]) {
      const ctx = await context(b, theme, w);
      for (const u of HERO_PAGES) {
        const key = u + ' ' + theme + ' ' + w;
        results.header[key] = {};
        for (const y of [0, 400]) {
          const p = await ctx.newPage();
          await p.goto(B + u, { waitUntil: 'load' });
          await p.evaluate((yy) => scrollTo(0, yy), y);
          await p.waitForTimeout(500);
          const r = await headerContrast(p);
          results.header[key][y ? 'scrolled' : 'top'] = r;
          const alpha = (r.headerBg.match(/rgba\([^)]*,\s*([\d.]+)\)/) || [0, 1])[1];
          if (y === 0 && (r.scrolled || Number(alpha) > 0.01)) fail('header', `${key}: not transparent at the top (${r.headerBg})`);
          if (y && (!r.scrolled || Number(alpha) < 0.8)) fail('header', `${key}: not solid/frosted after scrolling (${r.headerBg})`);
          if (r.contrast < 4.5) fail('header', `${key} ${y ? 'scrolled' : 'top'}: nav text contrast ${r.contrast}`);
          await p.close();
        }
      }
      await ctx.close();
    }
  }

  // ---------- photo bands: lazy, ≤ 250 KB
  {
    const ctx = await context(b, 'light', 1440);
    for (const u of ['/', '/our-impact/', '/about/']) {
      const p = await ctx.newPage();
      const imgs = [];
      p.on('response', async (r) => { if (r.request().resourceType() === 'image') imgs.push(r.url()); });
      await p.goto(B + u, { waitUntil: 'load' });
      await p.waitForTimeout(500);
      const atLoad = await p.evaluate(() => [...document.querySelectorAll('[data-photo-band]')].map((e) => ({ v: e.getAttribute('data-photo-band'), loaded: !!e.style.getPropertyValue('--band-photo'), top: Math.round(e.getBoundingClientRect().top) })));
      await scrollThrough(p);
      await p.waitForTimeout(500);
      const bands = await p.evaluate(() => [...document.querySelectorAll('[data-photo-band]')].map((e) => ({ v: e.getAttribute('data-photo-band'), url: e.getAttribute('data-band-photo'), loaded: !!e.style.getPropertyValue('--band-photo'), attachment: getComputedStyle(e).backgroundAttachment })));
      for (const bd of bands) {
        const head = await p.request.head(bd.url);
        bd.bytes = Number(head.headers()['content-length'] || 0);
        bd.type = head.headers()['content-type'];
        if (bd.bytes > 250 * 1024) fail('bands', `${u} ${bd.v}: photo ${bd.bytes} bytes`);
        if (!bd.loaded) fail('bands', `${u} ${bd.v}: photo not set after scrolling`);
      }
      atLoad.forEach((a) => { if (a.loaded && a.top > 900 + 400) fail('bands', `${u} ${a.v}: photo loaded while ${a.top}px below the top`); });
      results.bands[u] = { atLoad, bands };
      if (!bands.length) fail('bands', `${u}: no photo band`);
      await p.close();
    }
    await ctx.close();
  }

  fs.mkdirSync(path.dirname(OUT), { recursive: true });
  fs.writeFileSync(OUT, JSON.stringify(results, null, 1));
  console.log(JSON.stringify({ failures: results.failures, theme: results.theme, progress: results.progress, reveal: results.reveal, bands: Object.fromEntries(Object.entries(results.bands).map(([u, v]) => [u, v.bands.map((x) => [x.v, x.bytes, x.type, x.attachment])])) }, null, 1));
  await b.close();
})();
