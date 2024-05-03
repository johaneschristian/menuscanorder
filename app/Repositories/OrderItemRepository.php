<?php

namespace App\Repositories;

use App\CustomExceptions\ObjectNotFoundException;
use App\Models\OrderItemModel;
use App\Models\OrderItemStatusModel;
use App\Utils\Utils;

class OrderItemRepository
{
    public static function getAllOrderItemStatus() {
        $orderItemStatus = new OrderItemStatusModel();
        return $orderItemStatus->findAll();
    }

    public static function getOrderItemStatusByID($orderItemStatusID) {
        $orderItemStatus = new OrderItemStatusModel();
        return $orderItemStatus->where('id', $orderItemStatusID);
    }

    public static function getOrderItemStatusByIDOrThrowException($orderItemStatusID) {
        $foundOrderItemStatus = self::getOrderItemStatusByID($orderItemStatusID);

        if (is_null($foundOrderItemStatus)) {
            throw new ObjectNotFoundException("Order item status with ID $orderItemStatusID does not exist");

        } else {
            return $foundOrderItemStatus;
        }
    }

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
            'item_order_time' => Utils::getCurrentTime(),
            'order_item_status_id' => $receivedOrderItemStatus->id,
        ]);

        return $orderItemID;
    }

    public static function getOrderItemsByID($orderItemID) {
        $orderItem = new OrderItemModel();
        return $orderItem->where('order_item_id', $orderItemID)->first();
    }

    public static function getOrderItemByIDOrThrowException($orderItemID) {
        $foundOrderItem = self::getOrderItemsByID($orderItemID);

        if (is_null($foundOrderItem)) {
            throw new ObjectNotFoundException("Order Item with ID $orderItemID does not exist");

        } else {
            return $foundOrderItem;
        }
    }

    public static function updateOrderItem($orderItemID, $newData) {
        $orderItem = new OrderItemModel();
        return $orderItem->update($orderItemID, $newData);
    }

    public static function getOrderItemsOfOrder($orderID, $ordered = FALSE, $completeData = FALSE) {
        $orderItem = new OrderItemModel();
        $query = $orderItem->where('order_id', $orderID);

        if ($completeData) {
            $query = $query
                        ->select('order_items.order_item_id, order_items.num_of_items, order_items.item_order_time, order_items.order_item_status_id, order_items.notes, order_items.order_id, order_items.menu_item_id, order_items.price_when_bought, menu_items.name AS menu_item_name, order_item_statuses.status AS status_name')
                        ->join('menu_items', 'order_items.menu_item_id=menu_items.menu_item_id', 'left')
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
                ->join('menu_items', 'order_items.menu_item_id=menu_items.menu_item_id', 'left')
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

    public static function getOrderItemsSummaryOfBusiness($businessID) {
        $orderItem = new OrderItemModel();
        return $orderItem
                    ->select('order_items.order_item_status_id , order_item_statuses.status AS status_name, SUM(order_items.num_of_items) AS total_quantity')
                    ->join('orders', 'order_items.order_id=orders.order_id')
                    ->join('order_item_statuses', 'order_items.order_item_status_id=order_item_statuses.id')
                    ->where('orders.receiving_business_id', $businessID)
                    ->groupBy('order_items.order_item_status_id, order_item_statuses.status')
                    ->findAll();
    }

    public static function getOrderItemsOfBusiness($businessID) {
        $orderItem = new OrderItemModel();
        $query = $orderItem
                    ->select('order_items.order_item_id, order_items.num_of_items, order_items.item_order_time, order_items.order_item_status_id, order_item_statuses.status AS status_name, order_items.notes, order_items.order_id, orders.table_number, order_items.menu_item_id, menu_items.name AS menu_item_name, order_items.price_when_bought')
                    ->join('orders', 'order_items.order_id=orders.order_id')
                    ->join('order_item_statuses', 'order_items.order_item_status_id=order_item_statuses.id')
                    ->join('menu_items', 'order_items.menu_item_id=menu_items.menu_item_id', 'left')
                    ->where('orders.receiving_business_id', $businessID)
                    ->whereNotIn('order_item_statuses.status', ['served'])
                    ->orderBy('order_items.item_order_time', 'DESC');
                    
        return $query->findAll();
    }
}