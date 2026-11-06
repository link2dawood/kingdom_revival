/* Kingdom Revival Ministries — Phase 24 forms: Partner With Us sign-ups, Prayer Request (and the home prayer form)
   and Donate → donor details. Markup: form[data-krm-send="<form_type>"] (inc/form-handler.php prints the hidden
   fields and the message area; the message area may sit outside the form in [data-krm-for="<form id>"]).

   - Checks the fields in the browser (the server checks them again): required, email or WhatsApp number, amount.
     Errors appear under the field (aria-invalid + aria-describedby) and focus moves to the first one.
   - Sends with fetch: first a fresh nonce + start token (admin-ajax.php?action=krm_form_nonce, never cached, so cached
     pages keep working), then the form (action=krm_form_submit). Waits until 3 s after the start token (the server
     treats faster posts as bots), and gives up after 20 s.
   - While sending: button disabled with a spinner and "Sending…", form aria-busy="true", fields read-only, no double
     sends. Success replaces the form with the green-tick message; failure shows the red message and keeps every value.
   - Status text is also announced in a polite live region.
   - Without JavaScript (or fetch) the form posts normally to admin-post.php and the page shows the same messages. */
(function () {
  var C = window.KRM_FORMS || {};
  if (!C.ajaxUrl || !window.fetch || !window.FormData) return;
  var MSG = C.msgs || {};
  var EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  var TICK = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"></path></svg>';
  var ALERT = '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 8v5M12 16h.01"></path></svg>';
  var params = new URLSearchParams(location.search);

  function contactOk(v) {
    if (v.indexOf("@") !== -1) return EMAIL_RE.test(v);
    var digits = v.replace(/\D/g, "");
    return /^\+?[\d\s().-]{7,25}$/.test(v) && digits.length >= 7 && digits.length <= 15;
  }
  function delay(ms) { return new Promise(function (resolve) { setTimeout(resolve, ms); }); }
  /* fetch that gives up after C.timeout ms (20 s). */
  function fetchT(url, options) {
    var ctrl = window.AbortController ? new AbortController() : null;
    var timer;
    if (ctrl) options.signal = ctrl.signal;
    return Promise.race([
      fetch(url, options),
      new Promise(function (resolve, reject) {
        timer = setTimeout(function () { if (ctrl) ctrl.abort(); reject(new Error("timeout")); }, C.timeout || 20000);
      })
    ]).then(function (r) { clearTimeout(timer); return r; }, function (e) { clearTimeout(timer); throw e; });
  }
  function fill(text, name) {
    var first = String(name || "").trim().split(/\s+/)[0];
    return first ? text.replace(/\{name\}/g, first) : text.replace(/,?\s*\{name\}/g, "");
  }

  Array.prototype.forEach.call(document.querySelectorAll("form[data-krm-send]"), function (form) {
    var id = form.id;
    var scope = (id && document.querySelector('[data-krm-for="' + id + '"]')) || form;
    var msg = scope.querySelector("[data-krm-msg]");
    var live = scope.querySelector("[data-krm-live]");
    var submit = Array.prototype.filter.call(form.elements, function (el) { return el.type === "submit"; })[0];
    var label = submit ? submit.innerHTML : "";
    var busy = false;
    var session = null;
    var sessionAt = 0;
    var pending = null;

    function inputs() {
      return Array.prototype.filter.call(form.elements, function (el) {
        return el.name && el.type !== "hidden" && el.type !== "submit" && el.name !== "website" && el.tagName !== "FIELDSET";
      });
    }
    inputs().forEach(function (el) {
      var key = el.getAttribute("data-prefill");
      if (key && params.get(key) && !el.value) el.value = params.get(key).slice(0, 200);
    });

    /* A fresh nonce and start token, fetched once the visitor starts on the form (kept for 6 hours). */
    function getSession(force) {
      if (session && !force && Date.now() - sessionAt < 6 * 3600 * 1000) return Promise.resolve(session);
      if (pending) return pending;
      pending = fetchT(C.ajaxUrl + "?action=krm_form_nonce&_=" + Date.now(), { credentials: "same-origin", headers: { Accept: "application/json" } })
        .then(function (r) { return r.json(); })
        .then(function (json) {
          if (!json || !json.success || !json.data) throw new Error("session");
          session = json.data;
          sessionAt = Date.now();
          pending = null;
          return session;
        }, function (e) { pending = null; throw e; });
      return pending;
    }
    form.addEventListener("focusin", function () { getSession().catch(function () {}); }, { once: true });

    function say(text) {
      if (!live) return;
      live.textContent = "";
      setTimeout(function () { live.textContent = text; }, 60);
    }

    /* ---------- Field errors ---------- */
    function holderOf(el) { return el.closest("label") || el.closest("fieldset") || el.parentNode; }
    function mark(el, message) {
      var holder = holderOf(el);
      var noteId = "krm-err-" + id + "-" + el.name;
      var note = document.getElementById(noteId);
      if (message) {
        if (!note) {
          note = document.createElement("span");
          note.className = "krm-field-error";
          note.id = noteId;
          holder.appendChild(note);
        }
        note.textContent = message;
        note.hidden = false;
        el.setAttribute("aria-invalid", "true");
        var described = (el.getAttribute("aria-describedby") || "").split(" ").filter(Boolean);
        if (described.indexOf(noteId) === -1) described.push(noteId);
        el.setAttribute("aria-describedby", described.join(" "));
      } else {
        if (note) { note.textContent = ""; note.hidden = true; }
        el.removeAttribute("aria-invalid");
        var rest = (el.getAttribute("aria-describedby") || "").split(" ").filter(function (x) { return x && x !== noteId; });
        if (rest.length) el.setAttribute("aria-describedby", rest.join(" ")); else el.removeAttribute("aria-describedby");
      }
    }
    function problem(el) {
      var value = el.type === "checkbox" || el.type === "radio" ? "" : String(el.value || "").trim();
      if (el.name === "custom") {
        var amount = form.elements.amount;
        if (!amount || amount.value !== "Other") return "";
        return Number(value) > 0 ? "" : (MSG.amount || "Please choose or enter an amount.");
      }
      if (el.required && (el.type === "checkbox" ? !el.checked : !value)) return MSG.required || "Please fill in this field.";
      if (!value) return "";
      if (el.getAttribute("data-validate") === "email-or-phone" && !contactOk(value)) return MSG.contact || "Please enter a valid email address or WhatsApp number.";
      if (el.type === "email" && !EMAIL_RE.test(value)) return MSG.contact || "Please enter a valid email address.";
      return "";
    }
    function validate() {
      var first = null;
      inputs().forEach(function (el) {
        if (el.type === "radio") return;
        var message = problem(el);
        mark(el, message);
        if (message && !first) first = el;
      });
      return first;
    }
    /* Clear an error as soon as the field is fixed. */
    form.addEventListener("input", function (e) {
      var el = e.target;
      if (el.getAttribute && el.getAttribute("aria-invalid") === "true" && !problem(el)) mark(el, "");
    });
    form.addEventListener("change", function (e) {
      var custom = form.elements.custom;
      if (e.target.name === "amount" && custom && custom.getAttribute("aria-invalid") === "true" && !problem(custom)) mark(custom, "");
    });

    /* ---------- Sending state ---------- */
    function setBusy(on) {
      busy = on;
      form.setAttribute("aria-busy", on ? "true" : "false");
      inputs().forEach(function (el) {
        if (/^(text|email|tel|number|url|search)$/.test(el.type) || el.tagName === "TEXTAREA") el.readOnly = on;
        else el.setAttribute("aria-disabled", on ? "true" : "false");
      });
      if (submit) {
        submit.disabled = on;
        submit.innerHTML = on ? '<span class="krm-spinner" aria-hidden="true"></span>' + (C.sending || "Sending…") : label;
      }
    }
    /* Selects, radios and checkboxes can't be read-only: ignore key presses and clicks on them while sending. */
    form.addEventListener("keydown", function (e) {
      if (busy && e.key !== "Tab" && e.target.getAttribute && e.target.getAttribute("aria-disabled") === "true") e.preventDefault();
    }, true);
    form.addEventListener("click", function (e) {
      if (busy && e.target.closest && e.target.closest('[aria-disabled="true"], label')) e.preventDefault();
    }, true);

    function show(kind, text) {
      if (!msg) return;
      msg.className = "krm-form-msg " + (kind === "success" ? "is-success" : "is-error");
      msg.innerHTML = '<span class="krm-form-msg__icon" aria-hidden="true">' + (kind === "success" ? TICK : ALERT) + '</span><p class="krm-form-msg__text"></p>';
      msg.querySelector("p").textContent = text;
      msg.hidden = false;
    }
    function done(name) {
      var text = fill(msg ? msg.getAttribute("data-success") : "", name);
      setBusy(false);
      // Replace the form (or, on Donate, step 3's text and buttons) with the message.
      Array.prototype.forEach.call(scope.children, function (el) {
        if (el === msg || el === live || el.type === "hidden" || el.classList.contains("krm-hp")) return;
        el.hidden = true;
      });
      form.setAttribute("data-krm-done", "");
      show("success", text);
      say(text);
      if (msg) { msg.focus({ preventScroll: true }); msg.scrollIntoView({ block: "nearest", behavior: "smooth" }); }
    }
    function fail(text, focusEl) {
      text = text || (msg ? msg.getAttribute("data-error") : "") || "Sorry, your message couldn't be sent.";
      show("error", text);
      say(text);
      setBusy(false);
      (focusEl || submit || msg).focus();
    }

    function post(data, retried) {
      return getSession(retried).then(function (s) {
        data.set("_krm_nonce", s.nonce);
        data.set("token", s.token);
        return delay(Math.max(0, (C.minMs || 3250) - (Date.now() - sessionAt)));
      }).then(function () {
        return fetchT(C.ajaxUrl, { method: "POST", body: data, credentials: "same-origin", headers: { Accept: "application/json" } });
      }).then(function (r) {
        return r.json().catch(function () { return {}; }).then(function (json) {
          return { status: r.status, ok: r.ok && json.success === true, data: (json && json.data) || {} };
        });
      }).then(function (res) {
        // An expired nonce (e.g. a tab left open for a day): get a new one and send once more.
        if (!res.ok && res.status === 403 && res.data.code === "expired" && !retried) return post(data, true);
        return res;
      });
    }

    form.addEventListener("submit", function (event) {
      event.preventDefault();
      if (busy || form.hasAttribute("data-krm-done")) return;
      if (msg && msg.classList.contains("is-error")) { msg.hidden = true; }
      var first = validate();
      if (first) {
        say(C.check || "Please check the highlighted fields.");
        first.focus();
        return;
      }
      var data = new FormData(form);
      data.set("action", "krm_form_submit");
      setBusy(true);
      post(data, false).then(function (res) {
        if (res.ok) { done(res.data.name || data.get("name")); return; }
        if (res.status === 422 && res.data.fields) {
          var firstBad = null;
          Object.keys(res.data.fields).forEach(function (key) {
            var el = form.elements[key];
            if (el && el.length && !el.tagName) el = key === "amount" && form.elements.custom ? form.elements.custom : el[0];
            if (el) { mark(el, res.data.fields[key]); firstBad = firstBad || el; }
          });
          fail(res.data.message || C.check, firstBad);
          return;
        }
        fail(res.status === 429 && res.data.message ? res.data.message : null);
      }).catch(function () { fail(null); });
    });
  });
})();
