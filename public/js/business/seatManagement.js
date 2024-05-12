/**
 * Prepares the view modal to hold the QR code data of the selected table.
 * 
 * @param {string} tableNumber Table number whose QR code wants to be displayed at the modal. 
 */
function toggleQRView(tableNumber) {
	// Display the table number in the view modal
	const tableNumberSpan = document.querySelector(`#qr-view-table-number`);
	tableNumberSpan.innerText = tableNumber;

	// Display the QR code assigned to the table number in the view modal
	const qrCodeImage = document.querySelector(`#qr-view-qr-code`);
	qrCodeImage.src = getTableQRImage(tableNumber);
}

/**
 * Downloads the QR code that is currently displayed in the modal.
 */
function downloadQRImageFromModal() {
	downloadQRImage(getDisplayedQRCodeTableNumber());
}

/**
 * Prints the QR code for a table given the table number.
 * Adapted from https://www.sitepoint.com/community/t/print-specified-image-on-button-click/262539/2
 * 
 * @param {string} tableNumber Table number whose QR code wants to be printed.
 */
function printQRImage(tableNumber) {
	// Initialize the document to hold the QR code image and related data
	const documentToPrint = window.open("about:blank", "_new");
	documentToPrint.document.open();

	// Create element to hold the QR code image data with the appropriate sizing
	const qrToPrint = document.createElement("img");
	qrToPrint.src = getTableQRImage(tableNumber);
	qrToPrint.style = `width: 200px; height: 200px`;

	// Create element to display the title for the QR code
	const qrTitle = document.createElement("h1");
	qrTitle.innerText = `Table ${tableNumber}`;

	// Create container to hold both the title and the QR code image  
	const imageToPrint = document.createElement("div");
	imageToPrint.style = `display: flex; flex-direction: column; align-items: center;`;
	imageToPrint.appendChild(qrTitle);
	imageToPrint.appendChild(qrToPrint);

	// Write the container and its content to the document
	documentToPrint.document.write(imageToPrint.outerHTML.toString());
	documentToPrint.document.close();
	documentToPrint.focus();

	// Print and close the document after 1 second
	// The 1 second delay is provided to ensure that the container 
	// is written in the document before printing
	setTimeout(() => {
		documentToPrint.print();
		documentToPrint.close();
	}, 1000);
}

/**
 * Downloads the QR code for a table given the table number.
 * 
 * @param {string} tableNumber Table number whose QR code wants to be downloaded. 
 */
function downloadQRImage(tableNumber) {
	// Create a link that contains the table QR code image data
	const temporaryLink = document.createElement("a");
	temporaryLink.href = getTableQRImage(tableNumber);

	// Ensure that the image data will be downloaded when the link is clicked
	temporaryLink.setAttribute("download", `table-${tableNumber}-QR.png`);
	temporaryLink.setAttribute("target", "_blank");

	// Click the link for download
	temporaryLink.click();
}

/**
 * Retrieves the table number whose QR code is currently 
 * displayed in the view modal.
 * 
 * @returns The table number whose QR code is currently displayed. 
 */
function getDisplayedQRCodeTableNumber() {
	return document.querySelector(`#qr-view-table-number`).innerText;
}

/**
 * Retrieves the QR code image PNG data of for a table given the table number. 
 * 
 * @param {string} tableNumber Table number whose QR code image source is to be retrieved.
 * @returns The table assigned QR code PNG data.
 */
function getTableQRImage(tableNumber) {
	return document.querySelector(`#table-num-${tableNumber}-qr`).src;
}
