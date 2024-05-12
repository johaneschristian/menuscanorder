/**
 * Toggles the visibility of an HTML element by adding or removing the "d-none" class.
 *
 * @param {HTMLElement} element - The HTML element to toggle.
 * @param {boolean} shouldDisplay - If true, the element will be displayed; if false, it will be hidden.
 */
function toggleElement(element, shouldDisplay) {
	// Check if the element should be displayed
	if (shouldDisplay) {
		// If the element is currently hidden, remove the "d-none" class to display it
		if (element.classList.contains("d-none")) {
			element.classList.remove("d-none");
		}
	} else {
		// If the element should be hidden, add the "d-none" class if it's not already present
		if (!element.classList.contains("d-none")) {
			element.classList.add("d-none");
		}
	}
}

/**
 * Capitalizes the first letter of a string.
 * Adapted from: https://www.squash.io/how-to-capitalize-first-letter-in-javascript/
 *
 * @param {string} string - The input string.
 * @returns {string} The string with the first letter capitalized.
 */
function capitalizeFirstLetter(string) {
	return string.replace(/^\w/, (c) => c.toUpperCase());
}

/**
 * Displays an error toast notification with the specified error message.
 * @param {string} errorMessage - The error message to display.
 */
function displayErrorToast(errorMessage) {
	// Use Toastify library to create a toast notification with the error message
	Toastify({
		text: errorMessage,
		duration: 5000,
		offset: {
			x: 0,
			y: 10,
		},
		gravity: "bottom",
		style: {
			background: "red",
		},
	}).showToast();
}
