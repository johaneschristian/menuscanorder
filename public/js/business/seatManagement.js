function toggleQRView(table_number) {
	const tableNumberSpan = document.querySelector(`#qr-view-table-number`);
	const qrCodeImage = document.querySelector(`#qr-view-qr-code`);

	tableNumberSpan.innerText = table_number;
	qrCodeImage.src = getTableQRImage(table_number);
}

function downloadQRImageFromModal() {
	downloadQRImage(getDisplayedQRCodeTableNumber());
}

function printQRImage(tableNumber) {
	/* Adapted from https://www.sitepoint.com/community/t/print-specified-image-on-button-click/262539/2 */

	const documentToPrint = window.open("about:blank", "_new");
	documentToPrint.document.open();

	const qrToPrint = document.createElement("img");
	qrToPrint.src = getTableQRImage(tableNumber);
	qrToPrint.style = `width: 200px; height: 200px`;

	const qrTitle = document.createElement("h1");
	qrTitle.innerText = `Table ${tableNumber}`;

	const imageToPrint = document.createElement("div");
	imageToPrint.style = `display: flex; flex-direction: column; align-items: center;`;
	imageToPrint.appendChild(qrTitle);
	imageToPrint.appendChild(qrToPrint);

	documentToPrint.document.write(imageToPrint.outerHTML.toString());
	documentToPrint.document.close();
	documentToPrint.focus();

	// Ensure that QR is displayed before printing
	setTimeout(() => {
		documentToPrint.print();
		documentToPrint.close();
	}, 1000);
}

function downloadQRImage(table_number) {
	const temporaryLink = document.createElement("a");
	temporaryLink.href = getTableQRImage(table_number);
	temporaryLink.setAttribute("download", `table-${table_number}-QR.png`);
	temporaryLink.setAttribute("target", "_blank");
	temporaryLink.click();
}

function getDisplayedQRCodeTableNumber() {
	return document.querySelector(`#qr-view-table-number`).innerText;
}

function getTableQRImage(table_number) {
	return document.querySelector(`#table-num-${table_number}-qr`).src;
}
