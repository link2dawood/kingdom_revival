/* Kingdom Revival Ministries — Photo & Video Gallery page: tiles fade in once their photo has loaded (the skeleton
   shows until then), the grid's items fade in again after each filter choice (the filter itself is in
   components.js), and the chosen chip is scrolled into view in the phone chip row, whose "‹ ›" buttons show when
   there are more chips that way and scroll the row. */
(function () {
  var grid = document.querySelector(".krm-gal");
  if (!grid) return;

  function loaded(tile) { tile.classList.add("is-loaded"); }
  Array.prototype.forEach.call(grid.querySelectorAll(".krm-gal__tile"), function (tile) {
    var img = tile.querySelector("img");
    if (!img || (img.complete && img.naturalWidth)) { loaded(tile); return; }
    img.addEventListener("load", function () { loaded(tile); }, { once: true });
    img.addEventListener("error", function () { loaded(tile); }, { once: true });
  });

  var chips = document.querySelector(".krm-chips");
  if (!chips) return;
  chips.addEventListener("click", function (event) {
    var chip = event.target.closest("button[data-group]");
    if (!chip) return;
    // Restart the fade on the items the filter leaves.
    grid.classList.remove("is-filtered");
    void grid.offsetWidth;
    grid.classList.add("is-filtered");
    // Keep the chosen chip visible in the scrolling row (scroll the row only, never the page).
    if (chips.scrollWidth > chips.clientWidth) {
      chips.scrollTo({ left: chip.offsetLeft - (chips.clientWidth - chip.offsetWidth) / 2, behavior: "smooth" });
    }
  });
})();

/* Chip row "‹ ›" buttons: shown only while the row can scroll that way (phones / tablets); each press moves it by most
   of its width. The row is printed hidden and shown by components.js, so its size is watched. */
(function () {
  var wrap = document.querySelector("[data-krm-scroller]");
  var row = wrap && wrap.querySelector(".krm-chips");
  if (!row) return;
  var prev = wrap.querySelector(".krm-chips-nav--prev");
  var next = wrap.querySelector(".krm-chips-nav--next");
  var still = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  function update() {
    var max = row.scrollWidth - row.clientWidth;
    var canPrev = !row.hidden && max > 4 && row.scrollLeft > 24; // The row starts 16px in (its padding).
    var canNext = !row.hidden && max > 4 && row.scrollLeft < max - 4;
    prev.hidden = !canPrev;
    next.hidden = !canNext;
    wrap.classList.toggle("can-prev", canPrev);
    wrap.classList.toggle("can-next", canNext);
  }
  [prev, next].forEach(function (b) {
    b.addEventListener("click", function () {
      row.scrollBy({ left: Number(b.getAttribute("data-dir")) * row.clientWidth * 0.7, behavior: still ? "auto" : "smooth" });
    });
  });
  row.addEventListener("scroll", update, { passive: true });
  window.addEventListener("resize", update);
  window.addEventListener("load", update);
  if ("ResizeObserver" in window) new ResizeObserver(update).observe(row);
  update();
})();
