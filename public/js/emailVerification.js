const eVerificationToggle = document.getElementById("verification_toggle");
const eVerificationCon = document.querySelector(
  ".email_verification_container"
);
const eVerificationBtn = document.querySelector(".verification_btn");
const verificationState = document.querySelector('.verification_state');
eVerificationToggle.addEventListener("click", (event) => {
  event.preventDefault();
  eVerificationCon.classList.add("active");
  let email = event.target.parentElement.childNodes[3].value;
  event.target.style.display = "none";

  fetch(`/user/verification/email/send/${email}`)
    .then((res) => res.json())
    .then((data) => console.log(data));
});

eVerificationBtn.addEventListener("click", (event) => {
  event.preventDefault();
  let verificationCode = event.target.parentElement.childNodes[3].value;
  fetch(`/user/verification/${verificationCode}`)
    .then((res) => res.json())
    .then((data) => {
      let state = data.state;
      if (state) {
        eVerificationCon.innerHTML = "<p>Email hitelesítése sikeres!</p>";
        verificationState.style.display = "none";
      } else {
        eVerificationCon.classList.remove("active");
        eVerificationToggle.style.display = "block";
        verificationState.innerHTML = "Email hitelesítése sikertelen!"
      }
    });
});
