// manage-users.js
document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    let errors = [];

    const name = form.querySelector("input[name='name']");
    const email = form.querySelector("input[name='email']");
    const password = form.querySelector("input[name='password']");
    const role = form.querySelector("select[name='role']");
    const specialization = form.querySelector("select[name='specialization']");
    const photo = form.querySelector("input[name='photo']");

    // Name required
    if (!name.value.trim()) {
      errors.push("Name is required.");
    }

    // Email format
    const emailPattern = /^[^@\s]+@[^@\s]+\.[^@\s]+$/;
    if (!emailPattern.test(email.value.trim())) {
      errors.push("Enter a valid email address.");
    }

    // Password length
    if (password.value.length < 6) {
      errors.push("Password must be at least 6 characters.");
    }

    // Role required
    if (!role.value) {
      errors.push("Please select a role.");
    }

    // Specialization required for doctors
    if (role.value === "doctor" && !specialization.value) {
      errors.push("Doctor must have a specialization.");
    }

    // Photo validation for doctors
    if (role.value === "doctor") {
      if (photo.files.length === 0) {
        errors.push("Doctor photo is required.");
      } else {
        const file = photo.files[0];
        const allowedTypes = ["image/jpeg", "image/png", "image/gif", "image/webp"];
        if (!allowedTypes.includes(file.type)) {
          errors.push("Only JPG, PNG, GIF, or WEBP images are allowed.");
        }
        if (file.size > 2 * 1024 * 1024) { // 2 MB limit
          errors.push("Image must be smaller than 2 MB.");
        }
      }
    }

    if (errors.length > 0) {
      e.preventDefault();
      alert(errors.join("\n"));
    }
  });
});