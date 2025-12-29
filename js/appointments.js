// appointments.js
document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".appointments-form");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    let errors = [];

    const name = form.querySelector("input[name='name']");
    const gender = form.querySelector("select[name='gender']");
    const age = form.querySelector("input[name='age']");
    const mobile = form.querySelector("input[name='mobile']");
    const email = form.querySelector("input[name='email']");
    const address = form.querySelector("textarea[name='address']");
    const date = form.querySelector("input[name='appointment_date']");
    const time = form.querySelector("select[name='appointment_time']");

    // Name validation
    if (name.value.trim().length < 3) {
      errors.push("Name must be at least 3 characters.");
    }

    // Gender validation
    if (gender.value === "") {
      errors.push("Please select a gender.");
    }

    // Age validation
    if (age.value < 1 || age.value > 120) {
      errors.push("Age must be between 1 and 120.");
    }

    // Mobile validation (digits only, 7–15 length)
    const mobilePattern = /^[0-9]{7,15}$/;
    if (!mobilePattern.test(mobile.value.trim())) {
      errors.push("Enter a valid mobile number (7–15 digits).");
    }

    // Email validation
    const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
    if (!emailPattern.test(email.value.trim())) {
      errors.push("Enter a valid email address.");
    }

    // Address validation
    if (address.value.trim().length < 5) {
      errors.push("Address must be at least 5 characters.");
    }

    // Date validation
    if (date.value === "") {
      errors.push("Please select an appointment date.");
    }

    // Time validation
    if (time.value === "") {
      errors.push("Please select an appointment time.");
    }

    // Show errors
    if (errors.length > 0) {
      e.preventDefault();
      alert(errors.join("\n"));
    }
  });
});