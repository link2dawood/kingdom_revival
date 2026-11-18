/* Kingdom Revival Ministries — Page content / Site text fields: image pickers and repeatable lists */
(function ($) {
  var root = $(document);

  /* Image fields: pick from the Media Library or go back to the design's image. The alt text field next to an
     image (same name + "_alt") is filled with the chosen image's alt text. */
  function altInput(input) {
    var name = input.attr("name").replace(/\]$/, "_alt]");
    return input.closest(".krm-item, .krm-section-body").find('[name="' + name.replace(/(["\\])/g, "\\$1") + '"]');
  }

  root.on("click", ".krm-image-pick", function () {
    var box = $(this).closest("[data-krm-content-image]"), input = box.find('input[type="hidden"]');
    var frame = wp.media({ title: "Choose image", multiple: false, library: { type: "image" } });
    frame.on("select", function () {
      var a = frame.state().get("selection").first().toJSON();
      input.val(a.id);
      box.find("img").attr("src", (a.sizes && a.sizes.medium ? a.sizes.medium : a).url).prop("hidden", false);
      altInput(input).val(a.alt || "");
    });
    frame.open();
  });

  root.on("click", ".krm-image-default", function () {
    var box = $(this).closest("[data-krm-content-image]");
    box.find('input[type="hidden"]').val(box.attr("data-default"));
    box.find("img").attr("src", box.attr("data-default-src")).prop("hidden", !box.attr("data-default-src"));
  });

  /* Lists: add, remove and reorder items. */
  root.on("click", ".krm-list-add", function () {
    var field = $(this).closest(".krm-field-list"), tpl = field.children("template")[0];
    var html = tpl.innerHTML.replace(/__i__/g, String(Date.now()));
    field.children("[data-krm-list]").append(html);
  });
  root.on("click", ".krm-item-remove", function () {
    $(this).closest(".krm-item").remove();
  });
  root.on("click", ".krm-item-up", function () {
    var item = $(this).closest(".krm-item");
    item.prev(".krm-item").before(item);
  });
  root.on("click", ".krm-item-down", function () {
    var item = $(this).closest(".krm-item");
    item.next(".krm-item").after(item);
  });
})(jQuery);
