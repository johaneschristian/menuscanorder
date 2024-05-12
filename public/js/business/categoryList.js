/**
 * Populates the category edit modal with information related to a category.
 * @param {string} categoryID - The ID of the category.
 */
function setCategoryEditModal(categoryID) {
	// Set the value attribute of the input in the update modal to the category ID
	const categoryIDInput = document.querySelector("#edited-category-id");
	categoryIDInput.setAttribute("value", categoryID);

	// Set the value attribute of the input to the category name retrieved using the category ID
	const categoryNameInput = document.querySelector("#edited-category-name");
	categoryNameInput.setAttribute("value", getCategoryName(categoryID));
}

/**
 * Prepares the category delete modal with information of the category to be deleted.
 * @param {string} categoryID - The ID of the category to be deleted.
 */
function setCategoryDeleteModal(categoryID) {
	// Display the category ID in the category ID placeholder in the deletion modal
	const categoryIDInput = document.querySelector("#deleted-category-id");
	categoryIDInput.setAttribute("value", categoryID);

	// Display the category name in the category name placeholder in the deletion modal
	const categoryNameInput = document.querySelector("#deleted-category-name");
	categoryNameInput.innerText = getCategoryName(categoryID);
}

/**
 * Retrieves the name of a category given its ID.
 * 
 * @param {string} categoryID - The ID of the category whose name is to be retrieved.
 * @returns {string} The name corresponding to the category ID.
 */
function getCategoryName(categoryID) {
	return document.querySelector(`#menu-category-${categoryID}`).innerText;
}
