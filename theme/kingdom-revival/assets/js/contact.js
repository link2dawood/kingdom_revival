/* Kingdom Revival Ministries — contact form: validation (unchanged from the design), then send to WordPress */
(function () {
  var form = document.getElementById("contact-form");
  if (!form) return;
  var status = document.getElementById("form-status");
  var failure = document.getElementById("form-error");
  var submit = form.querySelector('button[type="submit"]');
  var submitHtml = submit.innerHTML;
  var prayerLink = document.getElementById("prayer-link");
  prayerLink.addEventListener("click", function (event) {
    event.preventDefault();
    form.elements.topic.value = "Prayer request";
    form.scrollIntoView({ block: "start" });
    form.elements.name.focus({ preventScroll: true });
  });

  function mark(key, valid) {
    var input = form.elements[key];
    var field = input.closest(".field");
    field.classList.toggle("invalid", !valid);
    field.querySelector(".error").hidden = valid;
    input.setAttribute("aria-invalid", valid ? "false" : "true");
  }

  form.addEventListener("submit", function (event) {
    event.preventDefault();
    var ok = true;
    ["name", "email", "message"].forEach(function (key) {
      var input = form.elements[key];
      var valid = input.value.trim() !== "" && (key !== "email" || /^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(input.value.trim()));
      mark(key, valid);
      if (!valid && ok) { input.focus(); ok = false; }
    });
    if (!ok) return;

    status.hidden = true;
    failure.hidden = true;
    submit.disabled = true;
    submit.textContent = "Sending…";
    KRM.sendForm(form, "contact", {
      name: form.elements.name.value.trim(),
      email: form.elements.email.value.trim(),
      phone: form.elements.phone.value.trim(),
      topic: form.elements.topic.value,
      message: form.elements.message.value.trim()
    }).then(function () {
      form.reset();
      status.hidden = false;
      status.scrollIntoView({ block: "nearest" });
    }).catch(function (error) {
      if (error.fields) {
        Object.keys(error.fields).forEach(function (key) { if (form.elements[key]) mark(key, false); });
      }
      failure.hidden = false;
    }).then(function () {
      submit.disabled = false;
      submit.innerHTML = submitHtml;
    });
  });
})();
