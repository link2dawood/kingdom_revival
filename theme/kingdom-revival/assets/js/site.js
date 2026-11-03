/* Kingdom Revival Ministries — shared behaviour: navigation, donation cards, image viewer */
(function () {
  /* WordPress passes its URLs and settings in KRM_CONFIG (see inc/enqueue.php). */
  var CONFIG = window.KRM_CONFIG || {};
  var DONATE_URL = CONFIG.donateUrl || "./donate.html";
  /* Newsletter sign-ups are saved in WordPress (Kingdom Revival → Submissions) and emailed to the ministry.
     Without WordPress (no ajaxUrl) the popup falls back to opening an email to the ministry. */
  var NEWSLETTER_ENDPOINT = CONFIG.ajaxUrl || "";
  var NEWSLETTER_EMAIL = CONFIG.email || "Info@kingdomrevivalministries.com";
  var NEWSLETTER_DELAY = typeof CONFIG.newsletterDelay === "number" ? CONFIG.newsletterDelay : 3000;
  var NEWSLETTER_IMAGE = CONFIG.newsletterImage || "https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-40-scaled.jpg";
  var NEWSLETTER_SNOOZE_DAYS = 7;
  var KRM = (window.KRM = window.KRM || {});
  KRM.donateUrl = DONATE_URL;

  /* ---------- Navigation ---------- */
  var MENU_WIDTH = 258;

  function closeMenus(except) {
    document.querySelectorAll('.site-nav .sub-toggle[aria-expanded="true"]').forEach(function (button) {
      if (!except || !button.parentElement.contains(except)) button.setAttribute("aria-expanded", "false");
    });
  }

  function placeSubmenu(li) {
    var sub = li.querySelector(":scope > .submenu");
    if (!sub) return;
    if (li.parentElement.classList.contains("menu")) {
      sub.classList.toggle("align-right", li.getBoundingClientRect().left + MENU_WIDTH > window.innerWidth - 8);
    } else {
      sub.classList.toggle("open-left", li.parentElement.getBoundingClientRect().right + MENU_WIDTH > window.innerWidth - 8);
    }
  }

  document.addEventListener("click", function (event) {
    var button = event.target.closest(".site-nav .sub-toggle, .site-nav .menu-toggle");
    if (!button) {
      if (!event.target.closest(".site-nav")) {
        closeMenus();
        document.querySelectorAll(".site-nav .menu-toggle").forEach(function (b) { b.setAttribute("aria-expanded", "false"); });
      }
      return;
    }
    var open = button.getAttribute("aria-expanded") !== "true";
    if (button.classList.contains("sub-toggle")) {
      closeMenus(button);
      if (open) placeSubmenu(button.parentElement);
    } else if (!open) {
      closeMenus();
    }
    button.setAttribute("aria-expanded", open ? "true" : "false");
  });

  document.addEventListener("pointerover", function (event) {
    var li = event.target.closest && event.target.closest(".site-nav .has-sub");
    if (li) placeSubmenu(li);
  });

  document.addEventListener("keydown", function (event) {
    if (event.key !== "Escape" || document.querySelector(".lb")) return;
    var open = Array.prototype.slice.call(document.querySelectorAll('.site-nav .sub-toggle[aria-expanded="true"]')).pop();
    if (open) { open.setAttribute("aria-expanded", "false"); open.focus(); }
  });

  /* ---------- Donation cards ---------- */
  var AMOUNTS = ["$25", "$50", "$100", "$250", "$500", "Other"];
  var giveCount = 0;

  KRM.initGive = function (form) {
    if (!form || form.dataset.ready) return;
    form.dataset.ready = "true";
    var id = "give-" + (++giveCount);
    var target = form.dataset.give || "Kingdom Revival Ministries";
    var heading = form.dataset.heading;
    var intro = form.dataset.intro;

    form.innerHTML =
      (heading ? '<h3></h3>' : "") +
      (intro ? '<p class="give-intro"></p>' : "") +
      '<fieldset class="give-frequency"><legend class="visually-hidden">Gift frequency</legend>' +
      '<label><input type="radio" name="frequency" value="One-time" checked><span>One-time</span></label>' +
      '<label><input type="radio" name="frequency" value="Monthly"><span>Monthly</span></label></fieldset>' +
      '<fieldset class="give-amounts"><legend>Choose an amount</legend>' +
      AMOUNTS.map(function (amount) {
        return '<label><input type="radio" name="amount" value="' + amount + '"' + (amount === "$50" ? " checked" : "") + "><span>" + amount + "</span></label>";
      }).join("") +
      "</fieldset>" +
      '<label class="give-custom" hidden><span>Enter amount (USD)</span><input type="number" id="' + id + '-custom" min="1" step="1" inputmode="numeric" placeholder="e.g. 75"></label>' +
      '<button class="button button-primary give-submit" type="submit"></button>' +
      '<p class="give-note"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"></rect><path d="M8 11V7a4 4 0 0 1 8 0v4"></path></svg>Secure giving · every gift goes to the field</p>';

    if (heading) form.querySelector("h3").textContent = heading;
    if (intro) form.querySelector(".give-intro").textContent = intro;

    var custom = form.querySelector(".give-custom");
    var customInput = custom.querySelector("input");
    var submit = form.querySelector(".give-submit");

    function update() {
      var amount = form.elements.amount.value;
      var monthly = form.elements.frequency.value === "Monthly";
      custom.hidden = amount !== "Other";
      var value = amount === "Other" ? (customInput.value ? "$" + customInput.value : "") : amount;
      submit.textContent = "Donate " + (value ? value + " " : "") + (monthly ? "monthly " : "") + (form.dataset.short ? "" : "to " + target);
    }

    form.addEventListener("change", update);
    customInput.addEventListener("input", update);
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      if (form.elements.amount.value === "Other" && !customInput.value) { customInput.focus(); return; }
      var amount = form.elements.amount.value === "Other" ? customInput.value : form.elements.amount.value.replace("$", "");
      var fund = form.dataset.give && form.dataset.give !== "Kingdom Revival Ministries" ? form.dataset.give : "Where it is needed most";
      window.location.href = DONATE_URL + "?amount=" + encodeURIComponent(amount) + "&frequency=" + encodeURIComponent(form.elements.frequency.value) +
        "&for=" + encodeURIComponent(fund) + "#give";
    });
    update();
  };

  /* ---------- Image viewer ---------- */
  var ICONS = {
    close: '<path d="M18 6L6 18M6 6l12 12"></path>',
    zoomIn: '<circle cx="11" cy="11" r="7"></circle><path d="M21 21l-4.3-4.3M11 8v6M8 11h6"></path>',
    zoomOut: '<circle cx="11" cy="11" r="7"></circle><path d="M21 21l-4.3-4.3M8 11h6"></path>',
    reset: '<path d="M4 9V4h5M20 15v5h-5M4 15v5h5M20 9V4h-5"></path>',
    rotateLeft: '<path d="M3 12a9 9 0 1 0 3-6.7L3 8"></path><path d="M3 3v5h5"></path>',
    rotateRight: '<path d="M21 12a9 9 0 1 1-3-6.7L21 8"></path><path d="M21 3v5h-5"></path>',
    full: '<path d="M8 3H5a2 2 0 0 0-2 2v3M21 8V5a2 2 0 0 0-2-2h-3M3 16v3a2 2 0 0 0 2 2h3M16 21h3a2 2 0 0 0 2-2v-3"></path>',
    open: '<path d="M14 3h7v7M10 14L21 3M19 14v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h5"></path>',
    prev: '<path d="M15 18l-6-6 6-6"></path>',
    next: '<path d="M9 18l6-6-6-6"></path>'
  };
  function icon(name, size) {
    return '<svg width="' + (size || 20) + '" height="' + (size || 20) + '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' + ICONS[name] + "</svg>";
  }
  function tool(act, label, title, iconName, extra) {
    return '<button type="button" data-act="' + act + '"' + (extra || "") + ' aria-label="' + label + '" title="' + title + '">' + icon(iconName) + "</button>";
  }

  /* Zoom is relative to the fitted size: 1 = the whole photo fits the stage (at its current rotation). */
  var MIN = 1, MAX = 5, STEP = 1.5, GAP = 16;
  var lb = null, state = null;

  function groupFor(img) {
    var container = img.closest("[data-gallery]");
    var images = container ? container.querySelectorAll("img") : document.querySelectorAll('img[data-zoom="' + img.dataset.zoom + '"]');
    return Array.prototype.filter.call(images, function (el) { return !el.closest(".lb"); });
  }

  KRM.refreshZoomables = function (root) {
    (root || document).querySelectorAll("[data-gallery] img, img[data-zoom]").forEach(function (img) {
      if (img.dataset.zoomReady) return;
      img.dataset.zoomReady = "true";
      img.tabIndex = 0;
      img.setAttribute("role", "button");
      img.setAttribute("aria-label", "View larger: " + (img.alt || "photo"));
    });
  };

  function openViewer(img) {
    var group = groupFor(img);
    state = {
      items: group.map(function (el) { return { src: el.currentSrc || el.src, full: el.dataset.full || el.src, alt: el.alt || "" }; }),
      index: Math.max(0, group.indexOf(img)),
      scale: 1, x: 0, y: 0, rotate: 0,
      fit: { w: 0, h: 0, k: 1 },
      pointers: new Map(), returnFocus: img
    };
    build();
    show(state.index);
    document.documentElement.style.overflow = "hidden";
  }

  function build() {
    lb = document.createElement("div");
    lb.className = "lb";
    lb.setAttribute("role", "dialog");
    lb.setAttribute("aria-modal", "true");
    lb.setAttribute("aria-label", "Photo viewer");
    var many = state.items.length > 1;
    lb.innerHTML =
      '<div class="lb-top"><span class="lb-count" aria-live="polite"></span>' +
      '<div class="lb-tools" role="toolbar" aria-label="Photo tools">' +
      tool("out", "Zoom out", "Zoom out (−)", "zoomOut") +
      '<span class="lb-zoom-level">100%</span>' +
      tool("in", "Zoom in", "Zoom in (+)", "zoomIn") +
      '<span class="lb-sep" aria-hidden="true"></span>' +
      tool("left", "Rotate left", "Rotate left (L)", "rotateLeft") +
      tool("right", "Rotate right", "Rotate right (R)", "rotateRight") +
      tool("reset", "Reset zoom and rotation", "Reset (0)", "reset") +
      "</div>" +
      '<span class="lb-sep" aria-hidden="true"></span><div class="lb-extra">' +
      (document.fullscreenEnabled ? tool("full", "Full screen", "Full screen (F)", "full") : "") +
      '<a class="lb-btn" data-act="open" target="_blank" rel="noopener" aria-label="Open original image" title="Open original">' + icon("open") + "</a>" +
      '</div><span class="lb-sep" aria-hidden="true"></span>' +
      '<button type="button" class="lb-close" data-act="close" aria-label="Close viewer" title="Close (Esc)">' + icon("close", 24) + "</button>" +
      "</div>" +
      '<div class="lb-stage"><span class="lb-spinner" aria-hidden="true"></span><img class="lb-img is-loading" alt="" draggable="false">' +
      (many ? '<button type="button" class="lb-nav lb-prev" data-act="prev" aria-label="Previous photo">' + icon("prev", 26) + '</button><button type="button" class="lb-nav lb-next" data-act="next" aria-label="Next photo">' + icon("next", 26) + "</button>" : "") +
      "</div>" +
      '<div class="lb-bottom"><div class="lb-pager">' +
      (many ? '<button type="button" class="lb-step" data-act="prev" aria-label="Previous photo">' + icon("prev", 24) + "</button>" : "") +
      '<p class="lb-caption"></p>' +
      (many ? '<button type="button" class="lb-step" data-act="next" aria-label="Next photo">' + icon("next", 24) + "</button>" : "") +
      '</div><p class="lb-hint">Scroll or pinch to zoom · drag to move · double-click to zoom in/out · R / L to rotate' + (many ? " · ← → for next photo" : "") + "</p>" +
      (many ? '<div class="lb-thumbs">' + state.items.map(function (item, i) {
        return '<button type="button" class="lb-thumb" data-index="' + i + '" aria-label="Photo ' + (i + 1) + '"><img src="' + item.src + '" alt="" loading="lazy"></button>';
      }).join("") + "</div>" : "") +
      "</div>";
    document.body.appendChild(lb);

    var stage = lb.querySelector(".lb-stage");
    var image = lb.querySelector(".lb-img");
    image.addEventListener("load", function () { if (lb) { imageReady(); refit(false); } });
    image.addEventListener("error", function () { if (lb) imageReady(); });

    // Keep the photo fitted when the window, the phone's orientation or full screen changes the stage size.
    if (window.ResizeObserver) {
      state.observer = new ResizeObserver(function () { if (lb) refit(false); });
      state.observer.observe(stage);
    } else {
      state.onResize = function () { if (lb) refit(false); };
      window.addEventListener("resize", state.onResize);
    }

    lb.addEventListener("click", function (event) {
      var thumb = event.target.closest(".lb-thumb");
      if (thumb) { show(Number(thumb.dataset.index)); return; }
      var act = event.target.closest("[data-act]");
      if (!act) {
        if (state.downOnBackdrop && state.scale === 1 && !state.moved) close();
        return;
      }
      var a = act.dataset.act;
      if (a === "close") close();
      else if (a === "prev") show(state.index - 1);
      else if (a === "next") show(state.index + 1);
      else if (a === "in") zoomTo(state.scale * STEP, 0, 0, true);
      else if (a === "out") zoomTo(state.scale / STEP, 0, 0, true);
      else if (a === "reset") reset();
      else if (a === "left") rotateBy(-90);
      else if (a === "right") rotateBy(90);
      else if (a === "full") toggleFullscreen();
    });

    stage.addEventListener("wheel", function (event) {
      event.preventDefault();
      var p = stagePoint(event);
      zoomTo(state.scale * Math.exp(-event.deltaY * 0.0018), p.x, p.y, false);
    }, { passive: false });

    stage.addEventListener("dblclick", function (event) {
      if (event.target.closest("button") || Date.now() - (state.tapZoomAt || 0) < 500) return;
      toggleZoom(stagePoint(event));
    });

    stage.addEventListener("pointerdown", function (event) {
      if (event.target.closest("button")) return;
      stage.setPointerCapture(event.pointerId);
      state.pointers.set(event.pointerId, { x: event.clientX, y: event.clientY });
      state.moved = false;
      state.downOnBackdrop = event.target === stage;
      state.start = { x: event.clientX, y: event.clientY, ox: state.x, oy: state.y };
      if (state.pointers.size === 2) state.pinch = pinchInfo();
      stage.classList.add("is-dragging");
    });
    stage.addEventListener("pointermove", function (event) {
      if (!state.pointers.has(event.pointerId)) return;
      state.pointers.set(event.pointerId, { x: event.clientX, y: event.clientY });
      if (state.pointers.size === 2 && state.pinch) {
        var now = pinchInfo();
        var mid = stagePoint({ clientX: now.cx, clientY: now.cy });
        zoomTo(state.pinch.scale * (now.dist / state.pinch.dist), mid.x, mid.y, false);
        state.moved = true;
        return;
      }
      var dx = event.clientX - state.start.x, dy = event.clientY - state.start.y;
      if (Math.abs(dx) + Math.abs(dy) > 4) state.moved = true;
      if (state.scale > 1) {
        state.x = state.start.ox + dx;
        state.y = state.start.oy + dy;
        clampPan();
        apply(false);
      } else if (state.items.length > 1) {
        image.classList.remove("is-animating");
        image.style.transform = transform(dx, 0, 1);
      }
    });
    function endPointer(event) {
      if (!state || !state.pointers.has(event.pointerId)) return;
      state.pointers.delete(event.pointerId);
      if (state.pointers.size < 2) state.pinch = null;
      if (state.pointers.size > 0) {
        var rest = state.pointers.values().next().value;
        state.start = { x: rest.x, y: rest.y, ox: state.x, oy: state.y };
        state.moved = true;
        return;
      }
      stage.classList.remove("is-dragging");
      if (event.type === "pointerup" && event.pointerType !== "mouse" && !state.moved && !state.downOnBackdrop) {
        // Double tap on a touch screen: zoom in on that spot, or back out.
        var tap = { t: Date.now(), x: event.clientX, y: event.clientY };
        if (state.lastTap && tap.t - state.lastTap.t < 320 && Math.hypot(tap.x - state.lastTap.x, tap.y - state.lastTap.y) < 30) {
          state.lastTap = null;
          state.tapZoomAt = tap.t;
          toggleZoom(stagePoint(event));
          return;
        }
        state.lastTap = tap;
      }
      if (state.scale === 1) {
        var dx = event.clientX - state.start.x;
        if (Math.abs(dx) > 60 && state.items.length > 1) show(state.index + (dx < 0 ? 1 : -1));
        else apply(true);
      } else {
        clampPan();
        apply(true);
      }
      setTimeout(function () { if (state) state.moved = false; }, 0);
    }
    stage.addEventListener("pointerup", endPointer);
    stage.addEventListener("pointercancel", endPointer);

    // On the document, so the keys still work after a click or tap on the photo moves focus out of the viewer.
    document.addEventListener("keydown", onKey);
    lb.querySelector('[data-act="close"]').focus();
  }

  function pinchInfo() {
    var pts = Array.from(state.pointers.values());
    return {
      dist: Math.hypot(pts[0].x - pts[1].x, pts[0].y - pts[1].y) || 1,
      cx: (pts[0].x + pts[1].x) / 2, cy: (pts[0].y + pts[1].y) / 2,
      scale: state.scale
    };
  }

  /* A point relative to the centre of the stage, which is where the photo's transform origin sits. */
  function stagePoint(event) {
    var r = lb.querySelector(".lb-stage").getBoundingClientRect();
    return { x: event.clientX - r.left - r.width / 2, y: event.clientY - r.top - r.height / 2 };
  }

  function sideways() {
    return ((state.rotate % 360) + 360) % 180 !== 0;
  }

  /* Room the photo may use: the stage less a margin, and less the side arrows where they are shown. */
  function room() {
    var stage = lb.querySelector(".lb-stage"), nav = lb.querySelector(".lb-next");
    var sx = GAP;
    if (nav && nav.offsetParent) sx = stage.clientWidth - nav.offsetLeft + GAP / 2;
    return { w: Math.max(40, stage.clientWidth - 2 * sx), h: Math.max(40, stage.clientHeight - 2 * GAP), stage: stage };
  }

  /* Size the photo so it fits the stage unrotated (its layout box), and work out the extra scale (k) that makes it
     fit when turned 90° or 270°, where its width and height swap. Photos are never enlarged past their own size. */
  function fit() {
    var image = lb.querySelector(".lb-img");
    var nw = image.naturalWidth, nh = image.naturalHeight;
    if (!nw || !nh) return false;
    var r = room();
    var flat = Math.min(1, r.w / nw, r.h / nh);
    var turned = Math.min(1, r.w / nh, r.h / nw);
    state.fit = { w: nw * flat, h: nh * flat, k: sideways() ? turned / flat : 1 };
    image.style.width = state.fit.w + "px";
    image.style.height = state.fit.h + "px";
    image.classList.add("is-sized");
    return true;
  }

  function refit(animate) {
    if (!fit()) return;
    clampPan();
    apply(animate);
  }

  function imageReady() {
    lb.querySelector(".lb-spinner").setAttribute("hidden", "");
    lb.querySelector(".lb-img").classList.remove("is-loading");
  }

  function zoomTo(scale, px, py, animate) {
    var next = Math.min(MAX, Math.max(MIN, scale));
    var ratio = next / state.scale;
    state.x = px - (px - state.x) * ratio;
    state.y = py - (py - state.y) * ratio;
    state.scale = next;
    if (next === 1) { state.x = 0; state.y = 0; }
    clampPan();
    apply(animate);
  }

  function toggleZoom(p) {
    zoomTo(state.scale > 1 ? 1 : 2.5, p.x, p.y, true);
  }

  function rotateBy(deg) {
    // The angle keeps counting past 360 so the turn always animates the short way round.
    state.rotate += deg;
    refit(true);
  }

  function reset() {
    state.rotate = Math.round(state.rotate / 360) * 360;
    state.scale = 1; state.x = 0; state.y = 0;
    refit(true);
  }

  /* Pan only as far as the photo's edges (plus the margin); a side smaller than the stage stays centred. */
  function clampPan() {
    var stage = lb.querySelector(".lb-stage");
    var size = state.fit.k * state.scale;
    var w = (sideways() ? state.fit.h : state.fit.w) * size;
    var h = (sideways() ? state.fit.w : state.fit.h) * size;
    var maxX = Math.max(0, (w - stage.clientWidth) / 2 + GAP);
    var maxY = Math.max(0, (h - stage.clientHeight) / 2 + GAP);
    if (w <= stage.clientWidth - 2 * GAP) maxX = 0;
    if (h <= stage.clientHeight - 2 * GAP) maxY = 0;
    state.x = Math.min(maxX, Math.max(-maxX, state.x));
    state.y = Math.min(maxY, Math.max(-maxY, state.y));
  }

  function transform(x, y, scale) {
    return "translate(" + x + "px," + y + "px) rotate(" + state.rotate + "deg) scale(" + state.fit.k * scale + ")";
  }

  function apply(animate) {
    var image = lb.querySelector(".lb-img");
    image.classList.toggle("is-animating", !!animate);
    image.style.transform = transform(state.x, state.y, state.scale);
    lb.querySelector(".lb-zoom-level").textContent = Math.round(state.scale * 100) + "%";
    lb.querySelector('[data-act="out"]').disabled = state.scale <= MIN;
    lb.querySelector('[data-act="in"]').disabled = state.scale >= MAX;
    lb.querySelector(".lb-stage").style.cursor = state.scale > 1 ? "" : "zoom-in";
  }

  function show(index) {
    var total = state.items.length;
    state.index = (index + total) % total;
    state.scale = 1; state.x = 0; state.y = 0; state.rotate = 0; state.fit.k = 1;
    var item = state.items[state.index];
    var image = lb.querySelector(".lb-img");
    lb.querySelector(".lb-spinner").removeAttribute("hidden");
    image.classList.add("is-loading");
    image.classList.remove("is-sized");
    image.style.width = image.style.height = "";
    image.src = item.full;
    image.alt = item.alt;
    if (image.complete && image.naturalWidth) { imageReady(); fit(); }
    lb.querySelector(".lb-caption").textContent = item.alt;
    lb.querySelector(".lb-count").textContent = total > 1 ? (state.index + 1) + " / " + total : "";
    lb.querySelector('[data-act="open"]').href = item.full;
    var strip = lb.querySelector(".lb-thumbs");
    lb.querySelectorAll(".lb-thumb").forEach(function (thumb, i) {
      thumb.setAttribute("aria-current", i === state.index ? "true" : "false");
      // Scroll the strip itself; scrollIntoView could also scroll the page behind the viewer.
      if (i === state.index) strip.scrollLeft = thumb.offsetLeft - (strip.clientWidth - thumb.offsetWidth) / 2;
    });
    [state.index - 1, state.index + 1].forEach(function (i) {
      new Image().src = state.items[(i + total) % total].full;
    });
    apply(false);
  }

  function toggleFullscreen() {
    if (!document.fullscreenEnabled) return;
    if (document.fullscreenElement) document.exitFullscreen(); else lb.requestFullscreen().catch(function () {});
  }

  function onKey(event) {
    var k = event.key;
    var panStep = lb.querySelector(".lb-stage").clientWidth * 0.1;
    if (k === "Escape") { event.preventDefault(); close(); }
    else if (/^Arrow/.test(k) && state.scale > 1) {
      // Zoomed in: the arrow keys move the photo.
      event.preventDefault();
      if (k === "ArrowLeft") state.x += panStep;
      else if (k === "ArrowRight") state.x -= panStep;
      else if (k === "ArrowUp") state.y += panStep;
      else state.y -= panStep;
      clampPan();
      apply(true);
    }
    else if (k === "ArrowLeft") show(state.index - 1);
    else if (k === "ArrowRight") show(state.index + 1);
    else if (k === "+" || k === "=") zoomTo(state.scale * STEP, 0, 0, true);
    else if (k === "-" || k === "_") zoomTo(state.scale / STEP, 0, 0, true);
    else if (k === "0") reset();
    else if (k === "r" || k === "R") rotateBy(event.shiftKey ? -90 : 90);
    else if (k === "l" || k === "L") rotateBy(-90);
    else if (k === "f" || k === "F") toggleFullscreen();
    else if (k === "Tab") {
      var focusables = Array.prototype.filter.call(lb.querySelectorAll("button, a[href]"), function (el) {
        return !el.disabled && el.offsetParent !== null;
      });
      var first = focusables[0], last = focusables[focusables.length - 1];
      if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
      else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
    }
  }

  function close() {
    if (!lb) return;
    if (document.fullscreenElement) document.exitFullscreen();
    if (state.observer) state.observer.disconnect();
    if (state.onResize) window.removeEventListener("resize", state.onResize);
    document.removeEventListener("keydown", onKey);
    lb.remove();
    lb = null;
    document.documentElement.style.overflow = "";
    if (state.returnFocus) state.returnFocus.focus({ preventScroll: true });
    state = null;
  }

  document.addEventListener("click", function (event) {
    var img = event.target.closest && event.target.closest("[data-gallery] img, img[data-zoom]");
    if (img && !img.closest(".lb")) { event.preventDefault(); openViewer(img); }
  });
  document.addEventListener("keydown", function (event) {
    var img = event.target;
    if ((event.key === "Enter" || event.key === " ") && img.matches && img.matches("[data-gallery] img, img[data-zoom]") && !img.closest(".lb")) {
      event.preventDefault();
      openViewer(img);
    }
  });

  /* ---------- Floating donate button (the home page has its own) ---------- */
  var PLUS = "M12 5v14M5 12h14", CROSS = "M18 6L6 18M6 6l12 12";

  function floatingGive() {
    if (document.getElementById("floating-donation-panel") || document.querySelector(".float-give") || /donate\.html$/.test(location.pathname) || CONFIG.isDonatePage) return;
    var wrap = document.createElement("div");
    wrap.className = "float-give";
    wrap.innerHTML =
      '<div class="float-give-panel" id="float-give-panel" role="region" aria-label="Choose a donation amount" hidden>' +
      '<form class="give-card" data-give="Kingdom Revival Ministries" data-short="true" data-heading="Choose an amount" data-intro="Your gift helps share the Gospel and care for vulnerable families."></form></div>' +
      '<button type="button" class="float-give-toggle" aria-controls="float-give-panel" aria-expanded="false">' +
      '<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="' + PLUS + '"></path></svg><span>Donate</span></button>';
    document.body.appendChild(wrap);

    var panel = wrap.querySelector(".float-give-panel");
    var form = panel.querySelector("form");
    var toggle = wrap.querySelector(".float-give-toggle");
    KRM.initGive(form);
    var eyebrow = document.createElement("p");
    eyebrow.className = "float-eyebrow";
    eyebrow.textContent = "SUPPORT THE MISSION";
    form.prepend(eyebrow);

    function setOpen(open) {
      panel.hidden = !open;
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
      toggle.querySelector("path").setAttribute("d", open ? CROSS : PLUS);
      toggle.querySelector("span").textContent = open ? "Close" : "Donate";
    }
    toggle.addEventListener("click", function () { setOpen(panel.hidden); });
    document.addEventListener("click", function (event) {
      if (!panel.hidden && !wrap.contains(event.target)) setOpen(false);
    });
    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape" && !panel.hidden && !document.querySelector(".lb")) { setOpen(false); toggle.focus(); }
    });
  }

  /* ---------- Newsletter popup ---------- */
  var NL_KEY = "krm-newsletter";

  function nlStored() {
    try { return JSON.parse(localStorage.getItem(NL_KEY) || "null"); } catch (e) { return null; }
  }
  function nlStore(value) {
    try { localStorage.setItem(NL_KEY, JSON.stringify(value)); } catch (e) {}
  }
  function nlShouldShow() {
    if (/[?&]newsletter=1\b/.test(location.search)) return true;
    var saved = nlStored();
    if (!saved) return true;
    if (saved.subscribed) return false;
    return Date.now() - saved.dismissed > NEWSLETTER_SNOOZE_DAYS * 864e5;
  }

  var CHECK_PATH = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"></path></svg>';

  KRM.openNewsletter = function () {
    if (document.querySelector(".nl")) return;
    var returnFocus = document.activeElement;
    var nl = document.createElement("div");
    nl.className = "nl";
    nl.innerHTML =
      '<div class="nl-box" role="dialog" aria-modal="true" aria-labelledby="nl-title">' +
      '<button type="button" class="nl-close" aria-label="Close">' + '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12"></path></svg></button>' +
      '<div class="nl-media" style="background-image: url(\'' + NEWSLETTER_IMAGE.replace(/'/g, "%27") + '\')"><p>Every story of hope starts with someone who cares.<span>Kingdom Revival Ministries</span></p></div>' +
      '<div class="nl-body">' +
      '<p class="nl-eyebrow"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="M22 6l-10 7L2 6"></path></svg>Our newsletter</p>' +
      '<h2 id="nl-title">Get stories of hope in your inbox</h2>' +
      '<p class="nl-text">Hear what God is doing among brick kiln families, orphans and villages in Pakistan — and how you can pray and help.</p>' +
      '<ul class="nl-perks"><li>' + CHECK_PATH + 'Stories and photos from the field</li><li>' + CHECK_PATH + 'Prayer requests and answered prayers</li><li>' + CHECK_PATH + 'Ways to give where the need is greatest</li></ul>' +
      '<form class="nl-form" novalidate>' +
      '<p aria-hidden="true" style="position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden"><label>Leave this field empty<input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>' +
      '<label>First name (optional)<input name="name" autocomplete="given-name" placeholder="Your name"></label>' +
      '<label>Email address<input name="email" type="email" autocomplete="email" placeholder="you@example.com" required></label>' +
      '<p class="nl-error" role="alert" hidden>Please enter a valid email address.</p>' +
      '<button class="nl-submit" type="submit">Subscribe</button>' +
      '<p class="nl-privacy">We respect your inbox. No spam, and you can unsubscribe any time.</p>' +
      '<button type="button" class="nl-skip">No thanks, maybe later</button>' +
      "</form></div></div>";
    document.body.appendChild(nl);
    document.documentElement.style.overflow = "hidden";

    var form = nl.querySelector("form");
    var openedAt = Date.now();
    var email = form.elements.email;
    var error = nl.querySelector(".nl-error");

    function close(remember) {
      if (remember !== false) nlStore({ dismissed: Date.now() });
      nl.remove();
      document.documentElement.style.overflow = "";
      if (returnFocus && returnFocus.focus) returnFocus.focus({ preventScroll: true });
      document.removeEventListener("keydown", onKey, true);
    }
    function onKey(event) {
      if (event.key === "Escape") { event.stopPropagation(); close(); }
      if (event.key === "Tab") {
        var items = Array.prototype.filter.call(nl.querySelectorAll("button, input"), function (el) { return el.offsetParent !== null; });
        var first = items[0], last = items[items.length - 1];
        if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
        else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
      }
    }
    document.addEventListener("keydown", onKey, true);
    nl.addEventListener("click", function (event) {
      if (event.target === nl || event.target.closest(".nl-close, .nl-skip")) close();
    });

    function done() {
      nlStore({ subscribed: true, at: Date.now() });
      nl.querySelector(".nl-body").innerHTML =
        '<div class="nl-done"><span class="nl-check"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path d="M20 6L9 17l-5-5"></path></svg></span>' +
        '<h2 id="nl-title">Thank you for subscribing!</h2>' +
        "<p>" + (NEWSLETTER_ENDPOINT ? "You will start receiving stories of hope from the field soon." : "Your email app should now be open — just press send to finish subscribing.") + "</p>" +
        '<button type="button" class="nl-submit nl-finish" style="padding: 0 28px; margin-top: 8px">Continue browsing</button></div>';
      nl.querySelector(".nl-finish").addEventListener("click", function () { close(false); });
      nl.querySelector(".nl-finish").focus();
    }

    form.addEventListener("submit", function (event) {
      event.preventDefault();
      var value = email.value.trim();
      var valid = /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(value);
      error.hidden = valid;
      email.setAttribute("aria-invalid", valid ? "false" : "true");
      if (!valid) { email.focus(); return; }
      var name = form.elements.name.value.trim();
      if (!NEWSLETTER_ENDPOINT) {
        window.location.href = "mailto:" + NEWSLETTER_EMAIL + "?subject=" + encodeURIComponent("Newsletter subscription") +
          "&body=" + encodeURIComponent("Please add me to the Kingdom Revival Ministries newsletter.\n\nName: " + (name || "-") + "\nEmail: " + value);
        done();
        return;
      }
      var button = form.querySelector(".nl-submit");
      button.disabled = true;
      button.textContent = "Subscribing…";
      var data = new FormData();
      data.append("action", "krm_form");
      data.append("form", "newsletter");
      data.append("nonce", CONFIG.nonce || "");
      data.append("token", CONFIG.formToken || "");
      data.append("website", form.elements.website.value);
      data.append("elapsed", String(Date.now() - openedAt));
      data.append("email", value);
      data.append("name", name);
      fetch(NEWSLETTER_ENDPOINT, { method: "POST", body: data, credentials: "same-origin", headers: { Accept: "application/json" } })
        .then(function (response) { return response.json().then(function (json) { if (!response.ok || !json.success) throw new Error(response.status); done(); }); })
        .catch(function () {
          button.disabled = false;
          button.textContent = "Subscribe";
          error.textContent = "Sorry, something went wrong. Please try again in a moment.";
          error.hidden = false;
        });
    });
    setTimeout(function () { email.focus(); }, 50);
  };

  function scheduleNewsletter() {
    if (!nlShouldShow() || CONFIG.noNewsletter) return;
    setTimeout(function tryOpen() {
      // wait while the visitor is busy with the photo viewer or a donation panel
      var busy = document.querySelector(".lb") || document.querySelector('.float-give-toggle[aria-expanded="true"]') ||
        (document.getElementById("floating-donation-panel") && document.getElementById("floating-donation-panel").style.display === "block");
      if (busy) { setTimeout(tryOpen, 3000); return; }
      KRM.openNewsletter();
    }, NEWSLETTER_DELAY);
  }

  /* ---------- Form posts to WordPress (contact, partner) ---------- */
  KRM.formStarted = Date.now();
  KRM.sendForm = function (form, name, fields) {
    var data = new FormData();
    data.append("action", "krm_form");
    data.append("form", name);
    data.append("nonce", CONFIG.nonce || "");
    data.append("token", CONFIG.formToken || "");
    data.append("website", form.elements.website ? form.elements.website.value : "");
    data.append("elapsed", String(Date.now() - KRM.formStarted));
    Object.keys(fields).forEach(function (key) { data.append(key, fields[key]); });
    return fetch(CONFIG.ajaxUrl, { method: "POST", body: data, credentials: "same-origin", headers: { Accept: "application/json" } })
      .then(function (response) {
        return response.json().catch(function () { return {}; }).then(function (json) {
          if (!response.ok || !json.success) {
            var error = new Error((json.data && json.data.message) || "Request failed");
            error.fields = json.data && json.data.fields;
            throw error;
          }
          return json;
        });
      });
  };

  /* Jump to #section links once the page content exists (the home page builds its content with script) */
  function scrollToHash() {
    if (!location.hash || location.hash.length < 2) return;
    var target = document.getElementById(decodeURIComponent(location.hash.slice(1)));
    if (target) target.scrollIntoView();
  }

  function ready() {
    KRM.refreshZoomables();
    document.querySelectorAll("form[data-give]").forEach(KRM.initGive);
    floatingGive();
    scheduleNewsletter();
    setTimeout(scrollToHash, 50);
    window.addEventListener("load", function () { setTimeout(scrollToHash, 50); });
  }
  if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", ready);
  else ready();
})();
