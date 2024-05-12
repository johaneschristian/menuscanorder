<?php

namespace App\Services;

use App\CustomExceptions\InvalidRequestException;
use App\CustomExceptions\NotAuthorizedException;
use App\Repositories\BusinessRepository;
use App\Repositories\MenuRepository;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderRepository;
use App\Utils\Utils;
use App\Utils\Validator;
use App\CustomExceptions\ObjectNotFoundException;

/**
 * Service to deal with business logic for order related operations
 */
class OrderService
{
    /**
     * Handle the retrieval of business menus grouped by category.
     *
     * @param string $businessID The ID of the business whose menus are to be retrieved.
     * @return array An array containing business information and menus grouped by category.
     * @throws ObjectNotFoundException If the business matching the ID does not exist.
     */
    public static function handleGetBusinessMenus($businessID)
    {
        // Retrieve the business by its ID or throw an exception if not found
        $business = BusinessRepository::getBusinessByIDOrThrowException($businessID);
        
        // Retrieve categories and menu items of the business grouped by category
        $categories_and_menu = MenuRepository::getMenuItemsOfBusinessGroupByCategory($businessID, TRUE);

        // Return business information and menus grouped by category
        return [
            'business' => $business,
            'categories_and_menu' => $categories_and_menu
        ];
    }

    /**
     * Validate order creation data.
     *
     * @param object $business The business object where the order is assigned to.
     * @param array $orderData An associative array containing order creation data to be validated.
     * @throws InvalidRequestException When validation fails, this exception is thrown with the validation errors.
     */
    private static function validateOrderCreation($business, $orderData)
    {
        // Check if the business is open
        if (!$business->is_open) {
            throw new InvalidRequestException("Business with ID {$business->business_id} is currently closed");

        } elseif (!is_array($orderData['selected_menus'])) {
            // Check if selected_menus is an array
            throw new InvalidRequestException("Selected menus must be an array");
        }

        // Define validation rules for order creation data
        $rules = [
            'table_number' => "required|is_natural|less_than_equal_to[{$business->num_of_tables}]",
            'selected_menus' => 'required',
        ];

        // Perform validation on order creation data
        $validationResult = Validator::validate($rules, [], $orderData);
        if ($validationResult !== TRUE) {
            throw new InvalidRequestException($validationResult);
        }

        // Validate each selected menu within the order data
        foreach ($orderData['selected_menus'] as $selectedMenu) {
            $rules = [
                'quantity' => 'required|is_natural_no_zero',
                'notes' => 'permit_empty|string',
            ];

            $validationResult = Validator::validate($rules, [], $selectedMenu);
            if ($validationResult !== TRUE) {
                throw new InvalidRequestException($validationResult);
            }
        }
    }

    /**
     * Convert selected menu data for processing.
     *
     * @param array $selectedMenus An array containing selected menu items data.
     * @return array Converted selected menu items data (with selected menu record).
     * @throws ObjectNotFoundException If a selected menu item does not exist.
     */
    private static function convertSelectedMenu($selectedMenus)
    {
        // Initialize array to store converted selected menu items
        $convertedSelectedMenus = [];

        foreach ($selectedMenus as $selectedMenu) {
            // Convert selected menu item data
            // Ensure 'notes' field is either NULL or a non-empty string
            $convertedSelectedMenus[] = [
                ...$selectedMenu,
                'notes' => (!is_string($selectedMenu['notes']) || empty($selectedMenu['notes'])) ? NULL : $selectedMenu['notes'],
                'menu_item' => MenuRepository::getMenuByIDOrThrowException($selectedMenu['menu_item_id']),
            ];
        }

        // Return converted selected menu items
        return $convertedSelectedMenus;
    }

    /**
     * Validate that all selected menu items are currently available.
     *
     * @param array $selectedMenus An array containing selected menu items data.
     * @throws InvalidRequestException If any selected menu item is not available.
     */
    private static function validateAllSelectedMenuAreAvailable($selectedMenus)
    {
        foreach ($selectedMenus as $selectedMenu) {
            if (!$selectedMenu['menu_item']->is_available) {
                throw new InvalidRequestException("Menu with ID {$selectedMenu['menu_item']->menu_item_id} is currently not available");
            }
        }
    }

    /**
     * Get or create an order for the submitting user at the receiving business and table number.
     *
     * @param object $submittingUser The user object representing the submitting user.
     * @param object $receivingBusiness The business object representing the receiving business.
     * @param int $tableNumber The table number for the order.
     * @return string The order ID.
     */
    private static function getOrCreateOrder($submittingUser, $receivingBusiness, $tableNumber)
    {
        // Retrieve the user's latest uncompleted order in the business for the specified table
        $userLatestUncompleteOrderInBusiness = OrderRepository::getLatestUncompleteOrderOfCustomerInBusiness(
            $submittingUser->id,
            $receivingBusiness->business_id,
            $tableNumber
        );

        // If a user's uncompleted order exists, return its order ID
        if (!is_null($userLatestUncompleteOrderInBusiness)) {
            return $userLatestUncompleteOrderInBusiness->order_id;

        } else {
            // If no uncompleted order exists, create a new order and return its ID
            return OrderRepository::createOrder($submittingUser->id, $receivingBusiness->business_id, $tableNumber);
        }
    }

    /**
     * Recompute the total price of an order by adding the provided subtotal.
     *
     * @param string $orderID The ID of the order to update.
     * @param float $addedSubtotal The additional subtotal to add to the order's total price.
     */
    private static function recomputeOrderTotalPrice($orderID, $addedSubtotal)
    {
        // Retrieve the order by its ID
        $order = OrderRepository::getOrderByID($orderID);
        
        // Calculate the new total price by adding the provided subtotal
        $orderNewTotal = $order->total_price + $addedSubtotal;
        
        // Update the order with the new total price
        OrderRepository::updateOrder($orderID, ['total_price' => $orderNewTotal]);
    }

    /**
     * Compute the status of an order based on its items' statuses.
     *
     * @param string $orderID The ID of the order to compute the status for.
     * @return object The order status object.
     */
    private static function computeOrderStatus($orderID)
    {
        // Retrieve all items of the order
        $itemsOfOrder = OrderItemRepository::getOrderItemsOfOrder($orderID);

        // Retrieve the 'received' (by kitchen) order item status
        $receivedOrderItemStatus = OrderItemRepository::getOrderItemStatusByName('received');

        $allAreReceived = TRUE;

        // Check if all order's item status are received
        foreach ($itemsOfOrder as $itemOfOrder) {
            if ($itemOfOrder->order_item_status_id !== $receivedOrderItemStatus->id) {
                $allAreReceived = FALSE;
                break;
            }
        }

        if ($allAreReceived) {
            // If all items are received, return the 'New Order' status
            return OrderRepository::getOrderStatusByName('New Order');

        } else {
            // If any item is not received, return the 'In-Progress' status
            return OrderRepository::getOrderStatusByName('In-Progress');
        }
    }

    /**
     * Set the status of an order based on its items' statuses.
     *
     * @param string $orderID The ID of the order to set the status for.
     */
    private static function setOrderStatus($orderID)
    {
        // Compute the order status based on its items' statuses
        $orderStatus = self::computeOrderStatus($orderID);
        
        // Update the order with the computed order status ID
        OrderRepository::updateOrder($orderID, ['order_status_id' => $orderStatus->id]);
    }

    /**
     * Register order items for the specified order ID with selected menu items.
     *
     * @param string $orderID The ID of the order to register items for.
     * @param array $selectedMenus An array containing selected menu items data.
     */
    private static function registerOrderItem($orderID, $selectedMenus)
    {
        // Initialize total price
        $totalPrice = 0;

        // Create an order item for each selected menu item
        foreach ($selectedMenus as $selectedMenu) {
            OrderItemRepository::createOrderItemModel([
                'num_of_items' => $selectedMenu['quantity'],
                'price_when_bought' => $selectedMenu['menu_item']->price,
                'notes' => $selectedMenu['notes'],
                'order_id' => $orderID,
                'menu_item_id' => $selectedMenu['menu_item']->menu_item_id,
            ]);

            // Calculate subtotal for the current menu item and add it to the total price
            $subtotal = $selectedMenu['menu_item']->price * $selectedMenu['quantity'];
            $totalPrice += $subtotal;
        }

        // Recompute the total price of the order with the added subtotal
        self::recomputeOrderTotalPrice($orderID, $totalPrice);
        
        // Set the status of the order based on its items' statuses
        self::setOrderStatus($orderID);
    }

    /**
     * Handle the creation of a new order.
     *
     * @param object $user The user object representing the logged-in customer, creating the order.
     * @param array $requestData The request data containing order details.
     * @throws NotAuthorizedException If the user does not have authorization to create the order.
     * @throws ObjectNotFoundException If the business matching the ID does not exist.
     * @throws InvalidRequestException If validation of order data fails during creation process.
     */
    public static function handleCreateOrder($user, $requestData)
    {
        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        // Trim whitespace from all string values in the request data
        $requestData = Utils::trimAllString($requestData);
        
        // Retrieve the receiving business by ID
        $receivingBusiness = BusinessRepository::getBusinessByIDOrThrowException($requestData['business_id'] ?? NULL);
        
        // Validate order creation
        self::validateOrderCreation($receivingBusiness, $requestData);
        
        // Convert selected menu data for processing
        $convertedSelectedMenus = self::convertSelectedMenu($requestData['selected_menus']);
        
        // Validate that all selected menu items are available
        self::validateAllSelectedMenuAreAvailable($convertedSelectedMenus);
        
        // Get or create an order for the user at the receiving business and table number
        $modifiedOrderID = self::getOrCreateOrder($user, $receivingBusiness, $requestData['table_number']);
        
        // Register order items for the created order with selected menu items
        self::registerOrderItem($modifiedOrderID, $convertedSelectedMenus);

        // Complete transaction
        $db->transComplete();
    }

    /**
     * Transform customer order list request data for processing.
     *
     * @param array $requestData The request data containing search, status_id, and page parameters.
     * @return array Transformed request data.
     */
    private static function transformCustomerOrderListRequestData($requestData)
    {
        // Initialize transformed request data with default values
        $transformedRequestData = [
            'business_ids' => NULL,
            'status_id' => NULL,
            'page' => (int) ($requestData['page'] ?? 1),
        ];

        // Set business_ids in transformed request data to IDs of businesses matching name 
        if (array_key_exists('business_name', $requestData) && !empty(trim($requestData['business_name']))) {
            // Retrieve businesses matching the provided name
            $businessesMatchingName = BusinessRepository::getBusinessesMatchingName(trim($requestData['business_name']));
            
            // Extract business IDs from matched businesses
            $businessesIDMatchingName = array_map(
                function ($business) {
                    return $business->business_id;
                },
                $businessesMatchingName
            );
            
            // Set business_ids in transformed request data
            $transformedRequestData['business_ids'] = $businessesIDMatchingName;
        }

        // Set status_id in transformed request data if it is provided and is numeric
        if (array_key_exists('status_id', $requestData) && is_numeric($requestData['status_id'])) {
            $transformedRequestData['status_id'] = (int) $requestData['status_id'];
        }

        // Return the transformed request data
        return $transformedRequestData;
    }

    /**
     * Append relevant information to the order object for display.
     *
     * @param object $order The order object to append information to.
     * @return object The order object with appended information.
     */
    private static function appendRelevantInformationOfOrder($order)
    {
        // Clone the order object to avoid modifying the original object
        $formattedOrder = clone $order;

        // Retrieve and append business name associated with receiving_business_id
        $formattedOrder->business_name = BusinessRepository::getBusinessByID($order->receiving_business_id)->business_name;

        // Retrieve and append status name associated with order_status_id
        $formattedOrder->status_name = OrderRepository::getOrderStatusByID($order->order_status_id)->status;

        // Calculate and append duration of the order
        $formattedOrder->duration = Utils::calculateDuration($order->order_creation_time, $order->order_completion_time ?? Utils::getCurrentTime());

        // Extract and append start date from order_creation_time
        $formattedOrder->start_date = Utils::getDateFromDateTime($order->order_creation_time);

        // Format and append creation time for display
        $formattedOrder->formatted_creation_time = Utils::formatDateTimeForDisplay($order->order_creation_time);

        // Return the order object with appended information
        return $formattedOrder;
    }

    /**
     * Append related information to each order in the provided array for display.
     *
     * @param array $orders An array of order objects to append information to.
     * @return array The array of orders with appended information.
     */
    private static function appendRelatedInformationOfOrders($orders)
    {
        // Append relevant information to the each order
        for ($orderIndex = 0; $orderIndex < sizeof($orders); $orderIndex++) {
            $orders[$orderIndex] = self::appendRelevantInformationOfOrder($orders[$orderIndex]);
        }

        return $orders;
    }

    /**
     * Handle the retrieval of a paginated list of orders for a customer.
     *
     * @param object $user The user object representing the logged-in customer.
     * @param array $requestData The request data containing search, business name, status ID, and page parameters.
     * @return array An array containing orders data, pagination information, statuses, search term, and selected status ID.
     */
    public static function handleCustomerGetOrderList($user, $requestData)
    {
        // Transform request data for processing
        $transformedRequestData = self::transformCustomerOrderListRequestData($requestData);
        
        // Retrieve paginated orders of the user based on transformed request data
        $userOrdersPaginated = OrderRepository::getPaginatedOrdersOfUser(
            $user->id,
            $transformedRequestData['business_ids'],
            $transformedRequestData['status_id'],
            10,
            $transformedRequestData['page'],
        );

        // Append related information to each order in the paginated result
        $userCompleteOrderData = self::appendRelatedInformationOfOrders($userOrdersPaginated['result']);

        // Retrieve all order statuses
        $allStatus = OrderRepository::getAllOrderStatus();

        // Construct and return the response array
        return [
            'orders' => $userCompleteOrderData,
            'pager' => $userOrdersPaginated['pager'],
            'statuses' => $allStatus,
            'search' => $requestData['business_name'] ?? '',
            'selected_status_id' => $requestData['status_id'] ?? '',
        ];
    }

    /**
     * Validate if user is the owner of the given order.
     *
     * @param object $user The user object whose ownership over order is to be tested.
     * @param object $order The order object to validate ownership against.
     * @throws NotAuthorizedException If the user is not the owner of the order.
     */
    private static function validateCustomerOrderOwnership($user, $order)
    {
        // Check if the submitting_user_id of the order matches the ID of the logged-in user
        // Note: No type-safe comparison is used due to unexpected type conversion by PHP
        if ($order->submitting_user_id != $user->id) {
            throw new NotAuthorizedException("User is not the owner of order with ID {$order->order_id}");
        }
    }

    /**
     * Create a summary of order items.
     * Order items summary consist of the total quantity and notes of the order items regardless
     * when they are bought within the order. Order items associated with the same menu item is
     * considered distinct when they are bought at different price.
     *
     * @param object $order The order object to create the summary for.
     * @param string $menuItemID The ID of the menu item.
     * @param string $menuItemName The name of the menu item.
     * @param double $price The price when the order item was bought.
     * @return array The summary of order items with the specified menu item ID and price.
     */
    private static function createOrderMenuItemIDAndPriceSummary($order, $menuItemID, $menuItemName, $price)
    {
        // Initialize order item summary with default values
        $orderItemSummary = [
            'menu_item_id' => $menuItemID,
            'menu_item_name' => $menuItemName,
            'num_of_items' => 0,
            'price_when_bought' => $price,
            'subtotal' => 0,
            'notes' => []
        ];

        // Retrieve order items matching the specified menu item ID and price
        $orderItems = OrderItemRepository::getOrderItemsOfOrderMatchingMenuItemIDAndPrice($order->order_id, $menuItemID, $price);
        
        foreach ($orderItems as $orderItem) {
            // Update num_of_items and calculate subtotal
            $orderItemSummary['num_of_items'] += $orderItem->num_of_items;
            $orderItemSummary['subtotal'] += $orderItem->num_of_items * $price;

            // Add notes to the summary if they are not null
            if (!is_null($orderItem->notes)) {
                $orderItemSummary['notes'][] = $orderItem->notes;
            }
        }

        // Return the summary of order items with the specified menu item ID and price
        return $orderItemSummary;
    }

    /**
     * Get a summary of order items for the provided order.
     *
     * @param object $order The order object to generate the summary for.
     * @return array The summary of order items.
     */
    private static function getOrderItemsSummary($order)
    {
        // Retrieve unique combinations of menu item ID and price for the order
        $uniqueCombinationOfMenuItemAndPrice = OrderItemRepository::getDistinctCombinationOfMenuItemIDAndPriceOfOrder($order->order_id);

        // Initialize order summary array
        $orderSummary = [];

        // Create a summary for each unique combination and add it to the order summary array
        // Menu item ID and name will be null when the item is deleted after purchase.
        foreach ($uniqueCombinationOfMenuItemAndPrice as $menuItemAndPrice) {
            $orderSummary[] = self::createOrderMenuItemIDAndPriceSummary(
                $order,
                $menuItemAndPrice->menu_item_id,
                $menuItemAndPrice->menu_item_name ?? 'Deleted Item',
                $menuItemAndPrice->price_when_bought
            );
        }

        // Return the summary of order items
        return $orderSummary;
    }

    /**
     * Format the order items for display.
     *
     * @param array $orderItems An array of order item objects to format.
     * @return array The formatted order items.
     */
    private static function formatOrderItems($orderItems)
    {
        $formattedOrderItems = [];

        for ($i = 0; $i < sizeof($orderItems); $i++) {
            // Clone the order item to avoid modifying the original object
            $orderItem = clone $orderItems[$i];
            
            // Set a default value for menu_item_name if it's null
            $orderItem->menu_item_name = $orderItem->menu_item_name ?? 'Deleted Item';
            
            // Format item order date and time for display
            $orderItem->formatted_item_order_date = Utils::getDateFromDateTime($orderItem->item_order_time);
            $orderItem->formatted_item_order_time = Utils::getTimeFromDateTime($orderItem->item_order_time);
            
            // Add the formatted order item to the array
            $formattedOrderItems[] = $orderItem;
        }

        // Return the array of formatted order items
        return $formattedOrderItems;
    }

    /**
     * Get formatted order items of the order.
     *
     * @param object $order The order object to get the formatted order items for.
     * @return array The formatted order items.
     */
    private static function getFormattedOrderItemsOfOrder($order)
    {
        // Retrieve order items of the order with additional information
        $orderItems = OrderItemRepository::getOrderItemsOfOrder($order->order_id, TRUE, TRUE);

        // Return the formatted order items
        return self::formatOrderItems($orderItems);;
    }

    /**
     * Get complete details of the provided order including base information, order summary, and formatted order items.
     *
     * @param object $order The order object to retrieve complete details for.
     * @return object The order object with complete details.
     */
    private static function getOrderCompleteDetails($order)
    {
        $orderWithBaseInformation = self::appendRelevantInformationOfOrder($order);
        $orderWithBaseInformation->order_summary = self::getOrderItemsSummary($order);
        $orderWithBaseInformation->order_items = self::getFormattedOrderItemsOfOrder($order);
        return $orderWithBaseInformation;
    }

    /**
     * Handle the retrieval of detailed information for a specific order requested by a customer.
     *
     * @param object $user The user object representing the logged-in customer.
     * @param string $orderID The ID of the order to retrieve details for.
     * @return array An array containing detailed information of the requested order.
     * @throws ObjectNotFoundException If the order matching the provided ID does not exist.
     * @throws NotAuthorizedException If the user is not authorized to access the order.
     */
    public static function handleCustomerGetOrderDetail($user, $orderID)
    {
        // Retrieve the order by its ID or throw an exception if not found
        $order = OrderRepository::getOrderByIDOrThrowException($orderID);
        
        // Validate if the logged-in user is the owner of the order, otherwise throw an exception
        self::validateCustomerOrderOwnership($user, $order);
        
        // Get complete details of the order (base information, order summary, formatted order items)
        $orderWithCompleteDetails = self::getOrderCompleteDetails($order);

        // Return an array containing detailed information of the requested order
        return [
            'order' => $orderWithCompleteDetails,
        ];
    }

    /**
     * Transform request data for processing business order list.
     *
     * @param array $requestData The request data containing table_number, status_id, and page parameters.
     * @return array Transformed request data.
     */
    private static function transformBusinessOrderListRequestData($requestData)
    {
        // Initialize transformed request data array with default values
        $transformedRequestData = [
            'table_number' => NULL,
            'status_id' => NULL,
            'page' => (int) ($requestData['page'] ?? 1),
        ];

        // Check if table_number parameter exists and is numeric, then transform it
        if (array_key_exists('table_number', $requestData) && is_numeric($requestData['table_number'])) {
            $transformedRequestData['table_number'] = (int) $requestData['table_number'];
        }

        // Check if status_id parameter exists and is numeric, then transform it
        if (array_key_exists('status_id', $requestData) && is_numeric($requestData['status_id'])) {
            $transformedRequestData['status_id'] = (int) $requestData['status_id'];
        }

        // Return the transformed request data
        return $transformedRequestData;
    }

    /**
     * Handle the retrieval of a paginated list of orders for a business.
     *
     * @param object $user The user object representing the logged-in business.
     * @param array $requestData The request data containing table_number, status_id, and page parameters.
     * @return array An array containing orders data, pager, statuses, search term, and selected status ID.
     */
    public static function handleBusinessGetOrderList($user, $requestData)
    {
        // Transform request data for processing
        $transformedRequestData = self::transformBusinessOrderListRequestData($requestData);
        
        // Retrieve paginated orders of the business based on transformed request data
        $businessOrdersPaginated = OrderRepository::getPaginatedOrdersOfBusiness(
            $user->business_id,
            $transformedRequestData['status_id'],
            $transformedRequestData['table_number'],
            10,
            $transformedRequestData['page'],
        );

        // Append related information to each order in the paginated result
        $businessCompleteOrderData = self::appendRelatedInformationOfOrders($businessOrdersPaginated['result']);

        // Retrieve all order statuses
        $allStatus = OrderRepository::getAllOrderStatus();

        // Construct and return the response array
        return [
            'orders' => $businessCompleteOrderData,
            'pager' => $businessOrdersPaginated['pager'],
            'statuses' => $allStatus,
            'search' => $requestData['table_number'] ?? '',
            'selected_status_id' => $requestData['status_id'] ?? '',
        ];
    }

    /**
     * Validate if the provided business is the owner of the given order.
     *
     * @param string $businessID The ID of the business to validate ownership against.
     * @param object $order The order object to validate ownership for.
     * @throws NotAuthorizedException If the provided business is not the owner of the order.
     */
    private static function validateBusinessOrderOwnership($businessID, $order)
    {
        if ($order->receiving_business_id !== $businessID) {
            throw new NotAuthorizedException("Business is not the owner of order with ID {$order->order_id}");
        }
    }

    /**
     * Handle the retrieval of detailed information for a specific order when requested by a business.
     *
     * @param object $user The user object representing the logged-in business.
     * @param string $orderID The ID of the order to retrieve details for.
     * @return array An array containing detailed information of the requested order.
     * @throws ObjectNotFoundException If the order matching the provided ID does not exist.
     * @throws NotAuthorizedException If the business is not authorized to access the order.
     */
    public static function handleBusinessGetOrderDetails($user, $orderID)
    {
        // Retrieve the order by its ID or throw an exception if not found
        $order = OrderRepository::getOrderByIDOrThrowException($orderID);
        
        // Validate if the business is the owner of the order
        self::validateBusinessOrderOwnership($user->business_id, $order);
        
        // Get complete details of the order (base information, order summary, formatted order items)
        $orderWithCompleteDetails = self::getOrderCompleteDetails($order);

        // Return an array containing detailed information of the requested order
        return [
            'order' => $orderWithCompleteDetails,
        ];
    }

    /**
     * Validate if the order can be marked as completed.
     *
     * @param object $order The order object to validate.
     * @param object $completedOrderStatus The completed order status object.
     * @throws InvalidRequestException If the order is already completed.
     */
    private static function validateOrderCompletion($order, $completedOrderStatus)
    {
        if ($order->order_status_id === $completedOrderStatus->id) {
            throw new InvalidRequestException("Order with ID {$order->order_id} is already completed");
        }
    }

    /**
     * Mark the order as completed.
     *
     * @param object $order The order object to mark as completed.
     * @throws InvalidRequestException If the order is already completed.
     */
    private static function completeOrder($order)
    {
        // Retrieve the completed order status
        $completedOrderStatus = OrderRepository::getOrderStatusByName('Completed');
        
        // Validate if the order can be marked as completed
        self::validateOrderCompletion($order, $completedOrderStatus);
        
        // Update the order status to completed and set the completion time
        OrderRepository::updateOrder(
            $order->order_id,
            [
                'order_status_id' => $completedOrderStatus->id,
                'order_completion_time' => Utils::getCurrentTime(),
            ]
        );
    }

    /**
     * Handle the completion of an order requested by a business.
     *
     * @param object $user The user object representing the logged-in business, initiating the status update.
     * @param array $requestData The request data containing the ID of the order to complete.
     * @throws ObjectNotFoundException If the order matching the provided ID does not exist.
     * @throws NotAuthorizedException If the business is not authorized to complete the order.
     * @throws InvalidRequestException If the order is already completed.
     */
    public static function handleBusinessCompleteOrder($user, $requestData)
    {
        // Retrieve the order by its ID from the repository or throw an exception if not found
        $order = OrderRepository::getOrderByIDOrThrowException($requestData['order_id'] ?? '');
        
        // Validate if the business is the owner of the order, otherwise throw an exception
        self::validateBusinessOrderOwnership($user->business_id, $order);
        
        // Mark the order as completed
        self::completeOrder($order);
    }

    /**
     * Handle the retrieval of kitchen data for orders received by the business.
     *
     * @param object $user The user object representing the logged-in business.
     * @return array An array containing kitchen data including order item summary, order items, and order item statuses.
     */
    public static function handleBusinessGetOrderKitchenData($user)
    {
        // Retrieve order item summary of the business
        $businessOrderItemSummary = OrderItemRepository::getOrderItemsSummaryOfBusiness($user->business_id);
        
        // Retrieve order items of the business
        $businessOrderItems = OrderItemRepository::getOrderItemsOfBusiness($user->business_id);
        
        // Format order items for display
        $businessOrderItems = self::formatOrderItems($businessOrderItems);
        
        // Retrieve all order item statuses
        $allStatus = OrderItemRepository::getAllOrderItemStatus();

        // Construct and return the response array
        return [
            'order_item_summary' => $businessOrderItemSummary,
            'order_items' => $businessOrderItems,
            'order_item_statuses' => $allStatus,
        ];
    }

    /**
     * Validate the update of an order item status.
     *
     * @param object $orderItem The order item object to validate the status update for.
     * @param int $newOrderItemStatusID The ID of the new order item status.
     * @throws InvalidRequestException If the order item status update is not valid.
     */
    private static function validateOrderItemStatusUpdate($orderItem, $newOrderItemStatusID)
    {
        // Retrieve the current order item status and the new order item status
        $currentOrderItemStatus = OrderItemRepository::getOrderItemStatusByIDOrThrowException($orderItem->order_item_id);
        $newOrderItemStatus = OrderItemRepository::getOrderItemStatusByIDOrThrowException($newOrderItemStatusID);

        // Validate the update based on the current and new order item statuses
        if ($currentOrderItemStatus->status === "received" && $newOrderItemStatus->status !== "being prepared") {
            throw new InvalidRequestException("Order item with status 'received' can only be updated to 'being prepared'");

        } elseif ($currentOrderItemStatus->status === "being prepared" && $newOrderItemStatus->status !== "served") {
            throw new InvalidRequestException("Order item with status 'being prepared' can only be updated to 'served'");

        } elseif ($currentOrderItemStatus->status === "served") {
            throw new InvalidRequestException("Order item with status 'served' can no longer be modified");
        }
    }

    /**
     * Handle the update of an order item status requested by a business.
     *
     * @param object $user The user object representing the logged-in business, initiating the status update.
     * @param array $updateData The update data containing the ID of the order item and the new status ID.
     * @throws ObjectNotFoundException If the order item matching the provided ID does not exist.
     * @throws NotAuthorizedException If the business is not authorized to update the order item status.
     * @throws InvalidRequestException If the order item status update is not valid.
     */
    public static function handleBusinessUpdateOrderItemStatus($user, $updateData)
    {
        // Start a database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        // Retrieve the order item by its ID or throw an exception if not found
        $orderItem = OrderItemRepository::getOrderItemByIDOrThrowException($updateData['order_item_id'] ?? '');

        // Retrieve the order of the item
        $orderOfItem = OrderRepository::getOrderByID($orderItem->order_id);

        // Validate if the business is the owner of the order, otherwise throw an exception
        self::validateBusinessOrderOwnership($user->business_id, $orderOfItem);

        // Validate the update of the order item status
        self::validateOrderItemStatusUpdate($orderItem, $updateData['new_status_id'] ?? '');

        // Update the order item status
        OrderItemRepository::updateOrderItem($orderItem->order_item_id, ['order_item_status_id' =>  $updateData['new_status_id']]);

        // Set the overall order status based on the updated status of its items
        self::setOrderStatus($orderOfItem->order_id);

        // Complete the database transaction
        $db->transComplete();
    }
}
