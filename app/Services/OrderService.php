<?php 

namespace App\Services;

use App\CustomExceptions\InvalidRegistrationException;
use App\Exceptions\NotAuthorizedException;
use App\Repositories\BusinessRepository;
use App\Repositories\MenuRepository;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderRepository;
use App\Utils\Utils;

class OrderService 
{
    public static function handleGetBusinessMenus($businessID) {
        $business = BusinessRepository::getBusinessByIdOrThrowException($businessID);
        $categories_and_menu = MenuRepository::getMenuItemsOfBusinessGroupByCategory($businessID, TRUE);

        return [
            'business' => $business,
            'categories_and_menu' => $categories_and_menu
        ];
    }

    private static function validateOrderCreation($business, $orderData) {
        if (!$business->is_open) {
            throw new InvalidRegistrationException("Business with ID {$business->business_id} is currently closed");

        } else if (!array_key_exists('table_number', $orderData) || !is_numeric($orderData['table_number'])) {
            throw new InvalidRegistrationException("Order must include table number");

        } else if (!array_key_exists('selected_menus', $orderData)) {
            throw new InvalidRegistrationException("Order must include selected menus");

        } else if (!is_array($orderData['selected_menus'])) {
            throw new InvalidRegistrationException("Selected menus must be an array");

        } else if ($orderData['table_number'] > $business->num_of_tables) {
            throw new InvalidRegistrationException(sprintf("Business does not have table %", $orderData['table_number']));
        }

        foreach ($orderData['selected_menus'] as $selectedMenu) {
            if (!array_key_exists('quantity', $selectedMenu) || $selectedMenu['quantity'] <= 0) {
                throw new InvalidRegistrationException("Each selected menu must have a numeric quantity greater than 0");

            } else if (array_key_exists('notes', $selectedMenu) && !is_string($selectedMenu['notes'])) {
                throw new InvalidRegistrationException("Notes must be a string");
            }
        }
    }

    private static function convertSelectedMenu($selectedMenus) {
        $convertedSelectedMenus = [];

        foreach ($selectedMenus as $selectedMenu) {
            $convertedSelectedMenus[] = [
                ...$selectedMenu,
                'notes' => (!is_string($selectedMenu['notes']) || trim($selectedMenu['notes']) === "") ? NULL : trim($selectedMenu['notes']),
                'menu_item' => MenuRepository::getMenuByIDOrThrowException($selectedMenu['menu_item_id']),
            ];
        }

        return $convertedSelectedMenus;
    }

    private static function validateAllSelectedMenuAreAvailable($selectedMenus) {
        foreach ($selectedMenus as $selectedMenu) {
            if(!$selectedMenu['menu_item']->is_available) {
                throw new InvalidRegistrationException("Menu with ID {$selectedMenu->menu_item_id} is currently not available");
            }
        }
    }

    private static function getOrCreateOrder($submittingUser, $receivingBusiness, $tableNumber) {
        $userLatestUncompleteOrderInBusiness = OrderRepository::getLatestUncompleteOrderOfCustomerInBusiness(
            $submittingUser->id,
            $receivingBusiness->business_id,
            $tableNumber
        );

        if ($userLatestUncompleteOrderInBusiness !== NULL) {
            return $userLatestUncompleteOrderInBusiness->order_id;

        } else {
            return OrderRepository::createOrder($submittingUser, $receivingBusiness, $tableNumber);
        }
    }

    private static function recomputeOrderTotalPrice($orderID, $addedSubtotal) {
        $order = OrderRepository::getOrderByID($orderID);
        $orderNewTotal = $order->total_price + $addedSubtotal;
        OrderRepository::updateOrderTotalPrice($orderID, $orderNewTotal);
    }

    private static function computeOrderStatus($orderID) {
        $itemsOfOrder = OrderItemRepository::getOrderItemsOfOrder($orderID);
        $receivedOrderItemStatus = OrderItemRepository::getOrderItemStatusByName('received');
        
        $allAreReceived = TRUE;
        foreach ($itemsOfOrder as $itemOfOrder) {
            if ($itemOfOrder->order_item_status_id !== $receivedOrderItemStatus->id) {
                $allAreReceived = FALSE;
                break;
            }
        }

        if ($allAreReceived) {
            return OrderRepository::getOrderStatusByName('New Order');

        } else {
            return OrderRepository::getOrderStatusByName('In-Progress');

        }
    }

    private static function setOrderStatus($orderID) {
        $orderStatus = self::computeOrderStatus($orderID);
        OrderRepository::setOrderStatus($orderID, $orderStatus->id);
    }

    private static function registerOrderItem($orderID, $selectedMenus) {
        $totalPrice = 0;
        foreach($selectedMenus as $selectedMenu) {
            OrderItemRepository::createOrderItemModel([
                'num_of_items' => $selectedMenu['quantity'],
                'price_when_bought' => $selectedMenu['menu_item']->price,
                'notes' => $selectedMenu['notes'],
                'order_id' => $orderID,
                'menu_item_id' => $selectedMenu['menu_item']->menu_item_id,
            ]);

            $subtotal = $selectedMenu['menu_item']->price * $selectedMenu['quantity'];
            $totalPrice += $subtotal;
        }

        self::recomputeOrderTotalPrice($orderID, $totalPrice);
        self::setOrderStatus($orderID);       
    }

    public static function handleCreateOrder($user, $orderData) {
        $receivingBusiness = BusinessRepository::getBusinessByIdOrThrowException($orderData['business_id'] ?? NULL);
        self::validateOrderCreation($receivingBusiness, $orderData);
        $convertedSelectedMenus = self::convertSelectedMenu($orderData['selected_menus']);
        self::validateAllSelectedMenuAreAvailable($convertedSelectedMenus);
        $modifiedOrderID = self::getOrCreateOrder($user, $receivingBusiness, $orderData['table_number']);  
        self::registerOrderItem($modifiedOrderID, $convertedSelectedMenus);      
    }

    private static function transformCustomerOrderListRequestData($requestData) {
        $transformedRequestData = [
            'business_ids' => NULL,
            'status_id' => NULL,
            'page' => (int) ($requestData['page'] ?? 1),
        ];

        if (array_key_exists('business_name', $requestData) && trim($requestData['business_name']) !== '') {
            $businessesMatchingName = BusinessRepository::getBusinessesMatchingName(trim($requestData['business_name']));
            $businessesIDMatchingName = array_map(function ($business) { return $business->business_id; }, $businessesMatchingName);
            $transformedRequestData['business_ids'] = $businessesIDMatchingName;
        }

        if (array_key_exists('status_id', $requestData) && is_numeric($requestData['status_id'])) {
            $transformedRequestData['status_id'] = (int) $requestData['status_id'];
        }

        return $transformedRequestData;
    }

    private static function appendRelevantInformationOfOrder($order) {
        $order->business_name = BusinessRepository::getBusinessById($order->receiving_business_id)->business_name;
        $order->status_name = OrderRepository::getOrderStatusByID($order->order_status_id)->status;
        $order->duration = Utils::calculateDuration($order->order_creation_time, $order->order_completion_time ?? date("c"));
        $order->start_date = Utils::getDateFromDateTime($order->order_creation_time);
        $order->formatted_creation_time = Utils::formatDateTimeForDisplay($order->order_creation_time);
        return $order;
    }

    private static function appendRelatedInformationOfOrders($orders) {
        for ($orderIndex = 0; $orderIndex < sizeof($orders); $orderIndex++) {
            $orders[$orderIndex] = self::appendRelevantInformationOfOrder($orders[$orderIndex]);
        }

        return $orders;
    }

    public static function handleCustomerOrderList($user, $requestData) {
        $transformedRequestData = self::transformCustomerOrderListRequestData($requestData);
        $userOrdersPaginated = OrderRepository::getPaginatedOrdersOfUser(
            $user->id, 
            $transformedRequestData['business_ids'], 
            $transformedRequestData['status_id'],
            10,
            $transformedRequestData['page'],
        );
        
        $userCompleteOrderData = self::appendRelatedInformationOfOrders($userOrdersPaginated['result']);
        $allStatus = OrderRepository::getAllOrderStatus();

        return [
            'orders' => $userCompleteOrderData,
            'pager' => $userOrdersPaginated['pager'],
            'statuses' => $allStatus,
            'search' => $requestData['business_name'] ?? '',
            'selected_status_id' => $requestData['status_id'] ?? '',
        ];
    }

    private static function validateCustomerOrderOwnership($user, $order) {
        // No-type safe is used as submitting_user_id is unexpectedly converted to string by PHP
        if ($order->submitting_user_id != $user->id) {
            throw new NotAuthorizedException("User is not the owner of order with ID {$order->order_id}");
        }
    }

    private static function createOrderMenuItemIDAndPriceSummary($order, $menuItemID, $menuItemName, $price) {
        // Get all order items matching them belonging to the same ORDER ID of menu item ID and price
        // Have an aggregate summary:
        // Get total quantity X
        // Get individual item price X
        // Set notes as an array containing multiple notes X
        // Set subtotal X

        $orderItemSummary = [
            'menu_item_id' => $menuItemID,
            'menu_item_name' => $menuItemName,
            'num_of_items' => 0,
            'price_when_bought' => $price,
            'subtotal' => 0,
            'notes' => []
        ];

        $orderItems = OrderItemRepository::getOrderItemsOfOrderMatchingMenuItemIDAndPrice($order->order_id, $menuItemID, $price);
        foreach ($orderItems as $orderItem) {
            $orderItemSummary['num_of_items'] += $orderItem->num_of_items;
            $orderItemSummary['subtotal'] += $orderItem->num_of_items * $price;
            
            if ($orderItem->notes !== NULL) {
                $orderItemSummary['notes'][] = $orderItem->notes;
            }
        }

        return $orderItemSummary;        
    }

    private static function getOrderItemsSummary($order) {
        $uniqueCombinationOfMenuItemAndPrice = OrderItemRepository::getDistinctCombinationOfMenuItemIDAndPriceOfOrder($order->order_id);
        
        $orderSummary = [];
        foreach($uniqueCombinationOfMenuItemAndPrice as $menuItemAndPrice) {
            $orderSummary[] = self::createOrderMenuItemIDAndPriceSummary(
                $order, 
                $menuItemAndPrice->menu_item_id,
                $menuItemAndPrice->menu_item_name,
                $menuItemAndPrice->price_when_bought
            );
        }

        return $orderSummary;
    }

    private static function getFormattedOrderItemsOfOrder($order) {
        $orderItems = OrderItemRepository::getOrderItemsOfOrder($order->order_id, TRUE, TRUE);

        foreach($orderItems as $orderItem) {
            $orderItem->formatted_item_order_date = Utils::getDateFromDateTime($orderItem->item_order_time);
            $orderItem->formatted_item_order_time = Utils::getTimeFromDateTime($orderItem->item_order_time);
        }

        return $orderItems;
    }

    private static function getOrderCompleteDetails($order) {
        $orderWithBaseInformation = self::appendRelevantInformationOfOrder($order);
        $orderWithBaseInformation->order_summary = self::getOrderItemsSummary($order);
        $orderWithBaseInformation->order_items = self::getFormattedOrderItemsOfOrder($order);
        return $orderWithBaseInformation;
    }

    public static function handleCustomerOrderDetail($user, $orderID) {
        $order = OrderRepository::getOrderByIDOrThrowException($orderID);
        self::validateCustomerOrderOwnership($user, $order);
        $orderWithCompleteDetails = self::getOrderCompleteDetails($order);

        return [
            'order' => $orderWithCompleteDetails,
        ];
    }

    private static function transformBusinessOrderListRequestData($requestData) {
        $transformedRequestData = [
            'table_number' => NULL,
            'status_id' => NULL,
            'page' => (int) ($requestData['page'] ?? 1), 
        ];

        if (array_key_exists('table_number', $requestData) && is_numeric($requestData['table_number'])) {
            $transformedRequestData['table_number'] = (int) $requestData['table_number'];
        }

        if (array_key_exists('status_id', $requestData) && is_numeric($requestData['status_id'])) {
            $transformedRequestData['status_id'] = (int) $requestData['status_id'];
        }

        return $transformedRequestData;
    }

    public static function handleBusinessOrderList($user, $requestData) {
        $transformedRequestData = self::transformBusinessOrderListRequestData($requestData);
        $userBusiness = BusinessRepository::getBusinessByUserIdOrThrowException($user->id);
        $businessOrdersPaginated = OrderRepository::getPaginatedOrdersOfBusiness(
            $userBusiness->business_id,
            $transformedRequestData['status_id'],
            $transformedRequestData['table_number'],
            10,
            $transformedRequestData['page'],
        );

        $businessCompleteOrderData = self::appendRelatedInformationOfOrders($businessOrdersPaginated['result']);
        $allStatus = OrderRepository::getAllOrderStatus();

        return [
            'orders' => $businessCompleteOrderData,
            'pager' => $businessOrdersPaginated['pager'],
            'statuses' => $allStatus,
            'search' => $requestData['table_number'] ?? '',
            'selected_status_id' => $requestData['status_id'] ?? '',
        ];
    }
}

