// Phase 22: the client's navbar, checked on the RENDERED site (logged out, caches bypassed), so a menu stored in the
// database that differs from the theme can't slip through again.
//   structure  — top level exactly: Home · About Us · What We Do ▾ · Our Impact ▾ · News & Updates · Partner With Us ·
//                Contact · Donate (red button); What We Do = two linked group headings with their ministries in
//                the client's order + "View all of What We Do →"; Our Impact = 3 items; no "Ministries" menu item;
//                no ">Ministries<" anywhere in the home page HTML; Youth Ministry / Women Empowerment not listed
//   footer     — Quick Links exactly: Home, About Us, What We Do, Our Impact, News & Updates, Partner With Us, Contact,
//                Donate
//   links      — every link of both dropdowns, the top level and Quick Links answers 200 itself (no redirect, no 404)
//   behaviour  — desktop: hover opens What We Do; click / Enter set aria-expanded; Esc closes and returns focus;
//                a click outside closes; aria-current on the page, its group heading and the top-level item.
//                mobile 390: menu → What We Do → both groups (chevrons, aria-expanded) and Our Impact open
//   sameHeader — the same navbar on 8 pages as a visitor gets them; cache — no page served from a page cache built
//                with another theme version (a private window showed the old header on live, theme 1.17.1);
//                testimonials — cards, no form, heading "Testimonials"
//   soon       — Persecuted Christian Support, Food Relief, Disaster Relief: 200, "Details about this ministry are
//                coming soon.", Donate with ?ministry=slug, Contact us, the other-ministries row, no stats /
//                gifts / story / gallery sections, no [brackets]
//   shots      — out/nav/ (named after the output file): desktop 1440 closed / What We Do open / Our Impact open, mobile 390 menu with What We Do
//                (both groups) open / Our Impact open; light and dark
// usage: node nav_check.js [outfile.json]   env: WP_URL (default http://127.0.0.1:8899; the live site works too,
//        read-only), CHROME_PATH. Exit code 1 on any failure.
const fs = require('fs');
const path = require('path');
const { chromium } = require('playwright-core');

const B = (process.env.WP_URL || 'http://127.0.0.1:8899').replace(/\/$/, '');
const OUT = process.argv[2] || path.join(__dirname, 'out', 'nav.json');
const SHOTS = path.join(path.dirname(OUT), path.basename(OUT, '.json')); // out/nav.json → out/nav/
const BUST = 'krmnav=' + Date.now(); // A query string no page cache has seen: the page is built fresh.

const TOP = ['Home', 'About Us', 'What We Do', 'Our Impact', 'News & Updates', 'Partner With Us', 'Contact', 'Donate'];
const WWD = 'what-we-do';
const GROUPS = [
  ['Gospel & Spiritual Ministries', `/${WWD}/gospel-spiritual-ministries/`, [
    ['Evangelism & Outreach', 'evangelism-outreach'], ['Church Planting', 'church-planting'], ['Bible Distribution', 'bible-distribution'],
    ['Leadership Training & Equipping', 'leadership-training'], ['Baptisms & Discipleship', 'baptisms-discipleship'],
    ['Prayer & Deliverance Ministry', 'prayer-deliverance'],
  ]],
  ['Compassion & Humanitarian Ministries', `/${WWD}/compassion-humanitarian-ministries/`, [
    ['Slavery & Freedom Ministry', 'slavery-freedom'], ['Brick Kiln Family Care', 'brick-kiln-family-care'],
    ['Children & Orphan Care', 'children-orphan-care'], ['Clean Water Projects', 'clean-water'],
    ['Persecuted Christian Support', 'persecuted-christians'], ['Widows Support', 'widows-support'],
    ['Medical & Health Outreach', 'medical-health'], ['Food Relief', 'food-relief'],
    ['Disaster Relief & Recovery Ministry', 'disaster-relief'],
  ]],
];
const IMPACT = [['Our Impact', '/our-impact/'], ['Testimonials', '/our-impact/testimonies/'], ['Photo & Video Gallery', '/our-impact/gallery/']];
const QUICK = ['Home', 'About Us', 'What We Do', 'Our Impact', 'News & Updates', 'Partner With Us', 'Contact', 'Donate'];
const SOON = ['persecuted-christians', 'food-relief', 'disaster-relief'];
const SOON_LINE = 'Details about this ministry are coming soon.';

const results = { site: B, checked: new Date().toISOString(), structure: {}, footer: [], links: [], behaviour: {}, soon: {}, shots: [], failures: [] };
const fail = (area, msg) => { results.failures.push(area + ': ' + msg); console.error('FAIL', area, msg); };
const pathOf = (u) => { try { const x = new URL(u, B); return x.origin === new URL(B).origin ? x.pathname : u; } catch (e) { return u; } };
const withBust = (u) => u + (u.includes('?') ? '&' : '?') + BUST;
// One GET, redirects not followed: { status, location, body }. (Node's http, not fetch: undici trips over the PHP
// development server's responses.)
const get = (u) => new Promise((resolve) => {
  const mod = u.startsWith('https:') ? require('https') : require('http');
  const req = mod.get(u, { headers: { 'Cache-Control': 'no-cache', Pragma: 'no-cache', 'User-Agent': 'KRM-nav-check' } }, (res) => {
    let body = '';
    res.setEncoding('utf8');
    res.on('data', (c) => { body += c; });
    res.on('end', () => resolve({ status: res.statusCode, location: res.headers.location || '', body }));
  });
  req.setTimeout(30000, () => req.destroy(new Error('timeout')));
  req.on('error', (e) => resolve({ status: String(e.message), location: '', body: '' }));
});
const clean = (s) => (s || '').replace(/[→→]/g, '').replace(/\s+/g, ' ').trim();

async function context(b, theme, w) {
  const ctx = await b.newContext({
    viewport: { width: w, height: w < 600 ? 844 : 900 },
    colorScheme: theme === 'dark' ? 'dark' : 'light',
    extraHTTPHeaders: { 'Cache-Control': 'no-cache', Pragma: 'no-cache' },
  });
  await ctx.addInitScript((t) => {
    try {
      localStorage.setItem('krm-newsletter', JSON.stringify({ dismissed: Date.now() }));
      localStorage.setItem('krm-theme', t);
    } catch (e) {}
  }, theme);
  return ctx;
}

// The main menu as rendered: [{ title, href, cls, children: [...] }].
const readMenu = () => {
  const walk = (ul) => Array.from(ul.children).filter((li) => !li.classList.contains('menu-theme')).map((li) => {
    const head = li.querySelector(':scope > a, :scope > .sub-toggle:not([aria-label])');
    const link = li.querySelector(':scope > a');
    const sub = li.querySelector(':scope > .submenu');
    return {
      title: (head ? head.textContent : li.textContent).replace(/\s+/g, ' ').trim(),
      href: link ? link.href : '',
      cls: li.className,
      children: sub ? walk(sub) : [],
    };
  });
  return walk(document.getElementById('site-menu'));
};

async function shot(p, name) {
  const file = path.join(SHOTS, name + '.png');
  await p.screenshot({ path: file });
  results.shots.push(path.relative(path.join(__dirname, '..', '..'), file));
}

(async () => {
  fs.mkdirSync(SHOTS, { recursive: true });
  const b = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });

  // ---------- raw HTML of the home page (what a logged-out visitor is served)
  const raw = (await get(withBust(B + '/'))).body;
  results.structure.ministriesInHomeHtml = (raw.match(/>\s*Ministries\s*</g) || []).length;
  if (results.structure.ministriesInHomeHtml) fail('structure', `">Ministries<" found ${results.structure.ministriesInHomeHtml}× in the home page HTML`);
  if (/Youth Ministry \/ Women Empowerment/.test(raw)) fail('structure', 'Youth Ministry / Women Empowerment is listed on the home page');

  // ---------- structure + footer (desktop, light)
  const ctx = await context(b, 'light', 1440);
  const p = await ctx.newPage();
  const errors = [];
  p.on('pageerror', (e) => errors.push(String(e)));
  await p.goto(withBust(B + '/'), { waitUntil: 'networkidle' });
  const menu = await p.evaluate(readMenu);
  results.structure.menu = menu;
  const top = menu.map((n) => clean(n.title));
  if (JSON.stringify(top) !== JSON.stringify(TOP)) fail('structure', 'top level is [' + top.join(' · ') + ']');
  if (top.some((t) => /^(Our )?Ministries$/i.test(t))) fail('structure', '"Ministries" is a top-level item');
  const wwd = menu.find((n) => clean(n.title) === 'What We Do');
  if (wwd) {
    if (!/\bis-mega\b/.test(wwd.cls)) fail('structure', 'What We Do is not the two-column panel (is-mega)');
    GROUPS.forEach(([name, url, items], gi) => {
      const g = wwd.children[gi];
      if (!g || clean(g.title) !== name) return fail('structure', `What We Do column ${gi + 1} is "${g && g.title}", expected "${name}"`);
      if (pathOf(g.href) !== url) fail('structure', `${name} heading links to ${pathOf(g.href)}, expected ${url}`);
      const got = g.children.map((c) => clean(c.title));
      const want = items.map((i) => i[0]);
      if (JSON.stringify(got) !== JSON.stringify(want)) fail('structure', `${name} lists [${got.join(', ')}]`);
      g.children.forEach((c, i) => {
        if (items[i] && pathOf(c.href) !== `${url}${items[i][1]}/`) fail('structure', `${c.title} links to ${pathOf(c.href)}, expected ${url}${items[i][1]}/`);
      });
    });
    const all = wwd.children[GROUPS.length];
    if (!all || clean(all.title) !== 'View all of What We Do' || pathOf(all.href) !== `/${WWD}/` || wwd.children.length !== GROUPS.length + 1) {
      fail('structure', 'What We Do panel does not end with "View all of What We Do →" → /what-we-do/');
    }
  } else fail('structure', 'no What We Do item');
  const oi = menu.find((n) => clean(n.title) === 'Our Impact' && n.children.length);
  if (!oi || JSON.stringify(oi.children.map((c) => [clean(c.title), pathOf(c.href)])) !== JSON.stringify(IMPACT)) fail('structure', 'Our Impact dropdown is not ' + IMPACT.map((i) => i[0]).join(' · '));
  const donate = await p.evaluate(() => {
    const a = document.querySelector('#site-menu > li:nth-last-child(2) > a'); // last item before the dark-mode row
    if (!a) return null;
    const cs = getComputedStyle(a);
    const red = getComputedStyle(document.documentElement).getPropertyValue('--color-secondary').trim();
    const probe = document.createElement('i'); probe.style.color = red; document.body.appendChild(probe);
    const redRgb = getComputedStyle(probe).color; probe.remove();
    return { text: a.textContent.trim(), href: a.href, bg: cs.backgroundColor, red: redRgb, li: a.parentElement.className };
  });
  results.structure.donate = donate;
  if (!donate || donate.text !== 'Donate' || donate.bg !== donate.red) fail('structure', 'last item is not the red "Donate" button: ' + JSON.stringify(donate));
  if (donate && !/\/donate\/?$/.test(pathOf(donate.href))) fail('structure', 'Donate links to ' + donate.href);

  const quick = await p.evaluate(() => {
    const h = Array.from(document.querySelectorAll('footer h2, footer h3, footer h4')).find((x) => /quick links/i.test(x.textContent));
    const box = h && (h.nextElementSibling || h.parentElement);
    return box ? Array.from(box.querySelectorAll('a')).map((a) => [a.textContent.replace(/\s+/g, ' ').trim(), a.href]) : [];
  });
  results.footer = quick;
  if (JSON.stringify(quick.map((q) => clean(q[0]))) !== JSON.stringify(QUICK)) fail('footer', 'Quick Links are [' + quick.map((q) => q[0]).join(', ') + ']');

  // ---------- every menu / Quick Links link answers 200 itself
  const urls = new Set();
  const collect = (nodes) => nodes.forEach((n) => { if (n.href && !n.href.endsWith('#')) urls.add(n.href.split('#')[0]); collect(n.children); });
  collect(menu);
  quick.forEach((q) => urls.add(q[1].split('#')[0]));
  for (const u of urls) {
    const { status, location } = await get(withBust(u));
    results.links.push({ url: pathOf(u), status, location });
    if (status !== 200) fail('links', `${pathOf(u)} → ${status}${location ? ' ' + location : ''}`);
  }

  // ---------- desktop behaviour
  const bh = results.behaviour;
  try {
  const expanded = (sel) => p.$eval(sel, (el) => el.getAttribute('aria-expanded'));
  const panelVisible = (id) => p.$eval(`#${id} > .submenu`, (el) => getComputedStyle(el).display !== 'none' && el.getBoundingClientRect().height > 0);
  await p.mouse.move(5, 600);
  bh.closedAtStart = !(await panelVisible('nav-what-we-do'));
  await p.hover('#nav-what-we-do > .sub-toggle');
  await p.waitForTimeout(300);
  bh.hoverOpens = await panelVisible('nav-what-we-do');
  await p.mouse.move(5, 880);
  await p.waitForTimeout(300);
  bh.hoverOutCloses = !(await panelVisible('nav-what-we-do'));
  await p.click('#nav-what-we-do > .sub-toggle');
  bh.clickExpands = (await expanded('#nav-what-we-do > .sub-toggle')) === 'true' && (await panelVisible('nav-what-we-do'));
  bh.groupHeadingsVisible = await p.$$eval('#nav-what-we-do .sub-head', (els) => els.length === 2 && els.every((e) => e.getBoundingClientRect().height > 0));
  await p.mouse.click(700, 880);
  await p.waitForTimeout(200);
  bh.outsideClickCloses = (await expanded('#nav-what-we-do > .sub-toggle')) === 'false';
  await p.focus('#nav-our-impact > .sub-toggle');
  await p.keyboard.press('Enter');
  bh.enterExpands = (await expanded('#nav-our-impact > .sub-toggle')) === 'true';
  await p.keyboard.press('Tab');
  bh.tabIntoPanel = await p.evaluate(() => document.activeElement.closest('#nav-our-impact .submenu') !== null);
  await p.keyboard.press('Escape');
  bh.escCloses = (await expanded('#nav-our-impact > .sub-toggle')) === 'false';
  bh.escReturnsFocus = await p.evaluate(() => document.activeElement.matches('#nav-our-impact > .sub-toggle'));
  await p.focus('#nav-what-we-do > .sub-toggle');
  await p.keyboard.press(' ');
  bh.spaceExpands = (await expanded('#nav-what-we-do > .sub-toggle')) === 'true';
  await p.keyboard.press('Escape');
  bh.jsErrors = errors.length;
  for (const k of Object.keys(bh)) if (bh[k] !== true && !(k === 'jsErrors' && bh[k] === 0)) fail('behaviour', `desktop ${k} = ${bh[k]}`);

  // aria-current: a ministry page marks itself, What We Do is highlighted; a group page marks its heading only.
  const cur = async (u) => {
    await p.goto(withBust(B + u), { waitUntil: 'domcontentloaded' });
    return p.evaluate(() => ({
      current: Array.from(document.querySelectorAll('#site-menu [aria-current="page"]')).map((a) => a.textContent.trim()),
      top: Array.from(document.querySelectorAll('#site-menu > li.is-current > .sub-toggle')).map((a) => a.textContent.trim()),
    }));
  };
  const c1 = await cur(`/${WWD}/compassion-humanitarian-ministries/food-relief/`);
  const c2 = await cur(`/${WWD}/gospel-spiritual-ministries/`);
  const c3 = await cur('/our-impact/testimonies/');
  bh.ariaCurrent = { ministry: c1, group: c2, testimonies: c3 };
  if (JSON.stringify(c1.current) !== '["Food Relief"]' || c1.top[0] !== 'What We Do') fail('behaviour', 'aria-current on Food Relief: ' + JSON.stringify(c1));
  if (JSON.stringify(c2.current) !== '["Gospel & Spiritual Ministries"]' || c2.top[0] !== 'What We Do') fail('behaviour', 'aria-current on the group page: ' + JSON.stringify(c2));
  if (JSON.stringify(c3.current) !== '["Testimonials"]' || c3.top[0] !== 'Our Impact') fail('behaviour', 'aria-current on Testimonials: ' + JSON.stringify(c3));
  } catch (e) { fail('behaviour', 'desktop check stopped: ' + String(e.message || e).split('\n')[0]); }
  await ctx.close();

  // ---------- screenshots + mobile behaviour, light and dark
  for (const theme of ['light', 'dark']) {
    const d = await context(b, theme, 1440);
    const dp = await d.newPage();
    await dp.goto(withBust(B + '/'), { waitUntil: 'networkidle' });
    await dp.mouse.move(5, 880);
    await shot(dp, `desktop-1440-${theme}-closed`);
    try {
    await dp.click('#nav-what-we-do > .sub-toggle', { timeout: 3000 });
    await dp.mouse.move(5, 880);
    await dp.waitForTimeout(350);
    await shot(dp, `desktop-1440-${theme}-what-we-do`);
    await dp.click('#nav-our-impact > .sub-toggle');
    await dp.mouse.move(5, 880);
    await dp.waitForTimeout(350);
    await shot(dp, `desktop-1440-${theme}-our-impact`);
    } catch (e) { fail('shots', `desktop ${theme} stopped: ` + String(e.message || e).split('\n')[0]); }
    await d.close();

    const m = await context(b, theme, 390);
    const mp = await m.newPage();
    await mp.goto(withBust(B + '/'), { waitUntil: 'networkidle' });
    await mp.click('.menu-toggle');
    try {
    await mp.click('#nav-what-we-do > .sub-toggle', { timeout: 3000 });
    const heads = await mp.$$('#nav-what-we-do li.has-link > .sub-toggle');
    for (const h of heads) await h.click();
    const mb = await mp.evaluate(() => ({
      menuOpen: document.querySelector('.menu-toggle').getAttribute('aria-expanded') === 'true',
      wwdOpen: document.querySelector('#nav-what-we-do > .sub-toggle').getAttribute('aria-expanded') === 'true',
      groupsOpen: Array.from(document.querySelectorAll('#nav-what-we-do li.has-link > .sub-toggle')).map((x) => x.getAttribute('aria-expanded')),
      groupLinks: Array.from(document.querySelectorAll('#nav-what-we-do .sub-head')).map((a) => a.textContent.trim()),
      ministriesVisible: Array.from(document.querySelectorAll('#nav-what-we-do li.has-link > .submenu a')).filter((a) => a.getBoundingClientRect().height > 0).length,
      chevronSize: Array.from(document.querySelectorAll('#nav-what-we-do li.has-link > .sub-toggle')).map((x) => [Math.round(x.getBoundingClientRect().width), Math.round(x.getBoundingClientRect().height)]),
      overflowX: document.documentElement.scrollWidth > innerWidth,
    }));
    // The menu scrolls inside the screen; for the picture it is shown at full height.
    await mp.evaluate(() => { const u = document.getElementById('site-menu'); u.style.maxHeight = 'none'; u.style.overflow = 'visible'; });
    await mp.setViewportSize({ width: 390, height: Math.max(844, await mp.evaluate(() => document.getElementById('site-menu').getBoundingClientRect().bottom + 20)) });
    await shot(mp, `mobile-390-${theme}-what-we-do`);
    await mp.setViewportSize({ width: 390, height: 844 });
    await mp.click('#nav-what-we-do > .sub-toggle'); // close it
    await mp.click('#nav-our-impact > .sub-toggle');
    mb.impactItems = await mp.$$eval('#nav-our-impact > .submenu a', (as) => as.filter((a) => a.getBoundingClientRect().height > 0).map((a) => a.textContent.trim()));
    await mp.keyboard.press('Escape');
    mb.escClosesImpact = (await mp.$eval('#nav-our-impact > .sub-toggle', (el) => el.getAttribute('aria-expanded'))) === 'false';
    await mp.click('#nav-our-impact > .sub-toggle');
    await shot(mp, `mobile-390-${theme}-our-impact`);
    results.behaviour['mobile-' + theme] = mb;
    const total = GROUPS.reduce((n, g) => n + g[2].length, 0);
    if (!mb.menuOpen || !mb.wwdOpen || mb.groupsOpen.join() !== 'true,true' || mb.ministriesVisible !== total) fail('behaviour', `mobile ${theme}: What We Do accordion ${JSON.stringify(mb)}`);
    if (mb.groupLinks.join('|') !== GROUPS.map((g) => g[0]).join('|')) fail('behaviour', `mobile ${theme}: group links ${mb.groupLinks}`);
    if (mb.chevronSize.some(([w, h]) => w < 44 || h < 44)) fail('behaviour', `mobile ${theme}: chevron under 44px ${JSON.stringify(mb.chevronSize)}`);
    if (JSON.stringify(mb.impactItems) !== JSON.stringify(IMPACT.map((i) => i[0])) || !mb.escClosesImpact) fail('behaviour', `mobile ${theme}: Our Impact ${JSON.stringify(mb.impactItems)} esc=${mb.escClosesImpact}`);
    if (mb.overflowX) fail('behaviour', `mobile ${theme}: horizontal scroll`);
    } catch (e) {
      fail('behaviour', `mobile ${theme} stopped: ` + String(e.message || e).split('\n')[0]);
      await shot(mp, `mobile-390-${theme}-menu`);
    }
    await m.close();
  }

  // ---------- the same header on every page, and no page served stale from a page cache (theme 1.17.1)
  // A visitor in a private window gets what the cache holds; the admin (logged in) never does. A page whose cached
  // copy was built with another theme version is reported, as is a page whose navbar differs from the home page's.
  results.sameHeader = {};
  results.cache = {};
  const PAGES = ['/', '/about/', '/what-we-do/', '/our-impact/', '/our-impact/testimonies/', '/news/', '/contact/', '/donate/'];
  const ver = (html) => ((html.match(/assets\/js\/site\.js\?ver=([0-9.]+)/) || [])[1] || '');
  const hctx = await context(b, 'light', 1440);
  const hp = await hctx.newPage();
  let homeTop = null;
  for (const u of PAGES) {
    const fresh = await get(withBust(B + u));
    const asVisitor = await get(B + u);
    results.cache[u] = { fresh: ver(fresh.body), visitor: ver(asVisitor.body), status: asVisitor.status };
    if (asVisitor.status !== 200) fail('cache', `${u} → ${asVisitor.status} for a visitor`);
    else if (ver(fresh.body) !== ver(asVisitor.body)) fail('cache', `${u} is served from a stale page cache (theme ${ver(asVisitor.body) || '?'}, current ${ver(fresh.body)}): purge the page cache`);
    // The menu as a visitor sees it (no cache-busting query).
    await hp.goto(B + u, { waitUntil: 'domcontentloaded' });
    const top = await hp.evaluate(() => Array.from(document.querySelectorAll('#site-menu > li:not(.menu-theme)')).map((li) => {
      const h = li.querySelector(':scope > a, :scope > .sub-toggle');
      return (h ? h.textContent : '').replace(/\s+/g, ' ').trim() + ':' + li.querySelectorAll('a').length;
    }).join(' · '));
    results.sameHeader[u] = top;
    if (homeTop === null) homeTop = top;
    else if (top !== homeTop) fail('sameHeader', `${u} navbar differs from the home page's: [${top}]`);
  }
  // Testimonials: the dummy (or real) testimonials, no form (client's request, theme 1.17.1).
  await hp.goto(withBust(B + '/our-impact/testimonies/'), { waitUntil: 'domcontentloaded' });
  results.testimonials = await hp.evaluate(() => ({
    h1: (document.querySelector('main h1') || {}).textContent,
    cards: document.querySelectorAll('main figure.testimony').length,
    forms: document.querySelectorAll('main form').length,
  }));
  if (!results.testimonials.cards || results.testimonials.forms || (results.testimonials.h1 || '').trim() !== 'Testimonials') fail('testimonials', JSON.stringify(results.testimonials));
  await hctx.close();

  // ---------- "coming soon" ministries
  const sctx = await context(b, 'light', 1440);
  const sp = await sctx.newPage();
  for (const slug of SOON) {
    const u = `/${WWD}/compassion-humanitarian-ministries/${slug}/`;
    const r = await sp.goto(withBust(B + u), { waitUntil: 'domcontentloaded' });
    if (r.status() !== 200 || !(await sp.$('#ministry-hero'))) { fail('soon', `${slug}: ${r.status()} at ${pathOf(sp.url())}, no ministry page`); continue; }
    const s = await sp.evaluate((line) => {
      const main = document.querySelector('main');
      const hero = document.getElementById('ministry-hero');
      return {
        line: !!hero && hero.textContent.includes(line),
        donate: Array.from(hero ? hero.querySelectorAll('a') : []).filter((a) => a.textContent.trim() === 'Donate').map((a) => a.href),
        contact: Array.from(hero ? hero.querySelectorAll('a') : []).some((a) => a.textContent.trim() === 'Contact us'),
        others: main.querySelectorAll('.ministry-others .cause-card').length,
        extra: Array.from(main.querySelectorAll('section')).map((x) => x.id || x.className).filter((x) => !/ministry-hero|ministry-others/.test(x)),
        brackets: /\[[^\]]*\]/.test(main.innerText),
      };
    }, SOON_LINE);
    s.status = r.status();
    results.soon[slug] = s;
    if (s.status !== 200 || !s.line || !s.contact || s.others < 1 || s.extra.length || s.brackets) fail('soon', `${slug}: ${JSON.stringify(s)}`);
    if (!s.donate.length || !s.donate.every((h) => h.includes('ministry=' + slug))) fail('soon', `${slug}: Donate does not preselect it: ${s.donate}`);
  }
  for (const u of [`/${WWD}/`, `/${WWD}/compassion-humanitarian-ministries/`]) {
    await sp.goto(withBust(B + u), { waitUntil: 'domcontentloaded' });
    const names = await sp.evaluate(() => (document.querySelector('main') || document.body).innerText);
    const missing = ['Persecuted Christian Support', 'Food Relief', 'Disaster Relief & Recovery Ministry'].filter((n) => !names.includes(n));
    if (missing.length) fail('soon', `${u} does not list ${missing.join(', ')}`);
    if (names.includes('Youth Ministry / Women Empowerment')) fail('soon', `${u} lists Youth Ministry / Women Empowerment`);
  }
  await sctx.close();
  await b.close();

  fs.writeFileSync(OUT, JSON.stringify(results, null, 2));
  console.log(JSON.stringify({ site: B, links: results.links.length, shots: results.shots.length, failures: results.failures.length }));
  process.exit(results.failures.length ? 1 : 0);
})().catch((e) => { console.error(e); process.exit(2); });
