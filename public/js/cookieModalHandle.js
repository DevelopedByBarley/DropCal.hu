const acceptCookie = document.getElementById("accept_cookie");
const acceptCookieBanner = document.getElementById("cookie-modal-banner");


acceptCookie.addEventListener("click", (event) => {
  event.preventDefault();
  const d = new Date();
  d.setTime(d.getTime() + 365 * 24 * 60 * 60 * 1000); // egy évig érvényes
  const expires = "expires=" + d.toUTCString();
  const cookieValue = "1"; // itt tároljuk el az elfogadás állapotát
  document.cookie =
    "cookie_level=" + cookieValue + ";" + expires + ";path=/"; // itt állítjuk be a cookie-t
  event.target.parentElement.parentElement.classList.remove("active");
  document.cookie =
  "cookie_accepted=" + "true" + ";" + expires + ";path=/"; // itt állítjuk be a cookie-t
  acceptCookieBanner.style.display = "none";
});

function setCookieSettings(event) {
  event.preventDefault();
  let cookieLevel = 0;
  if (event.target.elements[0].checked) {
    cookieLevel += 1;
  }
  if (event.target.elements[1].checked) {
    cookieLevel += 2;
  }
  if (event.target.elements[2].checked) {
    cookieLevel += 3;
  }

  const d = new Date();
  d.setTime(d.getTime() + 365 * 24 * 60 * 60 * 1000); // egy évig érvényes
  const expires = "expires=" + d.toUTCString();
  const cookieValue = `${cookieLevel}`; // itt tároljuk el az elfogadás állapotát
  document.cookie =
    "cookie_level=" + cookieValue + ";" + expires + ";path=/"; // itt állítjuk be a cookie-t
  document.querySelector("#cookieconsent3").remove();
  document.cookie =
  "cookie_accepted=" + "true" + ";" + expires + ";path=/"; // itt állítjuk be a cookie-t
  acceptCookieBanner.style.display = "none";
}

/**
 * cookie szintek
 * 1 = Szükséges
 * 2 = Analitika
 * 3 = Marketing
 *
 *
 * 1 = csak szükséges cookiek
 * 3 = Szükséges és analitikai cookies
 * 4 = Szükséges és Marketing Cookiek
 * 6 = Minden cookie
 *
 */
