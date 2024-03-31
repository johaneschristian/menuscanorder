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