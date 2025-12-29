// doctor_schedule.js
document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".doctor-schedule-form");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    let errors = [];

    const doctor = form.querySelector("select[name='doctor_id']");
    const day = form.querySelector("select[name='day_of_week']");
    const start = form.querySelector("input[name='start_time']");
    const end = form.querySelector("input[name='end_time']");

    if (!doctor.value) errors.push("Doctor is required.");
    if (!day.value) errors.push("Day is required.");
    if (!start.value) errors.push("Start time is required.");
    if (!end.value) errors.push("End time is required.");

    // Ensure start < end
    if (start.value && end.value && start.value >= end.value) {
      errors.push("End time must be later than start time.");
    }

    if (errors.length > 0) {
      e.preventDefault();
      alert(errors.join("\n"));
    }
  });
});