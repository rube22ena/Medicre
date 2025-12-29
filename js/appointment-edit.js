// appointment-edit.js
document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    let errors = [];

    const name = form.querySelector("input[name='name']");
    const age = form.querySelector("input[name='age']");
    const mobile = form.querySelector("input[name='mobile']");
    const email = form.querySelector("input[name='email']");
    const date = form.querySelector("input[name='appointment_date']");
    const time = form.querySelector("input[name='appointment_time']");

    // Name required
    if (name.value.trim().length < 2) {
      errors.push("Name must be at least 2 characters.");
    }

    // Age range
    if (age.value < 1 || age.value > 120) {
      errors.push("Age must be between 1 and 120.");
    }

    // Mobile basic check (digits only, 7–15 length)
    const mobilePattern = /^[0-9]{7,15}$/;
    if (!mobilePattern.test(mobile.value.trim())) {
      errors.push("Enter a valid mobile number (7–15 digits).");
    }

    // Email format
    const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
    if (!emailPattern.test(email.value.trim())) {
      errors.push("Enter a valid email address.");
    }

    // Date required
    if (!date.value) {
      errors.push("Please select an appointment date.");
    }

    // Time required
    if (!time.value) {
      errors.push("Please select an appointment time.");
    }

    if (errors.length > 0) {
      e.preventDefault();
      alert(errors.join("\n"));
    }
  });
});