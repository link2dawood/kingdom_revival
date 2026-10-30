/* Kingdom Revival Ministries — donate page (logic unchanged from the design; PayPal checkout from settings) */
(function () {
  var SETTINGS = window.KRM_DONATE || {};
  var PAYPAL = SETTINGS.paypalMe || 'https://www.paypal.me/Givetotheneedy7';
  /* With a PayPal account email set (Kingdom Revival → Settings), gifts use PayPal's secure hosted checkout,
     which supports one-time and monthly (recurring) giving. Otherwise the PayPal.me link is used (one-time). */
  var PAYPAL_EMAIL = SETTINGS.paypalEmail || '';
  var EMAIL = SETTINGS.email || 'Info@kingdomrevivalministries.com';
  var form = document.getElementById("gift");
  if (!form) return;

  function paypalUrl(g) {
    if (!PAYPAL_EMAIL) return PAYPAL + (g.amount ? "/" + g.amount + "USD" : "");
    var item = "Gift to Kingdom Revival Ministries — " + g.fund;
    var common = "&business=" + encodeURIComponent(PAYPAL_EMAIL) + "&currency_code=USD&no_shipping=1" +
      (SETTINGS.returnUrl ? "&return=" + encodeURIComponent(SETTINGS.returnUrl) : "");
    if (g.monthly && g.amount) {
      return "https://www.paypal.com/cgi-bin/webscr?cmd=_xclick-subscriptions" + common +
        "&item_name=" + encodeURIComponent(item + " (monthly)") + "&a3=" + g.amount + "&p3=1&t3=M&src=1";
    }
    return "https://www.paypal.com/donate/?" + common.slice(1) + "&item_name=" + encodeURIComponent(item) + (g.amount ? "&amount=" + g.amount : "");
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

  function gift() {
    var a = form.elements.amount.value;
    var value = a === "Other" ? form.elements.custom.value : a.replace("$", "");
    return {
      amount: Number(value) > 0 ? Number(value) : 0,
      monthly: form.elements.frequency.value === "Monthly",
      fund: form.elements.fund.value
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
    document.getElementById("paypal-monthly").hidden = !g.monthly || !!PAYPAL_EMAIL;
    var body = "Hello Kingdom Revival Ministries,\n\nI have just sent a gift.\n\nAmount: " + (g.amount ? "$" + g.amount : "-") +
      "\nFrequency: " + (g.monthly ? "Monthly" : "One-time") + "\nFor: " + g.fund +
      "\nPayment method: " + document.querySelector('.tab[aria-selected="true"]').dataset.method +
      "\nReference / tracking number: \n\nName: \nCountry: \n\nGod bless,";
    document.getElementById("notify-link").href = "mailto:" + EMAIL + "?subject=" + encodeURIComponent("My gift to Kingdom Revival Ministries") + "&body=" + encodeURIComponent(body);
    var url = new URL(location.href);
    url.searchParams.set("amount", g.amount || "");
    url.searchParams.set("frequency", g.monthly ? "Monthly" : "One-time");
    url.searchParams.set("for", g.fund);
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
