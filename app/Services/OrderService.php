<?php 

namespace App\Services;

use App\Repositories\BusinessRepository;
use App\Repositories\MenuRepository;
use OrderRepository;

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
        // Validate business is open, selected_menus is an array, table number is within business range, 
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
        // Get customer latest order in the restaurant, 
        //  if the latest order is completed 
        //      Create order set total price to 0 
        //  else
        //      return order

        return OrderRepository::createOrder($submittingUser, $receivingBusiness, $tableNumber);
    }

    public static function handleCreateOrder($user, $orderData) {
        $receivingBusiness = BusinessRepository::getBusinessByIdOrThrowException($orderData['business_id'] ?? NULL);
        self::validateOrderCreation($receivingBusiness, $orderData);
        $convertedSelectedMenus = self::convertSelectedMenu($orderData['selected_menus']);
        $modifiedOrderID = self::getOrCreateOrder($user, $receivingBusiness, $orderData['table_number']);        
        
        // Create order item for each of selected menu, with subtotal quantity
        // Compute total price, add to order current total
    }
}

