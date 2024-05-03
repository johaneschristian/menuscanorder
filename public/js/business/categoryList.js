function setCategoryEditModal(category_id) {
	const categoryIDInput = document.querySelector("#edited-category-id");
	categoryIDInput.setAttribute("value", category_id);

	const categoryNameInput = document.querySelector("#edited-category-name");
	categoryNameInput.setAttribute("value", getCategoryName(category_id));
}

function setCategoryDeleteModal(category_id) {
	console.log(category_id);
	const categoryIDInput = document.querySelector("#deleted-category-id");
	categoryIDInput.setAttribute("value", category_id);

	const categoryNameInput = document.querySelector("#deleted-category-name");
	categoryNameInput.innerText = getCategoryName(category_id);
}

function getCategoryName(category_id) {
	return document.querySelector(`#menu-category-${category_id}`).innerText;
}
