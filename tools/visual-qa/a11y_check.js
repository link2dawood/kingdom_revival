// Accessibility audit (axe-core, WCAG 2.1 A/AA) of the original HTML pages and the WordPress pages.
// usage: node a11y_check.js [outfile] [pageName ...]   (default outfile: out/a11y.json; WIDTH=390 for mobile)
// Prints one JSON line per page: violations per rule on each side, so design-inherited issues can be told apart
// from ones the theme adds. Also checks keyboard basics: skip link target, focus visible on the first Tab stops.
const { chromium } = require('playwright-core');
const fs = require('fs');
const path = require('path');
const { prepare } = require('./lib');

const HTML = process.env.HTML_URL || 'http://127.0.0.1:8898';
const WP = process.env.WP_URL || 'http://127.0.0.1:8899';
const PAGES = {
  home: ['/index.html', '/'],
  about: ['/about.html', '/about/'],
  contact: ['/contact.html', '/contact/'],
  donate: ['/donate.html', '/donate/'],
  partner: ['/partner.html', '/partner-with-us/'],
  blog: ['/blog.html', '/news/'],
  post: ['/blog-post.html?post=learning-beside-the-kilns', '/news/learning-beside-the-kilns/'],
  ministry: ['/all-ministries.html?ministry=brick-kiln-children', '/what-we-do/brick-kiln-family-care/'],
  'ministries-none': ['/all-ministries.html', '/what-we-do/'],
  notfound: [null, '/no-such-page-krm/'],
};
const AXE = fs.readFileSync(require.resolve('axe-core/axe.min.js'), 'utf8');
const out = process.argv[2] || 'out/a11y.json';
const only = process.argv.slice(3);

async function audit(page, url) {
  await page.goto(url, { waitUntil: 'load', timeout: 120000 });
  await page.waitForTimeout(1500);
  await page.addScriptTag({ content: AXE });
  const res = await page.evaluate(async () => {
    const r = await window.axe.run(document, { runOnly: { type: 'tag', values: ['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'best-practice'] } });
    return r.violations.map((v) => ({
      id: v.id,
      impact: v.impact,
      help: v.help,
      nodes: v.nodes.slice(0, 6).map((n) => ({ target: n.target.join(' '), summary: (n.failureSummary || '').split('\n').slice(0, 3).join(' | '), html: n.html.slice(0, 160) })),
      count: v.nodes.length,
    }));
  });
  // Keyboard: first Tab stop should be the skip link, its target should exist, and the next stops should show a focus ring.
  const keys = await page.evaluate(() => {
    const skip = document.querySelector('.skip-link');
    const id = skip ? skip.getAttribute('href').slice(1) : '';
    return { skip: !!skip, target: id ? !!document.getElementById(id) : false };
  });
  await page.evaluate(() => { document.activeElement && document.activeElement.blur(); window.scrollTo(0, 0); });
  const stops = [];
  for (let i = 0; i < 6; i++) {
    await page.keyboard.press('Tab');
    stops.push(await page.evaluate(() => {
      const el = document.activeElement;
      if (!el || el === document.body) return null;
      const cs = getComputedStyle(el);
      const ring = (cs.outlineStyle !== 'none' && parseFloat(cs.outlineWidth) > 0) || cs.boxShadow !== 'none';
      return { el: el.tagName.toLowerCase() + (el.className && typeof el.className === 'string' ? '.' + el.className.split(' ')[0] : ''), text: (el.innerText || el.getAttribute('aria-label') || '').trim().slice(0, 30), ring };
    }));
  }
  return { violations: res, keys, stops };
}

(async () => {
  const browser = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });
  const lines = [];
  for (const [name, [h, w]] of Object.entries(PAGES)) {
    if (only.length && !only.includes(name)) continue;
    const row = { page: name };
    for (const [side, base, p] of [['html', HTML, h], ['wp', WP, w]]) {
      if (!p) continue;
      const ctx = await browser.newContext({ viewport: { width: Number(process.env.WIDTH) || 1440, height: 900 } });
      await prepare(ctx);
      const page = await ctx.newPage();
      try {
        const r = await audit(page, base + p);
        row[side] = r;
      } catch (e) {
        row[side] = { error: String(e).slice(0, 200) };
      }
      await ctx.close();
    }
    const summary = (r) => (r && r.violations ? Object.fromEntries(r.violations.map((v) => [v.id, v.count])) : r);
    console.log(JSON.stringify({ page: name, html: summary(row.html), wp: summary(row.wp), wpKeys: row.wp && row.wp.keys }));
    lines.push(row);
  }
  fs.mkdirSync(path.dirname(out), { recursive: true });
  fs.writeFileSync(out, JSON.stringify(lines, null, 1));
  await browser.close();
})();
