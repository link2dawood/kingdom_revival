// Site health check (Phase 12): every WordPress page at desktop, tablet and mobile width, plus the original HTML page
// where there is one, for: JavaScript errors and console errors, failed or 4xx/5xx requests, images that did not load,
// horizontal overflow, noindex (meta robots or X-Robots-Tag), and PHP warnings/notices printed into the page.
// usage: node site_check.js [outfile.json] [page name filter (regex)]
const { chromium } = require('playwright-core');
const fs = require('fs');
const { prepare } = require('./lib');

const HTML = process.env.HTML_URL || 'http://127.0.0.1:8898';
const WP = process.env.WP_URL || 'http://127.0.0.1:8899';
const OUT = process.argv[2] || 'out/site-check.json';

const MINISTRIES = ['children-ministry', 'orphan-and-children-care', 'brick-kiln-children', 'widows-ministry', 'freedom-from-slavery', 'free-medical-camps', 'clean-water-project', 'outreach-ministry', 'urdu-bibles', 'baptisms', 'deliverance-and-healing', 'leadership-and-discipleship'];
const POSTS = ['gods-word-in-every-home', 'learning-beside-the-kilns', 'new-life-in-the-water', 'a-home-for-the-orphaned', 'standing-with-widows', 'raising-up-shepherds', 'prayer-for-the-sick-and-oppressed'];

// [name, html path or null, wp path, expect noindex]
const PAGES = [
  ['home', '/index.html', '/'],
  ['about', '/about.html', '/about/'],
  ['contact', '/contact.html', '/contact/'],
  ['donate', '/donate.html', '/donate/'],
  ['partner', '/partner.html', '/partner/'],
  ['blog', '/blog.html', '/blog/'],
  ['ministries', '/all-ministries.html', '/ministries/'],
  ...MINISTRIES.map((s) => [`ministry: ${s}`, `/all-ministries.html?ministry=${s}`, `/ministries/${s}/`]),
  ...POSTS.map((s) => [`post: ${s}`, `/blog-post.html?post=${s}`, `/${s}/`]),
  ['donate thanks', null, '/donate/?thanks=1', true],
  ['search', null, '/?s=water', true],
  ['category', null, '/category/uncategorized/'],
  ['sermons', null, '/sermons/'],
  ['events', null, '/events/'],
  ['testimonials', null, '/testimonials/'],
  ['404', null, '/no-such-page-krm/', true],
];
const WIDTHS = { desktop: [1440, 900], tablet: [768, 1024], mobile: [390, 844] };
const PHP_NOTICE = /<b>(Warning|Notice|Deprecated|Fatal error|Parse error)<\/b>:|\b(PHP (Warning|Notice|Deprecated|Fatal error))\b|There has been a critical error/;

async function check(browser, url, [w, h]) {
  const ctx = await browser.newContext({ viewport: { width: w, height: h } });
  await prepare(ctx);
  const page = await ctx.newPage();
  const r = { console: [], pageErrors: [], badRequests: [], brokenImages: [], overflow: null, noindex: [], php: false, status: 0 };
  page.on('console', (m) => { if (m.type() === 'error') r.console.push(m.text().slice(0, 200)); });
  page.on('pageerror', (e) => r.pageErrors.push(String(e.message || e).slice(0, 200)));
  page.on('requestfailed', (q) => {
    const u = q.url();
    if (/\.mp4(\?|$)/.test(u)) return; // videos are blocked on purpose by prepare()
    r.badRequests.push(`FAILED ${q.failure() && q.failure().errorText} ${u}`);
  });
  page.on('response', (s) => { if (s.status() >= 400 && s.url() !== url) r.badRequests.push(`${s.status()} ${s.url()}`); });
  const res = await page.goto(url, { waitUntil: 'load', timeout: 90000 });
  r.status = res.status();
  const xr = res.headers()['x-robots-tag'];
  if (xr && /noindex/i.test(xr)) r.noindex.push(`X-Robots-Tag: ${xr}`);
  r.php = PHP_NOTICE.test(await res.text().catch(() => ''));
  // Scroll through so lazy images load, then let them settle.
  await page.evaluate(async () => {
    for (let y = 0; y < document.body.scrollHeight; y += 600) { window.scrollTo(0, y); await new Promise((f) => setTimeout(f, 60)); }
    window.scrollTo(0, 0);
  });
  await page.waitForLoadState('networkidle', { timeout: 30000 }).catch(() => {});
  const dom = await page.evaluate(() => {
    const de = document.documentElement;
    const vw = de.clientWidth;
    const wide = [];
    if (de.scrollWidth > vw || document.body.scrollWidth > vw) {
      document.querySelectorAll('body *').forEach((el) => {
        const b = el.getBoundingClientRect();
        if (b.width && b.right > vw + 1 && wide.length < 5) wide.push(`${el.tagName.toLowerCase()}.${[...el.classList].join('.')} right=${Math.round(b.right)}`);
      });
    }
    const broken = [...document.images]
      .filter((i) => i.currentSrc && i.complete && i.naturalWidth === 0 && i.loading !== 'lazy')
      .map((i) => i.currentSrc);
    const lazyPending = [...document.images].filter((i) => i.currentSrc && !i.complete).length;
    const robots = [...document.querySelectorAll('meta[name="robots"], meta[name="googlebot"]')].map((m) => m.content);
    return { scrollWidth: Math.max(de.scrollWidth, document.body.scrollWidth), vw, wide, broken, lazyPending, robots };
  });
  r.overflow = dom.scrollWidth > dom.vw ? { scrollWidth: dom.scrollWidth, viewport: dom.vw, elements: dom.wide } : null;
  r.brokenImages = dom.broken;
  r.noindex.push(...dom.robots.filter((c) => /noindex/i.test(c)));
  await ctx.close();
  return r;
}

(async () => {
  const browser = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });
  const all = [];
  const only = process.argv[3] ? new RegExp(process.argv[3]) : null;
  for (const [name, htmlPath, wpPath, expectNoindex] of PAGES.filter((p) => !only || only.test(p[0]))) {
    for (const [wname, size] of Object.entries(WIDTHS)) {
      const wp = await check(browser, WP + wpPath, size);
      const html = htmlPath ? await check(browser, HTML + htmlPath, size) : null;
      const row = {
        page: name, width: wname, status: wp.status,
        jsErrors: [...wp.pageErrors, ...wp.console],
        badRequests: wp.badRequests, brokenImages: wp.brokenImages, overflow: wp.overflow,
        noindex: wp.noindex, noindexExpected: !!expectNoindex, phpNotice: wp.php,
        html: html && { jsErrors: [...html.pageErrors, ...html.console], badRequests: html.badRequests, brokenImages: html.brokenImages, overflow: html.overflow },
      };
      row.ok = row.jsErrors.length === 0 && row.badRequests.length === 0 && row.brokenImages.length === 0 && !row.overflow &&
        !row.phpNotice && (row.noindex.length > 0) === row.noindexExpected && (row.status === 200 || (name === '404' && row.status === 404));
      all.push(row);
      console.log(JSON.stringify({ page: name, width: wname, ok: row.ok, status: row.status, js: row.jsErrors.length, bad: row.badRequests.length, img: row.brokenImages.length, overflow: !!row.overflow, noindex: row.noindex.length > 0, php: row.phpNotice }));
      if (!row.ok) console.log('   ', JSON.stringify({ jsErrors: row.jsErrors, badRequests: row.badRequests, brokenImages: row.brokenImages, overflow: row.overflow, noindex: row.noindex }));
    }
  }
  fs.mkdirSync(require('path').dirname(OUT), { recursive: true });
  fs.writeFileSync(OUT, JSON.stringify(all, null, 2));
  console.log(`${all.filter((r) => r.ok).length}/${all.length} ok → ${OUT}`);
  await browser.close();
})();
