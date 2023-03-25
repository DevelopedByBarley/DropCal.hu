const acceptCookie = document.getElementById('accept_cookie');
const layout = document.getElementById('layout');

acceptCookie.addEventListener('click', (event) => {
  event.preventDefault();
  const d = new Date();
  d.setTime(d.getTime() + (365 * 24 * 60 * 60 * 1000)); // egy évig érvényes
  const expires = "expires=" + d.toUTCString();
  const cookieValue = "true"; // itt tároljuk el az elfogadás állapotát
  document.cookie = "cookie_accepted=" + cookieValue + ";" + expires + ";path=/"; // itt állítjuk be a cookie-t
  event.target.parentElement.parentElement.classList.remove("active");
  layout.classList.remove("inactive");
})