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
  var topbar = doc.querySelector(".topbar");
  var max = 0;
  var queued = false;
  function measure() {
    max = Math.max(0, html.scrollHeight - win.innerHeight);
    // The home hero slides up under the top bar too (theme-global.css): its height, which wraps on small screens.
    if (topbar) html.style.setProperty("--topbar-h", topbar.offsetHeight + "px");
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
  if ("ResizeObserver" in win) {
    var ro = new ResizeObserver(remeasure);
    ro.observe(doc.body); // images, fonts, opened panels
    if (topbar) ro.observe(topbar);
  }
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

  /* ---------- Top bar verse: the full verse on hover (CSS), keyboard focus or a tap ---------- */
  all("[data-krm-verse]").forEach(function (box) {
    var btn = box.querySelector("button");
    if (!btn) return;
    function set(open) { btn.setAttribute("aria-expanded", open ? "true" : "false"); }
    btn.addEventListener("click", function () { set(btn.getAttribute("aria-expanded") !== "true"); });
    doc.addEventListener("click", function (e) { if (!box.contains(e.target)) set(false); });
    doc.addEventListener("keydown", function (e) { if (e.key === "Escape" && btn.getAttribute("aria-expanded") === "true") { set(false); btn.focus(); } });
  });

  /* ---------- Photo focus: a cropped photo (object-fit: cover) keeps its faces in view ---------- */
  // data-focal ("x% y%") comes from krm_img_src() (inc/data/focal.php, built by tools/focal/build_focal.py).
  all("img[data-focal]").forEach(function (img) { img.style.objectPosition = img.getAttribute("data-focal"); });

  /* ---------- Heading highlights (.hl): 1–2 key words of each main heading on a solid colour block ---------- */
  // Words already marked in the content (<em>, or a span in the accent colour) are used as they are; otherwise the
  // first phrases of this list found in the heading. The copy itself never changes.
  var HL = ["Jesus Christ", "Vulnerable", "Brick Kiln Children", "Know Christ", "Our prayer", "Your gift", "your generosity",
    "your giving", "Gospel hope", "Pakistan’s Forgotten", "Pakistan's Forgotten", "Bring Hope", "be free", "Punjab",
    "in action", "the field", "Stand With Us", "pray with you", "faith", "on the ground", "news", "God’s Word", "New Life",
    "Brick Kiln", "a person", "twelve expressions", "Growth", "a new beginning", "Still Ahead", "a Calling", "here to do",
    "staff", "integrity", "the mission", "help", "God", "a project", "partner", "Partnership", "projects", "Testimonials",
    "Gallery", "We Do", "ministry groups", "Ministries", "this work", "serves", "Outreach", "Church", "Bible", "Discipleship",
    "Deliverance", "Freedom", "Orphan Care", "Clean Water", "Christian", "Widows", "Health", "Food", "Recovery", "Equipping",
    "Sunday School", "planted", "completed", "photos", "hope", "Gospel", "an amount", "Search", "Categories", "updates"];
  var LETTER = /[A-Za-zÀ-ɏ’']/;
  function wrapFirst(h, phrase) {
    var walker = doc.createTreeWalker(h, 4, null);
    var lower = phrase.toLowerCase();
    for (var n = walker.nextNode(); n; n = walker.nextNode()) {
      if (n.parentElement.closest(".hl")) continue;
      var t = n.nodeValue;
      var at = t.toLowerCase().indexOf(lower);
      while (at > -1) {
        var end = at + phrase.length;
        if (!(at > 0 && LETTER.test(t[at - 1])) && !(end < t.length && LETTER.test(t[end]))) {
          var mid = n.splitText(at);
          mid.splitText(phrase.length);
          var span = doc.createElement("span");
          span.className = "hl";
          mid.parentNode.replaceChild(span, mid);
          span.appendChild(mid);
          return true;
        }
        at = t.toLowerCase().indexOf(lower, at + 1);
      }
    }
    return false;
  }
  // Main headings (26px and up, outside forms and popups), plus smaller titles marked data-krm-hl in the templates.
  var heads = all("#main h1, #main h2, [data-krm-hl]").filter(function (h) {
    return h.hasAttribute("data-krm-hl") || (!h.closest("form, [role='dialog'], .give-card, [data-krm-give-card]") && parseFloat(win.getComputedStyle(h).fontSize) >= 26);
  });
  heads.forEach(function (h) {
    var marked = all("em, span[style*='--color-accent']", h);
    marked.forEach(function (m) { m.classList.add("hl"); });
    if (marked.length || h.querySelector(".hl")) return;
    var done = 0;
    for (var i = 0; i < HL.length && done < 2; i++) if (wrapFirst(h, HL[i])) done++;
  });
  // On light backgrounds the accent as text is too pale to read (2:1), so there it underlines the words instead
  // (class hl--on-light: headings in a dark colour). Rechecked when the theme changes; dark mode always uses the text colour.
  // On a mid-tone colour band (the red one) the accent is unreadable in both themes: class hl--plain, same stroke.
  function rgb(c) { return (c.match(/[\d.]+/g) || []).map(Number); }
  function lumOf(c) { var m = rgb(c); return m.length >= 3 ? (0.2126 * m[0] + 0.7152 * m[1] + 0.0722 * m[2]) / 255 : 1; }
  function relLum(m) {
    var f = function (v) { v /= 255; return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4); };
    return 0.2126 * f(m[0]) + 0.7152 * f(m[1]) + 0.0722 * f(m[2]);
  }
  function bandColor(el) {
    for (var p = el; p && p !== doc.body; p = p.parentElement) {
      var cs = win.getComputedStyle(p);
      if (cs.backgroundImage !== "none" && !/^linear-gradient/.test(cs.backgroundImage)) return null; // a photo under a dark overlay
      var m = rgb(cs.backgroundColor);
      if (m.length >= 3 && m[3] !== 0) return m;
    }
    return null;
  }
  function hlTone() {
    var dark = current() === "dark";
    all(".hl").forEach(function (el) {
      var h = el.closest("h1, h2, h3, h4, h5, h6, [data-krm-hl]") || el.parentElement;
      var light = lumOf(win.getComputedStyle(h).color) >= 0.45;
      var bg = bandColor(h);
      var acc = rgb(win.getComputedStyle(el).getPropertyValue("--color-accent").trim().replace(/^#(..)(..)(..)$/, function (x, r, g, b) { return parseInt(r, 16) + "," + parseInt(g, 16) + "," + parseInt(b, 16); }));
      var ratio = 21;
      if (bg && acc.length >= 3) { var a = relLum(acc), c = relLum(bg); ratio = (Math.max(a, c) + 0.05) / (Math.min(a, c) + 0.05); }
      el.classList.toggle("hl--plain", light && ratio < 3);
      if (!dark) el.classList.toggle("hl--on-light", !light);
    });
  }
  hlTone();
  doc.addEventListener("krm:themechange", hlTone);
  if (darkMq.addEventListener) darkMq.addEventListener("change", hlTone);

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
/* Section links without "#" in the address bar (every page; front.js does the same on the home page). Links keep their
   "#section" href, so they still work without script. Arriving with an anchor (/about/#beliefs, /donate/?…#give), the
   anchor is taken out of the address at once and the section is scrolled to after the content is ready and again
   after load (photos above it can move it). Same-page section links scroll without adding "#". Skip links are left
   alone so keyboard focus moves as usual. */
(function () {
  "use strict";
  if (!window.history || !history.replaceState) return;
  function sectionFor(hash) {
    if (!hash || hash.length < 2) return null;
    try { return document.getElementById(decodeURIComponent(hash.slice(1))); } catch (e) { return null; }
  }
  function cleanAddress() { history.replaceState(history.state, "", location.pathname + location.search); }
  var hash = location.hash;
  if (hash && hash.length > 1) {
    var jump = function () {
      setTimeout(function () { var s = sectionFor(hash); if (s) { s.scrollIntoView(); if (location.hash) cleanAddress(); } }, 60);
    };
    if (sectionFor(hash)) cleanAddress();
    if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", jump); else jump();
    if (document.readyState !== "complete") window.addEventListener("load", jump);
  }
  document.addEventListener("click", function (event) {
    if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
    var link = event.target.closest && event.target.closest("a[href]");
    if (!link || link.target || link.classList.contains("skip-link") || link.origin !== location.origin || link.pathname !== location.pathname || link.search !== location.search) return;
    var section = sectionFor(link.hash);
    if (!section) return;
    event.preventDefault();
    if (link.closest(".site-nav")) {
      document.querySelectorAll('.site-nav [aria-expanded="true"]').forEach(function (b) { b.setAttribute("aria-expanded", "false"); });
    }
    section.scrollIntoView();
    cleanAddress();
  });
})();

/* Map of Pakistan (home "Where we serve", Our Impact): the village/kiln dots pop in once the map scrolls into view (visible without JS). */
(function () {
  var dots = document.querySelector(".krm-where__dots");
  if (!dots || !("IntersectionObserver" in window)) return;
  dots.classList.add("is-armed");
  var io = new IntersectionObserver(function (entries) {
    if (!entries[0].isIntersecting) return;
    dots.classList.add("is-in");
    io.disconnect();
  }, { threshold: 0.25 });
  io.observe(dots);
})();
