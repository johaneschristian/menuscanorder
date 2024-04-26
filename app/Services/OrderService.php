<?php 

namespace App\Services;

use App\CustomExceptions\InvalidRegistrationException;
use App\Repositories\BusinessRepository;
use App\Repositories\MenuRepository;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderRepository;
use App\Utils\Utils;

class OrderService 
{
    public static function handleGetBusinessMenus($businessID) {
        $business = BusinessRepository::getBusinessByIdOrThrowException($businessID);
        $categories_and_menu = MenuRepository::getMenuItemsOfBusinessGroupByCategory($businessID, "");

        return [
            'business' => $business,
            'categories_and_menu' => $categories_and_menu
        ];
    }

    private static function validateOrderCreation($business, $orderData) {
        if (!$business->is_open) {
            throw new InvalidRegistrationException("Business with ID {$business->business_id} is currently closed");

        } else if (!array_key_exists('table_number', $orderData) || !is_int($orderData['table_number'])) {
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
                'menu_item' => MenuRepository::getMenuByIDOrThrowException($selectedMenu['menu_item_id']),
            ];
        }

        return $convertedSelectedMenus;
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
        // Change to New Order, In-Progress, Completed
        $itemsOfOrder = OrderItemRepository::getOrderItemsOfOrder($orderID);
        $receivedOrderItemStatus = OrderItemRepository::getOrderItemStatusByName('received');
        $servedOrderItemStatus = OrderItemRepository::getOrderItemStatusByName('served');
        
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
            $subtotal = $selectedMenu['menu_item']->price * $selectedMenu['quantity'];
            OrderItemRepository::createOrderItemModel([
                'num_of_items' => $selectedMenu['quantity'],
                'subtotal' => $subtotal,
                'notes' => $selectedMenu['notes'],
                'order_id' => $orderID,
                'menu_item_id' => $selectedMenu['menu_item']->menu_item_id,
            ]);
            $totalPrice += $subtotal;
        }

        self::recomputeOrderTotalPrice($orderID, $totalPrice);
        self::setOrderStatus($orderID);       
    }

    public static function handleCreateOrder($user, $orderData) {
        $receivingBusiness = BusinessRepository::getBusinessByIdOrThrowException($orderData['business_id'] ?? NULL);
        self::validateOrderCreation($receivingBusiness, $orderData);
        $convertedSelectedMenus = self::convertSelectedMenu($orderData['selected_menus']);
        $modifiedOrderID = self::getOrCreateOrder($user, $receivingBusiness, $orderData['table_number']);  
        self::registerOrderItem($modifiedOrderID, $convertedSelectedMenus);      
    }

    private static function transformOrderListRequestData($requestData) {
        $transformedRequestData = [
            'business_ids' => NULL,
            'status_id' => NULL,
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
        return $order;
    }

    private static function appendRelatedInformationOfOrders($orders) {
        for ($orderIndex = 0; $orderIndex < sizeof($orders); $orderIndex++) {
            $orders[$orderIndex] = self::appendRelevantInformationOfOrder($orders[$orderIndex]);
        }

        return $orders;
    }

    public static function handleCustomerOrderList($user, $requestData) {
        $transformedRequestData = self::transformOrderListRequestData($requestData);
        $userOrders = OrderRepository::getOrdersOfUser(
            $user->id, 
            $transformedRequestData['business_ids'], 
            $transformedRequestData['status_id'],
        );
        
        $userCompleteOrderData = self::appendRelatedInformationOfOrders($userOrders);
        $allStatus = OrderRepository::getAllOrderStatus();

        return [
            'orders' => $userCompleteOrderData,
            'statuses' => $allStatus,
            'search' => $requestData['business_name'] ?? '',
            'selected_status_id' => $requestData['status_id'] ?? '',
        ];
    }
}

