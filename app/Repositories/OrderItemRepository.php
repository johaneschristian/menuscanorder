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

    public static function getOrderItemsOfOrder($orderID) {
        $orderItem = new OrderItemModel();
        return $orderItem->where('order_id', $orderID)->findAll();
    }
}