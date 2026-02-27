document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    let errors = [];

    const doctor = form.querySelector("select[name='doctor_id']");
    const date = form.querySelector("input[name='leave_date']");
    const reason = form.querySelector("input[name='reason']");

    // Doctor required
    if (!doctor.value) {
      errors.push("Please select a doctor.");
    }

    // Date required and must not be in the past
    if (!date.value) {
      errors.push("Leave date is required.");
    } else {
      const today = new Date();
      const chosen = new Date(date.value);
      today.setHours(0,0,0,0);
      chosen.setHours(0,0,0,0); // normalize chosen date too
      if (chosen < today) {
        errors.push("Leave date cannot be in the past.");
      }
    }

    // Reason optional but limit length
    if (reason.value.trim().length > 200) {
      errors.push("Reason must be less than 200 characters.");
    }

    if (errors.length > 0) {
      e.preventDefault();
      alert(errors.join("\n"));
    }
  });
});