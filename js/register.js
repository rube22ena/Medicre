// Wait until page loads
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("registerForm");
  const errorBox = document.getElementById("errorBox");

  form.addEventListener("submit", function (event) {
    let errors = [];

    // Name validation
    const name = document.getElementById("name").value.trim();
    if (name.length < 3) {
      errors.push("Name must be at least 3 characters.");
    }

    // Email validation (basic check)
    const email = document.getElementById("email").value.trim();
    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!emailPattern.test(email)) {
      errors.push("Please enter a valid email address.");
    }

    // Password validation
    const password = document.getElementById("password").value;
    if (password.length < 6) {
      errors.push("Password must be at least 6 characters.");
    }

    // Show errors if any
    if (errors.length > 0) {
      event.preventDefault(); // stop form from submitting
      errorBox.innerHTML = errors.join("<br>");
      errorBox.style.color = "red";
    }
  });
});