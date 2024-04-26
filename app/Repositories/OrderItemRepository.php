<?php

namespace App\Repositories;

use App\Models\OrderItemModel;
use App\Models\OrderItemStatusModel;
use App\Utils\Utils;

class OrderItemRepository
{
    public static function getOrderItemStatusByName($statusName) {
        $orderItemStatus = new OrderItemStatusModel();
        return $orderItemStatus->where('status', $statusName)->first();
    }

    public static function createOrderItemModel($orderItemData) {
        $orderItem = new OrderItemModel();
        $receivedOrderItemStatus = self::getOrderItemStatusByName('received');
        
        $orderItemID = Utils::generateUUID();
        $orderItem->insert([
            ...$orderItemData,
            'order_item_id' => $orderItemID,
            'item_order_time' => date('c'),
            'order_item_status_id' => $receivedOrderItemStatus->id,
        ]);

        return $orderItemID;
    }

    public static function getOrderItemsOfOrder($orderID, $ordered = FALSE, $completeData = FALSE) {
        $orderItem = new OrderItemModel();
        $query = $orderItem->where('order_id', $orderID);

        if ($completeData) {
            $query = $query
                        ->select('order_items.order_item_id, order_items.num_of_items, order_items.item_order_time, order_items.order_item_status_id, order_items.notes, order_items.order_id, order_items.menu_item_id, order_items.price_when_bought, menu_items.name AS menu_item_name, order_item_statuses.status AS status_name')
                        ->join('menu_items', 'order_items.menu_item_id=menu_items.menu_item_id')
                        ->join('order_item_statuses', 'order_items.order_item_status_id=order_item_statuses.id');
        }
        
        if ($ordered) {
            $query = $query->orderBy('item_order_time', 'DESC');
        }

        return $query->findAll();
    }

    public static function getDistinctCombinationOfMenuItemIDAndPriceOfOrder($orderID) {
        $orderItem = new OrderItemModel();
        return $orderItem
                ->select('order_items.menu_item_id, order_items.price_when_bought, menu_items.name AS menu_item_name')
                ->join('menu_items', 'order_items.menu_item_id=menu_items.menu_item_id')
                ->where('order_id', $orderID)
                ->distinct()
                ->findAll();
    }

    public static function getOrderItemsOfOrderMatchingMenuItemIDAndPrice($orderID, $menuItemID, $price) {
        $orderItem = new OrderItemModel();
        return $orderItem
                    ->where('order_id', $orderID)
                    ->where('menu_item_id', $menuItemID)
                    ->where('price_when_bought', $price)
                    ->findAll();
    }
}