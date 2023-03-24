const fileInput = document.getElementById("fileInput");
const preview = document.getElementById("preview");

if (fileInput) {
  fileInput.addEventListener("change", () => {
    const file = fileInput.files[0];

    if (file) {
      const reader = new FileReader();

      reader.addEventListener("load", () => {
        preview.src = reader.result;
        preview.style.display = "block";
      });

      reader.readAsDataURL(file);
    } else {
      preview.src = "";
      preview.style.display = "none";
    }
  });
}
