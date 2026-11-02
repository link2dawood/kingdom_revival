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
        { bg: "#0C1C4F", fg: "#FFFFFF", border: "#0C1C4F" },
        { bg: "#FFFFFF", fg: "#141B33", border: "#D3D9E6" });
    });
    var href = DONATE_URL + "?amount=" + (amount === "Other" ? "" : encodeURIComponent(amount.replace("$", ""))) + "&frequency=One-time#give";
    var hero = document.querySelector('[data-krm-donate="hero"]');
    var float = document.querySelector('[data-krm-donate="float"]');
    // The amount sits in its own <span>, a separate flex item, exactly as the design rendered it.
    if (hero) { hero.href = href; hero.querySelector("span").textContent = amount === "Other" ? "Now" : amount; }
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
        paint(b, on, { bg: "#0C1C4F", fg: "#FFFFFF" }, { bg: "#F4F6FB", fg: "#141B33" });
        var num = b.querySelector("span");
        if (num) num.style.color = on ? "#F5A04A" : "#D4070B";
      });
      var img = part("img");
      if (img) {
        if (item.srcset) img.srcset = item.srcset; else img.removeAttribute("srcset");
        img.src = item.img;
        img.alt = item.title;
      }
      part("num").textContent = item.num;
      part("title").textContent = item.title;
      part("desc").textContent = item.desc;
      part("link").href = item.url;
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

  /* ---------- Hero video ---------- */
  // Started once the page has loaded, so its download doesn't hold up the first view.
  var heroVideo = document.querySelector("video[data-krm-autoplay]");
  if (heroVideo) {
    var startVideo = function () {
      heroVideo.autoplay = true;
      var playing = heroVideo.play();
      if (playing && playing.catch) playing.catch(function () {});
    };
    if (document.readyState === "complete") startVideo();
    else window.addEventListener("load", startVideo);
  }
})();
