function showToast(message) {
    const toastContainer = document.getElementById("toast-container");

    let myToast = `
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" id="myToast">
    <div class="toast-header">
        <img src="..." class="rounded me-2" alt="...">
        <strong class="me-auto">Bootstrap</strong>
        <small>11 mins ago</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
        ${message}
    </div>
</div>
    `

    toastContainer.innerHTML = myToast;
    var toast = new bootstrap.Toast(document.getElementById("myToast"));
    toast.show();

    setTimeout(() => {
        toast.hide();
    }, 4000)
}

