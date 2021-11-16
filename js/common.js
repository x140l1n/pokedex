//Toggle display alert.
let alert = document.querySelector("div[role='alert']");

//If exists alert.
if (alert) {
  alert.classList.add("in");

  setTimeout(function () {
    alert.classList.remove("in");
  }, 3000);
}