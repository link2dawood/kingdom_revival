// Placeholder check (Phase 19 follow-up): crawls the site LOGGED OUT from the home page and the WordPress sitemap,
// following every internal link, and lists any "[…]" placeholder left in visible text, alt/title/aria-label/
// placeholder attributes, the <title> or meta tags. Also reports links to the Testimonies page and to hidden
// ministries, and whether the sitemap lists them. Every list should be empty for visitors.
// usage: node placeholder_check.js     env: WP_URL (default http://127.0.0.1:8899), CHROME_PATH
const { chromium } = require('playwright-core');
const B = (process.env.WP_URL || 'http://127.0.0.1:8899').replace(/\/$/, '');
(async () => {
  const b = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });
  const p = await (await b.newContext({ viewport: { width: 1440, height: 900 } })).newPage();
  const seen = new Set(), queue = ['/'], found = [], codes = {}, testiLinks = [], hiddenLinks = [];
  // sitemap URLs too
  for (const sm of ['/wp-sitemap.xml']) {
    const r = await p.request.get(B + sm); const x = await r.text();
    for (const m of x.matchAll(/<loc>([^<]+)<\/loc>/g)) {
      if (/\.xml$/.test(m[1])) { const y = await (await p.request.get(m[1])).text(); for (const n of y.matchAll(/<loc>([^<]+)<\/loc>/g)) queue.push(new URL(n[1]).pathname + new URL(n[1]).search); }
    }
  }
  const sitemapAll = [...queue];
  while (queue.length) {
    const u = queue.shift(); if (seen.has(u)) continue; seen.add(u);
    const resp = await p.goto(B + u, { waitUntil: 'load' }).catch(() => null);
    codes[u] = resp ? resp.status() : 'ERR';
    if (!resp || resp.status() >= 400 || !p.url().startsWith(B)) continue;
    const r = await p.evaluate(() => {
      const re = /\[[^\]]*\]/;
      const hits = [];
      const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT);
      let n; while ((n = walker.nextNode())) {
        const el = n.parentElement; if (!el || el.closest('script,style,noscript,template')) continue;
        if (re.test(n.textContent)) hits.push('text: ' + n.textContent.trim().slice(0, 80) + '  <' + el.tagName.toLowerCase() + (el.className ? '.' + String(el.className).split(' ')[0] : '') + '>');
      }
      document.querySelectorAll('[alt],[title],[aria-label],[placeholder],[value]').forEach(e => ['alt', 'title', 'aria-label', 'placeholder'].forEach(a => { const v = e.getAttribute(a); if (v && re.test(v)) hits.push(a + ': ' + v.slice(0, 80)); }));
      if (re.test(document.title)) hits.push('title: ' + document.title);
      document.querySelectorAll('meta[content]').forEach(m => { if (re.test(m.content)) hits.push('meta ' + (m.name || m.getAttribute('property')) + ': ' + m.content.slice(0, 80)); });
      const links = Array.from(document.querySelectorAll('a[href]')).map(a => a.href);
      return { hits, links };
    });
    if (r.hits.length) found.push([u, [...new Set(r.hits)]]);
    for (const l of r.links) {
      if (/\/our-impact\/testimonies/.test(l)) testiLinks.push(u + ' -> ' + l);
      if (/\/what-we-do\/(food-relief|persecuted-christians|disaster-relief|youth-or-women)/.test(l)) hiddenLinks.push(u + ' -> ' + l);
      if (!l.startsWith(B)) continue;
      const url = new URL(l); if (/^\/wp-(admin|login|json|content|includes)|\/feed\/?$|xmlrpc|\.(jpg|png|webp|pdf|mp4|xml)$/.test(url.pathname)) continue;
      const key = url.pathname + (url.search && !/replytocom/.test(url.search) ? url.search : '');
      if (!seen.has(key) && seen.size + queue.length < 400) queue.push(key);
    }
  }
  console.log('pages crawled', seen.size, 'non-200:', JSON.stringify(Object.entries(codes).filter(([u, c]) => c !== 200)));
  // Phase 21: Testimonies is always public (menu, links, sitemap); only hidden ministries must be absent.
  console.log('sitemap has hidden ministries:', sitemapAll.filter(u => /food-relief|persecuted|disaster-relief|youth-or-women/.test(u)));
  console.log('links to testimonies (expected since Phase 21):', testiLinks.length);
  console.log('links to hidden ministries:', hiddenLinks.length, hiddenLinks.slice(0, 5));
  console.log('PAGES WITH PLACEHOLDERS:', found.length);
  for (const [u, h] of found) console.log(u, JSON.stringify(h));
  await b.close();
  process.exitCode = found.length ? 1 : 0;
})();
