// Image viewer check (Phase 14): opens the photo viewer on each gallery page at phone, tablet and desktop widths
// and checks that every control is inside the viewport and none overlap, that the photo fits the stage and is centred
// at 0°, 90°, 180° and 270° (its width and height swap when turned), that zoom starts centred and panning stops
// at the photo's edges, that pinch (two-finger touch) and double tap zoom, that Reset and the next photo restore
// the default view, and that Escape closes the viewer and gives focus back to the photo that opened it.
// usage: node viewer_check.js [outfile.json]   env: WIDTHS=320,390 to limit widths
const { chromium } = require('playwright-core');
const fs = require('fs');
const { prepare } = require('./lib');

const WP = process.env.WP_URL || 'http://127.0.0.1:8899';
const OUT = process.argv[2] || 'out/viewer.json';
const WIDTHS = (process.env.WIDTHS || '320,360,375,390,414,480,768,1024,1440').split(',').map(Number);
const PAGES = [
  ['home', '/', '.krm-field-stories__gallery img'],
  ['about collage', '/about/', '.collage img'],
  ['about photos', '/about/', '.photo-grid img'],
  ['about founder', '/about/', 'img[data-zoom]'],
  ['ministry', '/ministries/brick-kiln-children/', '#ministry-gallery img'],
  ['ministry outreach (portrait photos)', '/ministries/outreach-ministry/', '#ministry-gallery img'],
  ['story', '/learning-beside-the-kilns/', '#article-body img'],
].filter(([name]) => !process.env.PAGE || new RegExp(process.env.PAGE).test(name));
const TOL = 1.5;

/* Runs in the page: geometry of the open viewer. */
function measure() {
  const lb = document.querySelector('.lb');
  if (!lb) return null;
  const vw = window.innerWidth, vh = window.innerHeight;
  const stage = lb.querySelector('.lb-stage').getBoundingClientRect();
  const img = lb.querySelector('.lb-img');
  const ib = img.getBoundingClientRect();
  const controls = Array.from(lb.querySelectorAll('button, a.lb-btn, .lb-zoom-level')).filter((el) => el.offsetParent && !el.closest('.lb-thumbs'))
    .map((el) => ({ name: el.getAttribute('aria-label') || el.className, r: el.getBoundingClientRect() }));
  const problems = [];
  controls.forEach((c, i) => {
    if (c.r.left < -0.5 || c.r.top < -0.5 || c.r.right > vw + 0.5 || c.r.bottom > vh + 0.5) problems.push(`off-screen: ${c.name}`);
    controls.slice(i + 1).forEach((d) => {
      if (Math.min(c.r.right, d.r.right) - Math.max(c.r.left, d.r.left) > 0.5 && Math.min(c.r.bottom, d.r.bottom) - Math.max(c.r.top, d.r.top) > 0.5) problems.push(`overlap: ${c.name} / ${d.name}`);
    });
  });
  const lbr = lb.getBoundingClientRect();
  if (lbr.width > vw + 0.5 || lbr.height > vh + 0.5) problems.push(`viewer ${lbr.width}x${lbr.height} larger than viewport ${vw}x${vh}`);
  // Arrows shown over the stage must not cover the fitted photo.
  lb.querySelectorAll('.lb-nav').forEach((n) => {
    if (!n.offsetParent) return;
    const r = n.getBoundingClientRect();
    if (Math.min(r.right, ib.right) - Math.max(r.left, ib.left) > 0.5 && Math.min(r.bottom, ib.bottom) - Math.max(r.top, ib.top) > 0.5) problems.push('side arrow covers photo');
  });
  return {
    problems,
    stage: { l: stage.left, t: stage.top, r: stage.right, b: stage.bottom, cx: stage.left + stage.width / 2, cy: stage.top + stage.height / 2, w: stage.width, h: stage.height },
    img: { l: ib.left, t: ib.top, r: ib.right, b: ib.bottom, cx: ib.left + ib.width / 2, cy: ib.top + ib.height / 2, w: ib.width, h: ib.height, nw: img.naturalWidth, nh: img.naturalHeight, loading: img.classList.contains('is-loading') },
    zoom: lb.querySelector('.lb-zoom-level').textContent,
    transform: img.style.transform,
    count: lb.querySelector('.lb-count').textContent,
    docOverflow: document.documentElement.scrollWidth > vw + 1,
  };
}

async function settle(page) {
  await page.waitForTimeout(320);
}

async function run(browser, [name, path, sel], w) {
  const h = w < 700 ? Math.round(w * 2.1) : w < 1100 ? 1024 : 900;
  const touch = w < 1024;
  const ctx = await browser.newContext({ viewport: { width: w, height: h }, isMobile: w < 700, hasTouch: touch, deviceScaleFactor: 1 });
  await prepare(ctx);
  const page = await ctx.newPage();
  const errors = [];
  const fail = (m) => errors.push(m);
  page.on('pageerror', (e) => fail('JS error: ' + String(e.message || e).slice(0, 160)));
  try {
    await page.goto(WP + path, { waitUntil: 'load', timeout: 90000 });
    const trigger = page.locator(sel).first();
    await trigger.scrollIntoViewIfNeeded();
    await page.waitForTimeout(300);
    const scrollBefore = await page.evaluate(() => window.scrollY);
    await trigger.click();
    await page.waitForSelector('.lb');
    await page.waitForFunction(() => { const i = document.querySelector('.lb-img'); return i && i.complete && !i.classList.contains('is-loading'); }, null, { timeout: 30000 });
    await settle(page);

    const fits = (m, label) => {
      m.problems.forEach((p) => fail(`${label}: ${p}`));
      if (m.img.l < m.stage.l - TOL || m.img.r > m.stage.r + TOL || m.img.t < m.stage.t - TOL || m.img.b > m.stage.b + TOL) fail(`${label}: photo ${Math.round(m.img.w)}x${Math.round(m.img.h)} not inside stage ${Math.round(m.stage.w)}x${Math.round(m.stage.h)}`);
      if (Math.abs(m.img.cx - m.stage.cx) > TOL || Math.abs(m.img.cy - m.stage.cy) > TOL) fail(`${label}: photo off centre by ${Math.round(m.img.cx - m.stage.cx)},${Math.round(m.img.cy - m.stage.cy)}`);
      // It should fill the stage in at least one direction (unless the photo is smaller than the stage).
      const fillsW = m.img.w >= m.stage.w - 2 * 16 - 140, fillsH = m.img.h >= m.stage.h - 2 * 16 - TOL;
      const small = Math.max(m.img.w / Math.max(m.img.nw, m.img.nh), m.img.h / Math.max(m.img.nw, m.img.nh)) >= 0.99;
      if (!fillsW && !fillsH && !small) fail(`${label}: photo ${Math.round(m.img.w)}x${Math.round(m.img.h)} does not use the stage ${Math.round(m.stage.w)}x${Math.round(m.stage.h)}`);
      if (m.docOverflow) fail(`${label}: page scrolls sideways`);
    };

    const base = await page.evaluate(measure);
    fits(base, '0°');
    const ratio = base.img.w / base.img.h;
    const info = { natural: `${base.img.nw}x${base.img.nh}`, fitted: `${Math.round(base.img.w)}x${Math.round(base.img.h)}`, stage: `${Math.round(base.stage.w)}x${Math.round(base.stage.h)}` };

    // Rotate right through a full turn: the box swaps width and height at 90° and 270°.
    for (const deg of [90, 180, 270, 360]) {
      await page.click('.lb [data-act="right"]');
      await settle(page);
      const m = await page.evaluate(measure);
      fits(m, `${deg}°`);
      const want = deg % 180 ? 1 / ratio : ratio;
      if (Math.abs(m.img.w / m.img.h / want - 1) > 0.02) fail(`${deg}°: box ratio ${(m.img.w / m.img.h).toFixed(3)} expected ${want.toFixed(3)}`);
      if (deg === 90) info.turned = `${Math.round(m.img.w)}x${Math.round(m.img.h)}`;
    }
    await page.click('.lb [data-act="left"]');
    await settle(page);
    fits(await page.evaluate(measure), 'rotate left (270°)');

    // Zoom in twice while turned: stays centred, then pan to a corner and check it stops at the photo's edge.
    await page.click('.lb [data-act="in"]');
    await page.click('.lb [data-act="in"]');
    await settle(page);
    let m = await page.evaluate(measure);
    if (m.zoom !== '225%') fail(`zoom label ${m.zoom}, expected 225%`);
    if (Math.abs(m.img.cx - m.stage.cx) > TOL || Math.abs(m.img.cy - m.stage.cy) > TOL) fail('zoomed photo not centred');
    const sb = m.stage;
    await page.mouse.move(sb.cx, sb.cy);
    await page.mouse.down();
    await page.mouse.move(sb.cx + 3000, sb.cy + 3000, { steps: 8 });
    await page.mouse.up();
    await settle(page);
    m = await page.evaluate(measure);
    // After a drag to the bottom-right, the photo's top-left edge may come in by at most the 16px margin.
    if (m.img.w > m.stage.w && (m.img.l > m.stage.l + 16 + TOL)) fail(`pan: left edge ${Math.round(m.img.l - m.stage.l)}px inside the stage`);
    if (m.img.h > m.stage.h && (m.img.t > m.stage.t + 16 + TOL)) fail(`pan: top edge ${Math.round(m.img.t - m.stage.t)}px inside the stage`);
    if (m.img.r < m.stage.l + 40 || m.img.b < m.stage.t + 40) fail('pan: photo dragged out of view');

    // Reset: back to 0°, 100%, centred.
    await page.click('.lb [data-act="reset"]');
    await settle(page);
    m = await page.evaluate(measure);
    fits(m, 'reset');
    if (m.zoom !== '100%') fail(`reset: zoom ${m.zoom}`);
    if (Math.abs(m.img.w - base.img.w) > TOL || Math.abs(m.img.h - base.img.h) > TOL) fail('reset: size differs from first open');

    // Pinch with two fingers (real touch events) and double tap.
    if (touch) {
      const cdp = await ctx.newCDPSession(page);
      const c = m.stage;
      const tp = (dx) => [{ x: c.cx - dx, y: c.cy, id: 1 }, { x: c.cx + dx, y: c.cy, id: 2 }];
      await cdp.send('Input.dispatchTouchEvent', { type: 'touchStart', touchPoints: tp(30) });
      for (let d = 40; d <= 90; d += 10) await cdp.send('Input.dispatchTouchEvent', { type: 'touchMove', touchPoints: tp(d) });
      await cdp.send('Input.dispatchTouchEvent', { type: 'touchEnd', touchPoints: [] });
      await settle(page);
      m = await page.evaluate(measure);
      if (parseInt(m.zoom, 10) < 250) fail(`pinch: zoom ${m.zoom}, expected about 300%`);
      fits(await (async () => { await page.click('.lb [data-act="reset"]'); await settle(page); return page.evaluate(measure); })(), 'reset after pinch');
      const ic = await page.evaluate(measure);
      for (let i = 0; i < 2; i++) {
        await cdp.send('Input.dispatchTouchEvent', { type: 'touchStart', touchPoints: [{ x: ic.img.cx, y: ic.img.cy, id: 3 }] });
        await cdp.send('Input.dispatchTouchEvent', { type: 'touchEnd', touchPoints: [] });
        await page.waitForTimeout(90);
      }
      await settle(page);
      m = await page.evaluate(measure);
      if (m.zoom !== '250%') fail(`double tap: zoom ${m.zoom}, expected 250%`);
    }

    // Next photo (if any) opens at the default view even after rotating and zooming.
    {
      if (base.count) {
        await page.click('.lb [data-act="reset"]');
        await page.click('.lb [data-act="right"]');
        await page.click('.lb [data-act="in"]');
        await settle(page);
        await page.locator('.lb [data-act="next"]:visible').first().click();
        await page.waitForFunction(() => { const i = document.querySelector('.lb-img'); return i && i.complete && !i.classList.contains('is-loading'); }, null, { timeout: 30000 });
        await settle(page);
        m = await page.evaluate(measure);
        fits(m, 'next photo');
        if (m.zoom !== '100%' || !/rotate\(0deg\)/.test(m.transform)) fail(`next photo did not open at the default view (${m.zoom}, ${m.transform})`);
      }
      // Keyboard: R rotates, + zooms, Escape closes and focus returns to the photo that opened the viewer.
      await page.click('.lb [data-act="reset"]');
      await settle(page);
      // Click the photo first: the keys must still work when focus has left the viewer's buttons.
      m = await page.evaluate(measure);
      await page.mouse.click(m.img.cx, m.img.cy);
      await settle(page);
      await page.keyboard.press('r');
      await settle(page);
      fits(await page.evaluate(measure), 'key R');
      await page.keyboard.press('Escape');
      await page.waitForTimeout(100);
    }
    if (await page.$('.lb')) fail('Escape did not close the viewer');
    const back = await page.evaluate(() => document.activeElement && document.activeElement.matches('[data-gallery] img, img[data-zoom]'));
    if (!back) fail('focus did not return to the photo');
    const scrollAfter = await page.evaluate(() => window.scrollY);
    if (Math.abs(scrollAfter - scrollBefore) > 2) fail(`page scrolled behind the viewer (${scrollBefore} → ${scrollAfter})`);
    return { info, errors };
  } catch (e) {
    return { errors: errors.concat('crash: ' + String(e.message || e).split('\n')[0].slice(0, 200)) };
  } finally {
    await ctx.close();
  }
}

(async () => {
  const browser = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });
  const results = [];
  const jobs = [];
  for (const p of PAGES) for (const w of WIDTHS) jobs.push([p, w]);
  let next = 0;
  await Promise.all(Array.from({ length: 4 }, async () => {
    while (next < jobs.length) {
      const [p, w] = jobs[next++];
      const r = await run(browser, p, w);
      const row = { page: p[0], width: w, ...r };
      console.log(JSON.stringify({ page: row.page, width: w, ok: !r.errors.length, ...(r.errors.length ? { errors: r.errors } : { info: r.info }) }));
      results.push(row);
    }
  }));
  await browser.close();
  results.sort((a, b) => a.page.localeCompare(b.page) || a.width - b.width);
  fs.writeFileSync(OUT, JSON.stringify(results, null, 1));
  const bad = results.filter((r) => r.errors.length).length;
  console.log(`${results.length - bad}/${results.length} passed`);
})();
