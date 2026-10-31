// Dashboard check (Phase 12): log in as an administrator, open every screen the theme adds or relies on (JS errors,
// HTTP status, PHP messages), then edit real content the way an editor would and confirm the public page changes:
// a design page's Page content box, Site text, Settings, a story and a ministry in the block editor. Every edit is
// undone afterwards (sections restored with "Restore this section", other values set back).
// usage: ADMIN_USER=admin ADMIN_PASS=... node admin_check.js [outfile.json]
const { chromium } = require('playwright-core');
const fs = require('fs');

const WP = process.env.WP_URL || 'http://127.0.0.1:8899';
const OUT = process.argv[2] || 'out/admin-check.json';
const PHP_NOTICE = /<b>(Warning|Notice|Deprecated|Fatal error)<\/b>:|There has been a critical error/;

const rest = async (path) => (await (await fetch(WP + '/wp-json/wp/v2/' + path)).json())[0].id;
const publicText = async (path) => (await (await fetch(WP + path + (path.includes('?') ? '&' : '?') + 'nocache=' + Date.now())).text());

(async () => {
  const browser = await chromium.launch({ executablePath: process.env.CHROME_PATH || '/usr/bin/google-chrome' });
  const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 } });
  const page = await ctx.newPage();
  let errors = [];
  page.on('pageerror', (e) => errors.push(String(e.message || e).slice(0, 200)));
  page.on('console', (m) => { if (m.type() === 'error') errors.push(m.text().slice(0, 200)); });
  const results = { screens: [], edits: [] };

  await page.goto(WP + '/wp-login.php');
  await page.fill('#user_login', process.env.ADMIN_USER || 'admin');
  await page.fill('#user_pass', process.env.ADMIN_PASS);
  await page.click('#wp-submit');
  await page.waitForURL(/wp-admin/);

  const ids = {
    about: await rest('pages?slug=about'),
    home: await rest('pages?slug=home'),
    donate: await rest('pages?slug=donate'),
    story: await rest('posts?slug=learning-beside-the-kilns'),
    ministry: await rest('ministry?slug=brick-kiln-children'),
  };

  const SCREENS = [
    ['Dashboard', '/wp-admin/'],
    ['Themes', '/wp-admin/themes.php'],
    ['Plugins', '/wp-admin/plugins.php'],
    ['Pages', '/wp-admin/edit.php?post_type=page'],
    ['Edit home page', `/wp-admin/post.php?post=${ids.home}&action=edit`],
    ['Edit about page', `/wp-admin/post.php?post=${ids.about}&action=edit`],
    ['Edit donate page', `/wp-admin/post.php?post=${ids.donate}&action=edit`],
    ['Posts', '/wp-admin/edit.php'],
    ['Edit story (block editor)', `/wp-admin/post.php?post=${ids.story}&action=edit`, 'block'],
    ['New story', '/wp-admin/post-new.php', 'block'],
    ['Ministries', '/wp-admin/edit.php?post_type=ministry'],
    ['Edit ministry (block editor)', `/wp-admin/post.php?post=${ids.ministry}&action=edit`, 'block'],
    ['Sermons', '/wp-admin/edit.php?post_type=sermon'],
    ['New sermon', '/wp-admin/post-new.php?post_type=sermon', 'block'],
    ['New event', '/wp-admin/post-new.php?post_type=event', 'block'],
    ['New testimonial', '/wp-admin/post-new.php?post_type=testimonial', 'block'],
    ['Submissions', '/wp-admin/edit.php?post_type=krm_submission'],
    ['Donations', '/wp-admin/edit.php?post_type=krm_donation'],
    ['Media', '/wp-admin/upload.php'],
    ['Menus', '/wp-admin/nav-menus.php'],
    ['Customizer', '/wp-admin/customize.php', 'customizer'],
    ['KRM Settings', '/wp-admin/admin.php?page=krm-settings'],
    ['KRM Site text', '/wp-admin/admin.php?page=krm-site-text'],
    ['KRM Setup', '/wp-admin/admin.php?page=krm-setup'],
    ['Reading settings', '/wp-admin/options-reading.php'],
    ['Site Health', '/wp-admin/site-health.php'],
  ];
  for (const [name, path, kind] of SCREENS) {
    errors = [];
    const res = await page.goto(WP + path, { waitUntil: 'load', timeout: 90000 });
    if (kind === 'block') await page.waitForFunction(() => window.wp && wp.data && wp.data.select('core/editor') && wp.data.select('core/editor').getCurrentPostId(), null, { timeout: 60000 });
    if (kind === 'customizer') await page.frameLocator('#customize-preview iframe').locator('footer').first().waitFor({ timeout: 60000 });
    await page.waitForTimeout(kind ? 2500 : 500);
    const html = await page.content();
    const row = { screen: name, status: res.status(), jsErrors: errors, php: PHP_NOTICE.test(html), metaBox: /krm-content|krm_ministry_nonce|krm_story_nonce/.test(html) };
    results.screens.push(row);
    console.log(JSON.stringify(row));
  }

  async function edit(name, check) {
    errors = [];
    let ok = false; let note = '';
    try { ({ ok, note } = await check()); } catch (e) { note = 'threw: ' + e.message; }
    const row = { edit: name, ok, note, jsErrors: errors };
    results.edits.push(row);
    console.log(JSON.stringify(row));
  }
  async function classicSave(url, fill, button) {
    await page.goto(WP + url);
    await fill();
    await Promise.all([page.waitForNavigation({ waitUntil: 'load' }), page.click(button)]);
  }
  async function blockEdit(id, edits) {
    await page.goto(WP + `/wp-admin/post.php?post=${id}&action=edit`);
    await page.waitForFunction(() => window.wp && wp.data && wp.data.select('core/editor').getCurrentPostId());
    await page.evaluate(async (e) => {
      wp.data.dispatch('core/editor').editPost(e);
      await wp.data.dispatch('core/editor').savePost();
    }, edits);
    await page.waitForFunction(() => !wp.data.select('core/editor').isSavingPost() && !wp.data.select('core/edit-post').isSavingMetaBoxes(), null, { timeout: 60000 });
    await page.waitForTimeout(1000);
    return page.evaluate(() => wp.data.select('core/editor').didPostSaveRequestSucceed());
  }

  const aboutUrl = `/wp-admin/post.php?post=${ids.about}&action=edit`;
  await edit('About: Page content field → page', async () => {
    await classicSave(aboutUrl, () => page.fill('[name="krm_content[hero][eyebrow]"]', 'About Us QA12'), '#publish');
    const changed = (await publicText('/about/')).includes('About Us QA12');
    await classicSave(aboutUrl, () => page.check('[name="krm_content_reset[hero]"]'), '#publish');
    const restored = !(await publicText('/about/')).includes('QA12');
    return { ok: changed && restored, note: `changed=${changed} restored=${restored}` };
  });

  await edit('Site text: footer field → every page', async () => {
    const url = '/wp-admin/admin.php?page=krm-site-text';
    await classicSave(url, () => page.fill('[name="krm_content[footer][location]"]', 'Faisalabad QA12'), '#submit');
    const changed = (await publicText('/contact/')).includes('Faisalabad QA12') && (await publicText('/')).includes('Faisalabad QA12');
    await classicSave(url, () => page.check('[name="krm_content_reset[footer]"]'), '#submit');
    const restored = !(await publicText('/contact/')).includes('QA12');
    return { ok: changed && restored, note: `changed=${changed} restored=${restored}` };
  });

  await edit('Settings: phone → header/footer', async () => {
    const url = '/wp-admin/admin.php?page=krm-settings';
    await page.goto(WP + url);
    const old = await page.inputValue('[name="krm_settings[phone]"]');
    await classicSave(url, () => page.fill('[name="krm_settings[phone]"]', '+92-300-0000012'), '#submit');
    const changed = (await publicText('/about/')).includes('+92-300-0000012');
    await classicSave(url, () => page.fill('[name="krm_settings[phone]"]', old), '#submit');
    const restored = (await publicText('/about/')).includes(old);
    return { ok: changed && restored, note: `changed=${changed} restored=${restored}` };
  });

  await edit('Story: title in block editor → page', async () => {
    await page.goto(WP + `/wp-admin/post.php?post=${ids.story}&action=edit`);
    await page.waitForFunction(() => window.wp && wp.data && wp.data.select('core/editor').getCurrentPostId());
    const old = await page.evaluate(() => wp.data.select('core/editor').getEditedPostAttribute('title'));
    const saved = await blockEdit(ids.story, { title: old + ' QA12' });
    const changed = (await publicText('/learning-beside-the-kilns/')).includes(old + ' QA12');
    const saved2 = await blockEdit(ids.story, { title: old });
    const restored = !(await publicText('/learning-beside-the-kilns/')).includes('QA12');
    return { ok: saved && saved2 && changed && restored, note: `saved=${saved} changed=${changed} restored=${restored}` };
  });

  await edit('Ministry: title + meta box in block editor → page', async () => {
    await page.goto(WP + `/wp-admin/post.php?post=${ids.ministry}&action=edit`);
    await page.waitForFunction(() => window.wp && wp.data && wp.data.select('core/editor').getCurrentPostId());
    const old = await page.evaluate(() => wp.data.select('core/editor').getEditedPostAttribute('title'));
    const focusOld = await page.inputValue('[name="krm_focus"]');
    await page.fill('[name="krm_focus"]', focusOld + ' QA12focus');
    const saved = await blockEdit(ids.ministry, { title: old + ' QA12' });
    const html = await publicText('/ministries/brick-kiln-children/');
    const changed = html.includes(old + ' QA12') && html.includes('QA12focus');
    await page.fill('[name="krm_focus"]', focusOld);
    const saved2 = await blockEdit(ids.ministry, { title: old });
    const restored = !(await publicText('/ministries/brick-kiln-children/')).includes('QA12');
    return { ok: saved && saved2 && changed && restored, note: `saved=${saved} changed=${changed} restored=${restored}` };
  });

  fs.mkdirSync(require('path').dirname(OUT), { recursive: true });
  fs.writeFileSync(OUT, JSON.stringify(results, null, 2));
  const bad = results.screens.filter((s) => s.status !== 200 || s.jsErrors.length || s.php).length + results.edits.filter((e) => !e.ok).length;
  console.log(`${results.screens.length} screens, ${results.edits.length} edits, ${bad} problems → ${OUT}`);
  await browser.close();
})();
