// Final pass (whole site, logged out): every page found from the home page, the sitemap and internal links, checked for
//   consistency  — header/footer markup identical on every page (current-item marks ignored), fonts in use,
//                  hero heading size, colours drawn that are not in the token/design palette (computed styles)
//   responsive   — horizontal overflow at 360 / 768 / 1024 / 1440
//   a11y         — axe-core WCAG 2.1 A/AA at 1440 and 360, images without alt, tap targets under 44px at 360
//                  (links inside running text excluded: WCAG exempts them), a visible focus style on the first Tab stops
//   SEO          — <title>, meta description, canonical, og:image, favicon; unique titles/descriptions across pages
//   performance  — images without lazy-loading below the first screen, images without width/height,
//                  images served much larger than drawn, scripts loaded and how much of each ran (JS coverage);
//                  NO video request on a 390px viewport (network log, whole page scrolled), and as a control the
//                  home page at 1440 must request its hero video
// usage: node final_pass.js [outfile.json]      env: WP_URL (default http://127.0.0.1:8899), CHROME_PATH
const fs = require('fs');
const path = require('path');
const { chromium } = require('playwright-core');
const B = (process.env.WP_URL || 'http://127.0.0.1:8899').replace(/\/$/, '');
const OUT = process.argv[2] || path.join(__dirname, 'out', 'final_pass.json');
const AXE = fs.readFileSync(require.resolve('axe-core/axe.min.js'), 'utf8');
const WIDTHS = [360, 768, 1024, 1440];

async function collectPages(ctx) {
  const p = await ctx.newPage();
  const queue = ['/'];
  const sm = await (await p.request.get(B + '/wp-sitemap.xml')).text();
  for (const m of sm.matchAll(/<loc>([^<]+)<\/loc>/g)) {
    const x = await (await p.request.get(m[1])).text();
    for (const n of x.matchAll(/<loc>([^<]+)<\/loc>/g)) queue.push(new URL(n[1]).pathname);
  }
  const seen = new Set();
  while (queue.length) {
    const u = queue.shift();
    if (seen.has(u)) continue;
    const r = await p.goto(B + u, { waitUntil: 'domcontentloaded' }).catch(() => null);
    if (!r || r.status() !== 200 || !p.url().startsWith(B)) continue;
    seen.add(new URL(p.url()).pathname);
    const links = await p.$$eval('a[href]', (as) => as.map((a) => a.href));
    for (const l of links) {
      if (!l.startsWith(B)) continue;
      const pn = new URL(l).pathname;
      if (/^\/wp-|\/feed\/?$|\.(jpg|jpeg|png|webp|pdf|mp4|xml)$/i.test(pn) || /\/page\/\d+\/$/.test(pn)) continue;
      if (!seen.has(pn) && seen.size + queue.length < 300) queue.push(pn);
    }
  }
  await p.close();
  return [...seen];
}

(async () => {
  const b = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });
  const ctx = await b.newContext();
  await ctx.addInitScript(() => { try { localStorage.setItem('krm-newsletter', JSON.stringify({ dismissed: Date.now() })); } catch (e) {} });
  const pages = await collectPages(ctx);
  console.error('pages', pages.length);
  const results = {};
  for (const u of pages) {
    const r = (results[u] = { overflow: {}, axe: {} });
    // ---- 1440: consistency, SEO, perf, axe
    let p = await ctx.newPage();
    await p.setViewportSize({ width: 1440, height: 900 });
    const errors = [];
    p.on('pageerror', (e) => errors.push(e.message));
    await p.coverage.startJSCoverage({ resetOnNavigation: false });
    await p.goto(B + u, { waitUntil: 'load' });
    await p.waitForTimeout(600);
    Object.assign(r, await p.evaluate(() => {
      const norm = (el) => (el ? el.outerHTML
        .replace(/\s(aria-current|data-current)="[^"]*"/g, '')
        .replace(/\b(current-menu-item|current_page_item|current-menu-ancestor|current-menu-parent|current_page_parent|current_page_ancestor|is-current|is-active|active)\b/g, '')
        .replace(/\s+/g, ' ') : '');
      const hash = (s) => { let h = 0; for (let i = 0; i < s.length; i++) h = (h * 31 + s.charCodeAt(i)) | 0; return (h >>> 0).toString(16); };
      const header = document.querySelector('body > header, header.site-header, #masthead, header');
      const footer = Array.from(document.querySelectorAll('footer')).pop();
      const fonts = new Set();
      const colours = {};
      const vis = (el) => { const s = getComputedStyle(el); const rc = el.getBoundingClientRect(); return s.visibility !== 'hidden' && s.display !== 'none' && rc.width > 0 && rc.height > 0; };
      for (const el of document.querySelectorAll('body *')) {
        if (el.closest('script,style,svg,noscript,iframe,.krm-admin-only') || !vis(el)) continue;
        const s = getComputedStyle(el);
        if (el.childNodes.length && Array.from(el.childNodes).some((n) => n.nodeType === 3 && n.textContent.trim())) {
          fonts.add(s.fontFamily.split(',')[0].replace(/["']/g, '').trim());
          colours['color ' + s.color] = colours['color ' + s.color] || (el.tagName.toLowerCase() + '.' + String(el.className).split(' ')[0]);
        }
        if (s.backgroundColor !== 'rgba(0, 0, 0, 0)') colours['bg ' + s.backgroundColor] = colours['bg ' + s.backgroundColor] || (el.tagName.toLowerCase() + '.' + String(el.className).split(' ')[0]);
      }
      const h1 = document.querySelector('h1');
      const meta = (sel) => { const m = document.querySelector(sel); return m ? (m.content || m.href || '') : ''; };
      const imgs = Array.from(document.querySelectorAll('img'));
      const fold = innerHeight;
      return {
        title: document.title,
        desc: meta('meta[name="description"]'),
        canonical: meta('link[rel="canonical"]'),
        ogImage: meta('meta[property="og:image"]'),
        ogTitle: meta('meta[property="og:title"]'),
        favicon: meta('link[rel~="icon"]'),
        h1count: document.querySelectorAll('h1').length,
        h1size: h1 ? getComputedStyle(h1).fontSize : '',
        headerHash: hash(norm(header)), footerHash: hash(norm(footer)),
        fonts: [...fonts], colours,
        imgNoAlt: imgs.filter((i) => !i.hasAttribute('alt')).map((i) => i.src.split('/').pop()),
        imgEagerBelowFold: imgs.filter((i) => i.getBoundingClientRect().top + scrollY > fold * 1.5 && i.loading !== 'lazy').map((i) => i.src.split('/').pop()),
        imgNoSize: imgs.filter((i) => !i.getAttribute('width') || !i.getAttribute('height')).map((i) => i.src.split('/').pop()),
        imgOversized: imgs.filter((i) => i.complete && i.naturalWidth && i.getBoundingClientRect().width > 0 && i.naturalWidth > i.getBoundingClientRect().width * devicePixelRatio * 2.5 && i.naturalWidth > 600).map((i) => i.src.split('/').pop() + ' ' + i.naturalWidth + '>' + Math.round(i.getBoundingClientRect().width)),
        scripts: Array.from(document.scripts).filter((s) => s.src).map((s) => s.src.replace(/^.*\/(wp-content|wp-includes)\//, '').replace(/\?.*/, '')),
      };
    }));
    const cov = await p.coverage.stopJSCoverage();
    r.jsCoverage = cov.filter((c) => c.url.startsWith(B) && !/\?krm|inline/.test(c.url)).map((c) => {
      const total = c.source ? c.source.length : 0;
      const used = new Set();
      for (const f of c.functions) for (const rg of f.ranges) if (rg.count > 0) for (let i = rg.startOffset; i < rg.endOffset; i += 50) used.add(i);
      return [c.url.replace(/^.*\/(wp-content\/themes\/kingdom-revival|wp-includes)\//, '').replace(/\?.*/, ''), total, Math.min(100, Math.round((used.size * 50 * 100) / Math.max(total, 1)))];
    });
    // focus visible on the first Tab stops
    r.focus = [];
    for (let i = 0; i < 6; i++) {
      await p.keyboard.press('Tab');
      r.focus.push(await p.evaluate(() => {
        const el = document.activeElement; if (!el || el === document.body) return 'body';
        const s = getComputedStyle(el);
        const ok = (s.outlineStyle !== 'none' && parseFloat(s.outlineWidth) > 0) || s.boxShadow !== 'none';
        return (ok ? 'ok ' : 'NONE ') + el.tagName.toLowerCase() + ' ' + (el.textContent || el.getAttribute('aria-label') || '').trim().slice(0, 20);
      }));
    }
    await p.addScriptTag({ content: AXE });
    r.axe[1440] = await p.evaluate(async () => (await axe.run(document, { runOnly: ['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa'] })).violations.map((v) => [v.id, v.impact, v.nodes.length, v.nodes.slice(0, 2).map((n) => n.target.join(' '))]));
    r.overflow[1440] = await p.evaluate(() => document.documentElement.scrollWidth - innerWidth);
    r.errors = errors;
    await p.close();
    // ---- 360 / 768 / 1024
    for (const w of [360, 768, 1024]) {
      p = await ctx.newPage();
      await p.setViewportSize({ width: w, height: w === 360 ? 740 : 1000 });
      p.on('pageerror', (e) => errors.push(w + ' ' + e.message));
      await p.goto(B + u, { waitUntil: 'load' });
      await p.waitForTimeout(300);
      r.overflow[w] = await p.evaluate(() => document.documentElement.scrollWidth - innerWidth);
      if (w === 360) {
        r.smallTargets = await p.evaluate(() => {
          const out = new Set();
          for (const el of document.querySelectorAll('a[href], button, input:not([type=hidden]), select, textarea, [role=button], [role=tab], summary')) {
            const rc = el.getBoundingClientRect(); const s = getComputedStyle(el);
            if (!rc.width || !rc.height || s.visibility === 'hidden' || el.closest('[hidden], .krm-hp, .visually-hidden, .screen-reader-text')) continue;
            if (el.tagName === 'A' && s.display === 'inline' && el.closest('p, li > p, figcaption, .entry-content, td') && !el.closest('nav')) continue; // links in running text
            if ((el.type === 'checkbox' || el.type === 'radio') && el.closest('label') && el.closest('label').getBoundingClientRect().height >= 44) continue;
            if (rc.height < 44 || rc.width < 44) {
              const lab = el.closest('label');
              if (lab && lab.getBoundingClientRect().height >= 44 && lab.getBoundingClientRect().width >= 44) continue;
              out.add(el.tagName.toLowerCase() + (el.className ? '.' + String(el.className).split(' ')[0] : '') + ' "' + (el.textContent || el.getAttribute('aria-label') || el.name || '').trim().slice(0, 24) + '" ' + Math.round(rc.width) + 'x' + Math.round(rc.height));
            }
          }
          return [...out];
        });
        await p.addScriptTag({ content: AXE });
        r.axe[360] = await p.evaluate(async () => (await axe.run(document, { runOnly: ['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa'] })).violations.map((v) => [v.id, v.impact, v.nodes.length, v.nodes.slice(0, 2).map((n) => n.target.join(' '))]));
      }
      await p.close();
    }
    // ---- 390: the network log must contain no video request (hero video is desktop-only; others play on click)
    {
      p = await ctx.newPage();
      await p.setViewportSize({ width: 390, height: 844 });
      const media = [];
      p.on('request', (q) => { if (q.resourceType() === 'media' || /\.(mp4|webm|mov|m4v|ogv)(\?|$)/i.test(q.url())) media.push(q.url().replace(B, '')); });
      await p.goto(B + u, { waitUntil: 'load' });
      await p.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 600) { scrollTo(0, y); await new Promise((res) => setTimeout(res, 50)); } });
      await p.waitForTimeout(800);
      r.videoRequests390 = media;
      await p.close();
    }
    console.error(u, JSON.stringify(r.overflow), 'axe', r.axe[1440].length, r.axe[360].length, 'small', r.smallTargets.length);
  }
  // site-wide
  const vals = Object.values(results);
  const dup = (k) => { const m = {}; for (const [u, r] of Object.entries(results)) (m[r[k]] = m[r[k]] || []).push(u); return Object.entries(m).filter(([v, us]) => us.length > 1).map(([v, us]) => [v, us]); };
  const count = (k) => { const m = {}; for (const [u, r] of Object.entries(results)) (m[r[k]] = m[r[k]] || []).push(u); return Object.entries(m).map(([v, us]) => [v, us.length, us.slice(0, 4)]); };
  const summary = {
    pages: pages.length,
    headerVariants: count('headerHash'), footerVariants: count('footerHash'),
    fonts: [...new Set(vals.flatMap((r) => r.fonts))],
    duplicateTitles: dup('title'), duplicateDescriptions: dup('desc'),
    missing: Object.entries(results).filter(([u, r]) => !r.title || !r.desc || !r.ogImage || !r.favicon || !r.canonical || r.h1count !== 1).map(([u, r]) => [u, { title: !!r.title, desc: !!r.desc, og: !!r.ogImage, favicon: !!r.favicon, canonical: !!r.canonical, h1: r.h1count }]),
    overflow: Object.entries(results).filter(([u, r]) => Object.values(r.overflow).some((v) => v > 0)).map(([u, r]) => [u, r.overflow]),
    jsErrors: Object.entries(results).filter(([u, r]) => r.errors.length).map(([u, r]) => [u, r.errors]),
    videoRequestsOn390: Object.entries(results).filter(([u, r]) => r.videoRequests390.length).map(([u, r]) => [u, r.videoRequests390]),
  };
  // Control: the same detection must see the hero video on a desktop viewport, else the 390 check proves nothing.
  {
    const p = await ctx.newPage();
    await p.setViewportSize({ width: 1440, height: 900 });
    const media = [];
    p.on('request', (q) => { if (q.resourceType() === 'media' || /\.(mp4|webm)(\?|$)/i.test(q.url())) media.push(q.url().replace(B, '')); });
    await p.goto(B + '/', { waitUntil: 'load' });
    await p.waitForTimeout(2500);
    summary.heroVideoOn1440 = [...new Set(media)];
    await p.close();
  }
  fs.mkdirSync(path.dirname(OUT), { recursive: true });
  fs.writeFileSync(OUT, JSON.stringify({ summary, results }, null, 1));
  console.log(JSON.stringify(summary, null, 1));
  await b.close();
})();
