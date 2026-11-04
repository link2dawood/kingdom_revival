// Responsive image check (Phase 14): every page at phone, tablet and desktop widths, for horizontal page scroll,
// elements and images that stick out past the viewport, images drawn out of their aspect ratio (object-fit: fill
// on a box with another shape), and images that cover text, links or buttons. The matching HTML page gets the same
// checks, so problems inherited from the design can be told from ones the theme adds.
// usage: node responsive_check.js [outfile.json] [page name filter (regex)]   env: WIDTHS=320,360 to limit widths
const { chromium } = require('playwright-core');
const fs = require('fs');
const { prepare } = require('./lib');

const HTML = process.env.HTML_URL || 'http://127.0.0.1:8898';
const WP = process.env.WP_URL || 'http://127.0.0.1:8899';
const OUT = process.argv[2] || 'out/responsive.json';
const FILTER = process.argv[3] ? new RegExp(process.argv[3]) : null;
const WIDTHS = (process.env.WIDTHS || '320,360,375,390,414,480,768,1024,1440').split(',').map(Number);

const MINISTRIES = ['children-ministry', 'orphan-and-children-care', 'brick-kiln-children', 'widows-ministry', 'freedom-from-slavery', 'free-medical-camps', 'clean-water-project', 'outreach-ministry', 'urdu-bibles', 'baptisms', 'deliverance-and-healing', 'leadership-and-discipleship'];
const POSTS = ['gods-word-in-every-home', 'learning-beside-the-kilns', 'new-life-in-the-water', 'a-home-for-the-orphaned', 'standing-with-widows', 'raising-up-shepherds', 'prayer-for-the-sick-and-oppressed'];
const PAGES = [
  ['home', '/index.html', '/'],
  ['about', '/about.html', '/about/'],
  ['contact', '/contact.html', '/contact/'],
  ['donate', '/donate.html', '/donate/'],
  ['partner', '/partner.html', '/partner-with-us/'],
  ['blog', '/blog.html', '/news/'],
  ['ministries', '/all-ministries.html', '/what-we-do/'],
  ...MINISTRIES.map((s) => [`ministry: ${s}`, `/all-ministries.html?ministry=${s}`, `/ministries/${s}/`]),
  ...POSTS.map((s) => [`post: ${s}`, `/blog-post.html?post=${s}`, `/news/${s}/`]),
  ['donate thanks', null, '/donate/?thanks=1'],
  ['sermons', null, '/sermons/'],
  ['events', null, '/events/'],
].filter(([name]) => !FILTER || FILTER.test(name));

/* Runs in the page. Returns problems as short strings. */
function inspect() {
  const vw = document.documentElement.clientWidth;
  const out = { scrollWidth: document.scrollingElement.scrollWidth, vw, offscreen: [], distorted: [], overlaps: [] };
  const label = (el) => {
    let s = el.tagName.toLowerCase();
    if (el.id) s += '#' + el.id;
    else if (typeof el.className === 'string' && el.className.trim()) s += '.' + el.className.trim().split(/\s+/).slice(0, 2).join('.');
    if (el.tagName === 'IMG') s += ' ' + (el.currentSrc || el.src).split('/').pop().slice(0, 40);
    return s;
  };
  const visible = (el) => {
    const cs = getComputedStyle(el);
    return cs.display !== 'none' && cs.visibility !== 'hidden' && +cs.opacity > 0.05;
  };
  // An ancestor that clips (overflow other than visible) hides whatever sticks out of it.
  const clipRect = (el) => {
    let r = { left: -Infinity, right: Infinity, top: -Infinity, bottom: Infinity };
    for (let p = el.parentElement; p && p !== document.body; p = p.parentElement) {
      const cs = getComputedStyle(p);
      if (cs.overflowX !== 'visible' || cs.overflowY !== 'visible' || cs.clipPath !== 'none') {
        const b = p.getBoundingClientRect();
        r = { left: Math.max(r.left, b.left), right: Math.min(r.right, b.right), top: Math.max(r.top, b.top), bottom: Math.min(r.bottom, b.bottom) };
      }
      if (cs.position === 'fixed') break;
    }
    return r;
  };
  const sy = window.scrollY;
  document.querySelectorAll('body *').forEach((el) => {
    if (el.closest('svg') && el.tagName !== 'svg') return;
    const b = el.getBoundingClientRect();
    if (!b.width || !b.height || !visible(el)) return;
    const c = clipRect(el);
    const left = Math.max(b.left, c.left), right = Math.min(b.right, c.right);
    if (right <= left) return;
    if (right > vw + 1 || left < -1) {
      // Report the outermost offender only.
      const parent = el.parentElement && el.parentElement.getBoundingClientRect();
      if (!parent || Math.min(parent.right, c.right) <= vw + 1 && Math.max(parent.left, c.left) >= -1) {
        out.offscreen.push(`${label(el)} x ${Math.round(left)}..${Math.round(right)}`);
      }
    }
  });
  const texts = Array.from(document.querySelectorAll('p, h1, h2, h3, h4, li, a, button, label, figcaption, blockquote, input, select, textarea'))
    .filter((t) => visible(t) && t.getBoundingClientRect().width > 0 && (t.innerText || t.value || '').trim());
  document.querySelectorAll('img').forEach((img) => {
    if (!img.naturalWidth || !visible(img)) return;
    const b = img.getBoundingClientRect();
    if (b.width < 8 || b.height < 8) return;
    const cs = getComputedStyle(img);
    if (cs.objectFit === 'fill' || cs.objectFit === '') {
      const want = img.naturalWidth / img.naturalHeight, got = img.clientWidth / img.clientHeight;
      if (Math.abs(got / want - 1) > 0.03) out.distorted.push(`${label(img)} box ${img.clientWidth}x${img.clientHeight} natural ${img.naturalWidth}x${img.naturalHeight}`);
    }
    // A sticky or fixed header slides over the content on purpose.
    for (let p = img; p; p = p.parentElement) if (/^(fixed|sticky)$/.test(getComputedStyle(p).position)) return;
    const c = clipRect(img);
    const r = { left: Math.max(b.left, c.left), right: Math.min(b.right, c.right), top: Math.max(b.top, c.top), bottom: Math.min(b.bottom, c.bottom) };
    if (r.right <= r.left || r.bottom <= r.top) return;
    texts.forEach((t) => {
      if (t.contains(img) || img.contains(t)) return;
      // Text drawn on top of a photo on purpose (captions, overlays) sits inside the photo's positioned card.
      const card = img.closest('figure, a, article, [style*="position: relative"], [style*="position:relative"]');
      if (card && card.contains(t)) return;
      const tb = t.getBoundingClientRect();
      const ix = Math.min(tb.right, r.right) - Math.max(tb.left, r.left);
      const iy = Math.min(tb.bottom, r.bottom) - Math.max(tb.top, r.top);
      if (ix > 4 && iy > 4) {
        // Which one is drawn on top?
        const x = (Math.max(tb.left, r.left) + Math.min(tb.right, r.right)) / 2, y = (Math.max(tb.top, r.top) + Math.min(tb.bottom, r.bottom)) / 2;
        const top = document.elementFromPoint(x, y);
        const imgOnTop = top === img || (top && img.parentElement.contains(top) && !t.contains(top));
        if (imgOnTop) out.overlaps.push(`${label(img)} covers ${label(t)} "${(t.innerText || '').trim().slice(0, 30)}"`);
      }
    });
  });
  window.scrollTo(0, sy);
  out.overlaps = Array.from(new Set(out.overlaps));
  return out;
}

async function check(browser, url, w) {
  const ctx = await browser.newContext({ viewport: { width: w, height: w < 700 ? 800 : 1000 }, isMobile: w < 700, hasTouch: w < 1024, deviceScaleFactor: 1 });
  await prepare(ctx);
  const page = await ctx.newPage();
  try {
    await page.goto(url, { waitUntil: 'load', timeout: 90000 });
    await page.evaluate(async () => {
      for (let y = 0; y < document.body.scrollHeight; y += 500) { window.scrollTo(0, y); await new Promise((f) => setTimeout(f, 40)); }
      window.scrollTo(0, 0);
    });
    await page.waitForTimeout(400);
    // Overlap checks use viewport coordinates, so measure each screenful.
    const total = await page.evaluate(() => document.body.scrollHeight);
    const res = { scrollWidth: 0, vw: 0, offscreen: new Set(), distorted: new Set(), overlaps: new Set() };
    const step = w < 700 ? 800 : 1000;
    for (let y = 0; y < total; y += step) {
      await page.evaluate((yy) => window.scrollTo(0, yy), y);
      await page.waitForTimeout(60);
      const r = await page.evaluate(inspect);
      res.scrollWidth = Math.max(res.scrollWidth, r.scrollWidth); res.vw = r.vw;
      r.offscreen.forEach((s) => res.offscreen.add(s.replace(/ x .*/, '')));
      r.distorted.forEach((s) => res.distorted.add(s));
      r.overlaps.forEach((s) => res.overlaps.add(s));
    }
    return { hscroll: res.scrollWidth > res.vw + 1 ? res.scrollWidth - res.vw : 0, offscreen: [...res.offscreen], distorted: [...res.distorted], overlaps: [...res.overlaps] };
  } catch (e) {
    return { error: String(e.message || e).slice(0, 200) };
  } finally {
    await ctx.close();
  }
}

(async () => {
  const browser = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });
  const jobs = [];
  for (const [name, html, wp] of PAGES) for (const w of WIDTHS) jobs.push({ name, w, html, wp });
  const results = [];
  let next = 0;
  async function worker() {
    while (next < jobs.length) {
      const job = jobs[next++];
      const wpRes = await check(browser, WP + job.wp, job.w);
      const htmlRes = job.html ? await check(browser, HTML + job.html, job.w) : null;
      const row = { page: job.name, width: job.w, wp: wpRes, html: htmlRes };
      const bad = (r) => r && (r.error || r.hscroll || r.offscreen.length || r.distorted.length || r.overlaps.length);
      console.log(JSON.stringify({ page: job.name, width: job.w, wp: bad(wpRes) ? wpRes : 'ok', html: htmlRes ? (bad(htmlRes) ? 'issues' : 'ok') : '-' }));
      results.push(row);
    }
  }
  await Promise.all(Array.from({ length: 4 }, worker));
  await browser.close();
  results.sort((a, b) => a.page.localeCompare(b.page) || a.width - b.width);
  fs.writeFileSync(OUT, JSON.stringify(results, null, 1));
})();
