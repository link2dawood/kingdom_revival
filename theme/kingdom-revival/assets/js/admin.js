/* Kingdom Revival Ministries — ministry editor: photo gallery and explorer image pickers */
(function ($) {
  $("[data-krm-gallery]").each(function () {
    var box = $(this), list = box.find(".krm-gallery-list"), input = box.find('input[type="hidden"]'), frame;

    function sync() {
      input.val(list.children().map(function () { return $(this).attr("data-ref"); }).get().join("\n"));
    }
    list.sortable({ update: sync });
    list.on("click", ".krm-remove", function () { $(this).closest("li").remove(); sync(); });

    box.find(".krm-add").on("click", function () {
      if (!frame) {
        frame = wp.media({ title: "Add ministry photos", multiple: "add", library: { type: "image" }, button: { text: "Add photos" } });
        frame.on("select", function () {
          frame.state().get("selection").each(function (att) {
            var a = att.toJSON(), thumb = (a.sizes && a.sizes.thumbnail ? a.sizes.thumbnail : a).url;
            $("<li>").attr("data-ref", a.id)
              .append($("<img alt=''>").attr("src", thumb))
              .append($("<button type='button' class='krm-remove' aria-label='Remove photo'>×</button>"))
              .appendTo(list);
          });
          sync();
        });
      }
      frame.open();
    });
  });

  $("[data-krm-image]").each(function () {
    var box = $(this), img = box.find("img"), input = box.find('input[type="hidden"]'), frame;
    box.find(".krm-pick").on("click", function () {
      if (!frame) {
        frame = wp.media({ title: "Choose explorer image", multiple: false, library: { type: "image" } });
        frame.on("select", function () {
          var a = frame.state().get("selection").first().toJSON();
          input.val(a.id);
          img.attr("src", (a.sizes && a.sizes.medium ? a.sizes.medium : a).url).show();
        });
      }
      frame.open();
    });
    box.find(".krm-clear").on("click", function () { input.val(""); img.hide(); });
  });
})(jQuery);
