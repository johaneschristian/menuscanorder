/**
 * Display or hide the form for adding affiliated businesses and corresponding buttons.
 * 
 * @param {boolean} shouldDisplay - Whether or not the affiliated business form should be displayed.
 */
function toggleAffiliatedBusinessForm(shouldDisplay) {
	const affiliatedBusinessForm = document.querySelector(`#affiliated-business-form`);
	const removeAffiliatedBusinessButton = document.querySelector(`#remove-business-button`);
	const addAffiliatedBusinessButton = document.querySelector(`#add-business-button`);

	// Display or hide the affiliated business form and button
	toggleElement(affiliatedBusinessForm, shouldDisplay);
	toggleElement(removeAffiliatedBusinessButton, shouldDisplay);

	// Add affiliated button should not be present when form is displayed
	toggleElement(addAffiliatedBusinessButton, !shouldDisplay);

	if (!shouldDisplay) {
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
}
