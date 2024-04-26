<?php

namespace App\Repositories;

use App\CustomExceptions\ObjectNotFoundException;
use App\Models\OrderModel;
use App\Models\OrderStatusModel;
use App\Utils\Utils;

class OrderRepository
{
    public static function getAllOrderStatus() {
        $orderStatus = new OrderStatusModel();
        return $orderStatus->findAll();
    }

    public static function getOrderStatusByName($statusName) {
        $orderStatus = new OrderStatusModel();
        return $orderStatus->where('status', $statusName)->first();
    }

    public static function getOrderStatusByID($statusID) {
        $orderStatus = new OrderStatusModel();
        return $orderStatus->where('id', $statusID)->first();
    }

    public static function createOrder($submittingUser, $receivingBusiness, $tableNumber) {
        $order = new OrderModel();
        $receivedOrderStatus = self::getOrderStatusByName('received');
        
        $orderID = Utils::generateUUID();
        $order->insert([
            'order_id' => $orderID,
            'order_creation_time' => date('c'),
            'order_status_id' => $receivedOrderStatus->id,
            'table_number' => $tableNumber,
            'submitting_user_id' => $submittingUser->id,
            'receiving_business_id' => $receivingBusiness->business_id,
        ]);

        return $orderID;
    }

    public static function getOrderByID($orderID) {
        $order = new OrderModel();
        return $order->where('order_id', $orderID)->first();
    }

    
    public static function getOrderByIDOrThrowException($orderID) {
        $foundOrder = self::getOrderByID($orderID);

        if ($foundOrder === NULL) {
            throw new ObjectNotFoundException("Order with ID $orderID does not exist");

        } else {
            return $foundOrder;
        }
    }

    public static function getLatestUncompleteOrderOfCustomerInBusiness($submittingUserID, $businessID, $tableNumber) {
        $order = new OrderModel();
        $completedOrderStatus = self::getOrderStatusByName('completed');
        return $order->where('submitting_user_id', $submittingUserID)
                     ->where('receiving_business_id', $businessID)
                     ->where('table_number', $tableNumber)
                     ->whereNotIn('order_status_id', [$completedOrderStatus->id])
                     ->orderBy('order_creation_time', 'DESC')
                     ->first();
    }

    public static function updateOrderTotalPrice($orderID, $totalPrice) {
        $order = new OrderModel();
        $order->where('order_id', $orderID)
              ->set('total_price', $totalPrice)
              ->update();
    }

    public static function setOrderStatus($orderID, $statusID) {
        $order = new OrderModel();
        $order->where('order_id', $orderID)
              ->set('order_status_id', $statusID)
              ->update();
    }

    public static function getOrdersOfUser($submittingUserID, $businessesID, $statusID) {
        $order = new OrderModel();
        $query = $order->where('submitting_user_id', $submittingUserID);

        if ($businessesID !== NULL) {
            $query = $query->whereIn('receiving_business_id', $businessesID);
        }

        if ($statusID !== NULL) {
            $query = $query->where('order_status_id', $statusID);
        }

        return $query->orderBy('order_creation_time', 'DESC')->findAll();
    }
}