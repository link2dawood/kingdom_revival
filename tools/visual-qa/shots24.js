// Full-page screenshots of the pages with Phase 24 forms (before/after a change; compare with wpdiff.js).
// usage: node shots24.js <outdir>
const { chromium } = require('playwright-core');
const fs = require('fs');
const { cached } = require('./lib');
const WP = process.env.WP_URL || 'http://127.0.0.1:8899';
const OUT = process.argv[2] || 'out/shots24';
const PAGES = { home: '/', partner: '/partner-with-us/', prayer: '/partner-with-us/prayer-request/', donate: '/donate/' };
(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const browser = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });
  for (const [name, path] of Object.entries(PAGES)) {
    for (const [label, width] of [['desktop', 1440], ['tablet', 768], ['mobile', 390]]) {
      const ctx = await browser.newContext({ viewport: { width, height: 900 }, reducedMotion: 'reduce' });
      await ctx.route(/^https?:\/\/(?!127\.0\.0\.1)/, async (route) => {
        const url = route.request().url();
        if (/\.mp4(\?|$)|\.webm(\?|$)|paypal\.com/.test(url)) return route.abort();
        const { meta, body } = await cached(url, route.request().headers());
        return route.fulfill({ status: meta.status, contentType: meta.type, body });
      });
      await ctx.addInitScript(() => { try { localStorage.setItem('krm-newsletter', JSON.stringify({ dismissed: Date.now() })); localStorage.setItem('krm-theme', 'light'); } catch (e) {} });
      const page = await ctx.newPage();
      await page.goto(WP + path, { waitUntil: 'networkidle' });
      await page.evaluate(async () => { window.krmReveal && window.krmReveal.flush && window.krmReveal.flush(); document.querySelectorAll('[data-reveal]').forEach((e) => e.classList.add('is-revealed')); });
      await page.addStyleTag({ content: '*,*::before,*::after{transition:none!important;animation:none!important} video{visibility:hidden!important} [data-reveal]{opacity:1!important;transform:none!important}' });
      await page.waitForTimeout(400);
      await page.screenshot({ path: `${OUT}/${name}-${label}-wp.png`, fullPage: true });
      await ctx.close();
    }
  }
  await browser.close();
})();
