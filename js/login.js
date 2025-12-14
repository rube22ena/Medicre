document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.getElementById("loginForm");
  const loginErrorBox = document.getElementById("loginErrorBox");

  if (loginForm) {
    loginForm.addEventListener("submit", function (event) {
      let errors = [];

      // Email validation
      const email = document.getElementById("loginEmail").value.trim();
      const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
      if (!emailPattern.test(email)) {
        errors.push("Please enter a valid email address.");
      }

      // Password validation
      const password = document.getElementById("loginPassword").value;
      if (password.length < 6) {
        errors.push("Password must be at least 6 characters.");
      }

      // Show errors if any
      if (errors.length > 0) {
        event.preventDefault(); // stop form submission
        loginErrorBox.innerHTML = errors.join("<br>");
        loginErrorBox.style.color = "red";
      }
    });
  }
});