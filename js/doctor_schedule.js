document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".doctor-schedule-form");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    let errors = [];

    const dateField = form.querySelector("input[name='schedule_date']");
    const start = form.querySelector("input[name='start_time']");
    const end = form.querySelector("input[name='end_time']");

    if (!dateField.value) errors.push("Date is required.");
    if (!start.value) errors.push("Start time is required.");
    if (!end.value) errors.push("End time is required.");

    // Ensure start < end
    if (start.value && end.value && start.value >= end.value) {
      errors.push("End time must be later than start time.");
    }

    // Ensure start and end are not the same
    if (start.value && end.value && start.value === end.value) {
      errors.push("Start time and End time cannot be the same.");
    }

    // Prevent past dates
    const today = new Date();
    const selectedDate = new Date(dateField.value);

    if (selectedDate < new Date(today.toDateString())) {
      errors.push("Past dates are not allowed.");
    }

    // Prevent past times if selected date is today
    if (selectedDate.toDateString() === today.toDateString()) {
      const currentMinutes = today.getHours() * 60 + today.getMinutes();

      const [startHour, startMin] = start.value.split(":").map(Number);
      const [endHour, endMin] = end.value.split(":").map(Number);
      const startMinutes = startHour * 60 + startMin;
      const endMinutes = endHour * 60 + endMin;

      if (startMinutes < currentMinutes) {
        errors.push("Start time cannot be in the past for today.");
      }
      if (endMinutes < currentMinutes) {
        errors.push("End time cannot be in the past for today.");
      }
    }

    if (errors.length > 0) {
      e.preventDefault();
      alert(errors.join("\n"));
    }
  });
});
