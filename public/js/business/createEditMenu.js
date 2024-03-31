function displayUploadedImage() {
  const imageInput = document.querySelector('#menu-item-image');
  const imageInputPreview = document.querySelector('#menu-item-image-preview');

  if (imageInput.files && imageInput.files[0]) {
    const fileReader = new FileReader();
    
    fileReader.onload = () => {
      console.log(fileReader.result);
      imageInputPreview.src = fileReader.result
    };

    fileReader.readAsDataURL(imageInput.files[0]);
  }
}