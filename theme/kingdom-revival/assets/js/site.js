/* Kingdom Revival Ministries — shared behaviour: navigation, donation cards, popups, forms (the photo viewer is viewer.js) */
(function () {
  /* WordPress passes its URLs and settings in KRM_CONFIG (see inc/enqueue.php). */
  var CONFIG = window.KRM_CONFIG || {};
  var DONATE_URL = CONFIG.donateUrl || "./donate.html";
  /* Newsletter sign-ups are saved in WordPress (Kingdom Revival → Submissions) and emailed to the ministry.
     Without WordPress (no ajaxUrl) the popup falls back to opening an email to the ministry. */
  var NEWSLETTER_ENDPOINT = CONFIG.ajaxUrl || "";
  var NEWSLETTER_EMAIL = CONFIG.email || "Info@kingdomrevivalministries.com";
  var NEWSLETTER_DELAY = typeof CONFIG.newsletterDelay === "number" ? CONFIG.newsletterDelay : 3000;
  var NEWSLETTER_IMAGE = CONFIG.newsletterImage || "https://kingdomrevivalministries.com/wp-content/uploads/2026/08/Bricks-kiln-slaves-children-40-scaled.jpg";
  var NEWSLETTER_SNOOZE_DAYS = 7;
  var KRM = (window.KRM = window.KRM || {});
  KRM.donateUrl = DONATE_URL;

  /* ---------- Navigation ---------- */
  var MENU_WIDTH = 258;

  function closeMenus(except) {
    document.querySelectorAll('.site-nav .sub-toggle[aria-expanded="true"]').forEach(function (button) {
      if (!except || !button.parentElement.contains(except)) button.setAttribute("aria-expanded", "false");
    });
  }

  function placeSubmenu(li) {
    var sub = li.querySelector(":scope > .submenu");
    if (!sub) return;
    if (li.parentElement.classList.contains("menu")) {
      sub.classList.toggle("align-right", li.getBoundingClientRect().left + MENU_WIDTH > window.innerWidth - 8);
    } else {
      sub.classList.toggle("open-left", li.parentElement.getBoundingClientRect().right + MENU_WIDTH > window.innerWidth - 8);
    }
  }

  document.addEventListener("click", function (event) {
    var button = event.target.closest(".site-nav .sub-toggle, .site-nav .menu-toggle");
    if (!button) {
      if (!event.target.closest(".site-nav")) {
        closeMenus();
        document.querySelectorAll(".site-nav .menu-toggle").forEach(function (b) { b.setAttribute("aria-expanded", "false"); });
      }
      return;
    }
    var open = button.getAttribute("aria-expanded") !== "true";
    /* A ministry group's chevron (mobile menu, beside the group link) opens or closes only its own list, so both
       groups can be open together. */
    if (button.parentElement.classList.contains("has-link")) {
      button.setAttribute("aria-expanded", open ? "true" : "false");
      return;
    }
    if (button.classList.contains("sub-toggle")) {
      closeMenus(button);
      if (open) placeSubmenu(button.parentElement);
    } else if (!open) {
      closeMenus();
    }
    button.setAttribute("aria-expanded", open ? "true" : "false");
  });

  document.addEventListener("pointerover", function (event) {
    var li = event.target.closest && event.target.closest(".site-nav .has-sub");
    if (li) placeSubmenu(li);
  });

  document.addEventListener("keydown", function (event) {
    if (event.key !== "Escape" || document.querySelector(".lb")) return;
    var open = Array.prototype.slice.call(document.querySelectorAll('.site-nav .sub-toggle[aria-expanded="true"]')).pop();
    if (open) { open.setAttribute("aria-expanded", "false"); open.focus(); }
  });

  /* ---------- Donation cards ---------- */
  var AMOUNTS = ["$25", "$50", "$100", "$250", "$500", "Other"];
  var giveCount = 0;

  KRM.initGive = function (form) {
    if (!form || form.dataset.ready) return;
    form.dataset.ready = "true";
    var id = "give-" + (++giveCount);
    var target = form.dataset.give || "Kingdom Revival Ministries";
    var heading = form.dataset.heading;
    var intro = form.dataset.intro;

    form.innerHTML =
      (heading ? '<h3></h3>' : "") +
      (intro ? '<p class="give-intro"></p>' : "") +
      '<fieldset class="give-frequency"><legend class="visually-hidden">Gift frequency</legend>' +
      '<label><input type="radio" name="frequency" value="One-time" checked><span>One-time</span></label>' +
      '<label><input type="radio" name="frequency" value="Monthly"><span>Monthly</span></label></fieldset>' +
      '<fieldset class="give-amounts"><legend>Choose an amount</legend>' +
      AMOUNTS.map(function (amount) {
        return '<label><input type="radio" name="amount" value="' + amount + '"' + (amount === "$50" ? " checked" : "") + "><span>" + amount + "</span></label>";
      }).join("") +
      "</fieldset>" +
      '<label class="give-custom" hidden><span>Enter amount (USD)</span><input type="number" id="' + id + '-custom" min="1" step="1" inputmode="numeric" placeholder="e.g. 75"></label>' +
      '<button class="button button-primary give-submit" type="submit"></button>' +
      '<p class="give-note"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"></rect><path d="M8 11V7a4 4 0 0 1 8 0v4"></path></svg>Secure giving · every gift goes to the field</p>';

    if (heading) form.querySelector("h3").textContent = heading;
    if (intro) form.querySelector(".give-intro").textContent = intro;

    var custom = form.querySelector(".give-custom");
    var customInput = custom.querySelector("input");
    var submit = form.querySelector(".give-submit");

    function update() {
      var amount = form.elements.amount.value;
      var monthly = form.elements.frequency.value === "Monthly";
      custom.hidden = amount !== "Other";
      var value = amount === "Other" ? (customInput.value ? "$" + customInput.value : "") : amount;
      submit.textContent = "Donate " + (value ? value + " " : "") + (monthly ? "monthly " : "") + (form.dataset.short ? "" : "to " + target);
    }

    form.addEventListener("change", update);
    customInput.addEventListener("input", update);
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      if (form.elements.amount.value === "Other" && !customInput.value) { customInput.focus(); return; }
      var amount = form.elements.amount.value === "Other" ? customInput.value : form.elements.amount.value.replace("$", "");
      var fund = form.dataset.give && form.dataset.give !== "Kingdom Revival Ministries" ? form.dataset.give : "Where it is needed most";
      window.location.href = DONATE_URL + "?amount=" + encodeURIComponent(amount) + "&frequency=" + encodeURIComponent(form.elements.frequency.value) +
        "&for=" + encodeURIComponent(fund) + "#give";
    });
    update();
  };

  /* ---------- Floating donate button (the home page has its own) ---------- */
  var PLUS = "M12 5v14M5 12h14", CROSS = "M18 6L6 18M6 6l12 12";

  function floatingGive() {
    if (document.getElementById("floating-donation-panel") || document.querySelector(".float-give")) return;
    var wrap = document.createElement("div");
    wrap.className = "float-give";
    wrap.innerHTML =
      '<div class="float-give-panel" id="float-give-panel" role="region" aria-label="Choose a donation amount" hidden>' +
      '<form class="give-card" data-give="Kingdom Revival Ministries" data-short="true" data-heading="Choose an amount" data-intro="Your gift helps share the Gospel and care for vulnerable families."></form></div>' +
      '<button type="button" class="float-give-toggle" aria-controls="float-give-panel" aria-expanded="false">' +
      '<svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="' + PLUS + '"></path></svg><span>Donate</span></button>';
    document.body.appendChild(wrap);

    var panel = wrap.querySelector(".float-give-panel");
    var form = panel.querySelector("form");
    var toggle = wrap.querySelector(".float-give-toggle");
    KRM.initGive(form);
    var eyebrow = document.createElement("p");
    eyebrow.className = "float-eyebrow";
    eyebrow.textContent = "SUPPORT THE MISSION";
    form.prepend(eyebrow);

    function setOpen(open) {
      panel.hidden = !open;
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
      toggle.querySelector("path").setAttribute("d", open ? CROSS : PLUS);
      toggle.querySelector("span").textContent = open ? "Close" : "Donate";
    }
    toggle.addEventListener("click", function () { setOpen(panel.hidden); });
    document.addEventListener("click", function (event) {
      if (!panel.hidden && !wrap.contains(event.target)) setOpen(false);
    });
    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape" && !panel.hidden && !document.querySelector(".lb")) { setOpen(false); toggle.focus(); }
    });
  }

  /* ---------- Newsletter popup ---------- */
  var NL_KEY = "krm-newsletter";

  function nlStored() {
    try { return JSON.parse(localStorage.getItem(NL_KEY) || "null"); } catch (e) { return null; }
  }
  function nlStore(value) {
    try { localStorage.setItem(NL_KEY, JSON.stringify(value)); } catch (e) {}
  }
  function nlShouldShow() {
    if (/[?&]newsletter=1\b/.test(location.search)) return true;
    var saved = nlStored();
    if (!saved) return true;
    if (saved.subscribed) return false;
    return Date.now() - saved.dismissed > NEWSLETTER_SNOOZE_DAYS * 864e5;
  }

  var CHECK_PATH = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"></path></svg>';

  KRM.openNewsletter = function () {
    if (document.querySelector(".nl")) return;
    var returnFocus = document.activeElement;
    var nl = document.createElement("div");
    nl.className = "nl";
    nl.innerHTML =
      '<div class="nl-box" role="dialog" aria-modal="true" aria-labelledby="nl-title">' +
      '<button type="button" class="nl-close" aria-label="Close">' + '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M18 6L6 18M6 6l12 12"></path></svg></button>' +
      '<div class="nl-media" style="background-image: url(\'' + NEWSLETTER_IMAGE.replace(/'/g, "%27") + '\')"><p>Every story of hope starts with someone who cares.<span>Kingdom Revival Ministries</span></p></div>' +
      '<div class="nl-body">' +
      '<p class="nl-eyebrow"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="M22 6l-10 7L2 6"></path></svg>Our newsletter</p>' +
      '<h2 id="nl-title">Get stories of hope in your inbox</h2>' +
      '<p class="nl-text">Hear what God is doing among brick kiln families, orphans and villages in Pakistan — and how you can pray and help.</p>' +
      '<ul class="nl-perks"><li>' + CHECK_PATH + 'Stories and photos from the field</li><li>' + CHECK_PATH + 'Prayer requests and answered prayers</li><li>' + CHECK_PATH + 'Ways to give where the need is greatest</li></ul>' +
      '<form class="nl-form" novalidate>' +
      '<p aria-hidden="true" style="position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden"><label>Leave this field empty<input type="text" name="website" tabindex="-1" autocomplete="off"></label></p>' +
      '<label>First name (optional)<input name="name" autocomplete="given-name" placeholder="Your name"></label>' +
      '<label>Email address<input name="email" type="email" autocomplete="email" placeholder="you@example.com" required></label>' +
      '<p class="nl-error" role="alert" hidden>Please enter a valid email address.</p>' +
      '<button class="nl-submit" type="submit">Subscribe</button>' +
      '<p class="nl-privacy">We respect your inbox. No spam, and you can unsubscribe any time.</p>' +
      '<button type="button" class="nl-skip">No thanks, maybe later</button>' +
      "</form></div></div>";
    document.body.appendChild(nl);
    document.documentElement.style.overflow = "hidden";

    var form = nl.querySelector("form");
    var openedAt = Date.now();
    var email = form.elements.email;
    var error = nl.querySelector(".nl-error");

    function close(remember) {
      if (remember !== false) nlStore({ dismissed: Date.now() });
      nl.remove();
      document.documentElement.style.overflow = "";
      if (returnFocus && returnFocus.focus) returnFocus.focus({ preventScroll: true });
      document.removeEventListener("keydown", onKey, true);
    }
    function onKey(event) {
      if (event.key === "Escape") { event.stopPropagation(); close(); }
      if (event.key === "Tab") {
        var items = Array.prototype.filter.call(nl.querySelectorAll("button, input"), function (el) { return el.offsetParent !== null; });
        var first = items[0], last = items[items.length - 1];
        if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
        else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
      }
    }
    document.addEventListener("keydown", onKey, true);
    nl.addEventListener("click", function (event) {
      if (event.target === nl || event.target.closest(".nl-close, .nl-skip")) close();
    });

    function done() {
      nlStore({ subscribed: true, at: Date.now() });
      nl.querySelector(".nl-body").innerHTML =
        '<div class="nl-done"><span class="nl-check"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true"><path d="M20 6L9 17l-5-5"></path></svg></span>' +
        '<h2 id="nl-title">Thank you for subscribing!</h2>' +
        "<p>" + (NEWSLETTER_ENDPOINT ? "You will start receiving stories of hope from the field soon." : "Your email app should now be open — just press send to finish subscribing.") + "</p>" +
        '<button type="button" class="nl-submit nl-finish" style="padding: 0 28px; margin-top: 8px">Continue browsing</button></div>';
      nl.querySelector(".nl-finish").addEventListener("click", function () { close(false); });
      nl.querySelector(".nl-finish").focus();
    }

    form.addEventListener("submit", function (event) {
      event.preventDefault();
      var value = email.value.trim();
      var valid = /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(value);
      error.hidden = valid;
      email.setAttribute("aria-invalid", valid ? "false" : "true");
      if (!valid) { email.focus(); return; }
      var name = form.elements.name.value.trim();
      if (!NEWSLETTER_ENDPOINT) {
        window.location.href = "mailto:" + NEWSLETTER_EMAIL + "?subject=" + encodeURIComponent("Newsletter subscription") +
          "&body=" + encodeURIComponent("Please add me to the Kingdom Revival Ministries newsletter.\n\nName: " + (name || "-") + "\nEmail: " + value);
        done();
        return;
      }
      var button = form.querySelector(".nl-submit");
      button.disabled = true;
      button.textContent = "Subscribing…";
      var data = new FormData();
      data.append("action", "krm_form");
      data.append("form", "newsletter");
      data.append("nonce", CONFIG.nonce || "");
      data.append("token", CONFIG.formToken || "");
      data.append("website", form.elements.website.value);
      data.append("elapsed", String(Date.now() - openedAt));
      data.append("email", value);
      data.append("name", name);
      fetch(NEWSLETTER_ENDPOINT, { method: "POST", body: data, credentials: "same-origin", headers: { Accept: "application/json" } })
        .then(function (response) { return response.json().then(function (json) { if (!response.ok || !json.success) throw new Error(response.status); done(); }); })
        .catch(function () {
          button.disabled = false;
          button.textContent = "Subscribe";
          error.textContent = "Sorry, something went wrong. Please try again in a moment.";
          error.hidden = false;
        });
    });
    setTimeout(function () { email.focus(); }, 50);
  };

  function scheduleNewsletter() {
    if (!nlShouldShow() || CONFIG.noNewsletter) return;
    setTimeout(function tryOpen() {
      // wait while the visitor is busy with the photo viewer or a donation panel
      var busy = document.querySelector(".lb") || document.querySelector('.float-give-toggle[aria-expanded="true"]') ||
        (document.getElementById("floating-donation-panel") && document.getElementById("floating-donation-panel").style.display === "block");
      if (busy) { setTimeout(tryOpen, 3000); return; }
      KRM.openNewsletter();
    }, NEWSLETTER_DELAY);
  }

  /* ---------- Form posts to WordPress (contact, partner) ---------- */
  KRM.formStarted = Date.now();
  /* Web3Forms (Settings → Web3Forms access key): the server saves the submission and answers with the message to send
     ({ payload, entry, receipt }); the browser sends it to Web3Forms and reports the result back to the entry.
     Resolves true when Web3Forms accepted it. */
  KRM.web3forms = function (envelope, timeoutMs) {
    var ctrl = window.AbortController ? new AbortController() : null;
    var timer = setTimeout(function () { if (ctrl) ctrl.abort(); }, timeoutMs || 20000);
    return fetch("https://api.web3forms.com/submit", {
      method: "POST",
      headers: { "Content-Type": "application/json", Accept: "application/json" },
      body: JSON.stringify(envelope.payload),
      signal: ctrl ? ctrl.signal : undefined
    }).then(function (r) { return r.json().catch(function () { return {}; }); })
      .then(function (json) { return { ok: json.success === true, error: json.message || "" }; },
        function (e) { return { ok: false, error: String((e && e.name) || "network") }; })
      .then(function (res) {
        clearTimeout(timer);
        if (envelope.entry && CONFIG.ajaxUrl) {
          var report = new FormData();
          report.append("action", "krm_form_mailed");
          report.append("entry", envelope.entry);
          report.append("receipt", envelope.receipt || "");
          report.append("ok", res.ok ? "1" : "0");
          report.append("error", res.error);
          fetch(CONFIG.ajaxUrl, { method: "POST", body: report, credentials: "same-origin", keepalive: true }).catch(function () {});
        }
        return res.ok;
      });
  };
  /* Last resort when WordPress itself can't be reached or fails: send the fields straight to Web3Forms. */
  KRM.web3formsDirect = function (formName, fields) {
    if (!CONFIG.web3formsKey) return Promise.resolve(false);
    var label = String(formName).replace(/_/g, " ").replace(/^./, function (c) { return c.toUpperCase(); });
    var payload = { access_key: CONFIG.web3formsKey, subject: "[Website] " + label + (fields.name ? " — " + fields.name : ""), from_name: "Kingdom Revival Website", Note: "Sent directly by the visitor's browser because the website could not save it. It is NOT in Form Submissions." };
    Object.keys(fields).forEach(function (k) { if (typeof fields[k] === "string" && k !== "website") payload[k] = fields[k] || "—"; });
    if (fields.email && /@/.test(fields.email)) payload.replyto = fields.email;
    else if (fields.contact && /@/.test(fields.contact)) payload.replyto = fields.contact;
    payload["Sent from"] = location.href.split("#")[0];
    return KRM.web3forms({ payload: payload });
  };

  KRM.sendForm = function (form, name, fields) {
    var data = new FormData();
    data.append("action", "krm_form");
    data.append("form", name);
    data.append("nonce", CONFIG.nonce || "");
    data.append("token", CONFIG.formToken || "");
    data.append("website", form.elements.website ? form.elements.website.value : "");
    data.append("elapsed", String(Date.now() - KRM.formStarted));
    Object.keys(fields).forEach(function (key) { data.append(key, fields[key]); });
    return fetch(CONFIG.ajaxUrl, { method: "POST", body: data, credentials: "same-origin", headers: { Accept: "application/json" } })
      .then(function (response) {
        return response.json().catch(function () { return {}; }).then(function (json) {
          if (!response.ok || !json.success) {
            var error = new Error((json.data && json.data.message) || "Request failed");
            error.fields = json.data && json.data.fields;
            error.status = response.status;
            throw error;
          }
          // Saved; with Web3Forms the browser sends the email now (the entry keeps the result).
          if (json.data && json.data.w3) return KRM.web3forms(json.data.w3).then(function () { return json; });
          return json;
        });
      }, function () { var e = new Error("network"); e.status = 0; throw e; })
      .catch(function (error) {
        // WordPress failed (server error / no connection), not the visitor's input: email the fields directly.
        if (error.fields || (error.status >= 400 && error.status < 500)) throw error;
        return KRM.web3formsDirect(name, fields).then(function (ok) { if (!ok) throw error; return { success: true }; });
      });
  };

  /* Jump to #section links once the page content exists (the home page builds its content with script) */
  function scrollToHash() {
    if (!location.hash || location.hash.length < 2) return;
    var target = document.getElementById(decodeURIComponent(location.hash.slice(1)));
    if (target) target.scrollIntoView();
  }

  function ready() {
    if (KRM.refreshZoomables) KRM.refreshZoomables();
    document.querySelectorAll("form[data-give]").forEach(KRM.initGive);
    floatingGive();
    scheduleNewsletter();
    setTimeout(scrollToHash, 50);
    window.addEventListener("load", function () { setTimeout(scrollToHash, 50); });
  }
  if (document.readyState === "loading") document.addEventListener("DOMContentLoaded", ready);
  else ready();
})();
