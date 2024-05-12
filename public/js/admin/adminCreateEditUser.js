/**
 * Displays the form for adding affiliated businesses
 * and hides the display button.
 */
function displayAffiliatedBusinessForm() {
	const affiliatedBusinessForm = document.querySelector(`#affiliated-business-form`);
	const removeAffiliatedBusinessButton = document.querySelector(`#remove-business-button`);
	const addAffiliatedBusinessButton = document.querySelector(`#add-business-button`);

	// Display the affiliated business form and button
	toggleElement(affiliatedBusinessForm, true);
	toggleElement(removeAffiliatedBusinessButton, true);

	// Hide the button for adding affiliated businesses
	toggleElement(addAffiliatedBusinessButton, false);
}

/**
 * Hides the form for adding affiliated businesses, shows the corresponding buttons,
 * and clears the form content.
 */
function hideAffiliatedBusinessForm() {
	const affiliatedBusinessForm = document.querySelector(`#affiliated-business-form`);
	const removeAffiliatedBusinessButton = document.querySelector(`#remove-business-button`);
	const addAffiliatedBusinessButton = document.querySelector(`#add-business-button`);

	// Hide the affiliated business form and form related button
	toggleElement(affiliatedBusinessForm, false);
	toggleElement(removeAffiliatedBusinessButton, false);

	// Show the button for adding affiliated businesses
	toggleElement(addAffiliatedBusinessButton, true);

	// Clear the values of the form fields
	const affiliatedBusinessName = document.querySelector(`#affiliated-business-name`);
	const affiliatedBusinessAddress = document.querySelector(`#affiliated-business-address`);
	const affiliatedBusinessTableQuantity = document.querySelector(`#affiliated-business-table-quantity`);
	const affiliatedBusinessSubscriptionStatus = document.querySelector(`#affiliated-business-subcription-status`);

	affiliatedBusinessName.value = "";
	affiliatedBusinessAddress.value = "";
	affiliatedBusinessTableQuantity.value = "";
	affiliatedBusinessSubscriptionStatus.value = "active";
}
