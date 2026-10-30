// Screenshot the original HTML pages and the WordPress pages at three widths and pixel-diff them.
// usage: node compare.js <outdir> [pageName ...]
const { chromium } = require('playwright-core');
const fs = require('fs');
const path = require('path');
const { PNG } = require('pngjs');
const pixelmatch = require('pixelmatch');

const HTML = process.env.HTML_URL || 'http://127.0.0.1:8898';
const WP = process.env.WP_URL || 'http://127.0.0.1:8899';
const PAGES = {
  home: ['/index.html', '/'],
  about: ['/about.html', '/about/'],
  contact: ['/contact.html', '/contact/'],
  donate: ['/donate.html', '/donate/'],
  partner: ['/partner.html', '/partner/'],
  blog: ['/blog.html', '/blog/'],
  post: ['/blog-post.html?post=learning-beside-the-kilns', '/learning-beside-the-kilns/'],
  'post-oldest': ['/blog-post.html?post=prayer-for-the-sick-and-oppressed', '/prayer-for-the-sick-and-oppressed/'],
  ministry: ['/all-ministries.html?ministry=brick-kiln-children', '/ministries/brick-kiln-children/'],
  'ministry-empty': ['/all-ministries.html?ministry=free-medical-camps', '/ministries/free-medical-camps/'],
  'ministries-none': ['/all-ministries.html', '/ministries/'],
};
const CACHE = path.join(__dirname, 'cache');
fs.mkdirSync(CACHE, { recursive: true });
const inflight = new Map();

// Serve remote files (site uploads, Google Fonts) from a disk cache so both sides get the same bytes, fast.
async function cached(url, reqHeaders = {}) {
  const key = require('crypto').createHash('sha1').update(url).digest('hex');
  const file = path.join(CACHE, key);
  if (fs.existsSync(file + '.json')) {
    return { meta: JSON.parse(fs.readFileSync(file + '.json', 'utf8')), body: fs.readFileSync(file) };
  }
  if (!inflight.has(url)) {
    inflight.set(url, (async () => {
      for (let attempt = 0; attempt < 3; attempt++) {
        try {
          const res = await fetch(url, { signal: AbortSignal.timeout(90000), headers: { 'user-agent': reqHeaders['user-agent'] || '', accept: reqHeaders.accept || '*/*' } });
          const body = Buffer.from(await res.arrayBuffer());
          const meta = { status: res.status, type: res.headers.get('content-type') || 'application/octet-stream' };
          if (res.status === 200) {
            fs.writeFileSync(file, body);
            fs.writeFileSync(file + '.json', JSON.stringify(meta));
          }
          return { meta, body };
        } catch (e) {
          if (attempt === 2) return { meta: { status: 504, type: 'text/plain' }, body: Buffer.from('') };
        }
      }
    })());
  }
  return inflight.get(url);
}

const WIDTHS = { desktop: [1440, 900], tablet: [768, 1024], mobile: [390, 844] };

async function settle(page) {
  await page.addStyleTag({ content: 'video{visibility:hidden!important} *{caret-color:transparent!important}' });
  // Scroll through the page so lazy images load, then back to the top.
  await page.evaluate(async () => {
    const step = window.innerHeight / 2;
    for (let y = 0; y < document.documentElement.scrollHeight; y += step) {
      window.scrollTo(0, y);
      await new Promise((r) => setTimeout(r, 60));
    }
    window.scrollTo(0, 0);
  });
  await page.evaluate(async () => {
    await document.fonts.ready;
    await Promise.all([...document.images].map((img) => img.complete ? null : new Promise((r) => { img.onload = img.onerror = r; setTimeout(r, 15000); })));
  });
  await page.waitForTimeout(600);
}

(async () => {
  const out = process.argv[2];
  const only = process.argv.slice(3);
  fs.mkdirSync(out, { recursive: true });
  const browser = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });
  const results = [];
  for (const [name, [htmlUrl, wpUrl]] of Object.entries(PAGES)) {
    if (only.length && !only.includes(name)) continue;
    for (const [wname, [w, h]] of Object.entries(WIDTHS)) {
      const shots = {};
      for (const [side, url] of [['html', HTML + htmlUrl], ['wp', WP + wpUrl]]) {
        const ctx = await browser.newContext({ viewport: { width: w, height: h }, reducedMotion: 'reduce', deviceScaleFactor: 1 });
        await ctx.addInitScript(() => {
          try { localStorage.setItem('krm-newsletter', JSON.stringify({ dismissed: Date.now() })); } catch (e) {}
        });
        await ctx.route(/^https?:\/\/(?!127\.0\.0\.1)/, async (route) => {
          const url = route.request().url();
          if (/\.mp4(\?|$)/.test(url)) return route.abort();
          const { meta, body } = await cached(url, route.request().headers());
          return route.fulfill({ status: meta.status, contentType: meta.type, body, headers: { 'access-control-allow-origin': '*' } });
        });
        const page = await ctx.newPage();
        await page.goto(url, { waitUntil: 'load', timeout: 90000 });
        await page.waitForLoadState('networkidle', { timeout: 15000 }).catch(() => {});
        await settle(page);
        const file = path.join(out, `${name}-${wname}-${side}.png`);
        await page.screenshot({ path: file, fullPage: true });
        shots[side] = PNG.sync.read(fs.readFileSync(file));
        await ctx.close();
      }
      const a = shots.html, b = shots.wp;
      const width = Math.min(a.width, b.width), height = Math.min(a.height, b.height);
      const crop = (img) => {
        const c = new PNG({ width, height });
        PNG.bitblt(img, c, 0, 0, width, height, 0, 0);
        return c;
      };
      const diff = new PNG({ width, height });
      const n = pixelmatch(crop(a).data, crop(b).data, diff.data, width, height, { threshold: 0.1 });
      fs.writeFileSync(path.join(out, `${name}-${wname}-diff.png`), PNG.sync.write(diff));
      const r = { page: name, width: wname, htmlH: a.height, wpH: b.height, diffPct: +(100 * n / (width * height)).toFixed(3) };
      results.push(r);
      console.log(JSON.stringify(r));
    }
  }
  fs.writeFileSync(path.join(out, 'results.json'), JSON.stringify(results, null, 1));
  await browser.close();
})();
