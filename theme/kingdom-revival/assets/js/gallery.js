/* Kingdom Revival Ministries — Photo & Video Gallery page: tiles fade in once their photo has loaded (the skeleton
   shows until then), the grid's items fade in again after each filter choice (the filter itself is in
   components.js), and the chosen chip is scrolled into view in the phone chip row. */
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
