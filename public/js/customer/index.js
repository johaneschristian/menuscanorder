// Format
// {
//   menuNum: 1,
//   quantity: 1,
//   notes: ...
// }
let selectedMenu = [];

function toggleReadMore(menuItemId) {
	const menuDetailImage = document.querySelector('#menu-detail-image');
	const menuDetailName = document.querySelector('#menu-detail-name');
	const menuDetailDescription = document.querySelector('#menu-detail-description');

	menuDetailImage.src = getMenuImage(menuItemId);
	menuDetailName.innerText = getMenuName(menuItemId);
	menuDetailDescription.innerText = getMenuDescription(menuItemId);
}

function toggleEditNoteButton(menuItemId, shouldDisplay) {
	const editNoteButton = document.querySelector(`#menu-${menuItemId}-edit-note-button`);
	toggleElement(editNoteButton, shouldDisplay);

	const noteCollapse = document.querySelector(`#menu-${menuItemId}-note-collapse`);
	if (!shouldDisplay && noteCollapse.classList.contains("show")) {
		noteCollapse.classList.remove("show");
	}
}

function toggleEditNoteButtonColor(menuItemId) {
	const itemNote = getMenuNote(menuItemId);
	const editNoteButton = document.querySelector(`#menu-${menuItemId}-edit-note-button`);

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

function toggleCheckoutButton(shouldDisplay) {
	const checkoutButton = document.querySelector(`#checkout-button`);
	toggleElement(checkoutButton, shouldDisplay);
}

function setMenuCategoryActive(modifiedElement) {
	document.querySelectorAll(".menu-category").forEach((elem) => {
		if (elem.classList.contains("selected")) {
			elem.classList.remove("selected");
		}
	});

	if (!modifiedElement.classList.contains("selected")) {
		modifiedElement.classList.add("selected");
	}
}

function selectedMenuQuantityWithoutMenuItem(menuItemId) {
	return selectedMenu.filter(
		(menuQuantity) => menuQuantity.menu_item_id !== menuItemId
	);
}

function updateSelectedMenuQuantity(menuItemId, newQuantity) {
  const nonModifiedMenuQuantities = selectedMenuQuantityWithoutMenuItem(menuItemId);
	selectedMenu = [
		...nonModifiedMenuQuantities,
		{
			menu_item_id: menuItemId,
			quantity: newQuantity,
		},
	];
}

function updateTotalPrice(amountToModify) {
	const orderTotalValue = document.querySelector(`#order-total-value`);
	const orderTotalValueModal = document.querySelector(`#order-total-value-modal`);
	const newTotal = (Number(orderTotalValue.innerText) + amountToModify).toFixed(2);

	orderTotalValue.innerText = newTotal;
	orderTotalValueModal.innerText = newTotal;
}

function getMenuName(menuItemId) {
	return document.querySelector(`#menu-${menuItemId}-name`).innerText;
}

function getMenuImage(menuItemId) {
	return document.querySelector(`#menu-${menuItemId}-image`).src;
}

function getMenuDescription(menuItemId) {
	return document.querySelector(`#menu-${menuItemId}-description`).innerText;
}

function getMenuPrice(menuItemId) {
	return Number(document.querySelector(`#menu-${menuItemId}-price`).innerText);
}

function getMenuNote(menuItemId) {
  return document.querySelector(`#menu-${menuItemId}-note`).value;
}

function addMenuQuantity(menuItemId) {
	const quantitySpan = document.querySelector(`#menu-${menuItemId}-quantity`);
	const currentQuantity = Number(quantitySpan.innerText);
	const newQuantity = currentQuantity + 1;
	quantitySpan.innerText = newQuantity;
	updateSelectedMenuQuantity(menuItemId, newQuantity);

	const menuPrice = getMenuPrice(menuItemId);
	updateTotalPrice(menuPrice);

	toggleCheckoutButton(true);
	toggleEditNoteButton(menuItemId, true);
}

function removeMenuQuantity(menuItemId) {
  const quantitySpan = document.querySelector(`#menu-${menuItemId}-quantity`);
	const currentQuantity = Number(quantitySpan.innerText);
	const newQuantity = currentQuantity - 1;

  if (newQuantity >= 0) {
    quantitySpan.innerText = newQuantity;
    updateSelectedMenuQuantity(menuItemId, newQuantity);

		const menuPrice = getMenuPrice(menuItemId);
		updateTotalPrice(-menuPrice); 
  }

	if (newQuantity === 0) {
		selectedMenu = selectedMenuQuantityWithoutMenuItem(menuItemId);
		toggleEditNoteButton(menuItemId, false);

		if (selectedMenu.length === 0) {
			toggleCheckoutButton(false);
		}
	}
}

function setCartContent() {
	const cart_items_tbody = document.querySelector('#cart-items-table-body');
	cart_items_tbody.innerText = "";

	selectedMenu.forEach((selectedItem, index) => {
		const itemRow = document.createElement('tr');

		const itemNumber = document.createElement('td');
		itemNumber.innerText = index+1;
		itemRow.appendChild(itemNumber);

		const itemName = document.createElement('td');
		itemName.innerText = getMenuName(selectedItem.menu_item_id);
		itemRow.appendChild(itemName);

		const itemQuantity = document.createElement('td');
		itemQuantity.innerText = selectedItem.quantity;
		itemRow.appendChild(itemQuantity);

		const itemSubtotal = document.createElement('td');
		itemSubtotal.innerText = (getMenuPrice(selectedItem.menu_item_id) * selectedItem.quantity).toFixed(2);
		itemRow.appendChild(itemSubtotal);

		const itemNote = document.createElement('td');
		itemNote.innerText = getMenuNote(selectedItem.menu_item_id);
		itemRow.appendChild(itemNote);
		
		cart_items_tbody.appendChild(itemRow);
	});
}

