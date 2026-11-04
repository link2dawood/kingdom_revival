// Final pass interactions (logged out): mobile menu, Ministries submenu, before/after slider, ministry explorer,
// Our Impact charts and explorer, gallery filter + photo viewer, FAQ accordion, Donate payment tabs (keyboard),
// prayer / sign-up / contact form validation and sending, at 360 and 1024 (and 1440 for the desktop mega menu).
// usage: node final_interactions.js      env: WP_URL, CHROME_PATH. Prints one line per check: ok / FAIL.
const { chromium } = require('playwright-core');
const B = (process.env.WP_URL || 'http://127.0.0.1:8899').replace(/\/$/, '');
let fails = 0;
const check = (name, ok, info) => { if (!ok) fails++; console.log((ok ? 'ok   ' : 'FAIL ') + name + (info !== undefined ? '  ' + JSON.stringify(info) : '')); };

(async () => {
  const b = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });
  const ctx = await b.newContext();
  await ctx.addInitScript(() => { try { localStorage.setItem('krm-newsletter', JSON.stringify({ dismissed: Date.now() })); } catch (e) {} });
  const errs = [];
  const open = async (u, w) => { const p = await ctx.newPage(); p.on('pageerror', (e) => errs.push(u + ' ' + e.message)); await p.setViewportSize({ width: w, height: 800 }); await p.goto(B + u, { waitUntil: 'load' }); await p.waitForTimeout(400); return p; };
  const shown = (p, sel) => p.$eval(sel, (e) => { const r = e.getBoundingClientRect(); const s = getComputedStyle(e); return r.width > 0 && r.height > 0 && s.visibility !== 'hidden' && s.display !== 'none'; }).catch(() => false);

  for (const w of [360, 1024]) {
    for (const u of ['/', '/contact/']) {
      const p = await open(u, w);
      const toggle = await p.$('.site-nav .menu-toggle');
      const tv = toggle && (await toggle.isVisible());
      if (tv) {
        await toggle.click();
        await p.waitForTimeout(300);
        check(`${w} ${u} menu opens`, (await toggle.getAttribute('aria-expanded')) === 'true' && (await shown(p, '#site-menu')));
        const sub = await p.$('#site-menu .sub-toggle');
        if (sub && (await sub.isVisible())) {
          await sub.click(); await p.waitForTimeout(250);
          check(`${w} ${u} What We Do submenu opens`, (await sub.getAttribute('aria-expanded')) === 'true', await p.$$eval('#nav-what-we-do a', (a) => a.filter((x) => x.getBoundingClientRect().height > 0).length));
        }
        await p.keyboard.press('Escape'); await p.waitForTimeout(250);
        await toggle.click().catch(() => {}); await p.waitForTimeout(250);
        check(`${w} ${u} menu closes`, (await toggle.getAttribute('aria-expanded')) === 'false');
      } else check(`${w} ${u} menu toggle visible`, false);
      await p.close();
    }
  }
  // Desktop mega menu
  {
    const p = await open('/', 1440);
    const sub = await p.$('#nav-what-we-do .sub-toggle, #nav-what-we-do > button');
    if (sub) { await sub.click(); await p.waitForTimeout(300); check('1440 mega menu opens', (await sub.getAttribute('aria-expanded')) === 'true' && (await p.$$eval('#nav-what-we-do a', (a) => a.filter((x) => x.getBoundingClientRect().height > 0).length)) > 5); await p.keyboard.press('Escape'); await p.waitForTimeout(200); check('1440 mega menu Escape closes', (await sub.getAttribute('aria-expanded')) === 'false'); }
    else {
      await p.hover('#nav-what-we-do'); await p.waitForTimeout(400);
      check('1440 mega menu opens on hover', (await p.$$eval('#nav-what-we-do a', (a) => a.filter((x) => x.getBoundingClientRect().height > 0).length)) > 5);
    }
    await p.close();
  }
  for (const w of [360, 1440]) {
    // Before/after slider
    for (const u of ['/', '/what-we-do/slavery-freedom/']) {
      const p = await open(u, w);
      const range = await p.$('[data-krm-compare-range]');
      if (!range) { check(`${w} ${u} before/after slider present`, false); await p.close(); continue; }
      await range.evaluate((r) => { r.value = 20; r.dispatchEvent(new Event('input', { bubbles: true })); });
      const clip = await p.$eval('[data-krm-compare-before]', (e) => e.style.clipPath);
      check(`${w} ${u} before/after slider moves`, /80%/.test(clip), clip);
      await p.close();
    }
    // Ministry explorer (home)
    {
      const p = await open('/', w);
      const btns = await p.$$('#all-ministries button[data-index]');
      if (btns.length > 1) {
        const before = await p.$eval('[data-krm-explorer="support"]', (a) => a.href);
        await btns[2].scrollIntoViewIfNeeded(); await btns[2].click(); await p.waitForTimeout(300);
        const after = await p.$eval('[data-krm-explorer="support"]', (a) => a.href);
        check(`${w} home explorer switches ministry`, before !== after && /ministry=/.test(after), after.replace(B, ''));
      } else check(`${w} home explorer buttons`, false, btns.length);
      await p.close();
    }
    // Our Impact charts + explorer
    {
      const p = await open('/our-impact/', w);
      const charts = await p.$$eval('[data-impact-growth], [data-impact-financial], [data-impact-freedom], [data-impact-ministry-chart]', (els) => els.map((e) => (e.querySelector('svg, canvas') ? 'drawn' : 'EMPTY:' + e.textContent.trim().slice(0, 30)) + ' ' + Math.round(e.getBoundingClientRect().width)));
      check(`${w} impact charts drawn`, charts.length > 0 && charts.every((c) => c.startsWith('drawn')), charts);
      const tabs = await p.$$('[data-impact-index]');
      if (tabs.length > 1) { const n0 = await p.$eval('[data-impact-name]', (e) => e.textContent); await tabs[1].scrollIntoViewIfNeeded(); await tabs[1].click(); await p.waitForTimeout(300); const n1 = await p.$eval('[data-impact-name]', (e) => e.textContent); check(`${w} impact explorer switches`, n0 !== n1, [n0, n1]); }
      const overflowCharts = await p.$$eval('svg', (s) => s.filter((x) => x.getBoundingClientRect().right > innerWidth + 1).length);
      check(`${w} impact charts fit the screen`, overflowCharts === 0, overflowCharts);
      await p.close();
    }
    // Gallery: filter tabs + viewer
    {
      const p = await open('/our-impact/gallery/', w);
      const tabs = await p.$$('[data-krm-tabs] [role=tab]');
      if (tabs.length > 1) { await tabs[1].click(); await p.waitForTimeout(250); check(`${w} gallery filter`, (await tabs[1].getAttribute('aria-selected')) === 'true', await p.$$eval('[data-gallery] figure', (f) => f.filter((x) => x.getBoundingClientRect().height > 0).length)); await tabs[0].click(); }
      const img = await p.$('[data-gallery] img');
      if (img) { await img.scrollIntoViewIfNeeded(); await img.click(); await p.waitForTimeout(500); const lb = await shown(p, '.lb'); check(`${w} gallery viewer opens`, lb); await p.keyboard.press('ArrowRight'); await p.waitForTimeout(200); await p.keyboard.press('Escape'); await p.waitForTimeout(400); check(`${w} gallery viewer closes`, !(await shown(p, '.lb'))); }
      else check(`${w} gallery has photos`, false);
      await p.close();
    }
    // FAQ accordion + payment tabs keyboard (Donate)
    {
      const p = await open('/donate/', w);
      const sum = await p.$('.faq summary');
      if (sum) { await sum.scrollIntoViewIfNeeded(); await sum.click(); await p.waitForTimeout(200); check(`${w} FAQ opens`, await p.$eval('.faq details', (d) => d.open)); }
      else check(`${w} FAQ present`, false);
      await p.focus('#tab-paypal'); await p.keyboard.press('ArrowRight'); await p.waitForTimeout(150);
      check(`${w} payment tabs arrow key`, (await p.$eval('#tab-bank', (t) => t.getAttribute('aria-selected'))) === 'true' && (await shown(p, '#panel-bank')));
      await p.close();
    }
    // Forms: validation messages (not sent), and the sign-up form on Partner With Us
    {
      const p = await open('/partner-with-us/', w);
      const f = await p.$('form[data-krm-form="signup"]');
      if (f) {
        const btn = await f.$('button[type=submit]'); await btn.scrollIntoViewIfNeeded(); await btn.click(); await p.waitForTimeout(250);
        check(`${w} partner sign-up validation messages`, (await f.$$eval('.krm-field-error:not([hidden])', (e) => e.length)) > 0, await f.$$eval('[aria-invalid=true]', (e) => e.map((x) => x.name)));
      } else check(`${w} partner sign-up form present`, false);
      await p.close();
    }
    {
      const p = await open('/contact/', w);
      await p.click('#contact-form button[type=submit]'); await p.waitForTimeout(200);
      check(`${w} contact validation`, (await p.$$eval('#contact-form .error:not([hidden])', (e) => e.length)) === 3);
      await p.close();
    }
  }
  // One real send of each public form (waits past the 2.5 s anti-bot minimum); entries are deleted afterwards.
  for (const [u, sel, fill] of [
    ['/partner-with-us/prayer-request/', 'form[data-krm-form="prayer"]', { name: 'QA Final', contact: 'qa-final@example.com', request: 'QA final pass, please ignore.' }],
    ['/contact/', '#contact-form', { name: 'QA Final', email: 'qa-final@example.com', message: 'QA final pass, please ignore.' }],
  ]) {
    const p = await open(u, 1440);
    for (const [k, v] of Object.entries(fill)) await p.fill(`${sel} [name=${k}]`, v);
    await p.waitForTimeout(3000);
    const [resp] = await Promise.all([p.waitForResponse((r) => r.request().method() === 'POST', { timeout: 10000 }).catch(() => null), p.click(`${sel} button[type=submit]`)]);
    check(`send ${u}`, resp && resp.status() === 200 && /"success":true/.test(await resp.text()));
    await p.close();
  }
  check('no JS errors', errs.length === 0, errs.slice(0, 5));
  console.log(fails ? fails + ' FAILED' : 'ALL OK');
  await b.close();
})();
