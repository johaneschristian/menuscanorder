/**
 * Prepares the menu delete confirmation modal with information of the menu to be deleted.
 * @param {string} menuItemID - The ID of the menu to be deleted.
 */
function setDeletionModal(menuItemID) {
	// Display the menu ID in the ID placeholder in the deletion modal
	const deletedMenuItemID = document.querySelector(`#deleted-menu-item-id`);
	deletedMenuItemID.value = menuItemID;

	// Display the menu name in the name placeholder in the deletion modal
	const deletedMenuItemName = document.querySelector(`#deleted-menu-item-name`);
	deletedMenuItemName.innerText = getMenuName(menuItemID);
}

/**
 * Retrieves the name of a menu given its ID.
 * 
 * @param {string} menuItemID - The ID of the menu whose name is to be retrieved.
 * @returns {string} The name corresponding to the menu ID.
 */
function getMenuName(menuItemID) {
	return document.querySelector(`#menu-${menuItemID}-name`).innerText;
}
