// Format
// {
//   menu_item_id: 1,
//   quantity: 1,
//   notes: ...
// }
let selectedMenus = [];

/**
 * Extracts the table number from the URL of the current page.
 *
 * @returns {string} The table number.
 */
function getCustomerTableNumber() {
	return window.location.href.split("/").at(-1);
}

/**
 * Retrieves the current business ID from the URL of the page.
 *
 * @returns {string} The current business ID.
 */
function getCurrentBusinessID() {
	return window.location.href.split("/").at(-2);
}

/**
 * Retrieves the complete selected menu with notes for each selected menu item.
 *
 * @returns {Array} The complete selected menu with notes.
 */
function getCompleteSelectedMenu() {
	// Add the user entered notes to each of the selected menu item
	const completeSelectedMenu = selectedMenus.map((selectedMenu) => ({
		...selectedMenu,
		notes: getMenuNote(selectedMenu.menu_item_id),
	}));

	return completeSelectedMenu;
}

/**
 * Submits an order to the server.
 */
async function submitOrder() {
	// Prepare order data
	const orderData = {
		business_id: getCurrentBusinessID(),
		table_number: getCustomerTableNumber(),
		selected_menus: getCompleteSelectedMenu(),
	};

	// Send a POST request to submit the order
	const response = await fetch(BASE_URL + "customer/orders/submit", {
		method: "POST",
		mode: "cors",
		body: JSON.stringify(orderData),
	});

	// If the response is successful, redirect to the customer orders page
	if (response.ok) {
		window.location.href = BASE_URL + "/customer/orders/";

	} else {
		// If there's an error, display an error toast with the error message from the server
		const errorResponse = JSON.parse(await response.text());
		displayErrorToast(errorResponse.message);
	}
}

/**
 * Toggles the display of menu details when "read more" is clicked.
 *
 * @param {string} menuItemID - The ID of the menu item to be displayed.
 */
function toggleReadMore(menuItemID) {
	// Select elements representing menu details
	const menuDetailImage = document.querySelector("#menu-detail-image");
	const menuDetailName = document.querySelector("#menu-detail-name");
	const menuDetailDescription = document.querySelector("#menu-detail-description");

	// Update menu details with information from the selected menu item
	menuDetailImage.src = getMenuImage(menuItemID);
	menuDetailName.innerText = getMenuName(menuItemID);
	menuDetailDescription.innerText = getMenuDescription(menuItemID);
}

/**
 * Toggles the display of the edit note button for a menu item.
 *
 * @param {string} menuItemID - The ID of the menu item.
 * @param {boolean} shouldDisplay - Whether the edit note button should be displayed.
 */
function toggleEditNoteButton(menuItemID, shouldDisplay) {
	// Toggle the display of the edit note button
	const editNoteButton = document.querySelector(`#menu-${menuItemID}-edit-note-button`);
	toggleElement(editNoteButton, shouldDisplay);

	// If the edit note button is hidden and the note collapse is shown, hide the note collapse
	const noteCollapse = document.querySelector(`#menu-${menuItemID}-note-collapse`);
	if (!shouldDisplay && noteCollapse.classList.contains("show")) {
		noteCollapse.classList.remove("show");
	}
}

/**
 * Toggles the color of the edit note button based on whether a note exists for a menu item.
 *
 * @param {string} menuItemID - The ID of the menu item.
 */
function toggleEditNoteButtonColor(menuItemID) {
	const itemNote = getMenuNote(menuItemID);
	const editNoteButton = document.querySelector(`#menu-${menuItemID}-edit-note-button`);

	// Change the color of the edit button when a note is inputted or deleted
	if (itemNote.length > 0) {
		if (editNoteButton.classList.contains("btn-outline-primary")) {
			editNoteButton.classList.remove("btn-outline-primary");
		}

		if (!editNoteButton.classList.contains("btn-success")) {
			editNoteButton.classList.add("btn-success");
		}
	} else {
		if (editNoteButton.classList.contains("btn-success")) {
			editNoteButton.classList.remove("btn-success");
		}

		if (!editNoteButton.classList.contains("btn-outline-primary")) {
			editNoteButton.classList.add("btn-outline-primary");
		}
	}
}

/**
 * Toggles the display of the checkout button.
 *
 * @param {boolean} shouldDisplay - Whether the checkout button should be displayed.
 */
function toggleCheckoutButton(shouldDisplay) {
	const checkoutButton = document.querySelector(`#checkout-button`);
	toggleElement(checkoutButton, shouldDisplay);
}

/**
 * Extracts the category ID from the ID of a DOM select element.
 *
 * @param {string} elementID - The ID of the DOM select element.
 * @returns {string} The category ID.
 */
function getMenuCategoryIDFromDOMSelectElement(elementID) {
	return elementID.split("menu-category-")[1];
}

/**
 * Sets the specified menu category as active, displaying its content and visually marking it as selected.
 *
 * @param {HTMLElement} modifiedElement - The selected menu category element.
 */
function setMenuCategoryActive(modifiedElement) {
	// Deselect all menu categories
	document.querySelectorAll(".menu-category").forEach((elem) => {
		if (elem.classList.contains("selected")) {
			elem.classList.remove("selected");
		}
	});

	// Select the modified menu category element
	if (!modifiedElement.classList.contains("selected")) {
		modifiedElement.classList.add("selected");
	}

	// Hide all menu from each category
	document.querySelectorAll('[id^="category-holder-"]').forEach((elem) => {
		toggleElement(elem, false);
	});

	// Get the ID of the active category from the modified element's ID
	const activeCategoryID = getMenuCategoryIDFromDOMSelectElement(
		modifiedElement.id
	);

	// Show the menus under the active category
	const activeCategoryHolder = document.querySelector(
		`#category-holder-${activeCategoryID}`
	);
	toggleElement(activeCategoryHolder, true);
}

/**
 * Filters selected menu items excluding the one with the specified menu item ID.
 *
 * @param {string} menuItemID - The ID of the menu item to exclude.
 * @returns {array} An array of selected menu items excluding the specified menu item.
 */
function selectedMenuQuantityWithoutMenuItem(menuItemID) {
	return selectedMenus.filter(
		(menuQuantity) => menuQuantity.menu_item_id !== menuItemID
	);
}

/**
 * Adds or updates the quantity of a selected menu item.
 *
 * @param {string} menuItemID - The ID of the menu item.
 * @param {number} newQuantity - The new quantity for the menu item.
 */
function updateSelectedMenuQuantity(menuItemID, newQuantity) {
	const nonModifiedMenuQuantities =
		selectedMenuQuantityWithoutMenuItem(menuItemID);

	// Update selectedMenus array with the new quantity
	selectedMenus = [
		...nonModifiedMenuQuantities,
		{
			menu_item_id: menuItemID,
			quantity: newQuantity,
		},
	];
}

/**
 * Updates the total price displayed by modifying it with a given amount.
 *
 * @param {number} amountToModify - The amount by which to modify the total price.
 */
function updateTotalPrice(amountToModify) {
	// Select total price elements
	const orderTotalValue = document.querySelector(`#order-total-value`);
	const orderTotalValueModal = document.querySelector(`#order-total-value-modal`);

	// Calculate new total price
	const newTotal = 
		(Number(orderTotalValue.innerText) + amountToModify)
			.toFixed(2);

	// Update total price text content
	orderTotalValue.innerText = newTotal;
	orderTotalValueModal.innerText = newTotal;
}

/**
 * Retrieves the name of a menu item given its ID.
 *
 * @param {string} menuItemID - ID of the menu whose name is to be retrieved.
 * @returns {string} The name of the menu corresponding to the ID.
 */
function getMenuName(menuItemID) {
	return document.querySelector(`#menu-${menuItemID}-name`).innerText;
}

/**
 * Retrieves the image of a menu item given its ID.
 *
 * @param {string} menuItemID - ID of the menu whose image is to be retrieved.
 * @returns {string} The menu image data.
 */
function getMenuImage(menuItemID) {
	return document.querySelector(`#menu-${menuItemID}-image`).src;
}

/**
 * Retrieves the description of a menu item given its ID.
 *
 * @param {string} menuItemID - ID of the menu whose description is to be retrieved.
 * @returns {string} The description of the menu corresponding to the ID.
 */
function getMenuDescription(menuItemID) {
	return document.querySelector(`#menu-${menuItemID}-description`).innerText;
}

/**
 * Retrieves the price of a menu item given its ID.
 *
 * @param {string} menuItemID - ID of the menu whose price is to be retrieved.
 * @returns {number} The price of the menu corresponding to the ID.
 */
function getMenuPrice(menuItemID) {
	return Number(document.querySelector(`#menu-${menuItemID}-price`).innerText);
}

/**
 * Retrieves user inputted note for a menu item given its ID.
 *
 * @param {string} menuItemID - ID of the menu whose note is to be retrieved.
 * @returns {string} The inputted note for the menu corresponding to the ID.
 */
function getMenuNote(menuItemID) {
	return document.querySelector(`#menu-${menuItemID}-note`).value;
}

/**
 * Handles quantity add to a menu item and updates related elements.
 *
 * @param {string} menuItemID - The ID of the menu item whose quantity is to be added.
 */
function addMenuQuantity(menuItemID) {
	const quantitySpan = document.querySelector(`#menu-${menuItemID}-quantity`);

	// Update the quantity display and record with the new quantity
	const currentQuantity = Number(quantitySpan.innerText);
	const newQuantity = currentQuantity + 1;
	quantitySpan.innerText = newQuantity;
	updateSelectedMenuQuantity(menuItemID, newQuantity);

	// Update the total price by adding the menu item's price
	const menuPrice = getMenuPrice(menuItemID);
	updateTotalPrice(menuPrice);

	// Show the checkout button
	toggleCheckoutButton(true);

	// Show the edit note button for the menu item
	toggleEditNoteButton(menuItemID, true);
}

/**
 * Decreases the quantity of a selected menu item and updates related elements.
 *
 * @param {string} menuItemID - The ID of the menu item whose quantity is to be decreased.
 */
function removeMenuQuantity(menuItemID) {
	const quantitySpan = document.querySelector(`#menu-${menuItemID}-quantity`);

	// Calculate the new quantity
	const currentQuantity = Number(quantitySpan.innerText);
	const newQuantity = currentQuantity - 1;

	if (newQuantity >= 0) {
		// Update the quantity span display and record with the new quantity
		quantitySpan.innerText = newQuantity;
		updateSelectedMenuQuantity(menuItemID, newQuantity);

		// Update the total price by subtracting the menu item's price
		const menuPrice = getMenuPrice(menuItemID);
		updateTotalPrice(-menuPrice);
	}

	if (newQuantity === 0) {
		// Remove the menu item from the selected menus
		selectedMenus = selectedMenuQuantityWithoutMenuItem(menuItemID);

		// Hide the edit note button for the menu item
		toggleEditNoteButton(menuItemID, false);

		// If there are no selected menus left, hide the checkout button
		if (selectedMenus.length === 0) {
			toggleCheckoutButton(false);
		}
	}
}

/**
 * Populates the cart with selected menu items.
 */
function setCartContent() {
	// Get the table number
	const tableNumber = getCustomerTableNumber();

	// Update the table number in the cart
	const cartTableNumberSpan = document.querySelector("#order-table-number");
	cartTableNumberSpan.innerText = tableNumber;

	// Clear previously display menu items
	const cartItemsTBody = document.querySelector("#cart-items-table-body");
	cartItemsTBody.innerText = "";

	// Display each selected menu item as a row in the cart
	selectedMenus.forEach((selectedItem, index) => {
		const itemRow = document.createElement("tr");

		const itemNumber = document.createElement("td");
		itemNumber.innerText = index + 1;
		itemRow.appendChild(itemNumber);

		const itemName = document.createElement("td");
		itemName.innerText = getMenuName(selectedItem.menu_item_id);
		itemRow.appendChild(itemName);

		const itemQuantity = document.createElement("td");
		itemQuantity.innerText = selectedItem.quantity;
		itemRow.appendChild(itemQuantity);

		const itemSubtotal = document.createElement("td");
		itemSubtotal.innerText = (
			getMenuPrice(selectedItem.menu_item_id) * selectedItem.quantity
		).toFixed(2);
		itemRow.appendChild(itemSubtotal);

		const itemNote = document.createElement("td");
		itemNote.innerText = getMenuNote(selectedItem.menu_item_id);
		itemRow.appendChild(itemNote);

		cartItemsTBody.appendChild(itemRow);
	});
}
