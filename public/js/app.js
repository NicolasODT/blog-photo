function previewImage() {
  const preview = document.getElementById("image-preview");
  const preview2 = document.getElementById("image-preview2");
  const file = document.querySelector("input[type=file]").files[0];
  const reader = new FileReader();

  if (file) {
    reader.addEventListener(
      "load",
      function () {
        preview.src = reader.result;
      },
      false
    );

    reader.readAsDataURL(file);
    preview.style.display = "block";
    preview2.style.display = "none";
  } else {
    preview.style.display = "none";
  }
}

const fileInput = document.getElementById("fileInput");
if (fileInput) {
  fileInput.addEventListener("change", previewImage);
}
