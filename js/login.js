// login.js
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("loginForm");
  const email = document.getElementById("loginEmail");
  const password = document.getElementById("loginPassword");
  const errorBox = document.getElementById("loginErrorBox");

  form.addEventListener("submit", function (e) {
    let errors = [];

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