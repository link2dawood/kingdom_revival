/* Kingdom Revival Ministries — shared page components (every page).
   - Filters: [data-krm-filter="name"] (buttons with data-group, or a <select>) show the items of
     [data-krm-filter-list="name"] whose data-group contains the choice; a [data-krm-filter-empty] note right after a
     list shows when nothing in it matches. The bar is printed hidden and shown here, so
     without JavaScript everything is listed. data-param keeps the choice in the address (?group=…).
   - Show more: a list with data-krm-more="18" shows its first 18 items (of those its filter leaves) and a "Show 18
     more" button after it that reveals the next 18 each time; changing the filter starts again at 18. The button text
     is data-more-text ({n} = how many the next press shows). Without JavaScript every item is listed.
   - Tabs: [data-krm-tabs] with role="tab" buttons and role="tabpanel" panels; arrow keys move between tabs.
   - Videos: button[data-krm-video] loads the YouTube player only when pressed (no YouTube request before).
   - Forms: form[data-krm-form="prayer|signup|testimony"] post to WordPress with KRM.sendForm (site.js), as the contact
     form; a file input (the testimony photo) is checked for type and size and sent as the file itself.
   - Before/after slider: [data-krm-compare] with an input[data-krm-compare-range] (home and Slavery & Freedom).
   - Counters: [data-krm-count] numbers ("1,200+") count up once when they come into view (not with reduced motion).
   - Give card: [data-krm-give-card] amount buttons (data-give-amount) set its Donate link (ministry pages). */
(function () {
  function all(sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); }

  /* ---------- Show more ---------- */
  all("[data-krm-more]").forEach(function (list) {
    var step = parseInt(list.getAttribute("data-krm-more"), 10) || 18;
    var limit = step;
    var wrap = document.createElement("div");
    var button = document.createElement("button");
    wrap.className = "krm-more";
    button.type = "button";
    button.className = "button button-secondary krm-more-button";
    wrap.appendChild(button);
    // After the list's "nothing here" note, so the filter still finds the note right after the list.
    var after = list.nextElementSibling && list.nextElementSibling.hasAttribute("data-krm-filter-empty") ? list.nextElementSibling : list;
    after.parentNode.insertBefore(wrap, after.nextSibling);
    function render(focusAt) {
      var on = Array.prototype.filter.call(list.children, function (el) { return !el.hidden; });
      Array.prototype.forEach.call(list.children, function (el) { el.classList.remove("krm-more-hidden"); });
      on.forEach(function (el, i) { if (i >= limit) el.classList.add("krm-more-hidden"); });
      var left = Math.max(0, on.length - limit);
      wrap.hidden = left === 0;
      button.textContent = (list.getAttribute("data-more-text") || "Show {n} more").replace("{n}", Math.min(step, left));
      if (focusAt !== undefined && on[focusAt]) {
        // Keyboard and screen-reader users continue at the first newly shown item.
        var first = on[focusAt];
        var target = first.querySelector("a[href], button, [tabindex]") || first;
        if (target === first) first.tabIndex = -1;
        target.focus();
      }
      if (window.krmReveal) window.krmReveal.flush();
    }
    button.addEventListener("click", function () { var from = limit; limit += step; render(from); });
    list.krmMore = { reset: function () { limit = step; render(); } };
    render();
    list.setAttribute("data-krm-more-ready", "");
  });

  /* ---------- Filters ---------- */
  all("[data-krm-filter]").forEach(function (bar) {
    var name = bar.getAttribute("data-krm-filter");
    var param = bar.getAttribute("data-param");
    var select = bar.tagName === "SELECT" ? bar : bar.querySelector("select");
    var buttons = all("button[data-group]", bar);
    var lists = all('[data-krm-filter-list="' + name + '"]');
    var status = document.querySelector('[data-krm-filter-status="' + name + '"]');
    var values = select ? all("option", select).map(function (o) { return o.value; }) : buttons.map(function (b) { return b.getAttribute("data-group"); });

    function valid(v) { return values.indexOf(v) !== -1 ? v : ""; }
    function apply(group, announce) {
      var shown = 0;
      if (select) select.value = group;
      buttons.forEach(function (b) { b.setAttribute("aria-pressed", b.getAttribute("data-group") === group ? "true" : "false"); });
      lists.forEach(function (list) {
        var listShown = 0;
        Array.prototype.forEach.call(list.children, function (item) {
          var groups = (item.getAttribute("data-group") || "").split(" ");
          var on = !group || groups.indexOf(group) !== -1;
          item.hidden = !on;
          if (on) { shown++; listShown++; }
        });
        // The "nothing here" note right after a list.
        var note = list.nextElementSibling;
        if (note && note.hasAttribute("data-krm-filter-empty")) note.hidden = listShown > 0;
        if (list.krmMore) list.krmMore.reset();
      });
      if (announce && status) status.textContent = (status.getAttribute("data-text") || "").replace("{n}", shown);
    }
    function fromUrl() { return param ? valid(new URLSearchParams(location.search).get(param) || "") : ""; }
    function choose(group) {
      apply(group, true);
      if (!param) return;
      var url = new URL(location.href);
      if (group) url.searchParams.set(param, group); else url.searchParams.delete(param);
      history.pushState({ krmFilter: name }, "", url);
    }
    buttons.forEach(function (b) { b.addEventListener("click", function () { choose(b.getAttribute("data-group")); }); });
    if (select) select.addEventListener("change", function () { choose(select.value); });
    if (param) window.addEventListener("popstate", function () { apply(fromUrl(), true); });
    bar.hidden = false;
    apply(fromUrl(), false);
  });

  /* ---------- Tabs ---------- */
  all("[data-krm-tabs]").forEach(function (list) {
    var tabs = all('[role="tab"]', list);
    var param = list.getAttribute("data-param");
    function show(tab, focus, push) {
      tabs.forEach(function (t) {
        var on = t === tab;
        t.setAttribute("aria-selected", on ? "true" : "false");
        t.tabIndex = on ? 0 : -1;
        var panel = document.getElementById(t.getAttribute("aria-controls"));
        if (panel) panel.hidden = !on;
      });
      if (focus) tab.focus();
      if (push && param) {
        var url = new URL(location.href);
        url.searchParams.set(param, tab.getAttribute("data-tab"));
        history.replaceState(null, "", url);
      }
    }
    tabs.forEach(function (tab, i) {
      tab.addEventListener("click", function () { show(tab, false, true); });
      tab.addEventListener("keydown", function (e) {
        var next = e.key === "ArrowRight" ? i + 1 : e.key === "ArrowLeft" ? i - 1 : e.key === "Home" ? 0 : e.key === "End" ? tabs.length - 1 : null;
        if (next === null) return;
        e.preventDefault();
        show(tabs[(next + tabs.length) % tabs.length], true, true);
      });
    });
    var wanted = param && new URLSearchParams(location.search).get(param);
    var start = tabs.filter(function (t) { return t.getAttribute("data-tab") === wanted; })[0] || tabs.filter(function (t) { return t.getAttribute("aria-selected") === "true"; })[0] || tabs[0];
    if (start) show(start, false, false);
  });

  /* ---------- Before/after slider ---------- */
  all("[data-krm-compare]").forEach(function (compare) {
    var range = compare.parentNode.querySelector("[data-krm-compare-range]");
    var before = compare.querySelector("[data-krm-compare-before]");
    if (!range || !before) return;
    var lines = all("[data-krm-compare-line]", compare);
    range.addEventListener("input", function () {
      var split = Number(range.value);
      before.style.clipPath = "inset(0 " + (100 - split) + "% 0 0)";
      lines.forEach(function (line) { line.style.left = split + "%"; });
    });
  });

  /* ---------- Counters ---------- */
  var counters = all("[data-krm-count]").map(function (el) {
    var m = /^(\D*)(\d[\d,]*)(.*)$/.exec(el.textContent.trim());
    return m ? { el: el, pre: m[1], to: Number(m[2].replace(/,/g, "")), comma: m[2].indexOf(",") !== -1, post: m[3] } : null;
  }).filter(Boolean);
  var still = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  if (counters.length && !still && "IntersectionObserver" in window) {
    var fmt = function (c, n) { return c.pre + (c.comma ? n.toLocaleString("en-US") : String(n)) + c.post; };
    var run = function (c) {
      var start = null;
      var step = function (t) {
        if (start === null) start = t;
        var p = Math.min(1, (t - start) / 1200);
        c.el.textContent = fmt(c, Math.round(c.to * (1 - Math.pow(1 - p, 3))));
        if (p < 1) requestAnimationFrame(step);
      };
      requestAnimationFrame(step);
    };
    var seen = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (!entry.isIntersecting) return;
        seen.unobserve(entry.target);
        counters.forEach(function (c) { if (c.el === entry.target) run(c); });
      });
    }, { threshold: 0.4 });
    // The final value stays in the markup (and for screen readers); the count-up starts from 0 only once in view.
    // Numbers already on screen when the page opens keep their value (nothing above the fold animates on load); the
    // rest start counting when they are revealed by scrolling.
    counters.forEach(function (c) {
      var r = c.el.getBoundingClientRect();
      if (r.top < window.innerHeight && r.bottom > 0) return;
      seen.observe(c.el);
    });
  }

  /* ---------- Give card (ministry pages) ---------- */
  all("[data-krm-give-card]").forEach(function (card) {
    var link = card.querySelector("[data-give-link]");
    var base = card.getAttribute("data-donate") || "";
    var forName = card.getAttribute("data-for") || "";
    var ministry = card.getAttribute("data-ministry") || "";
    card.addEventListener("click", function (event) {
      var button = event.target.closest && event.target.closest("button[data-give-amount]");
      if (!button || !link) return;
      var amount = button.getAttribute("data-give-amount");
      all("button[data-give-amount]", card).forEach(function (b) { b.setAttribute("aria-pressed", b === button ? "true" : "false"); });
      link.href = base + "?amount=" + (amount === "Other" ? "" : encodeURIComponent(amount.replace("$", ""))) +
        "&frequency=One-time" + (ministry ? "&ministry=" + encodeURIComponent(ministry) : forName ? "&for=" + encodeURIComponent(forName) : "") + "#give";
      link.querySelector("span").textContent = amount === "Other" ? "Now" : amount;
    });
  });

  /* ---------- Videos ---------- */
  document.addEventListener("click", function (e) {
    var button = e.target.closest && e.target.closest("button[data-krm-video]");
    if (!button) return;
    var frame = document.createElement("iframe");
    frame.src = "https://www.youtube-nocookie.com/embed/" + encodeURIComponent(button.getAttribute("data-krm-video")) + "?autoplay=1&rel=0";
    frame.title = button.getAttribute("data-title") || "Video";
    frame.allow = "accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture";
    frame.allowFullscreen = true;
    frame.className = "video-frame";
    button.replaceWith(frame);
  });

  /* ---------- Forms ---------- */
  var params = new URLSearchParams(location.search);
  all("form[data-krm-form]").forEach(function (form) {
    var name = form.getAttribute("data-krm-form");
    var sent = form.querySelector("[data-krm-form-status]");
    var failed = form.querySelector("[data-krm-form-error]");
    var button = form.querySelector('button[type="submit"]');
    var label = button ? button.textContent : "";
    var fields = all("input[name], textarea[name], select[name]", form).filter(function (el) { return el.name !== "website"; });
    fields.forEach(function (el) {
      el.setAttribute("data-border", el.style.borderColor || "");
      var prefill = el.getAttribute("data-prefill");
      if (prefill && params.get(prefill)) el.value = params.get(prefill).slice(0, 200);
    });
    var EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    /* Problem with a field's value ("" when fine): required, email, or "email or WhatsApp number" (data-validate). */
    function problem(el, value) {
      if (el.type === "file") {
        /* Optional photo (Testimonies): an image of an allowed type, no larger than data-max-size bytes. */
        var file = el.files && el.files[0];
        if (!file) return el.required ? "Please choose a file." : "";
        var max = parseInt(el.getAttribute("data-max-size"), 10) || 0;
        if (!/^image\/(jpeg|png|webp)$/.test(file.type)) return "Please choose a JPG, PNG or WebP photo.";
        if (max && file.size > max) return "This photo is too large. Please choose one under " + Math.round(max / 1048576) + " MB.";
        return "";
      }
      if (el.required && (el.type === "checkbox" ? !el.checked : !value)) return el.getAttribute("data-error-required") || "Please fill in this field.";
      if (!value || el.type === "checkbox") return "";
      if (el.type === "email" && !EMAIL_RE.test(value)) return "Please enter a valid email address.";
      if (el.getAttribute("data-validate") === "email-or-phone" && !EMAIL_RE.test(value) && !/^\+?[\d\s()-]{7,20}$/.test(value)) {
        return "Please enter a valid email address or WhatsApp number.";
      }
      return "";
    }
    /* Red border plus a text message under the field (not colour alone), linked with aria-describedby. */
    function mark(el, bad, message) {
      el.setAttribute("aria-invalid", bad ? "true" : "false");
      if (el.getAttribute("data-border") !== null && el.style.borderColor !== undefined) el.style.borderColor = bad ? "var(--color-secondary)" : el.getAttribute("data-border");
      var holder = el.closest("label");
      if (!holder) return;
      var note = holder.querySelector(".krm-field-error");
      if (!note && bad) {
        note = document.createElement("span");
        note.className = "krm-field-error";
        note.id = "krm-err-" + name + "-" + el.name;
        holder.appendChild(note);
        el.setAttribute("aria-describedby", ((el.getAttribute("aria-describedby") || "") + " " + note.id).trim());
      }
      if (note) { note.textContent = bad ? (message || "Please check this field.") : ""; note.hidden = !bad; }
    }
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      if (!window.KRM || !KRM.sendForm) return;
      var data = {};
      var first = null;
      fields.forEach(function (el) {
        var value = el.type === "checkbox" ? (el.checked ? (el.value || "Yes") : "No")
          : el.type === "file" ? (el.files && el.files[0] ? el.files[0] : "") : el.value.trim();
        var message = problem(el, el.type === "checkbox" || el.type === "file" ? "" : value);
        var bad = !!message;
        mark(el, bad, message);
        if (bad && !first) first = el;
        data[el.name] = value;
      });
      if (first) { first.focus(); return; }
      if (sent) sent.hidden = true;
      if (failed) failed.hidden = true;
      if (button) { button.disabled = true; button.textContent = "Sending…"; }
      KRM.sendForm(form, name, data).then(function () {
        form.reset();
        if (sent) sent.hidden = false;
      }).catch(function (error) {
        if (error.fields) Object.keys(error.fields).forEach(function (key) { if (form.elements[key]) mark(form.elements[key], true); });
        if (failed) failed.hidden = false;
      }).then(function () {
        if (button) { button.disabled = false; button.textContent = label; }
      });
    });
  });
})();
