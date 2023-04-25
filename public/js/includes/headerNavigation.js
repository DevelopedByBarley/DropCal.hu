const profileToggleBtn = document.querySelector('.profile-toggle');
const profileNav = document.getElementById('profile-navigation');
const closeNav = document.getElementById('close-profile-nav');
if (profileToggleBtn) {
    profileToggleBtn.addEventListener('click', () => {
        if (profileNav) {
            profileNav.classList.toggle("active");
        }
    })
}

if (closeNav) {
    closeNav.addEventListener('click', () => {
        profileNav.classList.remove("active");
    })
}