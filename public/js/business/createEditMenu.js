/**
 * Displays a preview of the uploaded image.
 * Adapted from https://stackoverflow.com/questions/40809635/js-function-to-change-image-on-page-with-file-upload.
 */
function displayUploadedImage() {
	// Get the input element used for uploading the image
	const imageInput = document.querySelector("#menu-item-image");

	// Get the image element that will be used for displaying the preview
	const imageInputPreview = document.querySelector("#menu-item-image-preview");

	// Check if a file has been uploaded
	if (imageInput.files && imageInput.files[0]) {
		if (imageInput.files[0].size > 1000000) {
			// Prevent file from being greater than 1 MB
			imageInput.value = "";
			displayErrorToast("File cannot be greater than 1MB");

		} else {
			// Create a new FileReader object to read the selected file
			const fileReader = new FileReader();

			// Set the src attribute of the image element to the data URL representing the image
			// when the file has been loaded (displaying the preview)
			fileReader.onload = () => {
				imageInputPreview.src = fileReader.result;
			};

			// Read the selected file as a data URL and invoke onload when done
			fileReader.readAsDataURL(imageInput.files[0]);
		}
	}
}
