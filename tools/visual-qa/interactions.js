// Compare interactive states (hover, menus, panels, popups, form states) between the HTML design and WordPress.
// usage: node interactions.js <outdir> [scenario ...]
const { chromium } = require('playwright-core');
const fs = require('fs');
const path = require('path');
const { PNG } = require('pngjs');
const pixelmatch = require('pixelmatch');
const { prepare } = require('./lib');

const HTML = process.env.HTML_URL || 'http://127.0.0.1:8898';
const WP = process.env.WP_URL || 'http://127.0.0.1:8899';
const D = [1440, 900], T = [768, 1024], M = [390, 844];

// [name, viewport, htmlPath, wpPath, actions(page)]
const S = [
  ['inner-dropdown', D, '/about.html', '/about/', async (p) => { await p.hover('#nav-ministries > .sub-toggle, .site-nav li.has-sub > .sub-toggle'); }],
  ['inner-flyout', D, '/about.html', '/about/', async (p) => { await p.hover('.site-nav li.has-sub > .sub-toggle'); await p.hover('.site-nav .submenu li.has-sub > .sub-toggle'); }],
  ['home-dropdown', D, '/index.html', '/', async (p) => { await p.hover('.site-nav li.has-sub > .sub-toggle'); }],
  ['inner-mobile-menu', M, '/about.html', '/about/', async (p) => { await p.click('.menu-toggle'); }],
  ['inner-mobile-submenu', M, '/about.html', '/about/', async (p) => { await p.click('.menu-toggle'); await p.click('.site-nav li.has-sub > .sub-toggle'); }],
  ['home-mobile-menu', M, '/index.html', '/', async (p) => { await p.click('.menu-toggle'); }],
  ['tablet-mobile-menu', T, '/contact.html', '/contact/', async (p) => { await p.click('.menu-toggle'); }],
  ['inner-button-hover', D, '/about.html', '/about/', async (p) => { await p.hover('.page-hero .button-primary'); }],
  ['home-button-hover', D, '/index.html', '/', async (p) => { await p.hover('#donate a.k-y'); }],
  ['home-card-hover', D, '/index.html', '/', async (p) => { await p.locator('#ministries article').first().scrollIntoViewIfNeeded(); await p.hover('#ministries article >> nth=0'); }],
  ['home-explorer', D, '/index.html', '/', async (p) => { await p.locator('#all-ministries').scrollIntoViewIfNeeded(); await p.click('#all-ministries button >> nth=4'); }],
  ['home-amount', D, '/index.html', '/', async (p) => { await p.click('#donate button:has-text("$100")'); }],
  ['home-float-panel', D, '/index.html', '/', async (p) => { await p.click('[data-krm-float-toggle], button[aria-controls="floating-donation-panel"]'); }],
  ['inner-float-panel', D, '/about.html', '/about/', async (p) => { await p.click('.float-give-toggle'); }],
  ['inner-float-panel-mobile', M, '/blog.html', '/blog/', async (p) => { await p.click('.float-give-toggle'); }],
  ['newsletter', D, '/about.html?newsletter=1', '/about/?newsletter=1', async (p) => { await p.waitForSelector('.nl', { timeout: 15000 }); }],
  ['newsletter-mobile', M, '/contact.html?newsletter=1', '/contact/?newsletter=1', async (p) => { await p.waitForSelector('.nl', { timeout: 15000 }); }],
  ['lightbox', D, '/about.html', '/about/', async (p) => { const img = p.locator('[data-gallery] img').first(); await img.scrollIntoViewIfNeeded(); await img.click(); await p.waitForSelector('.lb'); }],
  ['lightbox-mobile', M, '/ministries/brick-kiln-children/', null, null],
  ['donate-bank-tab', D, '/donate.html', '/donate/', async (p) => { await p.locator('#tab-bank').scrollIntoViewIfNeeded(); await p.click('#tab-bank'); }],
  ['donate-prefill', D, '/donate.html?amount=100&frequency=Monthly&for=Widows%20Ministry&method=Western%20Union#give', '/donate/?amount=100&frequency=Monthly&for=Widows%20Ministry&method=Western%20Union#give', async () => {}],
  ['donate-other', M, '/donate.html', '/donate/', async (p) => { const o = p.locator('input[name="amount"][value="Other"]'); await o.scrollIntoViewIfNeeded(); await o.check({ force: true }); }],
  ['give-card', D, '/blog.html', '/blog/', async (p) => { const c = p.locator('form.give-card:visible').first(); await c.scrollIntoViewIfNeeded(); await c.locator('input[value="Monthly"]').check({ force: true }); await c.locator('input[name$="amount"] >> nth=2').check({ force: true }); }],
  ['blog-filter', D, '/blog.html', '/blog/', async (p) => { const c = p.locator('.chip[data-filter="Children"]'); await c.scrollIntoViewIfNeeded(); await c.click(); }],
  ['blog-search', M, '/blog.html', '/blog/', async (p) => { const s = p.locator('#search'); await s.scrollIntoViewIfNeeded(); await s.fill('widow'); await p.waitForTimeout(400); }],
  ['blog-search-empty', D, '/blog.html', '/blog/', async (p) => { const s = p.locator('#search'); await s.scrollIntoViewIfNeeded(); await s.fill('zzzz'); await p.waitForTimeout(400); }],
  ['partner-church', D, '/partner.html', '/partner/', async (p) => { const r = p.locator('input[name="type"][value="Church / Group Partner"]'); await r.scrollIntoViewIfNeeded(); await r.check({ force: true }); }],
  ['partner-type-param', M, '/partner.html?type=sponsor#join', '/partner/?type=sponsor#join', async () => {}],
  ['contact-invalid', D, '/contact.html', '/contact/', async (p) => { const b = p.locator('form button[type="submit"]').first(); await b.scrollIntoViewIfNeeded(); await b.click(); }],
  ['partner-invalid', M, '/partner.html', '/partner/', async (p) => { const b = p.locator('#partner-submit'); await b.scrollIntoViewIfNeeded(); await b.click(); }],
  ['post-scrolled', D, '/blog-post.html?post=learning-beside-the-kilns', '/learning-beside-the-kilns/', async (p) => { await p.mouse.wheel(0, 1800); await p.waitForTimeout(500); }],
];

async function settle(p) {
  await p.addStyleTag({ content: 'video{visibility:hidden!important} *{caret-color:transparent!important}' });
  await p.evaluate(async () => {
    await document.fonts.ready;
    await Promise.all([...document.images].filter((i) => i.loading !== 'lazy').map((img) => img.complete ? null : new Promise((r) => { img.onload = img.onerror = r; setTimeout(r, 15000); })));
  });
}

(async () => {
  const out = process.argv[2];
  const only = process.argv.slice(3);
  fs.mkdirSync(out, { recursive: true });
  const browser = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });
  for (const [name, [w, h], htmlPath, wpPath, act] of S) {
    if (!act || (only.length && !only.includes(name))) continue;
    const imgs = {};
    let err = '';
    for (const [side, url] of [['html', HTML + htmlPath], ['wp', WP + wpPath]]) {
      const ctx = await browser.newContext({ viewport: { width: w, height: h }, reducedMotion: 'reduce' });
      await prepare(ctx);
      if (name.startsWith('newsletter')) await ctx.addInitScript(() => localStorage.removeItem('krm-newsletter'));
      const p = await ctx.newPage();
      try {
        await p.goto(url, { waitUntil: 'load', timeout: 90000 });
        await settle(p);
        await p.waitForTimeout(300);
        await act(p);
        await p.waitForTimeout(700);
        await settle(p);
      } catch (e) { err += `${side}: ${e.message.split('\n')[0]} `; }
      const file = path.join(out, `${name}-${side}.png`);
      await p.screenshot({ path: file });
      imgs[side] = PNG.sync.read(fs.readFileSync(file));
      await ctx.close();
    }
    const { width, height } = imgs.html;
    const diff = new PNG({ width, height });
    const n = pixelmatch(imgs.html.data, imgs.wp.data, diff.data, width, height, { threshold: 0.1 });
    fs.writeFileSync(path.join(out, `${name}-diff.png`), PNG.sync.write(diff));
    console.log(JSON.stringify({ scenario: name, diffPct: +(100 * n / (width * height)).toFixed(3), err }));
  }
  await browser.close();
})();
