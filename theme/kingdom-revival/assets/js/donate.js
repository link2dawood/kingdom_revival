/* Kingdom Revival Ministries — donate page (logic unchanged from the design; PayPal checkout from settings) */
(function () {
  var SETTINGS = window.KRM_DONATE || {};
  var PAYPAL = SETTINGS.paypalMe || 'https://www.paypal.me/Givetotheneedy7';
  /* With a PayPal account email set (Kingdom Revival → Settings), gifts use PayPal's secure hosted checkout,
     which supports one-time and monthly (recurring) giving. PayPal reports each payment to notifyUrl, where the
     theme records it and emails the receipt (inc/donations.php); the donor then returns to the thank-you view.
     Otherwise the PayPal.me link is used (one-time). */
  var PAYPAL_EMAIL = SETTINGS.paypalEmail || '';
  var PAYPAL_HOST = SETTINGS.paypalHost || 'https://www.paypal.com';
  var form = document.getElementById("gift");
  if (!form) return;

  function paypalUrl(g) {
    if (!PAYPAL_EMAIL) return PAYPAL + (g.amount ? "/" + g.amount + "USD" : "");
    var item = "Gift to Kingdom Revival Ministries — " + g.fund;
    var back = new URL(SETTINGS.returnUrl || location.href);
    back.searchParams.set("amount", g.amount || "");
    back.searchParams.set("frequency", g.monthly ? "Monthly" : "One-time");
    back.searchParams.set("for", g.fund);
    var common = "&business=" + encodeURIComponent(PAYPAL_EMAIL) + "&currency_code=USD&no_shipping=1&charset=utf-8" +
      "&custom=" + encodeURIComponent(g.fund) +
      (SETTINGS.notifyUrl ? "&notify_url=" + encodeURIComponent(SETTINGS.notifyUrl) : "") +
      "&return=" + encodeURIComponent(back.href) + "&rm=1" +
      (SETTINGS.cancelUrl ? "&cancel_return=" + encodeURIComponent(SETTINGS.cancelUrl) : "");
    if (g.monthly && g.amount) {
      return PAYPAL_HOST + "/cgi-bin/webscr?cmd=_xclick-subscriptions" + common +
        "&item_name=" + encodeURIComponent(item + " (monthly)") + "&a3=" + g.amount + "&p3=1&t3=M&src=1";
    }
    return PAYPAL_HOST + "/cgi-bin/webscr?cmd=_donations" + common + "&item_name=" + encodeURIComponent(item) + (g.amount ? "&amount=" + g.amount : "");
  }
  var params = new URLSearchParams(location.search);

  function pick(name, value) {
    var input = form.querySelector('input[name="' + name + '"][value="' + value + '"]');
    if (input) { input.checked = true; return true; }
    return false;
  }
  var amount = (params.get("amount") || "").replace(/[^0-9.]/g, "");
  if (amount && !pick("amount", "$" + amount)) { pick("amount", "Other"); form.elements.custom.value = amount; }
  if (params.get("frequency")) pick("frequency", params.get("frequency"));
  if (params.get("for")) {
    var option = Array.prototype.find.call(form.elements.fund.options, function (o) { return o.value === params.get("for"); });
    if (option) option.selected = true;
  }
  if (params.get("ministry")) {
    var ministryOption = Array.prototype.find.call(form.elements.fund.options, function (o) { return o.dataset.slug === params.get("ministry"); });
    if (ministryOption) ministryOption.selected = true;
  }

  function gift() {
    var a = form.elements.amount.value;
    var value = a === "Other" ? form.elements.custom.value : a.replace("$", "");
    return {
      amount: Number(value) > 0 ? Number(value) : 0,
      monthly: form.elements.frequency.value === "Monthly",
      fund: form.elements.fund.value,
      ministry: form.elements.fund.options[form.elements.fund.selectedIndex].dataset.slug || "where-most-needed"
    };
  }

  function update() {
    var g = gift();
    document.getElementById("custom-wrap").hidden = form.elements.amount.value !== "Other";
    var label = g.amount ? "$" + g.amount + (g.monthly ? " / month" : "") : "Choose an amount";
    document.getElementById("summary-amount").textContent = label;
    document.getElementById("summary-fund").textContent = "for " + (g.fund === "Where it is needed most" ? "the mission, where it is needed most" : g.fund);
    document.querySelectorAll("[data-gift-label]").forEach(function (el) { el.textContent = g.amount ? "$" + g.amount : "your gift"; });
    document.getElementById("paypal-link").href = paypalUrl(g);
    /* PayPal's checkout returns to this site, so it opens in the same tab. */
    if (PAYPAL_EMAIL) document.getElementById("paypal-link").target = "_self";
    document.getElementById("paypal-monthly").hidden = !g.monthly || !!PAYPAL_EMAIL;
    /* Step 3 sends the gift card as the "donation" form (assets/js/forms.js); it records the chosen payment tab. */
    var method = form.querySelector("[data-krm-method]");
    var chosen = document.querySelector('.tab[aria-selected="true"]');
    if (method && chosen) method.value = chosen.dataset.method;
    var url = new URL(location.href);
    url.searchParams.set("amount", g.amount || "");
    url.searchParams.set("frequency", g.monthly ? "Monthly" : "One-time");
    url.searchParams.set("for", g.fund);
    url.searchParams.set("ministry", g.ministry);
    history.replaceState(null, "", url);
  }
  form.addEventListener("change", update);
  form.elements.custom.addEventListener("input", update);

  var tabs = Array.prototype.slice.call(document.querySelectorAll(".tab"));
  function select(tab, focus) {
    tabs.forEach(function (t) {
      var on = t === tab;
      t.setAttribute("aria-selected", on ? "true" : "false");
      t.tabIndex = on ? 0 : -1;
      document.getElementById(t.getAttribute("aria-controls")).hidden = !on;
    });
    if (focus) tab.focus();
    update();
  }
  tabs.forEach(function (tab, i) {
    tab.addEventListener("click", function () { select(tab); });
    tab.addEventListener("keydown", function (event) {
      var next = { ArrowRight: i + 1, ArrowLeft: i - 1, Home: 0, End: tabs.length - 1 }[event.key];
      if (next === undefined) return;
      event.preventDefault();
      select(tabs[(next + tabs.length) % tabs.length], true);
    });
  });
  var method = params.get("method");
  select(tabs.find(function (t) { return t.dataset.method.toLowerCase().indexOf((method || "").toLowerCase()) === 0 && method; }) || tabs[0]);

  document.addEventListener("click", function (event) {
    var button = event.target.closest(".copy");
    if (!button) return;
    var label = button.querySelector("span");
    var finish = function () {
      button.classList.add("done");
      label.textContent = "Copied";
      setTimeout(function () { button.classList.remove("done"); label.textContent = "Copy"; }, 1800);
    };
    if (navigator.clipboard) navigator.clipboard.writeText(button.dataset.copy).then(finish, function () {});
  });
  update();
})();
