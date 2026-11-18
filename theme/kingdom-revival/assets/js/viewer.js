/* Kingdom Revival Ministries — photo viewer (Phase 14): opens gallery photos and [data-zoom] images full screen
   with zoom, rotate and browsing. Loaded only on pages that have such photos (inc/enqueue.php, Phase 15 step 6);
   it was part of site.js before. */
(function () {
  var KRM = (window.KRM = window.KRM || {});

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
    // Photos a filter has hidden (Gallery page) are left out of browsing.
    return Array.prototype.filter.call(images, function (el) { return !el.closest(".lb") && !el.closest("[hidden]"); });
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

  if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", function () { KRM.refreshZoomables(); });
  else KRM.refreshZoomables();
})();
