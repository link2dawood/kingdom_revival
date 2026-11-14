// Image audit: every visible photo on every page (img + CSS background), at desktop / tablet / mobile.
// For each: page, width, kind (img|bg), section (closest section id/class), source file, alt, rendered box,
// object-fit / object-position or background-size / background-position, and a crop screenshot of the box.
// Usage: node image_audit.js [--shots]   (WP_URL, CHROME_PATH). Output: out/images/audit.json (+ out/images/*.png)
const fs = require('fs');
const path = require('path');
const { chromium } = require('playwright-core');
const { prepare } = require('./lib');

const WP = process.env.WP_URL || 'http://127.0.0.1:8899';
const OUT = path.join(__dirname, 'out', 'images');
const SHOTS = process.argv.includes('--shots');
const ONLY = (process.argv.find((a) => a.startsWith('--only=')) || '').slice(7);
const WIDTHS = { desktop: [1440, 900], tablet: [768, 1024], mobile: [390, 844] };

async function pages() {
  const list = ['/', '/about', '/what-we-do', '/our-impact', '/our-impact/testimonies', '/our-impact/gallery', '/news',
    '/partner-with-us', '/partner-with-us/prayer-request', '/donate', '/contact',
    '/what-we-do/gospel-spiritual-ministries', '/what-we-do/compassion-humanitarian-ministries'];
  const html = await (await fetch(WP + '/what-we-do')).text();
  for (const m of html.matchAll(/href="[^"]*?(\/what-we-do\/[a-z-]+-ministries\/[a-z-]+)\/?"/g)) list.push(m[1]);
  const news = await (await fetch(WP + '/news')).text();
  for (const m of news.matchAll(/href="[^"]*?(\/news\/[a-z0-9-]+)\/?"/g)) if (!/\/news\/(page|category)/.test(m[1])) list.push(m[1]);
  return [...new Set(list)].filter((p) => !ONLY || p.includes(ONLY));
}

(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const browser = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });
  const rows = [];
  for (const p of await pages()) {
    for (const [wname, [w, h]] of Object.entries(WIDTHS)) {
      const ctx = await browser.newContext({ viewport: { width: w, height: h }, reducedMotion: 'reduce' });
      await prepare(ctx);
      const page = await ctx.newPage();
      await page.goto(WP + p, { waitUntil: 'networkidle', timeout: 120000 }).catch(() => {});
      // Scroll through so lazy images and lazy photo bands load.
      await page.evaluate(async () => {
        for (let y = 0; y < document.body.scrollHeight; y += 600) { window.scrollTo(0, y); await new Promise((r) => setTimeout(r, 60)); }
        window.scrollTo(0, 0);
      });
      await page.waitForTimeout(800);
      const found = await page.evaluate(() => {
        const out = [];
        const sec = (el) => {
          const s = el.closest('section,[id],header,footer,article');
          return s ? (s.id ? '#' + s.id : '') + '.' + String(s.className || '').split(' ').filter(Boolean).slice(0, 2).join('.') : '';
        };
        const vis = (r) => r.width > 40 && r.height > 40;
        let i = 0;
        for (const el of document.querySelectorAll('img')) {
          const r = el.getBoundingClientRect(); if (!vis(r)) continue;
          const cs = getComputedStyle(el); if (cs.visibility === 'hidden' || cs.display === 'none') continue;
          if (/logo|icon|\.svg/i.test(el.currentSrc || el.src)) continue;
          el.dataset.auditId = String(++i);
          out.push({ id: i, kind: 'img', section: sec(el), src: el.currentSrc || el.src, alt: el.getAttribute('alt'),
            box: [Math.round(r.left), Math.round(r.top + scrollY), Math.round(r.width), Math.round(r.height)],
            natural: [el.naturalWidth, el.naturalHeight], fit: cs.objectFit, pos: cs.objectPosition });
        }
        for (const el of document.querySelectorAll('body *')) {
          const cs = getComputedStyle(el); const m = /url\("?([^")]+)"?\)/.exec(cs.backgroundImage);
          if (!m || /\.svg|data:/.test(m[1])) continue;
          const r = el.getBoundingClientRect(); if (!vis(r)) continue;
          el.dataset.auditId = String(++i);
          out.push({ id: i, kind: 'bg', section: sec(el), src: m[1], alt: el.getAttribute('aria-label') || '',
            box: [Math.round(r.left), Math.round(r.top + scrollY), Math.round(r.width), Math.round(r.height)],
            fit: cs.backgroundSize, pos: cs.backgroundPosition });
        }
        return out;
      });
      const slug = p.replace(/\//g, '_') || '_home';
      for (const f of found) {
        f.page = p; f.width = wname; f.file = decodeURIComponent(f.src.split('/').pop().split('?')[0]);
        if (SHOTS) {
          f.shot = `${slug}-${wname}-${f.id}.jpg`;
          await page.locator(`[data-audit-id="${f.id}"]`).first().screenshot({ path: path.join(OUT, f.shot), type: 'jpeg', quality: 60, timeout: 8000 }).catch(() => { f.shot = ''; });
        }
        rows.push(f);
      }
      process.stdout.write(`${p} ${wname}: ${found.length}\n`);
      await ctx.close();
    }
  }
  fs.writeFileSync(path.join(OUT, 'audit.json'), JSON.stringify(rows, null, 1));
  await browser.close();
})();
