/**
 * Home 9. Brick Kiln Family Freedom (theme 1.25.0, template-parts/home/kiln-freedom.php):
 *   - Before/after slider: drag anywhere on the photo with mouse, pen or touch (vertical swipes still scroll the
 *     page: touch-action pan-y), or focus the round handle and use the arrow keys / Home / End. The split is the
 *     CSS variable --kf-pos on the frame.
 *   - The first time the slider comes into view the handle moves gently to the left and back once, as a hint
 *     (not with prefers-reduced-motion, and never after the visitor has touched it).
 *   - With two or more families, the dots under the slider switch between them.
 * Without this script the first family is shown split in the middle.
 */
(function () {
  "use strict";
  var roots = document.querySelectorAll("[data-krm-kf]");
  if (!roots.length) return;
  var reduce = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  Array.prototype.forEach.call(roots, function (root) {
    var frame = root.querySelector("[data-krm-kf-frame]");
    var handle = root.querySelector("[data-krm-kf-handle]");
    if (!frame || !handle) return;
    var pos = 50;
    var touched = false;
    var hintFrame = 0;

    function set(p) {
      pos = Math.max(0, Math.min(100, p));
      var v = Math.round(pos * 10) / 10;
      frame.style.setProperty("--kf-pos", v + "%");
      handle.setAttribute("aria-valuenow", String(Math.round(pos)));
      handle.setAttribute("aria-valuetext", Math.round(pos) + "% before photo");
    }
    function stopHint() {
      touched = true;
      if (hintFrame) cancelAnimationFrame(hintFrame);
      hintFrame = 0;
    }
    function fromEvent(e) {
      var r = frame.getBoundingClientRect();
      if (r.width) set(((e.clientX - r.left) / r.width) * 100);
    }

    // Pointer dragging (mouse, pen and touch). Pointer capture keeps the drag going outside the photo.
    var dragging = null;
    frame.addEventListener("pointerdown", function (e) {
      if (e.pointerType === "mouse" && e.button !== 0) return;
      if (e.target.closest && e.target.closest("button, a")) return;
      stopHint();
      dragging = e.pointerId;
      frame.classList.add("is-dragging");
      try { frame.setPointerCapture(e.pointerId); } catch (err) { /* not supported */ }
      fromEvent(e);
      if (e.pointerType === "mouse") e.preventDefault(); // no text selection or image drag
    });
    frame.addEventListener("pointermove", function (e) {
      if (dragging === e.pointerId) fromEvent(e);
    });
    function end(e) {
      if (dragging !== e.pointerId) return;
      dragging = null;
      frame.classList.remove("is-dragging");
    }
    frame.addEventListener("pointerup", end);
    frame.addEventListener("pointercancel", end); // e.g. the browser took over a vertical swipe to scroll
    frame.addEventListener("lostpointercapture", end);

    // Keyboard on the handle.
    handle.addEventListener("keydown", function (e) {
      var step = e.shiftKey ? 20 : 5;
      var map = { ArrowLeft: pos - step, ArrowDown: pos - step, ArrowRight: pos + step, ArrowUp: pos + step, Home: 0, End: 100, PageDown: pos - 20, PageUp: pos + 20 };
      if (!(e.key in map)) return;
      e.preventDefault();
      stopHint();
      set(map[e.key]);
    });

    // Family dots.
    var dots = root.querySelector("[data-krm-kf-dots]");
    if (dots) {
      var pairs = root.querySelectorAll("[data-krm-kf-pair]");
      var caps = root.querySelectorAll("[data-krm-kf-cap]");
      var buttons = dots.querySelectorAll("[data-krm-kf-dot]");
      dots.hidden = false;
      Array.prototype.forEach.call(buttons, function (b) {
        b.addEventListener("click", function () {
          var n = Number(b.getAttribute("data-krm-kf-dot"));
          Array.prototype.forEach.call(pairs, function (p, i) { p.hidden = i !== n; });
          Array.prototype.forEach.call(caps, function (c, i) { c.hidden = i !== n; });
          Array.prototype.forEach.call(buttons, function (d) { d.setAttribute("aria-pressed", d === b ? "true" : "false"); });
          stopHint();
          set(50);
        });
      });
    }

    // Hint: 50% → 22% → 50% once, about 1.8 s, when at least half of the slider is on screen.
    if (reduce || !("IntersectionObserver" in window)) return;
    var io = new IntersectionObserver(function (entries) {
      if (!entries.some(function (en) { return en.isIntersecting; })) return;
      io.disconnect();
      if (touched) return;
      window.setTimeout(function () {
        if (touched) return;
        var start = null;
        var ease = function (t) { return t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2; };
        var tick = function (now) {
          if (touched) return;
          if (start === null) start = now;
          var t = Math.min(1, (now - start) / 1800);
          var swing = t < 0.5 ? ease(t * 2) : ease((1 - t) * 2); // out and back
          set(50 - 28 * swing);
          hintFrame = t < 1 ? requestAnimationFrame(tick) : 0;
        };
        hintFrame = requestAnimationFrame(tick);
      }, 450); // after the slider's own fade-in
    }, { threshold: 0.5 });
    io.observe(frame);
  });
})();
