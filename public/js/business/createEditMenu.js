function displayUploadedImage() {
  // Adapted from https://stackoverflow.com/questions/40809635/js-function-to-change-image-on-page-with-file-upload
  const imageInput = document.querySelector('#menu-item-image');
  const imageInputPreview = document.querySelector('#menu-item-image-preview');

  if (imageInput.files && imageInput.files[0]) {
    const fileReader = new FileReader();
    
    fileReader.onload = () => {
      imageInputPreview.src = fileReader.result
    };

    fileReader.readAsDataURL(imageInput.files[0]);
  }
}