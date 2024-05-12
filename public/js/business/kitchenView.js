window.addEventListener("load", async (e) => {
	// Initialize page by displaying all active order items and the summary
	await setupKitchenViewPage();

	// Refresh data every 10 seconds to allow receiving new order
	// without manually refreshing the page
	setInterval(async () => await setupKitchenViewPage(), 10000);
});

let statuses = [];

// Define the order items' statuses summary and their property
let statusData = [
	{
		frontendID: 1,
		name: "received",
		quantity: 0,
		color: "danger text-light",
	},
	{
		frontendID: 2,
		name: "being prepared",
		quantity: 0,
		color: "warning text-dark",
	},
	{
		frontendID: 3,
		name: "served",
		quantity: 0,
		color: "success text-light",
	},
];

/**
 * Retrieves the current number of order items with under a status given the status name.
 *
 * @param {string} statusName - Name of the status whose quantity is to be retrieved.
 * @returns {number} The quantity of order items currently with the status.
 */
function getStatusQuantity(statusName) {
	return Number(
		statusData.find((status) => status.name === statusName).quantity
	);
}

/**
 * Retrieves the color associated with the status.
 *
 * @param {string} statusName - Name of the status whose color is to be retrieved.
 * @returns {string} The color associated with the status.
 */
function getStatusColor(statusName) {
	return statusData.find((status) => status.name === statusName).color;
}

/**
 * Update the number of order items currently under a status.
 *
 * @param {string} statusName - Name of the status whose quantity is to be updated.
 * @param {number} newQuantity New quantity for the status.
 */
function updateStatusQuantity(statusName, newQuantity) {
	// Find the status data to be modified
	const modifiedStatus = statusData.find(
		(status) => status.name === statusName
	);

	// Retrieve the other statuses that are not modified
	const nonModifiedStatus = statusData.filter(
		(status) => status.name !== statusName
	);

	// Update the modified status quantity
	// and sort based on ID such that the array can be used
	// to determine the next status for an order item
	statusData = [
		...nonModifiedStatus,
		{
			...modifiedStatus,
			quantity: newQuantity,
		},
	].sort((statusA, statusB) => statusA.frontendID - statusB.frontendID);
}

/**
 * Find the next status' name of a given status.
 *
 * @param {string} statusName - Name of the status whose next status data is to be retrieved.
 * @returns {string} Name of the next status.
 */
function getNextStatus(statusName) {
	const statusIndex = statusData.findIndex(
		(status) => status.name === statusName
	);

	if (statusIndex + 1 < statusData.length) {
		return statusData[statusIndex + 1].name;

	} else {
		return null;
	}
}

/**
 * Fetches all required data for the kitchen page.
 *
 * @returns {array} Containing the list of active order items and order items summary grouped by its status.
 */
async function getKitchenViewData() {
	const response = await fetch(BASE_URL + "business/orders/kitchen-view/data");
	const responseData = JSON.parse(await response.text());

	if (!response.ok) {
		throw new Error(responseData.message);
	}

	return responseData;
}

/**
 * Reload the quantities of the status summary cards displayed at the kitchen view page.
 */
function reloadKitchenViewOrderItemSummary() {
	const receivedSummarySpan = document.querySelector("#order-item-summary-received");
	const beingPreparedSummarySpan = document.querySelector("#order-item-summary-being-prepared");
	const servedSummarySpan = document.querySelector("#order-item-summary-served");

	// Set the content of the summary card text element with the refreshed data
	receivedSummarySpan.innerHTML = getStatusQuantity("received");
	beingPreparedSummarySpan.innerHTML = getStatusQuantity("being prepared");
	servedSummarySpan.innerHTML = getStatusQuantity("served");
}

/**
 * Generate the display card for each order item.
 *
 * @param {object} orderItem - Object containing all the information of an order item.
 * @returns {string} Formatted order item card HTML string with complete data and action buttons.
 */
function getItemCard(orderItem) {
	// Get the next status for the order item
	const orderItemNextStatus = getNextStatus(orderItem.status_name);

	// Get the next status for the order item
	return `
	<div class="col-auto">
		<div
			class="card shadow-sm"
			style="min-height: 300px; min-width: 18rem"
		>
			<div
				class="card-body d-flex flex-column justify-content-between align-items-center"
			>
				<h5 class="card-title m-0">Table ${orderItem.table_number}</h5>
				<span class="badge rounded-pill bg-dark mt-2"
					>Ordered on ${orderItem.item_order_time}</span
				>
				<span id="order-item-${
					orderItem.order_item_id
				}-status" class="badge rounded-pill bg-${getStatusColor(orderItem.status_name)} mt-2">${orderItem.status_name}</span>
				<table class="table">
					<thead class="thead-dark">
						<tr>
							<th>Item</th>
							<th>Quantity</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>${orderItem.menu_item_name}</td>
							<td id="order-item-${orderItem.order_item_id}-quantity">${orderItem.num_of_items}</td>
						</tr>
					</tbody>
				</table>

				${
					orderItem.notes === null
						? ""
						: `<div class='collapse' id='note-${orderItem.order_item_id}'>${orderItem.notes}</div>`
				}
			 
				<div>
					${
						orderItem.status !== "served"
							? `<button id="order-item-${
									orderItem.order_item_id
							  }-action-button" onclick="updateItemStatus('${
									orderItem.order_item_id
							  }')" class="btn btn-${getStatusColor(
									orderItemNextStatus
							  )} mt-3">Mark as ${
									orderItem.status_name === "received"
										? "Being Prepared"
										: "Served"
							  }</button>`
							: ""
					}            
					${
						orderItem.notes === null
							? ""
							: `<button
							type="button"
							class="btn btn-outline-primary mt-3"
							data-bs-toggle="collapse"
							data-bs-target="#note-${orderItem.order_item_id}"
						>
							<svg
								xmlns="http://www.w3.org/2000/svg"
								width="16"
								height="16"
								fill="currentColor"
								class="bi bi-pencil-square"
								viewBox="0 0 16 16"
							>
								<path
									d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"
								/>
								<path
									fill-rule="evenodd"
									d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"
								/>
							</svg>
						</button>`
					}
				</div>
			</div>
		</div>
	</div>`;
}

/**
 * Populates the order items HTML element with the provided order items.
 *
 * @param {array} orderItems - An array of order item objects.
 */
function setupOrderItemsList(orderItems) {
	// Select the HTML element that will hold the order items
	const orderItemsHolder = document.querySelector("#order-items-holder");

	// Clear any existing content inside the holder
	orderItemsHolder.innerHTML = "";

	// Generate HTML card for each order item and append it to the holder
	for (let i = 0; i < orderItems.length; i++) {
		const orderItem = orderItems[i];
		orderItemsHolder.innerHTML += getItemCard(orderItem);
	}
}

/**
 * Sets up the kitchen view page.
 */
async function setupKitchenViewPage() {
	try {
		// Fetch kitchen view data from the server
		const kitchenViewData = await getKitchenViewData();

		// Update global statuses variable with data from the server
		statuses = kitchenViewData.order_item_statuses;

		// Update status quantities based on the fetched data
		statusData.forEach((statusDatum) => {
			updateStatusQuantity(
				statusDatum.name,
				kitchenViewData.order_item_summary.find(
					(status) => status.status_name === statusDatum.name
				)?.total_quantity ?? 0
			);
		});

		// Reload the kitchen view order item summary with the fetched data
		reloadKitchenViewOrderItemSummary();

		// Populate the order items list with the fetched order items
		setupOrderItemsList(kitchenViewData.order_items);

	} catch (exception) {
		// Display an error toast if an exception occurs
		displayErrorToast(exception.message);
	}
}

/**
 * Retrieves the current status of an order item based on its ID.
 *
 * @param {string} orderItemID - The ID of the order item.
 * @returns {string} The current status of the order item.
 */
function getOrderItemCurrentStatus(orderItemID) {
	const statusElement = document.querySelector(`#order-item-${orderItemID}-status`);
	return statusElement.innerHTML;
}

/**
 * Retrieves the quantity of an order item based on its ID.
 *
 * @param {string} orderItemID - The ID of the order item.
 * @returns {number} The quantity of the order item.
 */
function getOrderItemQuantity(orderItemID) {
	const quantityElement = document.querySelector(`#order-item-${orderItemID}-quantity`);
	return Number(quantityElement.innerHTML);
}

/**
 * Updates the status span of an order item with a new status, applying appropriate background color classes.
 *
 * @param {string} orderItemID - The ID of the order item.
 * @param {string} previousStatus - The previous status of the order item.
 * @param {string} newStatus - The new status of the order item.
 */
function updateItemStatusSpan(orderItemID, previousStatus, newStatus) {
	// Update the innerHTML of the status span to the new status
	const orderItemStatusSpan = document.querySelector(`#order-item-${orderItemID}-status`);
	orderItemStatusSpan.innerHTML = newStatus;

	// Remove previous status classes from the status span
	const previousStatusClasses = `bg-${getStatusColor(previousStatus)}`.split(" ");
	previousStatusClasses.forEach((previousClass) => {
		if (orderItemStatusSpan.classList.contains(previousClass)) {
			orderItemStatusSpan.classList.remove(previousClass);
		}
	});

	// Add new status classes to the status span
	const newStatusClasses = `bg-${getStatusColor(newStatus)}`.split(" ");
	newStatusClasses.forEach((newClass) => {
		if (!orderItemStatusSpan.classList.contains(newClass)) {
			orderItemStatusSpan.classList.add(newClass);
		}
	});
}

/**
 * Updates the action button of an order item based on its previous and new statuses.
 *
 * @param {string} orderItemID - The ID of the order item.
 * @param {string} previousStatus - The previous status name of the order item.
 * @param {string} newStatus - The new status name of the order item.
 */
function updateItemActionButton(orderItemID, previousStatus, newStatus) {
	const orderItemActionButton = document.querySelector(`#order-item-${orderItemID}-action-button`);

	// Get the next status after the new status
	const newStatusNextStatus = getNextStatus(newStatus);

	// If the next status is null (meaning that the new status is "served"), hide the action button
	if (newStatusNextStatus === null) {
		toggleElement(orderItemActionButton, false);

	} else {
		// Remove previous status classes from the action button
		const previousStatusClasses = `btn-${getStatusColor(newStatus)}`.split(" ");
		previousStatusClasses.forEach((previousClass) => {
			if (orderItemActionButton.classList.contains(previousClass)) {
				orderItemActionButton.classList.remove(previousClass);
			}
		});

		// Add new status' next status classes to the action button
		const newStatusClasses = `btn-${getStatusColor(newStatusNextStatus)}`.split(" ");
		newStatusClasses.forEach((newClass) => {
			if (!orderItemActionButton.classList.contains(newClass)) {
				orderItemActionButton.classList.add(newClass);
			}
		});

		// Update the text of the action button to reflect the next status
		orderItemActionButton.innerHTML = `Mark as ${capitalizeFirstLetter(newStatusNextStatus)}`;
	}
}

/**
 * Updates the display of an order item's status and action button based on its status.
 *
 * @param {string} orderItemID - The ID of the order item.
 * @param {string} previousStatus - The previous status of the order item.
 * @param {string} newStatus - The new status of the order item.
 */
function updateItemStatusDisplay(orderItemID, previousStatus, newStatus) {
	updateItemStatusSpan(orderItemID, previousStatus, newStatus);
	updateItemActionButton(orderItemID, previousStatus, newStatus);
}

/**
 * Submits an update for the status of an order item to the server.
 *
 * @param {string} orderItemID - The ID of the order item.
 * @param {string} newStatusID - The ID of the new status for the order item.
 * @returns {Promise<object>} A promise resolving to the response data from the server.
 * @throws {Error} If the server response is not successful.
 */
async function submitItemStatusUpdate(orderItemID, newStatusID) {
	// Send a POST request to the server endpoint with the order item ID and new status ID
	const response = await fetch(
		BASE_URL + "business/orders/item/update-status",
		{
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify({
				order_item_id: orderItemID,
				new_status_id: newStatusID,
			}),
		}
	);

	// Parse the response data as JSON
	const responseData = JSON.parse(await response.text());

	// If the response is not successful, throw an error with the error message from the server
	if (!response.ok) {
		throw new Error(responseData.message);
	}

	// Return the response data
	return responseData;
}

/**
 * Updates the status of an order item.
 *
 * @param {string} orderItemID - The ID of the order item.
 */
async function updateItemStatus(orderItemID) {
	// Get the current status of the order item
	const currentStatus = getOrderItemCurrentStatus(orderItemID);

	// Get the next status based on the current status
	const nextStatus = getNextStatus(currentStatus);
	
	// Find the ID of the next status in the statuses array
	const nextStatusID = statuses.find((status) => status.status === nextStatus).id;

	try {
		// Update the status of the order item remotely
		await submitItemStatusUpdate(orderItemID, nextStatusID);

		// Update item summary locally
		updateStatusQuantity(
			currentStatus,
			getStatusQuantity(currentStatus) - getOrderItemQuantity(orderItemID)
		);
		updateStatusQuantity(
			nextStatus,
			getStatusQuantity(nextStatus) + getOrderItemQuantity(orderItemID)
		);

		// Reload the kitchen view order item summary
		reloadKitchenViewOrderItemSummary();

		// Update item display (text, color, action button)
		updateItemStatusDisplay(orderItemID, currentStatus, nextStatus);
		
	} catch (exception) {
		// Display an error toast if an exception occurs
		displayErrorToast(exception.message);
	}
}
