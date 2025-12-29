// register.js
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("registerForm");
  const name = document.getElementById("name");
  const email = document.getElementById("email");
  const password = document.getElementById("password");
  const errorBox = document.getElementById("errorBox");

  form.addEventListener("submit", function (e) {
    let errors = [];

    // Name validation
    if (name.value.trim() === "") {
      errors.push("Full Name is required.");
    } else if (name.value.trim().length < 3) {
      errors.push("Name must be at least 3 characters.");
    }

    // Email validation
    if (email.value.trim() === "") {
      errors.push("Email is required.");
    } else {
      const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
      if (!emailPattern.test(email.value.trim())) {
        errors.push("Enter a valid email address.");
      }
    }

    // Password validation
    if (password.value.trim() === "") {
      errors.push("Password is required.");
    } else if (password.value.length < 6) {
      errors.push("Password must be at least 6 characters.");
    }

    // Show errors if any
    if (errors.length > 0) {
      e.preventDefault(); // stop form submission
      errorBox.innerHTML = errors.join("<br>");
      errorBox.style.color = "red";
    }
  });
});