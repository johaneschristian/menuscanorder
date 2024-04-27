window.addEventListener("load", async (e) => {
	await setupKitchenViewPage();
});

let statuses = [];

let statusData = [
  {
    frontendID: 1,
    name: "received",
    quantity: 0, 
    color: "danger text-light"
  },
  {
    frontendID: 2,
    name: "being prepared",
    quantity: 0, 
    color: "warning text-dark"
  },
  {
    frontendID: 3,
    name: "served",
    quantity: 0, 
    color: "success text-light"
  },
];

function getStatusQuantity(statusName) {
  return Number(statusData.find((status) => status.name === statusName).quantity);
}

function getStatusColor(statusName) {
  return statusData.find((status) => status.name === statusName).color;
}

function updateStatusQuantity(statusName, newQuantity) {
  const modifiedStatus = statusData.find((status) => status.name === statusName);
  const nonModifiedStatus = statusData.filter((status) => status.name !== statusName);
  statusData = [
    ...nonModifiedStatus,
    {
      ...modifiedStatus,
      quantity: newQuantity
    }
  ].sort(
    (statusA, statusB) => statusA.frontendID-statusB.frontendID
  );
}

function getNextStatus(statusName) {
  const statusIndex = statusData.findIndex((status) => status.name === statusName);
  if (statusIndex + 1 < statusData.length) {
    return statusData[statusIndex+1].name;
    
  } else {
    return null;
  }
}

async function getKitchenViewData() {
	const response = await fetch("/business/orders/kitchen-view/data");

	if (!response.ok) {
		throw new Error("Failed to fetch kitchen data");
	}

	const responseData = await response.text();

	return JSON.parse(responseData);
}

function reloadKitchenViewOrderItemSummary() {
	const receivedSummarySpan = document.querySelector(
		"#order-item-summary-received"
	);
	const beingPreparedSummarySpan = document.querySelector(
		"#order-item-summary-being-prepared"
	);
	const servedSummarySpan = document.querySelector(
		"#order-item-summary-served"
	);

	receivedSummarySpan.innerHTML = getStatusQuantity("received");
	beingPreparedSummarySpan.innerHTML = getStatusQuantity("being prepared");
	servedSummarySpan.innerHTML = getStatusQuantity("served");
}

function setupOrderItemsList(orderItems) {
	const orderItemsHolder = document.querySelector("#order-items-holder");
	orderItemsHolder.innerHTML = "";

	for (let i = 0; i < orderItems.length; i++) {
		const orderItem = orderItems[i];
    const orderItemNextStatus = getNextStatus(orderItem.status_name);

		orderItemsHolder.innerHTML += `
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
            <span id="order-item-${orderItem.order_item_id}-status" class="badge rounded-pill bg-${getStatusColor(orderItem.status_name)} mt-2">${orderItem.status_name}</span>
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
                  <td>${orderItem.num_of_items}</td>
                </tr>
              </tbody>
            </table>

            ${orderItem.notes === null ? "" : `<div class='collapse' id='note-${orderItem.order_item_id}'>${orderItem.notes}</div>`}
           
            <div>
              ${orderItem.status !== "served" ? `<button id="order-item-${orderItem.order_item_id}-action-button" onclick="updateItemStatus('${orderItem.order_item_id}')" class="btn btn-${getStatusColor(orderItemNextStatus)} mt-3">Mark as ${orderItem.status_name === "received" ? "Being Prepared" : "Served"}</button>` : ""}            
              ${orderItem.notes === null ? "" : (
                `<button
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
              )}
            </div>
          </div>
        </div>
      </div>`;
	}
}

async function setupKitchenViewPage() {
  const kitchenViewData = await getKitchenViewData();
  statuses = kitchenViewData.order_item_statuses;

  statusData.forEach((statusDatum) => {
    updateStatusQuantity(statusDatum.name, kitchenViewData.order_item_summary.find((status) => status.status_name === statusDatum.name)?.total_quantity ?? 0);
  });
  
  reloadKitchenViewOrderItemSummary(kitchenViewData.order_item_summary);
  setupOrderItemsList(kitchenViewData.order_items);
}

function getOrderItemCurrentStatus(orderItemID) {
  const orderItemStatusSpan = document.querySelector(`#order-item-${orderItemID}-status`);
  return orderItemStatusSpan.innerHTML;
}

function updateItemStatusSpan(orderItemID, previousStatus, newStatus) {
  const orderItemStatusSpan = document.querySelector(`#order-item-${orderItemID}-status`);  
  orderItemStatusSpan.innerHTML = newStatus;
  
  const previousStatusClasses = `bg-${getStatusColor(previousStatus)}`.split(' ');
  previousStatusClasses.forEach((previousClass) => {
    if (orderItemStatusSpan.classList.contains(previousClass)) {
      orderItemStatusSpan.classList.remove(previousClass);
    }
  });
  
  const newStatusClasses = `bg-${getStatusColor(newStatus)}`.split(' ');
  newStatusClasses.forEach((newClass) => {
    if (!orderItemStatusSpan.classList.contains(newClass)) {
      orderItemStatusSpan.classList.add(newClass);
    }
  });
}

function updateItemActionButton(orderItemID, previousStatus, newStatus) {
  const orderItemActionButton = document.querySelector(`#order-item-${orderItemID}-action-button`);
  
  // Get next status of new status
  const newStatusNextStatus = getNextStatus(newStatus);

  // Hide action button if next status is null (new status is "served")
  if (newStatusNextStatus === null) {
    toggleElement(orderItemActionButton, false);

  } else {
    const newStatusClasses = `btn-${getStatusColor(newStatus)}`.split(' ');
    newStatusClasses.forEach((newClass) => {
      if (orderItemActionButton.classList.contains(newClass)) {
        orderItemActionButton.classList.remove(newClass);
      }
    });

    const nextStatusClasses = `btn-${getStatusColor(newStatusNextStatus)}`.split(' ');
    nextStatusClasses.forEach((newClass) => {
      if (!orderItemActionButton.classList.contains(newClass)) {
        orderItemActionButton.classList.add(newClass);
      }
    });

    orderItemActionButton.innerHTML = `Mark as ${capitalizeFirstLetter(newStatusNextStatus)}`
  }
}

function updateItemStatusDisplay(orderItemID, previousStatus, newStatus) {
  updateItemStatusSpan(orderItemID, previousStatus, newStatus);
  updateItemActionButton(orderItemID, previousStatus, newStatus);
}

async function updateItemStatus(orderItemID) {
  const currentStatus = getOrderItemCurrentStatus(orderItemID);
  const nextStatus = getNextStatus(currentStatus);
  const nextStatusID = statuses.find((status) => status.status === nextStatus).id;
  
  // Update remote data

  // Update item summary
  updateStatusQuantity(currentStatus, getStatusQuantity(currentStatus) - 1);
  updateStatusQuantity(nextStatus, getStatusQuantity(nextStatus) + 1);
  reloadKitchenViewOrderItemSummary();

  // Update item text, color. and action button
  updateItemStatusDisplay(orderItemID, currentStatus, nextStatus);
}