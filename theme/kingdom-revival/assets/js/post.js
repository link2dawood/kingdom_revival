/* Kingdom Revival Ministries — story page: copy link (from the design). The reading progress bar is now site-wide (effects.js). */
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

})();
