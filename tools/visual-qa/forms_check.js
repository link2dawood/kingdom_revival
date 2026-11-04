// Forms and donation check (Phase 12). Fills and sends the contact, partner and newsletter forms in a real browser
// (waiting past the minimum time on form) and records the server's answer and the message the visitor sees. Then, with
// a PayPal account email configured, reads the PayPal checkout links the Donate page builds, and posts PayPal payment
// notifications (IPN) to the site: a completed gift, the same one again, and one for a different receiving account.
// Whether submissions/donations were saved and emails sent is checked on the server afterwards.
// usage: PAYPAL_EMAIL=... node forms_check.js [outfile.json]
const { chromium } = require('playwright-core');
const fs = require('fs');
const { cached } = require('./lib');

const WP = process.env.WP_URL || 'http://127.0.0.1:8899';
const OUT = process.argv[2] || 'out/forms-check.json';
const PAYPAL_EMAIL = process.env.PAYPAL_EMAIL || '';

async function newPage(browser, width = 1440) {
  const ctx = await browser.newContext({ viewport: { width, height: 900 } });
  await ctx.route(/^https?:\/\/(?!127\.0\.0\.1)/, async (route) => {
    const url = route.request().url();
    if (/\.mp4(\?|$)|paypal\.com/.test(url)) return route.abort();
    const { meta, body } = await cached(url, route.request().headers());
    return route.fulfill({ status: meta.status, contentType: meta.type, body });
  });
  const page = await ctx.newPage();
  page.errors = [];
  page.ajax = [];
  page.on('pageerror', (e) => page.errors.push(String(e.message || e).slice(0, 200)));
  page.on('console', (m) => { if (m.type() === 'error') page.errors.push(m.text().slice(0, 200)); });
  page.on('response', async (r) => { if (r.url().includes('admin-ajax.php')) page.ajax.push({ status: r.status(), body: (await r.text().catch(() => '')).slice(0, 200) }); });
  return page;
}

(async () => {
  const browser = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });
  const results = [];
  const log = (row) => { results.push(row); console.log(JSON.stringify(row)); };

  for (const width of [1440, 390]) {
    // Contact
    let p = await newPage(browser, width);
    await p.addInitScript(() => localStorage.setItem('krm-newsletter', JSON.stringify({ dismissed: Date.now() })));
    await p.goto(WP + '/contact/');
    await p.fill('#name', `QA Contact ${width}`);
    await p.fill('#email', 'qa-contact@example.test');
    await p.fill('#phone', '+1 555 0100');
    await p.fill('#message', 'Phase 12 test message from the contact form.');
    await p.waitForTimeout(3000);
    await p.click('#contact-form button[type="submit"]');
    await p.locator('#form-status').waitFor({ state: 'visible', timeout: 20000 }).catch(() => {});
    log({ form: 'contact', width, shown: await p.locator('#form-status').isVisible() ? (await p.locator('#form-status').innerText()).trim().slice(0, 120) : null, ajax: p.ajax, jsErrors: p.errors });
    await p.context().close();

    // Partner
    p = await newPage(browser, width);
    await p.addInitScript(() => localStorage.setItem('krm-newsletter', JSON.stringify({ dismissed: Date.now() })));
    await p.goto(WP + '/partner-with-us/');
    await p.locator('input[name="type"][value="Ministry Sponsor"]').check({ force: true });
    await p.fill('#name', `QA Partner ${width}`);
    await p.fill('#email', 'qa-partner@example.test');
    await p.fill('#country', 'Canada');
    await p.waitForTimeout(3000);
    await p.click('#partner-submit');
    await p.locator('#thanks').waitFor({ state: 'visible', timeout: 20000 }).catch(() => {});
    const thanks = await p.locator('#thanks').isVisible();
    log({ form: 'partner', width, shown: thanks ? (await p.locator('#thanks h2').innerText()).trim() : null, giveLink: thanks ? await p.locator('#thanks-give').getAttribute('href') : null, ajax: p.ajax, jsErrors: p.errors });
    await p.context().close();

    // Newsletter popup (opened with ?newsletter=1)
    p = await newPage(browser, width);
    await p.goto(WP + '/about/?newsletter=1');
    await p.locator('.nl-form').waitFor({ timeout: 20000 });
    await p.fill('.nl-form input[name="email"]', `qa-news-${width}@example.test`);
    await p.waitForTimeout(3000);
    await p.click('.nl-submit');
    await p.locator('.nl-done').waitFor({ timeout: 20000 }).catch(() => {});
    log({ form: 'newsletter', width, shown: await p.locator('.nl-done h2').isVisible() ? await p.locator('.nl-done h2').innerText() : null, ajax: p.ajax, jsErrors: p.errors });
    await p.context().close();
  }

  // Donate page: PayPal checkout links, one-time and monthly, from a link that preselects the gift.
  const p = await newPage(browser);
  await p.addInitScript(() => localStorage.setItem('krm-newsletter', JSON.stringify({ dismissed: Date.now() })));
  for (const [label, qs] of [['one-time', '?amount=100&for=Clean%20Water%20Project'], ['monthly', '?frequency=Monthly&amount=50']]) {
    await p.goto(WP + '/donate/' + qs);
    await p.click('#tab-paypal');
    const href = await p.locator('#paypal-link').getAttribute('href');
    const u = new URL(href);
    const q = Object.fromEntries(u.searchParams);
    log({ donate: label, host: u.origin + u.pathname, cmd: q.cmd, business: q.business, amount: q.amount || q.a3, period: q.t3 || null, item: q.item_name, custom: q.custom, notify_url: q.notify_url, return: q.return, cancel_return: q.cancel_return, monthlyNoteHidden: await p.locator('#paypal-monthly').isHidden(), jsErrors: p.errors });
  }
  // Return from PayPal: the thank-you view.
  await p.goto(WP + '/donate/?thanks=1&amount=100&frequency=One-time&for=Clean%20Water%20Project');
  log({ donate: 'thank-you page', h1: (await p.locator('h1').first().innerText()).trim(), robots: await p.locator('meta[name="robots"]').getAttribute('content').catch(() => null), jsErrors: p.errors });
  await p.context().close();

  // PayPal payment notifications.
  const ipn = async (fields) => {
    const res = await fetch(WP + '/wp-admin/admin-post.php?action=krm_paypal_ipn', { method: 'POST', headers: { 'content-type': 'application/x-www-form-urlencoded' }, body: new URLSearchParams(fields).toString() });
    return res.status;
  };
  const gift = { txn_type: 'web_accept', payment_status: 'Completed', txn_id: 'QA12TXN0001', receiver_email: PAYPAL_EMAIL, business: PAYPAL_EMAIL, mc_gross: '100.00', mc_currency: 'USD', payer_email: 'qa-donor@example.test', first_name: 'Qa', last_name: 'Donor', custom: 'Clean Water Project', item_name: 'Gift to Kingdom Revival Ministries — Clean Water Project', payment_date: '10:00:00 Oct 31, 2026 PDT' };
  log({ ipn: 'completed gift', status: await ipn(gift) });
  log({ ipn: 'same notification again', status: await ipn(gift) });
  log({ ipn: 'other receiving account', status: await ipn({ ...gift, txn_id: 'QA12TXN0002', receiver_email: 'someone-else@example.test', business: 'someone-else@example.test' }) });

  fs.mkdirSync(require('path').dirname(OUT), { recursive: true });
  fs.writeFileSync(OUT, JSON.stringify(results, null, 2));
  await browser.close();
})();
