function showAbout(event) {
  event.preventDefault();
  document.querySelectorAll("body > section").forEach(sec => sec.style.display = "none");
  document.getElementById("about").style.display = "block";
}

function showcategories(event) {
  event.preventDefault();
  document.querySelectorAll("body > section").forEach(sec => sec.style.display = "none");
  document.getElementById("categories").style.display = "block";
}

function showContact(event) {
  event.preventDefault();
  document.querySelectorAll("body > section").forEach(sec => sec.style.display = "none");
  document.getElementById("contact").style.display = "block";
}