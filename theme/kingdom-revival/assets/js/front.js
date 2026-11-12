/* Kingdom Revival Ministries — home page interactions (replaces the design's React state).
   - Amount picker: the hero card and the floating panel share one selected amount.
   - Ministry explorer: picking a ministry swaps the image, number, title, text and "See All" link.
   - Floating donation panel: open/close.
   - Section links (#all-ministries, #donate…) scroll to the section without leaving the "#…" in the address bar. */
(function () {
  var DONATE_URL = (window.KRM_CONFIG && KRM_CONFIG.donateUrl) || "./donate.html";

  /* ---------- Amount picker ---------- */
  var pressed = document.querySelector('button[data-amount][aria-pressed="true"]');
  var amount = pressed ? pressed.getAttribute("data-amount") : "$50";

  function paint(button, on, onStyle, offStyle) {
    var s = on ? onStyle : offStyle;
    button.setAttribute("aria-pressed", on ? "true" : "false");
    button.style.background = s.bg;
    button.style.color = s.fg;
    if (s.border) button.style.borderColor = s.border;
  }

  function renderAmounts() {
    document.querySelectorAll("button[data-amount]").forEach(function (b) {
      paint(b, b.dataset.amount === amount,
        { bg: "var(--color-button-navy)", fg: "var(--color-white)", border: "var(--color-button-navy)" },
        { bg: "var(--color-surface)", fg: "var(--color-text)", border: "var(--color-input-border)" });
    });
    var href = DONATE_URL + "?amount=" + (amount === "Other" ? "" : encodeURIComponent(amount.replace("$", ""))) + "&frequency=One-time#give";
    var hero = document.querySelector('[data-krm-donate="hero"]');
    var float = document.querySelector('[data-krm-donate="float"]');
    // The amount sits in its own <span>, a separate flex item, exactly as the design rendered it.
    // The hero card gives to its ministry (data-for, Pages → Home → card ministry); the floating panel does not.
    if (hero) { hero.href = hero.dataset.ministry ? href.replace("#give", "&ministry=" + encodeURIComponent(hero.dataset.ministry) + "#give") : hero.dataset.for ? href.replace("#give", "&for=" + encodeURIComponent(hero.dataset.for) + "#give") : href; hero.querySelector("span").textContent = amount === "Other" ? "Now" : amount; }
    if (float) { float.href = href; float.querySelector("span").textContent = amount === "Other" ? "Any Amount" : amount; }
  }

  document.addEventListener("click", function (event) {
    var button = event.target.closest && event.target.closest("button[data-amount]");
    if (!button) return;
    amount = button.dataset.amount;
    renderAmounts();
  });

  /* ---------- Ministry explorer ---------- */
  var dataEl = document.getElementById("krm-explorer-data");
  var items = [];
  try { items = dataEl ? JSON.parse(dataEl.textContent) : []; } catch (e) { items = []; }
  var group = dataEl && dataEl.nextElementSibling;

  function part(name) { return document.querySelector('[data-krm-explorer="' + name + '"]'); }

  if (group && items.length) {
    group.addEventListener("click", function (event) {
      var button = event.target.closest("button[data-index]");
      if (!button) return;
      var index = Number(button.dataset.index);
      var item = items[index];
      if (!item) return;
      group.querySelectorAll("button[data-index]").forEach(function (b) {
        var on = Number(b.dataset.index) === index;
        paint(b, on, { bg: "var(--color-band)", fg: "var(--color-white)" }, { bg: "var(--color-bg-soft)", fg: "var(--color-text)" });
        var num = b.querySelector("span");
        if (num) num.style.color = on ? "var(--color-accent)" : "var(--color-secondary-text)";
      });
      var img = part("img");
      if (img) {
        if (item.srcset) img.srcset = item.srcset; else img.removeAttribute("srcset");
        img.src = item.img;
        img.alt = item.title;
        img.style.objectPosition = item.focal || "center 20%"; // where the faces are, else the template's position
      }
      part("num").textContent = item.num;
      part("title").textContent = item.title;
      part("desc").textContent = item.desc;
      part("link").href = item.url;
      part("support").href = DONATE_URL + "?ministry=" + encodeURIComponent(item.slug) + "#give";
    });
  }

  /* ---------- Floating donation panel ---------- */
  var toggle = document.querySelector("[data-krm-float-toggle]");
  var panel = document.getElementById("floating-donation-panel");
  if (toggle && panel) {
    toggle.addEventListener("click", function () {
      var open = panel.style.display !== "block";
      panel.style.display = open ? "block" : "none";
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
      toggle.querySelector("path").setAttribute("d", open ? "M18 6L6 18M6 6l12 12" : "M12 5v14M5 12h14");
      toggle.querySelector("span").textContent = open ? "Close" : "Donate";
    });
  }

  /* The Freedom before/after slider is moved by assets/js/components.js (shared with the Slavery & Freedom page). */
  /* The prayer request form is sent by assets/js/components.js (shared with the Prayer Request page). */

  /* ---------- Section links without "#" in the address bar ---------- */
  // The links keep their "#section" href, so they still work without script. Arriving from another page
  // (/#all-ministries), the anchor is taken out of the address at once and the section is scrolled to here, after the
  // content is ready and again after load, as site.js does for anchors that stay.
  function sectionFor(hash) {
    return hash && hash.length > 1 ? document.getElementById(decodeURIComponent(hash.slice(1))) : null;
  }
  function cleanAddress() {
    if (history.replaceState) history.replaceState(history.state, "", location.pathname + location.search);
  }

  var arrived = sectionFor(location.hash);
  if (arrived) {
    cleanAddress();
    var jump = function () { setTimeout(function () { arrived.scrollIntoView(); }, 50); };
    if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", jump); else jump();
    if (document.readyState !== "complete") window.addEventListener("load", jump);
  }

  document.addEventListener("click", function (event) {
    if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
    var link = event.target.closest && event.target.closest("a[href]");
    if (!link || link.target || link.origin !== location.origin || link.pathname !== location.pathname || link.search !== location.search) return;
    var section = sectionFor(link.hash);
    if (!section) return;
    event.preventDefault();
    // A section link in the menu closes the menu (the page doesn't reload, so it would stay open over the section).
    if (link.closest(".site-nav")) {
      document.querySelectorAll('.site-nav [aria-expanded="true"]').forEach(function (b) { b.setAttribute("aria-expanded", "false"); });
    }
    section.scrollIntoView();
    cleanAddress();
  });

  /* ---------- Field stories video ---------- */
  // Its poster is set only when the section comes near the screen, so it doesn't compete with the hero image on
  // first load (a poster is otherwise downloaded at once, wherever the video is on the page).
  var lazyVideos = document.querySelectorAll("video[data-krm-poster]");
  var showPoster = function (v) { if (!v.poster) v.poster = v.getAttribute("data-krm-poster"); };
  if (lazyVideos.length) {
    if ("IntersectionObserver" in window) {
      var posterObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) { if (e.isIntersecting) { showPoster(e.target); posterObserver.unobserve(e.target); } });
      }, { rootMargin: "600px 0px" });
      lazyVideos.forEach(function (v) { posterObserver.observe(v); });
    } else {
      lazyVideos.forEach(showPoster);
    }
  }

  /* ---------- Hero video ---------- */
  // Autoplays (muted, inline) on every screen and restarts when it ends. Sources and poster are added only after the
  // page has loaded, so the download never holds up the first view; the HTML carries no src (hero.php). Skipped only
  // with data saver on: the section's background, the same picture, shows instead. Paused while scrolled out of view
  // or while the tab is hidden, and resumed after. The pause/play button (WCAG 2.2.2) stops it for good until pressed
  // again. If the browser refuses to autoplay (e.g. iOS low-power mode), it starts on the visitor's first touch or key.
  var heroVideo = document.querySelector("video[data-krm-autoplay]");
  var heroToggle = document.querySelector("[data-krm-video-toggle]");
  var saveData = !!(navigator.connection && navigator.connection.saveData === true);
  if (heroVideo && !saveData && heroVideo.getAttribute("data-src-mp4")) {
    var heroVisible = true;
    var userPaused = false;
    var started = false;
    var setToggle = function () {
      if (!heroToggle) return;
      var paused = heroVideo.paused;
      heroToggle.classList.toggle("is-paused", paused);
      heroToggle.setAttribute("aria-label", heroToggle.getAttribute(paused ? "data-label-play" : "data-label-pause"));
    };
    var play = function () {
      if (userPaused || !heroVisible || document.hidden) return;
      var playing = heroVideo.play();
      if (playing && playing.catch) playing.catch(function () { armGesture(); });
    };
    var armGesture = function () {
      var once = function () {
        ["pointerdown", "keydown", "touchstart", "scroll"].forEach(function (t) { window.removeEventListener(t, once, true); });
        play();
      };
      ["pointerdown", "keydown", "touchstart", "scroll"].forEach(function (t) { window.addEventListener(t, once, { capture: true, passive: true }); });
    };
    var startVideo = function () {
      if (started) return;
      started = true;
      var webp = (function () { try { return document.createElement("canvas").toDataURL("image/webp").indexOf("data:image/webp") === 0; } catch (e) { return false; } })();
      var poster = webp ? heroVideo.getAttribute("data-poster") : (heroVideo.getAttribute("data-poster-jpg") || heroVideo.getAttribute("data-poster"));
      if (poster) heroVideo.poster = poster;
      [["data-src-webm", "video/webm"], ["data-src-mp4", "video/mp4"]].forEach(function (s) {
        var src = heroVideo.getAttribute(s[0]);
        if (!src) return;
        var source = document.createElement("source");
        source.src = src;
        source.type = s[1];
        heroVideo.appendChild(source);
      });
      heroVideo.muted = true;
      heroVideo.loop = true;
      heroVideo.autoplay = true;
      heroVideo.preload = "auto";
      heroVideo.load();
      // Belt and braces for browsers that drop "loop" (or a stalled end): start again from the beginning.
      heroVideo.addEventListener("ended", function () { heroVideo.currentTime = 0; play(); });
      heroVideo.addEventListener("play", setToggle);
      heroVideo.addEventListener("pause", setToggle);
      heroVideo.addEventListener("playing", function () { if (heroToggle) heroToggle.hidden = false; });
      if ("IntersectionObserver" in window) {
        new IntersectionObserver(function (entries) {
          heroVisible = entries[0].isIntersecting;
          if (heroVisible) play(); else heroVideo.pause();
        }).observe(heroVideo);
      }
      document.addEventListener("visibilitychange", function () { if (!document.hidden) play(); });
      if (heroToggle) {
        heroToggle.addEventListener("click", function () {
          userPaused = !heroVideo.paused;
          if (userPaused) heroVideo.pause(); else play();
        });
      }
      play();
    };
    if (document.readyState === "complete") startVideo();
    else window.addEventListener("load", startVideo);
  }
  /* ---------- Gift calculator ("Your gift at work", template-parts/home/gift-calculator.php) ----------
     One rAF per frame at most: slider input only stores the value; the frame paints it. Numbers count to their new value
     (instant with prefers-reduced-motion). The Give link keeps the hero card's flow: Donate ?amount=N&frequency=One-time#give. */
  var calc = document.querySelector("[data-krm-calc]");
  if (calc) (function () {
    var range = calc.querySelector("[data-krm-calc-range]");
    var amountEl = calc.querySelector("[data-krm-calc-amount]");
    var give = calc.querySelector("[data-krm-calc-give]");
    var giveAmount = calc.querySelector("[data-krm-calc-give-amount]");
    var live = calc.querySelector("[data-krm-calc-live]");
    var chips = [].slice.call(calc.querySelectorAll("[data-krm-calc-chip]"));
    var min = Number(range.min), max = Number(range.max);
    var motion = window.matchMedia ? window.matchMedia("(prefers-reduced-motion: reduce)") : { matches: false };
    var money = function (n) { return "$" + Math.round(n).toLocaleString("en-US"); };
    var ease = function (t) { return 1 - Math.pow(1 - t, 3); };

    // Counters: the big amount plus one per row with a cost.
    var counters = [{ el: amountEl, fmt: money, shown: Number(range.value) }];
    var rows = [].slice.call(calc.querySelectorAll("li[data-unit]")).map(function (li) {
      var unit = Number(li.dataset.unit);
      var c = { el: li.querySelector("[data-krm-calc-num]"), fmt: function (n) { return String(Math.round(n)); }, shown: Math.floor(Number(range.value) / unit) };
      counters.push(c);
      return { li: li, unit: unit, counter: c, unlock: li.querySelector("[data-krm-calc-unlock]"), what: li.querySelector(".krm-calc__what[data-one]"), n: c.shown };
    });

    // Kiln debt row: no number (each family's debt differs); its broken-chain icon pulses when the amount changes.
    var free = calc.querySelector("[data-krm-calc-free]");
    if (free) free.addEventListener("animationend", function () { free.classList.remove("is-pulse"); });

    var tweenFrame = 0;
    function tweenTo(c, to) {
      c.from = c.shown; c.to = to; c.t0 = 0;
      if (motion.matches) { c.shown = to; c.el.textContent = c.fmt(to); return; }
      if (!tweenFrame) tweenFrame = requestAnimationFrame(step);
    }
    function step(now) {
      tweenFrame = 0;
      var busy = false;
      counters.forEach(function (c) {
        if (c.to === undefined || c.shown === c.to) return;
        if (!c.t0) c.t0 = now;
        var t = Math.min(1, (now - c.t0) / 320);
        c.shown = t >= 1 ? c.to : c.from + (c.to - c.from) * ease(t);
        c.el.textContent = c.fmt(c.shown);
        if (t < 1) busy = true;
      });
      if (busy) tweenFrame = requestAnimationFrame(step);
    }

    var announceTimer = 0;
    function paint(v) {
      range.style.setProperty("--p", ((v - min) / (max - min) * 100) + "%");
      range.setAttribute("aria-valuetext", money(v));
      chips.forEach(function (b) { b.setAttribute("aria-pressed", Number(b.dataset.krmCalcChip) === v ? "true" : "false"); });
      give.href = give.href.replace(/([?&]amount=)[^&#]*/, "$1" + v);
      giveAmount.textContent = money(v);
      tweenTo(counters[0], v);
      rows.forEach(function (r) {
        var n = Math.floor(v / r.unit);
        r.li.classList.toggle("is-locked", n < 1);
        r.unlock.hidden = n >= 1;
        if (n < 1) r.unlock.textContent = "Add " + money(r.unit - v) + " more to unlock";
        if (n >= 1 && r.n < 1 && !motion.matches) {
          r.li.classList.remove("is-unlocked");
          void r.li.offsetWidth; // restart the glow
          r.li.classList.add("is-unlocked");
        }
        r.n = n;
        if (r.what) r.what.textContent = n === 1 ? r.what.dataset.one : r.what.dataset.many; // plural unless exactly 1
        tweenTo(r.counter, n);
      });
      if (free && !motion.matches) { free.classList.remove("is-pulse"); void free.offsetWidth; free.classList.add("is-pulse"); }
      // Screen readers hear the amount once the slider settles, not every count.
      clearTimeout(announceTimer);
      announceTimer = setTimeout(function () { live.textContent = "Gift amount " + money(v); }, 700);
    }
    rows.forEach(function (r) { r.li.addEventListener("animationend", function () { r.li.classList.remove("is-unlocked"); }); });

    var pending = null, inputFrame = 0, slide = 0;
    range.addEventListener("input", function () {
      if (slide) { cancelAnimationFrame(slide); slide = 0; }
      pending = Number(range.value);
      if (!inputFrame) inputFrame = requestAnimationFrame(function () { inputFrame = 0; paint(pending); });
    });

    // Quick amounts: glide the slider to the chosen amount (jump with reduced motion).
    chips.forEach(function (b) {
      b.addEventListener("click", function () {
        var to = Number(b.dataset.krmCalcChip), from = Number(range.value), t0 = 0;
        if (slide) cancelAnimationFrame(slide);
        if (motion.matches || from === to) { range.value = to; paint(to); return; }
        paint(to); // counts and the Give link move now; the thumb glides there below
        range.style.setProperty("--p", ((from - min) / (max - min) * 100) + "%");
        slide = requestAnimationFrame(function frame(now) {
          if (!t0) t0 = now;
          var t = Math.min(1, (now - t0) / 420);
          var v = t >= 1 ? to : Math.round((from + (to - from) * ease(t)) / 5) * 5;
          range.value = v;
          range.style.setProperty("--p", ((v - min) / (max - min) * 100) + "%");
          slide = t < 1 ? requestAnimationFrame(frame) : 0;
        });
      });
    });
  })();
})();

/* ---------- Mission photo slider (template-parts/home/mission.php) ----------
   One photo at a time, cross-fading every 5 s; the progress bars fill as each photo shows. Pauses while hovered or
   focused and when off screen; arrows, swipe and ←/→ keys move it. With reduced motion it does not play by itself. */
(function () {
  document.querySelectorAll("[data-krm-slider]").forEach(function (root) {
    var slides = root.querySelectorAll(".krm-mission__slide");
    var bars = root.querySelectorAll(".krm-mission__bar");
    if (slides.length < 2) return;
    var MS = 5000;
    var still = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    var at = 0, timer = null, hover = false, seen = true;
    root.style.setProperty("--krm-slide-ms", MS + "ms");

    function go(n) {
      at = (n + slides.length) % slides.length;
      slides.forEach(function (s, i) {
        s.classList.toggle("is-active", i === at);
        if (i === at) s.removeAttribute("aria-hidden"); else s.setAttribute("aria-hidden", "true");
        var img = s.querySelector("img");
        if (img && (i === at || i === (at + 1) % slides.length)) img.loading = "eager"; // the next photo is ready in time
      });
      bars.forEach(function (b, i) {
        b.classList.remove("is-active");
        b.classList.toggle("is-done", i < at);
      });
      if (bars[at]) { void bars[at].offsetWidth; bars[at].classList.add("is-active"); } // restart its fill
      schedule();
    }
    function schedule() {
      clearTimeout(timer);
      var paused = hover || !seen || still;
      root.classList.toggle("is-paused", paused);
      if (!paused) timer = setTimeout(function () { go(at + 1); }, MS);
    }
    root.querySelector("[data-krm-prev]").addEventListener("click", function () { go(at - 1); });
    root.querySelector("[data-krm-next]").addEventListener("click", function () { go(at + 1); });
    root.addEventListener("mouseenter", function () { hover = true; schedule(); });
    root.addEventListener("mouseleave", function () { hover = false; go(at); });
    root.addEventListener("focusin", function () { hover = true; schedule(); });
    root.addEventListener("focusout", function () { hover = false; schedule(); });
    root.addEventListener("keydown", function (e) {
      if (e.key === "ArrowLeft") { e.preventDefault(); go(at - 1); }
      if (e.key === "ArrowRight") { e.preventDefault(); go(at + 1); }
    });
    var x0 = null;
    root.addEventListener("touchstart", function (e) { x0 = e.touches[0].clientX; }, { passive: true });
    root.addEventListener("touchend", function (e) {
      if (x0 === null) return;
      var dx = e.changedTouches[0].clientX - x0;
      x0 = null;
      if (Math.abs(dx) > 40) go(dx < 0 ? at + 1 : at - 1);
    });
    if ("IntersectionObserver" in window) {
      new IntersectionObserver(function (en) { seen = en[0].isIntersecting; if (seen) go(at); else schedule(); }, { threshold: 0.3 }).observe(root);
    }
    go(0);
  });
})();
