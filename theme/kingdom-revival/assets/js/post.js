/* Kingdom Revival Ministries — story page: copy link and reading progress (from the design) */
(function () {
  var copy = document.getElementById("share-copy");
  if (copy) {
    copy.addEventListener("click", function () {
      var done = function () {
        var note = document.getElementById("copied");
        note.hidden = false;
        setTimeout(function () { note.hidden = true; }, 2000);
      };
      if (navigator.clipboard) navigator.clipboard.writeText(copy.dataset.url || location.href).then(done, function () {});
    });
  }

  var bar = document.querySelector(".progress span");
  var article = document.getElementById("article");
  if (!bar || !article) return;
  function progress() {
    var rect = article.getBoundingClientRect();
    var total = rect.height - window.innerHeight * 0.6;
    var done = Math.min(1, Math.max(0, -rect.top / (total || 1)));
    bar.style.width = (done * 100) + "%";
  }
  window.addEventListener("scroll", progress, { passive: true });
  progress();
})();
