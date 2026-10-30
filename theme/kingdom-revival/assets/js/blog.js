/* Kingdom Revival Ministries — News & Blogs: category chips and live search over the server-rendered cards.
   Same rules as the design: with "All" and no search, the featured story is left out of the grid. */
(function () {
  var grid = document.getElementById("post-grid");
  var filters = document.getElementById("filters");
  var search = document.getElementById("search");
  if (!grid || !filters) return;
  var cards = Array.prototype.slice.call(grid.querySelectorAll(".post-card"));
  var empty = document.getElementById("empty");
  var count = document.getElementById("result-count");
  var filter = "All", query = "";

  function render() {
    var q = query.trim().toLowerCase();
    var shown = 0;
    cards.forEach(function (card) {
      var visible = filter === "All" && !q
        ? !card.dataset.featured
        : (filter === "All" || card.dataset.category === filter) && (!q || card.dataset.search.indexOf(q) !== -1);
      card.hidden = !visible;
      if (visible) shown++;
    });
    empty.hidden = shown > 0;
    count.textContent = shown + (shown === 1 ? " story" : " stories");
  }

  filters.addEventListener("click", function (event) {
    var chip = event.target.closest(".chip");
    if (!chip) return;
    filter = chip.dataset.filter;
    filters.querySelectorAll(".chip").forEach(function (c) { c.setAttribute("aria-pressed", c === chip ? "true" : "false"); });
    render();
  });
  if (search) search.addEventListener("input", function (event) { query = event.target.value; render(); });
})();
