const eVerificationToggle = document.getElementById("verification_toggle");
const eVerificationCon = document.querySelector(
  ".email_verification_container"
);
const eVerificationBtn = document.querySelector(".verification_btn");
const verificationState = document.querySelector(".verification_state");
const Email = document.getElementById("email");


if (eVerificationToggle && Email) {
  eVerificationToggle.addEventListener("click", (event) => {
    event.preventDefault();
    if (Email.value === '') {
      alert("Az email mező kitöltése kötelező!");
      return;
    }

    // Email formátum ellenőrzése
    const emailRegex = /^[^\s@]{4,}@[^\s@]{4,}\.[^\s@]{2,}$/;
    const email = Email.value.trim();

    if (!emailRegex.test(email)) {
      alert("Kérlek, adj meg egy érvényes email címet!");
      return;
    }

    eVerificationCon.classList.add("active");
    event.target.style.display = "none";

    fetch(`/user/verification/email/send/${email}`);
  });
}


if (eVerificationBtn) {
  eVerificationBtn.addEventListener("click", (event) => {
    event.preventDefault();
    let verificationCode = event.target.parentElement.childNodes[3].value;
    if (verificationCode.length !== 4) {
      alert("A hitelesitő kódnak 4 karekteresnek kell lennie!");
      return;
    }

    fetch(`/user/verification/email/${verificationCode}`)
      .then((res) => res.json())
      .then((data) => {
        let state = data.state;
        if (state) {
          eVerificationCon.innerHTML = "<p>Email hitelesítése sikeres!</p>";
          verificationState.style.display = "none";
        } else {
          eVerificationCon.classList.remove("active");
          eVerificationToggle.style.display = "block";
          verificationState.innerHTML = "Email hitelesítése sikertelen!";
        }
      });
  });
}
