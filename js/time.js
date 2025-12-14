document.addEventListener("DOMContentLoaded", function () {
  const timeInput = document.querySelector('input[name="appointment_time"]');

  timeInput.addEventListener("input", function () {
    const value = timeInput.value;
    if (value < "07:00" || value > "17:00") {
      alert("Please select a time between 7:00 AM and 5:00 PM.");
      timeInput.value = ""; // clear invalid input
    }
  });
});