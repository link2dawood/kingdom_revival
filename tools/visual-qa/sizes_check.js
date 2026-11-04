// Checks every responsive image's `sizes` against the width it really needs, at many viewport widths.
// "Needed" accounts for object-fit: cover (a box taller than the photo's shape needs a wider source).
// A `sizes` value below the needed width makes the browser pick a too-small file (soft image): reported as UNDER.
// usage: node sizes_check.js [page path ...]      env: WP_URL, CHROME_PATH
const { chromium } = require('playwright-core');
const { prepare } = require('./lib');

const WP = process.env.WP_URL || 'http://127.0.0.1:8899';
const PAGES = process.argv.slice(2).length ? process.argv.slice(2) : ['/', '/about/', '/contact/', '/donate/', '/partner-with-us/', '/news/',
  '/news/learning-beside-the-kilns/', '/what-we-do/brick-kiln-family-care/', '/what-we-do/'];
const WIDTHS = [360, 390, 414, 520, 600, 640, 700, 760, 768, 820, 900, 1000, 1024, 1040, 1100, 1240, 1280, 1440, 1920];

(async () => {
  const browser = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });
  const worst = new Map();
  let checked = 0;
  for (const p of PAGES) {
    for (const w of WIDTHS) {
      const ctx = await browser.newContext({ viewport: { width: w, height: 900 } });
      await prepare(ctx);
      const page = await ctx.newPage();
      await page.goto(WP + p, { waitUntil: 'load' });
      const rows = await page.evaluate(async () => {
        const imgs = [...document.images].filter((i) => i.getAttribute('sizes') && i.getAttribute('srcset'));
        imgs.forEach((i) => { i.loading = 'eager'; });
        await Promise.all(imgs.map((i) => (i.complete ? null : new Promise((r) => { i.onload = i.onerror = r; setTimeout(r, 20000); }))));
        const resolve = (sizes) => {
          for (const part of sizes.split(',').map((s) => s.trim())) {
            const m = part.match(/^(\(.*\))\s+(.+)$/);
            const [cond, len] = m ? [m[1], m[2]] : ['', part];
            if (cond && !matchMedia(cond).matches) continue;
            const n = parseFloat(len);
            return /vw$/.test(len) ? (n * innerWidth) / 100 : n;
          }
          return innerWidth;
        };
        return imgs.map((img) => {
          const r = img.getBoundingClientRect();
          if (!r.width) return null;
          const cover = getComputedStyle(img).objectFit === 'cover' && img.naturalWidth && img.naturalHeight;
          const needed = cover ? Math.max(r.width, (r.height * img.naturalWidth) / img.naturalHeight) : r.width;
          return { key: (img.getAttribute('src') || '').split('/').pop().slice(0, 50), sizes: img.getAttribute('sizes'), box: Math.round(r.width), needed: Math.round(needed), declared: Math.round(resolve(img.getAttribute('sizes'))) };
        }).filter(Boolean);
      });
      for (const r of rows) {
        checked++;
        const ratio = r.needed / r.declared;
        const k = `${p} ${r.key} [${r.sizes}]`;
        const prev = worst.get(k);
        if (!prev || ratio > prev.ratio) worst.set(k, { ratio, w, ...r });
      }
      await ctx.close();
    }
  }
  let under = 0;
  for (const [k, v] of [...worst].sort((a, b) => b[1].ratio - a[1].ratio)) {
    const flag = v.ratio > 1.1 ? 'UNDER' : 'ok';
    if (flag === 'UNDER') under++;
    console.log(JSON.stringify({ flag, image: k, worstAt: v.w, needed: v.needed, declared: v.declared, ratio: +v.ratio.toFixed(2) }));
  }
  console.log(JSON.stringify({ checked, images: worst.size, under }));
  await browser.close();
})();
