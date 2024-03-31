function setDeletionModal(menuItemId) {
  const deletedMenuItemName = document.querySelector(`#deleted-menu-item-name`);
  const deletedMenuItemId = document.querySelector(`#deleted-menu-item-id`);

  deletedMenuItemName.innerText = getMenuName(menuItemId);
  deletedMenuItemId.value = menuItemId;
}

function getMenuName(menuItemId) {
  return document.querySelector(`#menu-${menuItemId}-name`).innerText;
}