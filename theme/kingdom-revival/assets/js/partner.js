/* Kingdom Revival Ministries — partner sign-up (logic unchanged from the design; sign-ups are saved in WordPress) */
(function () {
  /* Sign-ups go to WordPress (Kingdom Revival → Submissions) and are emailed to the ministry. */
  var CONFIG = window.KRM_CONFIG || {};
  var PARTNER_ENDPOINT = CONFIG.ajaxUrl || "";
  var PARTNER_EMAIL = CONFIG.email || "Info@kingdomrevivalministries.com";
  var DONATE_URL = CONFIG.donateUrl || "./donate.html";
  var GIVING_TYPES = ["Monthly Partner", "Ministry Sponsor", "Church / Group Partner"];

  var form = document.getElementById("partner-form");
  if (!form) return;
  var amountSet = document.getElementById("amount-set");
  var ministrySet = document.getElementById("ministry-field");
  var churchField = document.getElementById("church-field");
  var customField = document.getElementById("custom-field");
  var submit = document.getElementById("partner-submit");

  function update() {
    var type = form.elements.type.value;
    var giving = GIVING_TYPES.indexOf(type) !== -1;
    amountSet.hidden = !giving;
    ministrySet.hidden = type !== "Ministry Sponsor";
    churchField.hidden = type !== "Church / Group Partner";
    customField.hidden = !giving || form.elements.amount.value !== "Other";
    var amount = form.elements.amount.value === "Other" ? (form.elements.custom.value ? "$" + form.elements.custom.value : "") : form.elements.amount.value;
    submit.textContent = giving ? "Become a " + type + (amount ? " · " + amount + "/month" : "") : "Join as a Prayer Partner";
  }
  form.addEventListener("change", update);
  form.elements.custom.addEventListener("input", update);

  function choose(type) {
    var radio = form.querySelector('input[name="type"][value="' + type + '"]');
    if (radio) { radio.checked = true; update(); }
    document.getElementById("join").scrollIntoView({ block: "start" });
    setTimeout(function () { (radio || form.elements.name).focus({ preventScroll: true }); }, 400);
  }
  document.querySelectorAll("[data-choose]").forEach(function (button) {
    button.addEventListener("click", function (event) { event.preventDefault(); choose(button.dataset.choose); });
  });
  var preset = new URLSearchParams(location.search).get("type");
  if (preset) {
    var match = Array.prototype.find.call(form.elements.type, function (r) { return r.value.toLowerCase().indexOf(preset.toLowerCase()) === 0; });
    if (match) { match.checked = true; }
  }

  function check(input, valid) {
    var field = input.closest(".field");
    input.setAttribute("aria-invalid", valid ? "false" : "true");
    field.querySelector(".error").hidden = valid;
    return valid;
  }

  form.addEventListener("submit", function (event) {
    event.preventDefault();
    var f = form.elements;
    var type = f.type.value;
    var giving = GIVING_TYPES.indexOf(type) !== -1;
    var checks = [
      check(f.name, f.name.value.trim() !== ""),
      check(f.email, /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(f.email.value.trim())),
      check(f.country, f.country.value.trim() !== "")
    ];
    if (giving && f.amount.value === "Other") checks.push(check(f.custom, Number(f.custom.value) > 0));
    if (checks.indexOf(false) !== -1) {
      var bad = form.querySelector('[aria-invalid="true"]');
      if (bad) bad.focus();
      return;
    }
    var amount = f.amount.value === "Other" ? "$" + f.custom.value : f.amount.value;
    var details = {
      "Partnership": type,
      "Monthly amount": giving ? amount : "—",
      "Ministry to sponsor": type === "Ministry Sponsor" ? f.ministry.value : "—",
      "Church / group": type === "Church / Group Partner" ? (f.church.value.trim() || "—") : "—",
      "Name": f.name.value.trim(),
      "Email": f.email.value.trim(),
      "Phone": f.phone.value.trim() || "—",
      "Country": f.country.value.trim(),
      "Prayer updates": f.updates.checked ? "Yes" : "No",
      "Message": f.message.value.trim() || "—"
    };

    function finish() {
      form.hidden = true;
      var thanks = document.getElementById("thanks");
      document.getElementById("thanks-name").textContent = f.name.value.trim().split(" ")[0];
      document.getElementById("thanks-type").textContent = type;
      document.getElementById("thanks-mail").hidden = !!PARTNER_ENDPOINT;
      document.getElementById("thanks-give").hidden = !giving;
      document.getElementById("thanks-give").href = DONATE_URL + "?frequency=Monthly&amount=" + encodeURIComponent(amount.replace("$", "")) +
        "&for=" + encodeURIComponent(type === "Ministry Sponsor" ? f.ministry.value : "Where it is needed most");
      thanks.hidden = false;
      thanks.scrollIntoView({ block: "center" });
      thanks.querySelector("h2").focus();
    }

    if (!PARTNER_ENDPOINT) {
      var body = "I would like to become a partner with Kingdom Revival Ministries.\n\n" +
        Object.keys(details).map(function (k) { return k + ": " + details[k]; }).join("\n");
      window.location.href = "mailto:" + PARTNER_EMAIL + "?subject=" + encodeURIComponent("New partner: " + type) + "&body=" + encodeURIComponent(body);
      finish();
      return;
    }
    submit.disabled = true;
    submit.textContent = "Sending…";
    document.getElementById("form-error").hidden = true;
    KRM.sendForm(form, "partner", {
      type: type,
      amount: giving ? amount : "",
      ministry: type === "Ministry Sponsor" ? f.ministry.value : "",
      church: type === "Church / Group Partner" ? f.church.value.trim() : "",
      name: f.name.value.trim(),
      email: f.email.value.trim(),
      phone: f.phone.value.trim(),
      country: f.country.value.trim(),
      updates: f.updates.checked ? "Yes" : "No",
      message: f.message.value.trim()
    })
      .then(finish)
      .catch(function () {
        submit.disabled = false;
        update();
        document.getElementById("form-error").hidden = false;
      });
  });
  update();
})();
