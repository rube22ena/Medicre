function showAbout(event) {
  // stop the link from jumping to the top
  event.preventDefault();

  // hide all sections
  document.querySelectorAll("section").forEach(sec => sec.style.display = "none");

  // show About section
  document.getElementById("about").style.display = "block";
}
function showContact(event) {
  event.preventDefault();

  // hide all sections
  document.querySelectorAll("section").forEach(sec => sec.style.display = "none");

  // show Contact section (footer)
  document.getElementById("contact").style.display = "block";
}