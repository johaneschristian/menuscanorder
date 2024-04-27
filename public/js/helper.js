function toggleElement(element, shouldDisplay) {
	if (shouldDisplay) {
		if(element.classList.contains("d-none")) {
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