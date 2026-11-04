// Interaction latency (the measure behind Interaction to Next Paint) on a phone-sized viewport with 4x CPU slowdown.
// Clicks/typing on each page's real controls; reports the slowest interaction per page from Event Timing entries
// (input delay + processing + time to the next paint). Good INP is <= 200 ms.
// usage: node inp_check.js        env: WP_URL, CHROME_PATH
const { chromium } = require('playwright-core');
const { prepare } = require('./lib');

const WP = process.env.WP_URL || 'http://127.0.0.1:8899';
// [path, [[action, selector, count], ...]]
const PLAN = [
  ['/', [['click', '.menu-toggle', 2], ['click', 'button[data-index]', 4], ['click', 'button[data-amount]', 3], ['click', '[data-krm-float-toggle]', 2]]],
  ['/about/', [['click', '.menu-toggle', 2], ['click', '[data-gallery] img', 1], ['key', 'Escape', 1]]],
  ['/donate/', [['click', 'button[data-amount], .amount-grid button, .amounts button', 3], ['click', '.tab', 4]]],
  ['/contact/', [['type', 'input[name="name"], input[type="text"]', 1], ['click', '.menu-toggle', 2]]],
  ['/news/', [['click', '.menu-toggle', 2], ['click', '.filters button, [data-filter]', 3]]],
  ['/news/learning-beside-the-kilns/', [['click', '.menu-toggle', 2], ['click', '.post-content figure img, article img', 1], ['key', 'Escape', 1]]],
  ['/what-we-do/brick-kiln-family-care/', [['click', '.menu-toggle', 2], ['click', '.photo img', 1], ['key', 'Escape', 1]]],
];

(async () => {
  const browser = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });
  for (const [p, steps] of PLAN) {
    const ctx = await browser.newContext({ viewport: { width: 390, height: 844 }, hasTouch: true, isMobile: true, deviceScaleFactor: 2 });
    await prepare(ctx);
    const page = await ctx.newPage();
    const cdp = await ctx.newCDPSession(page);
    await cdp.send('Emulation.setCPUThrottlingRate', { rate: 4 });
    await page.addInitScript(() => {
      window.__events = [];
      new PerformanceObserver((list) => {
        for (const e of list.getEntries()) if (e.interactionId) window.__events.push({ name: e.name, d: e.duration, id: e.interactionId });
      }).observe({ type: 'event', durationThreshold: 16, buffered: true });
    });
    await page.goto(WP + p, { waitUntil: 'load' });
    await page.waitForTimeout(1500);
    let done = 0;
    for (const [action, sel, count] of steps) {
      for (let i = 0; i < count; i++) {
        try {
          if (action === 'key') { await page.keyboard.press(sel); done++; continue; }
          const els = await page.locator(sel).all();
          const visible = [];
          for (const el of els) if (await el.isVisible()) visible.push(el);
          const el = visible[i % Math.max(visible.length, 1)];
          if (!el) break;
          if (action === 'type') { await el.click(); await el.type('Grace and peace', { delay: 60 }); } else { await el.click({ timeout: 3000 }); }
          done++;
        } catch (e) { /* control not present on this page */ }
        await page.waitForTimeout(400);
      }
    }
    await page.waitForTimeout(800);
    const events = await page.evaluate(() => window.__events);
    const byId = new Map();
    for (const e of events) byId.set(e.id, Math.max(byId.get(e.id) || 0, e.d));
    const worst = Math.max(0, ...byId.values());
    console.log(JSON.stringify({ page: p, interactions: done, measured: byId.size, worstMs: Math.round(worst), verdict: worst <= 200 ? 'good' : worst <= 500 ? 'needs improvement' : 'poor' }));
    await ctx.close();
  }
  await browser.close();
})();
