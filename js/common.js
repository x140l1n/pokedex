//Toggle display alert.
let _alert = document.querySelector("div[role='alert']");

//If exists alert.
if (_alert) {
  _alert.classList.add("in");

  setTimeout(() => {
    _alert.classList.remove("in");
  }, 5000);
}