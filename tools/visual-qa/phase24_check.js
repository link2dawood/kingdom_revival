// Phase 24 forms check: Partner With Us (4 sign-ups), Prayer Request (+ the home prayer form) and Donate → donor
// details, all sent by assets/js/forms.js to krm_form_submit() (inc/form-handler.php).
//
// Runs against a LOCAL WordPress whose wp-config.php defines KRM_MAIL_CAPTURE (emails are written to
// wp-content/krm-mail/ instead of being sent) and uses wp-cli to read the saved Form Submissions and to clear the
// rate-limit counter between tests. Never point it at the live site: it submits forms.
//
// Checks, at 1440 and 390 px:
//   success       — each form type: loading state (button disabled + spinner + "Sending…", aria-busy, read-only
//                   fields), the green-tick message with the visitor's first name replacing the form, the captured
//                   email (subject, Reply-To, HTML + plain text, PKT date, WhatsApp link), the saved submission and
//                   its email status "sent"
//   validation    — empty and malformed fields: inline errors, aria-invalid + aria-describedby, focus on the first
//   server error  — a 500 answer: the red message (with the WhatsApp number), button back, typed values kept
//   timeout       — no answer for 20 s: same red message
//   slow network  — answer delayed 4 s: loading state visible (screenshot)
//   double click  — two fast clicks send one request
//   honeypot      — filled: "success" for the bot, nothing saved, no email
//   rate limit    — 6th submission from one IP within 10 minutes is refused with the rate-limit message
//   mail failure  — capture folder holds FAIL: the visitor still sees success, the entry is saved with status "failed"
//   no JS         — JavaScript off: post + redirect shows the same success / error message, typed values kept
//   private       — "Keep my request private" puts PRIVATE in the subject
//   card data     — a card number typed into the donation message is removed before saving and emailing
//   dark          — success / error boxes and spinner in dark mode: text contrast >= 4.5:1 (screenshots)
//   mobile        — no horizontal overflow at 390 px with the messages shown
//   axe           — axe-core on each form page in the error state
//
// usage: node phase24_check.js [outdir]   (env: WP_URL, WP_PATH, WP_CLI, CHROME_PATH)
// Output: <outdir>/result.json, <outdir>/shots/*.png, <outdir>/emails/<type>.{html,txt,png}
const { chromium } = require('playwright-core');
const fs = require('fs');
const path = require('path');
const crypto = require('crypto');
const { execFileSync } = require('child_process');
const { cached } = require('./lib');

const WP = process.env.WP_URL || 'http://127.0.0.1:8899';
const WP_PATH = process.env.WP_PATH || path.join(process.env.HOME || '', 'krm-work/site');
const WP_CLI = process.env.WP_CLI || path.join(process.env.HOME || '', 'krm-work/wp-cli.phar');
const MAIL = path.join(WP_PATH, 'wp-content/krm-mail');
const OUT = process.argv[2] || path.join(__dirname, '../../qa-screenshots/phase-24');
const AXE = fs.readFileSync(require.resolve('axe-core/axe.min.js'), 'utf8');
fs.mkdirSync(path.join(OUT, 'shots'), { recursive: true });
fs.mkdirSync(path.join(OUT, 'emails'), { recursive: true });

const FORMS = {
  prayer_partner: { url: '/partner-with-us/', form: '#prayer-partners-form', subject: '[Website] New Prayer Partner — ' },
  financial_partner: { url: '/partner-with-us/?project=New%20well%20in%20Kasur', form: '#financial-partner-form', subject: '[Website] New Financial Partner — ' },
  church_partner: { url: '/partner-with-us/', form: '#church-ministry-partners-form', subject: '[Website] New Church & Ministry Partner — ' },
  volunteer: { url: '/partner-with-us/', form: '#volunteer-form', subject: '[Website] New Volunteer — ' },
  prayer_request: { url: '/partner-with-us/prayer-request/', form: '#request-form', subject: '[Website] PRIVATE Prayer Request — ' },
  donation: { url: '/donate/', form: '#gift', submit: '#notify-link', subject: '[Website] Donation Intent — $100/month for Widows Support — ' },
  home_prayer: { url: '/', form: '#prayer-form', type: 'prayer_request', subject: '[Website] Prayer Request — ' },
};

const results = [];
let failures = 0;
function log(row) {
  if (row.ok === false) failures++;
  results.push(row);
  console.log(JSON.stringify(row));
}
function wp(...args) {
  return execFileSync('php', [WP_CLI, '--path=' + WP_PATH, '--skip-plugins', ...args], { encoding: 'utf8', stdio: ['ignore', 'pipe', 'ignore'] }).trim();
}
function clearRate() {
  wp('transient', 'delete', 'krm_frl_' + crypto.createHash('md5').update('127.0.0.1').digest('hex'));
}
function mailFiles() { return fs.existsSync(MAIL) ? fs.readdirSync(MAIL).filter((f) => f.endsWith('.eml')).sort() : []; }
function newMail(before) { return mailFiles().filter((f) => !before.includes(f)); }
function lastSubmission() {
  const rows = JSON.parse(wp('post', 'list', '--post_type=krm_submission', '--post_status=private,draft', '--orderby=ID', '--order=DESC', '--posts_per_page=1', '--fields=ID,post_title', '--format=json') || '[]');
  if (!rows.length) return null;
  const id = rows[0].ID;
  return { id, title: rows[0].post_title, form: wp('post', 'meta', 'get', String(id), '_krm_form'), status: wp('post', 'meta', 'get', String(id), '_krm_mail_status'), fields: JSON.parse(wp('post', 'meta', 'get', String(id), '_krm_fields', '--format=json') || '{}') };
}
function submissionCount() { return Number(wp('post', 'list', '--post_type=krm_submission', '--post_status=private,draft', '--format=count')); }

async function context(browser, width, { dark = false, js = true } = {}) {
  const ctx = await browser.newContext({ viewport: { width, height: 900 }, javaScriptEnabled: js, reducedMotion: 'reduce' });
  await ctx.route(/^https?:\/\/(?!127\.0\.0\.1)/, async (route) => {
    const url = route.request().url();
    if (/\.(mp4|webm)(\?|$)|paypal\.com/.test(url)) return route.abort();
    const { meta, body } = await cached(url, route.request().headers());
    return route.fulfill({ status: meta.status, contentType: meta.type, body });
  });
  await ctx.addInitScript((d) => {
    try {
      localStorage.setItem('krm-newsletter', JSON.stringify({ dismissed: Date.now() }));
      localStorage.setItem('krm-theme', d ? 'dark' : 'light');
    } catch (e) {}
  }, dark);
  return ctx;
}
async function open(ctx, url) {
  const page = await ctx.newPage();
  page.errors = [];
  page.on('pageerror', (e) => page.errors.push(String(e.message || e).slice(0, 200)));
  page.posts = 0;
  page.on('request', (r) => { if (r.method() === 'POST' && r.url().includes('admin-ajax.php')) page.posts++; });
  await page.goto(WP + url, { waitUntil: 'domcontentloaded' });
  await page.waitForTimeout(300);
  return page;
}

/* Fill a form with valid data for its type. */
async function fill(page, key, name, extra = {}) {
  const f = FORMS[key];
  const type = f.type || key;
  const sel = f.form;
  const set = async (field, value) => { const el = page.locator(`${sel} [name="${field}"]`); if (await el.count()) await el.first().fill(value); };
  await page.locator(sel).scrollIntoViewIfNeeded();
  await set('name', name);
  await set('contact', extra.contact || (type === 'donation' ? '+92 300 1234567' : 'tester@example.com'));
  await set('country', 'Pakistan');
  if (type === 'church_partner') await set('church', 'Grace Church, Lahore');
  if (type === 'prayer_request') {
    await set('request', 'Please pray for my mother\'s health.\nShe is in hospital in Faisalabad.');
    if (await page.locator(`${sel} [name="type"]`).count()) await page.selectOption(`${sel} [name="type"]`, 'Healing');
    if (key === 'prayer_request') await page.check(`${sel} [name="private"]`);
  } else if (type === 'donation') {
    await page.click('#gift label:has(input[name="frequency"][value="Monthly"])');
    await page.click('#gift label:has(input[name="amount"][value="$100"])');
    await page.selectOption('#gift [name="fund"]', 'Widows Support');
    await page.click('#tab-bank');
    await set('message', extra.message || 'I will send it by bank transfer on Friday.');
  } else {
    await set('message', 'Happy to help with the next outreach.');
  }
}
function submitSel(key) { return FORMS[key].submit || `${FORMS[key].form} button[type="submit"]`; }
async function state(page, key) {
  const f = FORMS[key];
  return page.evaluate(({ sel, sub }) => {
    const form = document.querySelector(sel);
    const button = document.querySelector(sub);
    const scope = document.querySelector(`[data-krm-for="${form.id}"]`) || form;
    const msg = scope.querySelector('[data-krm-msg]');
    const live = scope.querySelector('[data-krm-live]');
    const field = form.querySelector('[name="name"]');
    return {
      busy: form.getAttribute('aria-busy'),
      disabled: button ? button.disabled : null,
      buttonText: button ? button.textContent.trim() : '',
      spinner: !!(button && button.querySelector('.krm-spinner')),
      readonly: field ? field.readOnly : null,
      msgShown: !!(msg && !msg.hidden && msg.offsetHeight > 0),
      msgClass: msg ? msg.className : '',
      msgText: msg ? msg.textContent.trim() : '',
      liveText: live ? live.textContent.trim() : '',
      liveAttr: live ? live.getAttribute('aria-live') : '',
      formHidden: !!(field && field.offsetParent === null),
      done: form.hasAttribute('data-krm-done'),
      nameValue: field ? field.value : '',
      overflow: document.documentElement.scrollWidth > window.innerWidth + 1,
    };
  }, { sel: f.form, sub: submitSel(key) });
}
async function waitDone(page, key, ms = 15000) {
  const f = FORMS[key];
  await page.waitForFunction((sel) => {
    const form = document.querySelector(sel);
    const scope = document.querySelector(`[data-krm-for="${form.id}"]`) || form;
    const msg = scope.querySelector('[data-krm-msg]');
    return msg && !msg.hidden && form.getAttribute('aria-busy') !== 'true';
  }, f.form, { timeout: ms });
}
async function shot(page, key, label) {
  const f = FORMS[key];
  const target = page.locator(f.form === '#gift' ? '#give' : f.form);
  await target.scrollIntoViewIfNeeded();
  await target.screenshot({ path: path.join(OUT, 'shots', `${key}-${label}.png`) });
}
/* Contrast of the message box text on its background. */
async function msgContrast(page, key) {
  return page.evaluate((sel) => {
    const form = document.querySelector(sel);
    const scope = document.querySelector(`[data-krm-for="${form.id}"]`) || form;
    const msg = scope.querySelector('[data-krm-msg]');
    const rgb = (s) => (s.match(/[\d.]+/g) || []).slice(0, 3).map(Number);
    const lum = ([r, g, b]) => { const c = [r, g, b].map((v) => { v /= 255; return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4); }); return 0.2126 * c[0] + 0.7152 * c[1] + 0.0722 * c[2]; };
    const cs = getComputedStyle(msg.querySelector('p'));
    const fg = lum(rgb(cs.color));
    const bg = lum(rgb(getComputedStyle(msg).backgroundColor));
    return +((Math.max(fg, bg) + 0.05) / (Math.min(fg, bg) + 0.05)).toFixed(2);
  }, FORMS[key].form);
}

(async () => {
  const browser = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });
  try { fs.unlinkSync(path.join(MAIL, 'FAIL')); } catch (e) {}

  /* ---------- success, every form type, desktop and mobile ---------- */
  for (const width of [1440, 390]) {
    for (const key of Object.keys(FORMS)) {
      clearRate();
      const ctx = await context(browser, width);
      const page = await open(ctx, FORMS[key].url);
      const name = `Test ${key.replace(/_/g, ' ')} ${width}`;
      await fill(page, key, name);
      const before = mailFiles();
      // Hold the answer 4 s so the loading state can be seen and photographed (slow network).
      let release;
      const held = new Promise((r) => { release = r; });
      await page.route('**/admin-ajax.php', async (route) => {
        if (route.request().method() !== 'POST') return route.continue();
        const response = await route.fetch();
        await held;
        return route.fulfill({ response });
      });
      await page.click(submitSel(key));
      await page.waitForFunction((sel) => document.querySelector(sel).getAttribute('aria-busy') === 'true', FORMS[key].form);
      const loading = await state(page, key);
      if (width === 390 || key === 'volunteer' || key === 'donation') await shot(page, key, `loading-${width}`);
      await page.waitForTimeout(4000);
      release();
      await waitDone(page, key);
      await page.waitForTimeout(300);
      const s = await state(page, key);
      await shot(page, key, `success-${width}`);
      const mails = newMail(before);
      const eml = mails.length ? fs.readFileSync(path.join(MAIL, mails[0]), 'utf8') : '';
      const sub = lastSubmission();
      const expectSubject = FORMS[key].subject + name;
      const txt = mails.length ? fs.readFileSync(path.join(MAIL, mails[0].replace(/\.eml$/, '.txt')), 'utf8') : '';
      // The capture's .txt starts with the decoded To / From / Reply-To / Subject lines.
      const decoded = ((txt.match(/^Subject: (.*)$/m) || [])[1] || '').trim();
      const checks = {
        loadingBusy: loading.busy === 'true', loadingDisabled: loading.disabled === true, loadingSpinner: loading.spinner,
        loadingText: /Sending/.test(loading.buttonText), loadingReadonly: loading.readonly === true,
        success: s.msgShown && /is-success/.test(s.msgClass), firstName: s.msgText.includes('Test') || key === 'donation',
        replaced: s.formHidden || key === 'donation', live: s.liveAttr === 'polite' && s.liveText.length > 0,
        oneEmail: mails.length === 1, subject: decoded.includes(expectSubject),
        multipart: /multipart\/alternative/.test(eml) && /text\/plain/.test(eml) && /text\/html/.test(eml),
        pkt: / PKT/.test(txt), saved: !!sub && sub.form === (FORMS[key].type || key) && sub.status === 'sent',
        replyTo: key === 'donation' ? /^Reply-To: \s*$/m.test(txt) : /^Reply-To: Test .*<tester@example\.com>$/m.test(txt),
        whatsapp: key === 'donation' ? /wa\.me\/923001234567/.test(txt) : true,
        noOverflow: !s.overflow, noJsErrors: page.errors.length === 0,
      };
      if (key === 'financial_partner') checks.projectPrefill = !!sub && sub.fields.project === 'New well in Kasur';
      const ok = Object.values(checks).every(Boolean);
      log({ test: 'success', key, width, ok, failed: Object.keys(checks).filter((k) => !checks[k]), subject: decoded, msg: s.msgText, errors: page.errors });
      // Keep one sample email per form type (desktop run).
      if (width === 1440 && mails.length) {
        const base = path.join(MAIL, mails[0].replace(/\.eml$/, ''));
        fs.copyFileSync(base + '.html', path.join(OUT, 'emails', `${key}.html`));
        fs.copyFileSync(base + '.txt', path.join(OUT, 'emails', `${key}.txt`));
        fs.copyFileSync(base + '.eml', path.join(OUT, 'emails', `${key}.eml`));
      }
      await ctx.close();
    }
  }

  /* ---------- render the sample emails ---------- */
  {
    const ctx = await browser.newContext({ viewport: { width: 720, height: 900 } });
    const page = await ctx.newPage();
    for (const key of Object.keys(FORMS)) {
      const file = path.join(OUT, 'emails', `${key}.html`);
      if (!fs.existsSync(file)) continue;
      await page.goto('file://' + file);
      await page.screenshot({ path: path.join(OUT, 'emails', `${key}.png`), fullPage: true });
    }
    await ctx.close();
  }

  /* ---------- validation (client side), every form, + axe in the error state ---------- */
  for (const key of ['volunteer', 'prayer_request', 'donation', 'home_prayer']) {
    for (const width of [1440, 390]) {
      const ctx = await context(browser, width);
      const page = await open(ctx, FORMS[key].url);
      const sel = FORMS[key].form;
      await page.locator(sel).scrollIntoViewIfNeeded();
      await page.locator(`${sel} [name="contact"]`).fill('not-an-email');
      if (key === 'donation') { await page.click('#gift label:has(input[name="amount"][value="Other"])'); }
      await page.click(submitSel(key));
      await page.waitForTimeout(300);
      const v = await page.evaluate((sel) => {
        const form = document.querySelector(sel);
        const bad = Array.from(form.querySelectorAll('[aria-invalid="true"]'));
        return {
          invalid: bad.map((el) => el.name),
          described: bad.every((el) => (el.getAttribute('aria-describedby') || '').split(' ').some((id) => { const n = document.getElementById(id); return n && n.textContent.trim().length > 3 && !n.hidden; })),
          focus: document.activeElement && document.activeElement.name,
          messages: bad.map((el) => document.getElementById(el.getAttribute('aria-describedby').split(' ').pop()).textContent),
        };
      }, sel);
      const posts = page.posts;
      let axe = null;
      if (width === 1440) {
        await page.addScriptTag({ content: AXE });
        axe = await page.evaluate(async (sel) => {
          const r = await window.axe.run(sel, { runOnly: { type: 'tag', values: ['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa'] } });
          return r.violations.map((x) => `${x.id} (${x.nodes.length})`);
        }, key === 'donation' ? '#give' : sel);
      }
      await shot(page, key, `validation-${width}`);
      const expect = key === 'donation' ? ['name', 'contact', 'custom'] : ['name', 'contact', 'request'].filter((n) => key !== 'volunteer' || n !== 'request').concat(key === 'volunteer' ? ['country'] : []);
      const ok = expect.every((n) => v.invalid.includes(n)) && v.described && v.focus === v.invalid[0] && posts === 0 && (!axe || axe.length === 0);
      log({ test: 'validation', key, width, ok, ...v, posts, axe });
      // Fixing a field clears its error.
      await page.locator(`${sel} [name="name"]`).fill('Fixed Name');
      const cleared = await page.evaluate((sel) => document.querySelector(`${sel} [name="name"]`).getAttribute('aria-invalid') !== 'true', sel);
      log({ test: 'validation-clears', key, width, ok: cleared });
      await ctx.close();
    }
  }

  /* ---------- server-side validation (bypassing the browser checks) ---------- */
  {
    clearRate();
    const ctx = await context(browser, 1440);
    const page = await open(ctx, '/partner-with-us/');
    const r = await page.evaluate(async () => {
      const s = await (await fetch(KRM_FORMS.ajaxUrl + '?action=krm_form_nonce')).json();
      await new Promise((res) => setTimeout(res, 3300));
      const post = async (fields) => {
        const d = new FormData();
        Object.entries(Object.assign({ action: 'krm_form_submit', _krm_nonce: s.data.nonce, token: s.data.token }, fields)).forEach(([k, v]) => d.append(k, v));
        const res = await fetch(KRM_FORMS.ajaxUrl, { method: 'POST', body: d });
        return { status: res.status, body: await res.json() };
      };
      return {
        empty: await post({ form_type: 'volunteer' }),
        badContact: await post({ form_type: 'volunteer', name: 'X', contact: '12', country: 'PK' }),
        badChoice: await post({ form_type: 'donation', name: 'X', contact: 'x@example.com', amount: '$50', frequency: 'Weekly', fund: 'Nope' }),
        unknownType: await post({ form_type: 'evil', name: 'X' }),
        badNonce: await post({ form_type: 'volunteer', _krm_nonce: 'nope', name: 'X', contact: 'x@example.com', country: 'PK' }),
        injection: await post({ form_type: 'volunteer', name: 'Eve\r\nBcc: victim@example.com', contact: 'eve@example.com\r\nBcc: x@example.com', country: 'PK' }),
      };
    });
    const leaks = JSON.stringify(r).match(/\/home\/|wp-content|Fatal|Warning|SQL|PHPMailer/i);
    const checks = {
      empty: r.empty.status === 422 && ['name', 'contact', 'country'].every((k) => r.empty.body.data.fields[k]),
      badContact: r.badContact.status === 422 && !!r.badContact.body.data.fields.contact,
      badChoice: r.badChoice.status === 422 && !!r.badChoice.body.data.fields.frequency && !!r.badChoice.body.data.fields.fund,
      unknownType: r.unknownType.status === 400,
      badNonce: r.badNonce.status === 403,
      injection: r.injection.status === 422 && !!r.injection.body.data.fields.contact,
      noServerDetails: !leaks,
    };
    log({ test: 'server-validation', ok: Object.values(checks).every(Boolean), failed: Object.keys(checks).filter((k) => !checks[k]), statuses: Object.fromEntries(Object.entries(r).map(([k, v]) => [k, v.status])) });
    await ctx.close();
  }

  /* ---------- server error (500) and timeout: red message, values kept, button back ---------- */
  for (const [label, handler, wait] of [
    ['server-error', (route) => route.fulfill({ status: 500, contentType: 'text/html', body: '<h1>Internal Server Error</h1>' }), 3000],
    ['timeout', () => {}, 26000],
  ]) {
    for (const key of label === 'timeout' ? ['volunteer'] : ['volunteer', 'donation', 'prayer_request']) {
      clearRate();
      const ctx = await context(browser, 390);
      const page = await open(ctx, FORMS[key].url);
      await fill(page, key, 'Kept Value');
      await page.route('**/admin-ajax.php', (route) => (route.request().method() === 'POST' ? handler(route) : route.continue()));
      const t0 = Date.now();
      await page.click(submitSel(key));
      await waitDone(page, key, wait + 5000);
      const took = Date.now() - t0;
      const s = await state(page, key);
      await shot(page, key, `${label}-390`);
      const ok = /is-error/.test(s.msgClass) && /\+92-341-5418733/.test(s.msgText) && s.disabled === false && s.readonly === false &&
        s.nameValue === 'Kept Value' && s.busy === 'false' && !s.done && (label !== 'timeout' || (took >= 19000 && took < 26000));
      log({ test: label, key, ok, msg: s.msgText, took, kept: s.nameValue });
      await ctx.close();
    }
  }

  /* ---------- double click ---------- */
  {
    clearRate();
    const ctx = await context(browser, 1440);
    const page = await open(ctx, FORMS.church_partner.url);
    await fill(page, 'church_partner', 'Double Click');
    const count = submissionCount();
    await page.dblclick(submitSel('church_partner'));
    await page.click(submitSel('church_partner'), { force: true }).catch(() => {});
    await waitDone(page, 'church_partner');
    await page.waitForTimeout(500);
    log({ test: 'double-click', ok: page.posts === 1 && submissionCount() === count + 1, posts: page.posts, saved: submissionCount() - count });
    await ctx.close();
  }

  /* ---------- honeypot ---------- */
  {
    clearRate();
    const ctx = await context(browser, 1440);
    const page = await open(ctx, FORMS.volunteer.url);
    await fill(page, 'volunteer', 'Honey Bot');
    await page.evaluate(() => { document.querySelector('#volunteer-form [name="website"]').value = 'http://spam.example'; });
    const before = mailFiles();
    const count = submissionCount();
    await page.click(submitSel('volunteer'));
    await waitDone(page, 'volunteer');
    const s = await state(page, 'volunteer');
    log({ test: 'honeypot', ok: /is-success/.test(s.msgClass) && newMail(before).length === 0 && submissionCount() === count, saved: submissionCount() - count, mails: newMail(before).length });
    await ctx.close();
  }

  /* ---------- rate limit: 5 accepted, the 6th refused ---------- */
  {
    clearRate();
    const ctx = await context(browser, 1440);
    const page = await open(ctx, '/partner-with-us/');
    const statuses = await page.evaluate(async () => {
      const s = await (await fetch(KRM_FORMS.ajaxUrl + '?action=krm_form_nonce')).json();
      await new Promise((res) => setTimeout(res, 3300));
      const out = [];
      for (let i = 1; i <= 5; i++) {
        const d = new FormData();
        Object.entries({ action: 'krm_form_submit', _krm_nonce: s.data.nonce, token: s.data.token, form_type: 'prayer_partner', name: 'Rate ' + i, contact: 'rate@example.com', country: 'PK' }).forEach(([k, v]) => d.append(k, v));
        out.push((await fetch(KRM_FORMS.ajaxUrl, { method: 'POST', body: d })).status);
      }
      return out;
    });
    await fill(page, 'volunteer', 'Sixth Try');
    await page.click(submitSel('volunteer'));
    await waitDone(page, 'volunteer');
    const s = await state(page, 'volunteer');
    await shot(page, 'volunteer', 'rate-limit-1440');
    log({ test: 'rate-limit', ok: statuses.every((x) => x === 200) && /is-error/.test(s.msgClass) && /several messages/.test(s.msgText) && s.nameValue === 'Sixth Try', statuses, msg: s.msgText });
    await ctx.close();
    clearRate();
  }

  /* ---------- email failure: saved with status "failed", visitor sees success ---------- */
  {
    clearRate();
    fs.writeFileSync(path.join(MAIL, 'FAIL'), 'simulate');
    const ctx = await context(browser, 1440);
    const page = await open(ctx, FORMS.volunteer.url);
    await fill(page, 'volunteer', 'Mail Fails');
    await page.click(submitSel('volunteer'));
    await waitDone(page, 'volunteer');
    const s = await state(page, 'volunteer');
    const sub = lastSubmission();
    fs.unlinkSync(path.join(MAIL, 'FAIL'));
    log({ test: 'mail-failure', ok: /is-success/.test(s.msgClass) && sub && sub.title.includes('Mail Fails') && sub.status === 'failed', status: sub && sub.status });
    await ctx.close();
  }

  /* ---------- card number in the donation message is removed ---------- */
  {
    clearRate();
    const ctx = await context(browser, 1440);
    const page = await open(ctx, FORMS.donation.url);
    await fill(page, 'donation', 'Card Typer', { contact: 'card@example.com', message: 'My card is 4111 1111 1111 1111, cvv 123. Reference 2026-11-06.' });
    const before = mailFiles();
    await page.click(submitSel('donation'));
    await waitDone(page, 'donation');
    const mails = newMail(before);
    const eml = mails.length ? fs.readFileSync(path.join(MAIL, mails[0].replace(/\.eml$/, '.txt')), 'utf8') : '';
    const sub = lastSubmission();
    const stored = JSON.stringify(sub && sub.fields);
    log({ test: 'card-data', ok: mails.length === 1 && !/4111/.test(eml) && !/4111/.test(stored) && /\[card number removed\]/.test(eml) && !/cvv 123/i.test(stored) && /2026-11-06/.test(stored), message: sub && sub.fields.message });
    await ctx.close();
  }

  /* ---------- without JavaScript ---------- */
  for (const key of ['volunteer', 'prayer_request', 'donation']) {
    clearRate();
    const ctx = await context(browser, 390, { js: false });
    const page = await open(ctx, FORMS[key].url);
    const sel = FORMS[key].form;
    // Error first: leave out the name.
    await page.locator(`${sel} [name="contact"]`).fill('nojs@example.com');
    if (key !== 'donation') await page.locator(`${sel} [name="country"]`).fill('Pakistan');
    if (key === 'prayer_request') await page.locator(`${sel} [name="request"]`).fill('Pray for rain.');
    await page.waitForTimeout(3200); // the signed page token must be 3 s old
    await Promise.all([page.waitForNavigation(), page.click(submitSel(key))]);
    const err = await page.evaluate((sel) => {
      const form = document.querySelector(sel);
      const scope = document.querySelector(`[data-krm-for="${form.id}"]`) || form;
      const msg = scope.querySelector('[data-krm-msg]');
      const nameEl = form.querySelector('[name="name"]');
      return { url: location.href, msg: msg && !msg.hidden ? msg.textContent.trim() : '', kept: form.querySelector('[name="contact"]').value, nameInvalid: nameEl.getAttribute('aria-invalid'), note: (document.getElementById(nameEl.getAttribute('aria-describedby') || 'x') || {}).textContent };
    }, sel);
    await page.locator(`${sel} [name="name"]`).fill('No Script');
    await page.waitForTimeout(3200);
    const count = submissionCount();
    await Promise.all([page.waitForNavigation(), page.click(submitSel(key))]);
    const okState = await page.evaluate((sel) => {
      const form = document.querySelector(sel);
      const scope = document.querySelector(`[data-krm-for="${form.id}"]`) || form;
      const msg = scope.querySelector('[data-krm-msg]');
      return { msg: msg && !msg.hidden ? msg.textContent.trim() : '', cls: msg ? msg.className : '', fieldsHidden: form.querySelector('[name="name"]').offsetParent === null || form.id === 'gift' };
    }, sel);
    await shot(page, key, 'nojs-success-390');
    const ok = /krm_sent=/.test(err.url) && /highlighted/.test(err.msg) && err.kept === 'nojs@example.com' && err.nameInvalid === 'true' && !!err.note &&
      /is-success/.test(okState.cls) && okState.msg.length > 10 && okState.fieldsHidden && submissionCount() === count + 1;
    log({ test: 'no-js', key, ok, error: err, success: okState });
    await ctx.close();
  }

  /* ---------- dark mode: messages and spinner ---------- */
  for (const theme of ['light', 'dark']) {
    for (const key of ['volunteer', 'donation', 'home_prayer']) {
      clearRate();
      const ctx = await context(browser, 390, { dark: theme === 'dark' });
      const page = await open(ctx, FORMS[key].url);
      await fill(page, key, `Theme ${theme}`);
      // error box first
      await page.route('**/admin-ajax.php', (route) => (route.request().method() === 'POST' ? route.fulfill({ status: 500, body: '' }) : route.continue()));
      await page.click(submitSel(key));
      await waitDone(page, key);
      const errC = await msgContrast(page, key);
      await shot(page, key, `error-${theme}-390`);
      await page.unroute('**/admin-ajax.php');
      let release;
      const held = new Promise((r) => { release = r; });
      await page.route('**/admin-ajax.php', async (route) => {
        if (route.request().method() !== 'POST') return route.continue();
        const response = await route.fetch();
        await held;
        return route.fulfill({ response });
      });
      await page.click(submitSel(key));
      await page.waitForFunction((sel) => document.querySelector(sel).getAttribute('aria-busy') === 'true', FORMS[key].form);
      await page.waitForTimeout(3600);
      await shot(page, key, `loading-${theme}-390`);
      const spin = await page.evaluate((sub) => { const s = document.querySelector(sub + ' .krm-spinner'); return s ? getComputedStyle(s).borderTopColor : ''; }, submitSel(key));
      release();
      await waitDone(page, key);
      const okC = await msgContrast(page, key);
      await shot(page, key, `success-${theme}-390`);
      const dark = await page.evaluate(() => document.documentElement.getAttribute('data-theme'));
      log({ test: 'theme', theme, key, ok: errC >= 4.5 && okC >= 4.5 && !!spin && (theme === 'light' || dark === 'dark'), errorContrast: errC, successContrast: okC, spinner: spin });
      await ctx.close();
    }
  }
  clearRate();

  await browser.close();
  fs.writeFileSync(path.join(OUT, 'result.json'), JSON.stringify({ failures, results }, null, 2));
  console.log(JSON.stringify({ done: true, tests: results.length, failures }));
  process.exit(failures ? 1 : 0);
})();
