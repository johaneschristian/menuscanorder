<?php 

namespace App\Services;

use App\Repositories\BusinessRepository;
use App\Repositories\MenuRepository;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderRepository;

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
        // TODO: Implement validation
        // Validate business is open, selected_menus is an array, table number is within business range, 
        // Validate quantity is greater than 0 and note is a string
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

    private static function setOrderStatus($orderID) {
        // Get all items of order
        // If all are received -> order status is received
        // If all are served -> order status is served
        // Else -> order status is being prepared
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
}

