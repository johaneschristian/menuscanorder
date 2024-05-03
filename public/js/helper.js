function toggleElement(element, shouldDisplay) {
	if (shouldDisplay) {
		if (element.classList.contains("d-none")) {
			element.classList.remove("d-none");
		}
	} else {
		if (!element.classList.contains("d-none")) {
			element.classList.add("d-none");
		}
	}
}

/**
 * https://www.squash.io/how-to-capitalize-first-letter-in-javascript/
 * @param {*} string
 * @returns
 */
function capitalizeFirstLetter(string) {
	return string.replace(/^\w/, (c) => c.toUpperCase());
}

function displayErrorToast(errorMessage) {
	Toastify({
		text: errorMessage,
		duration: 5000,
		offset: {
			x: 50, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
			y: 10, // vertical axis - can be a number or a string indicating unity. eg: '2em'
		},
		gravity: "bottom",
		style: {
			background: "red",
		},
	}).showToast();
}
