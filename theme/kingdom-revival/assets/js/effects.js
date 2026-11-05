/* Kingdom Revival Ministries — site-wide effects (Phase 20), on every page:
   - Theme toggle: button[data-theme-toggle] switches light/dark. The choice is saved in localStorage ("krm-theme")
     and set as <html data-theme> before the page paints by the inline script in header.php (no flash). With no
     saved choice the page follows the device setting. Other scripts can listen for the "krm:themechange" event.
   - Scroll progress bar (.krm-progress): scaleX() from 0 at the top to 1 at the bottom, updated once per frame.
   - Header: class "is-scrolled" after 80px (transparent over the hero at the top, frosted glass after).
   - Scroll reveal: [data-reveal] blocks and the items of [data-reveal-stagger] fade in and rise 20px once, when
     they come into view (classes krm-reveal / krm-reveal-stagger work too, for blocks made in the editor). Section
     headings, intro text and card grids get them automatically. Nothing on the first screen is hidden, nothing is
     hidden without JavaScript, and with reduced motion nothing moves.
   - Photo bands: [data-photo-band] get their background photo (data-band-photo, data-band-photo-s on phones) when
     they come near the screen. */
(function () {
  "use strict";
  var doc = document;
  var html = doc.documentElement;
  var win = window;
  var mq = function (q) { return win.matchMedia ? win.matchMedia(q) : { matches: false }; };
  var darkMq = mq("(prefers-color-scheme: dark)");
  var reduce = mq("(prefers-reduced-motion: reduce)").matches;
  var hasIO = "IntersectionObserver" in win;
  function all(sel, root) { return Array.prototype.slice.call((root || doc).querySelectorAll(sel)); }

  /* ---------- Light / dark theme ---------- */
  var toggles = all("[data-theme-toggle]");
  function saved() { try { return localStorage.getItem("krm-theme"); } catch (e) { return null; } }
  function current() {
    var t = html.getAttribute("data-theme");
    return t === "dark" || t === "light" ? t : (darkMq.matches ? "dark" : "light");
  }
  function sync() {
    var dark = current() === "dark";
    toggles.forEach(function (b) {
      b.setAttribute("aria-pressed", dark ? "true" : "false");
      b.setAttribute("aria-label", dark ? "Switch to light mode" : "Switch to dark mode");
    });
    var chosen = html.getAttribute("data-theme");
    all('meta[name="theme-color"]').forEach(function (m) {
      // With a saved choice both metas carry that theme's colour; otherwise each keeps its own media query's.
      var own = /dark/.test(m.getAttribute("media") || "") ? "dark" : "light";
      m.setAttribute("content", m.getAttribute("data-" + (chosen || own)));
    });
  }
  function announce() {
    var e;
    try { e = new CustomEvent("krm:themechange", { detail: { theme: current() } }); }
    catch (x) { e = doc.createEvent("CustomEvent"); e.initCustomEvent("krm:themechange", false, false, { theme: current() }); }
    doc.dispatchEvent(e);
  }
  function setTheme(t) {
    html.setAttribute("data-theme", t);
    try { localStorage.setItem("krm-theme", t); } catch (e) {}
    sync();
    announce();
  }
  toggles.forEach(function (b) {
    b.addEventListener("click", function () { setTheme(current() === "dark" ? "light" : "dark"); });
  });
  var onDevice = function () { if (!saved()) { sync(); announce(); } };
  if (darkMq.addEventListener) darkMq.addEventListener("change", onDevice);
  else if (darkMq.addListener) darkMq.addListener(onDevice);
  // A choice made in another tab applies here too.
  win.addEventListener("storage", function (e) {
    if (e.key !== "krm-theme") return;
    if (e.newValue === "dark" || e.newValue === "light") html.setAttribute("data-theme", e.newValue);
    else html.removeAttribute("data-theme");
    sync();
    announce();
  });
  sync();

  /* ---------- Scroll progress bar and header state ---------- */
  var bar = doc.querySelector(".krm-progress");
  var header = doc.querySelector(".site-header");
  var max = 0;
  var queued = false;
  function measure() {
    max = Math.max(0, html.scrollHeight - win.innerHeight);
    // Pages that (almost) fit the screen have nothing to show.
    if (bar) bar.classList.toggle("is-idle", max < 48);
  }
  function draw() {
    queued = false;
    var y = win.pageYOffset || html.scrollTop;
    if (bar) bar.style.transform = "scaleX(" + (max > 0 ? Math.min(1, Math.max(0, y / max)) : 0) + ")";
    if (header) header.classList.toggle("is-scrolled", y > 80);
  }
  function queue() { if (!queued) { queued = true; win.requestAnimationFrame(draw); } }
  function remeasure() { measure(); queue(); }
  win.addEventListener("scroll", queue, { passive: true });
  win.addEventListener("resize", remeasure, { passive: true });
  win.addEventListener("load", remeasure);
  if ("ResizeObserver" in win) new ResizeObserver(remeasure).observe(doc.body); // images, fonts, opened panels
  measure();
  draw();

  /* ---------- Photo bands (lazy background photo) ---------- */
  var bands = all("[data-photo-band][data-band-photo]");
  function loadBand(el) {
    var small = win.innerWidth <= 820 && el.getAttribute("data-band-photo-s");
    var url = small || el.getAttribute("data-band-photo");
    el.style.setProperty("--band-photo", 'url("' + url.replace(/"/g, "%22") + '")');
  }
  if (hasIO) {
    var bandWatch = new IntersectionObserver(function (entries) {
      entries.forEach(function (en) { if (en.isIntersecting) { bandWatch.unobserve(en.target); loadBand(en.target); } });
    }, { rootMargin: "400px 0px" });
    bands.forEach(function (b) { bandWatch.observe(b); });
  } else {
    bands.forEach(loadBand);
  }

  /* ---------- Scroll reveal ---------- */
  var main = doc.getElementById("main") || doc.querySelector("main");
  if (!main || !hasIO || reduce) return;

  // Never hidden: the hero, forms, the donation cards, floating buttons, popups and anything marked data-no-reveal.
  var SKIP = "#top, .page-hero, .post-hero, .impact-hero, [data-hero], form, .give-card, [data-krm-give-card], " +
    "[data-krm-donate], #give, .donate-form, .float-give, .krm-wa-float, [role='dialog'], [data-no-reveal], " +
    "[data-krm-explorer-panel], .impact-explorer";
  var STAGGER_STEP = 80;
  var STAGGER_MAX = 400;
  var tagged = [];
  var skipped = function (el) { return !!el.closest(SKIP) || !!el.querySelector("form, .give-card, [data-krm-give-card]"); };
  var inTagged = function (el) {
    for (var p = el; p && p !== main; p = p.parentElement) if (p.__krmReveal) return true;
    return false;
  };
  var hasTagged = function (el) { return tagged.some(function (t) { return el.contains(t); }); };
  function tag(el, group) {
    if (!el || el.__krmReveal || skipped(el) || inTagged(el) || hasTagged(el)) return;
    el.__krmReveal = group ? "group" : "one";
    tagged.push(el);
  }

  // 1. Blocks marked in the markup or the editor.
  all("[data-reveal], .krm-reveal", main).forEach(function (el) { tag(el, false); });
  all("[data-reveal-stagger], .krm-reveal-stagger", main).forEach(function (el) { tag(el, true); });
  // 2. Call-to-action banners, timelines and galleries.
  all(".cta-banner > div, [data-photo-band='cta'], .give-band", main).forEach(function (el) { tag(el, false); });
  all(".timeline, [data-gallery], .gallery-grid", main).forEach(function (el) { tag(el, true); });
  // 3. Section headings with their small heading (eyebrow) and introduction: the block that holds them when it holds
  //    nothing else, else each one.
  all("h2", main).forEach(function (h) {
    var box = h.parentElement;
    var kids = box ? Array.prototype.slice.call(box.children) : [];
    var simple = box && box !== main && kids.length <= 4 && kids.every(function (k) { return /^(P|H2|SPAN|DIV)$/.test(k.tagName) && !k.querySelector("img, form, ul, ol"); }) && kids.filter(function (k) { return k.tagName === "DIV"; }).length <= 1;
    if (simple) { tag(box, false); return; }
    var prev = h.previousElementSibling;
    var next = h.nextElementSibling;
    if (prev && /^(P|SPAN)$/.test(prev.tagName) && prev.textContent.length < 90) tag(prev, false);
    tag(h, false);
    if (next && next.tagName === "P") tag(next, false);
  });
  // 4. Card grids and wrapping rows of cards: their items, staggered.
  all("*", main).forEach(function (el) {
    if (el.childElementCount < 2 || el.__krmReveal) return;
    var cs = win.getComputedStyle(el);
    var grid = cs.display === "grid" || cs.display === "inline-grid";
    var wrapRow = cs.display === "flex" && cs.flexWrap === "wrap" && cs.flexDirection.indexOf("row") === 0 && el.childElementCount >= 3;
    if (!grid && !wrapRow) return;
    var kids = Array.prototype.slice.call(el.children).filter(function (k) { return k.offsetHeight > 0; });
    // Cards, not rows of buttons or form fields.
    if (kids.length < 2 || kids.some(function (k) { return k.offsetHeight < 60 || /^(INPUT|LABEL|BUTTON|SELECT|TEXTAREA)$/.test(k.tagName); })) return;
    tag(el, true);
  });

  var vh = win.innerHeight;
  function onScreen(el) {
    var r = el.getBoundingClientRect();
    return (r.width === 0 && r.height === 0) || (r.top < vh && r.bottom > 0);
  }
  var pending = [];
  var watch = new IntersectionObserver(function (entries) {
    var batch = [];
    entries.forEach(function (en) { if (en.isIntersecting) batch.push(en.target); });
    batch.sort(function (a, b) { return a.compareDocumentPosition(b) & 4 ? -1 : 1; });
    var perGroup = [];
    batch.forEach(function (el) {
      var delay = 0;
      if (el.__krmGroup) {
        var i = perGroup.indexOf(el.__krmGroup);
        if (i < 0) { perGroup.push(el.__krmGroup, 0); i = perGroup.length - 2; }
        delay = Math.min(perGroup[i + 1] * STAGGER_STEP, STAGGER_MAX);
        perGroup[i + 1] += 1;
      }
      show(el, delay);
    });
  }, { rootMargin: "0px 0px -15% 0px", threshold: 0 });

  function show(el, delay) {
    watch.unobserve(el);
    var at = pending.indexOf(el);
    if (at > -1) pending.splice(at, 1);
    if (!el.classList.contains("reveal-pending")) return;
    if (delay) el.style.setProperty("--reveal-delay", delay + "ms");
    el.classList.add("reveal-in");
    el.classList.remove("reveal-pending");
    win.setTimeout(function () {
      el.classList.remove("reveal-in");
      el.style.removeProperty("--reveal-delay");
    }, delay + 700);
  }
  function hide(el, group) {
    if (onScreen(el)) return; // the first screen is never hidden or animated
    el.__krmGroup = group || null;
    el.classList.add("reveal-pending");
    pending.push(el);
    watch.observe(el);
  }
  tagged.forEach(function (el) {
    if (el.__krmReveal === "group") {
      Array.prototype.slice.call(el.children).forEach(function (k) { if (!k.matches(SKIP) && !skipped(k)) hide(k, el); });
    } else {
      hide(el, null);
    }
  });

  // Safety net: at the very bottom of the page (or before printing) everything still waiting is shown.
  function flush() { pending.slice().forEach(function (el) { show(el, 0); }); }
  win.addEventListener("scroll", function () {
    if (pending.length && win.innerHeight + (win.pageYOffset || html.scrollTop) >= html.scrollHeight - 4) flush();
  }, { passive: true });
  win.addEventListener("beforeprint", flush);
  win.krmReveal = { flush: flush };
})();
/* Mega menu (desktop): the panel is centred under its menu item (theme-global.css); when that would cross the
   screen edge, --mega-shift moves it back to a 16px margin. Measured each time it opens. */
(function () {
  "use strict";
  var items = document.querySelectorAll(".menu > li.is-mega");
  if (!items.length) return;
  function fit(li) {
    var panel = li.querySelector(":scope > .submenu");
    if (!panel || window.innerWidth <= 900) return;
    panel.style.removeProperty("--mega-shift");
    var r = panel.getBoundingClientRect();
    if (!r.width) return;
    var vw = document.documentElement.clientWidth, gap = 16, shift = 0;
    if (r.left < gap) shift = gap - r.left;
    else if (r.right > vw - gap) shift = vw - gap - r.right;
    if (shift) panel.style.setProperty("--mega-shift", Math.round(shift) + "px");
  }
  Array.prototype.forEach.call(items, function (li) {
    var run = function () { fit(li); window.requestAnimationFrame(function () { fit(li); }); };
    li.addEventListener("mouseenter", run);
    li.addEventListener("focusin", run);
    li.addEventListener("click", run);
  });
})();
