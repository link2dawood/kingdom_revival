const { chromium } = require('playwright-core');
const fs = require('fs');
const path = require('path');

const base = process.env.WP_URL || 'http://127.0.0.1:8899';
const out = process.argv[2] || 'out/phase23';
const shots = path.join(out, 'shots');
fs.mkdirSync(shots, { recursive: true });

(async () => {
  const browser = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome', headless: true });
  const crawl = await browser.newPage();
  const queue = ['/'];
  const seen = new Set();
  const pages = [];
  const failures = [];
  while (queue.length) {
    const pathname = queue.shift();
    if (seen.has(pathname)) continue;
    seen.add(pathname);
    const response = await crawl.goto(base + pathname, { waitUntil: 'networkidle' });
    if (!response || response.status() >= 400) {
      failures.push(`${pathname}: HTTP ${response ? response.status() : 'none'}`);
      continue;
    }
    const legacy = await crawl.locator('.testimony .avatar, .testimonial .avatar').count();
    if (legacy) failures.push(`${pathname}: ${legacy} legacy grey avatar(s)`);
    const avatarCount = await crawl.locator('.krm-avatar').count();
    if (avatarCount) pages.push({ pathname, avatarCount });
    const hrefs = await crawl.locator('a[href]').evaluateAll((links, origin) => links.map(a => a.href).filter(h => h.startsWith(origin)), new URL(base).origin);
    for (const href of hrefs) {
      const u = new URL(href);
      if (u.origin === new URL(base).origin && !u.pathname.startsWith('/wp-') && !seen.has(u.pathname)) queue.push(u.pathname);
    }
  }
  await crawl.close();

  const screenshotPages = process.env.ONLY ? pages.filter(p => p.pathname === process.env.ONLY) : pages;
  for (const { pathname } of screenshotPages) {
    for (const width of [390, 1440]) {
      for (const theme of ['light', 'dark']) {
        const page = await browser.newPage({ viewport: { width, height: width === 390 ? 844 : 1000 }, colorScheme: theme });
        await page.addInitScript(t => localStorage.setItem('krm-theme', t), theme);
        await page.goto(base + pathname, { waitUntil: 'networkidle' });
        await page.evaluate(() => {
          document.documentElement.dataset.theme = localStorage.getItem('krm-theme');
          if (window.krmReveal) window.krmReveal.flush();
        });
        const slug = pathname === '/' ? 'home' : pathname.replace(/^\/+|\/+$/g, '').replaceAll('/', '--');
        await page.screenshot({ path: path.join(shots, `${slug}-${width}-${theme}.png`), fullPage: true, type: 'png' });
        const avatars = await page.locator('.krm-avatar').evaluateAll(nodes => nodes.map(n => ({
          w: n.getBoundingClientRect().width,
          h: n.getBoundingClientRect().height,
          text: n.textContent.trim(),
          hidden: n.getAttribute('aria-hidden'),
          bg: getComputedStyle(n).backgroundColor,
          color: getComputedStyle(n).color,
          font: getComputedStyle(n).fontFamily,
          weight: getComputedStyle(n).fontWeight,
          size: getComputedStyle(n).fontSize,
        })));
        avatars.forEach((a, i) => {
          if (a.w !== 48 || a.h !== 48 || a.hidden !== 'true' || a.weight !== '800' || a.size !== '20px' || !a.font.includes('Plus Jakarta Sans')) failures.push(`${pathname} ${width} ${theme} avatar ${i}: bad geometry/type/a11y ${JSON.stringify(a)}`);
          if (a.bg === 'rgb(221, 227, 240)') failures.push(`${pathname} ${width} ${theme} avatar ${i}: grey placeholder remains`);
        });
        await page.close();
      }
    }
  }
  await browser.close();
  const result = { crawled: seen.size, avatarPages: pages, screenshots: screenshotPages.length * 4, failures };
  fs.writeFileSync(path.join(out, 'result.json'), JSON.stringify(result, null, 2));
  console.log(JSON.stringify(result));
  process.exitCode = failures.length ? 1 : 0;
})().catch(error => { console.error(error); process.exit(1); });
