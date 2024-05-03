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

    public static function createOrder($submittingUserID, $receivingBusinessID, $tableNumber) {
        $order = new OrderModel();
        $receivedOrderStatus = self::getOrderStatusByName('New Order');
        
        $orderID = Utils::generateUUID();
        $order->insert([
            'order_id' => $orderID,
            'order_creation_time' => date('c'),
            'order_status_id' => $receivedOrderStatus->id,
            'table_number' => $tableNumber,
            'submitting_user_id' => $submittingUserID,
            'receiving_business_id' => $receivingBusinessID,
        ]);

        return $orderID;
    }

    public static function getOrderByID($orderID) {
        $order = new OrderModel();
        return $order->where('order_id', $orderID)->first();
    }

    
    public static function getOrderByIDOrThrowException($orderID) {
        $foundOrder = self::getOrderByID($orderID);

        if (is_null($foundOrder)) {
            throw new ObjectNotFoundException("Order with ID $orderID does not exist");

        } else {
            return $foundOrder;
        }
    }

    public static function getLatestUncompleteOrderOfCustomerInBusiness($submittingUserID, $businessID, $tableNumber) {
        $order = new OrderModel();
        $completedOrderStatus = self::getOrderStatusByName('Completed');
        return $order->where('submitting_user_id', $submittingUserID)
                     ->where('receiving_business_id', $businessID)
                     ->where('table_number', $tableNumber)
                     ->whereNotIn('order_status_id', [$completedOrderStatus->id])
                     ->orderBy('order_creation_time', 'DESC')
                     ->first();
    }

    public static function updateOrder($orderID, $orderData) {
        $order = new OrderModel();
        $order->update($orderID, $orderData);
    }

    private static function getQueryOfOrders($submittingUserID = NULL, $businessesID = NULL, $statusID = NULL, $tableNumber = NULL) {
        $query = new OrderModel();

        if (!is_null($submittingUserID)) {
            $query = $query->where('submitting_user_id', $submittingUserID);
        }

        if (!is_null($businessesID)) {
            $query = $query->whereIn('receiving_business_id', $businessesID);
        }

        if (!is_null($statusID)) {
            $query = $query->where('order_status_id', $statusID);
        }

        if (!is_null($tableNumber)) {
            $query = $query->where('table_number', $tableNumber);
        }
        
        return $query->orderBy('order_creation_time', 'DESC');
    }

    public static function getOrdersOfUser($submittingUserID, $businessesID, $statusID) {
        return self::getQueryOfOrders(
            $submittingUserID, 
            $businessesID, 
            $statusID
        )->findAll();
    }

    public static function getPaginatedOrdersOfUser($submittingUserID, $businessesID, $statusID, $perPage = 10, $currentPage = 1) {
        $query = self::getQueryOfOrders(
            $submittingUserID, 
            $businessesID, 
            $statusID
        );

        return Utils::paginate($query, $perPage, $currentPage);
    }

    public static function getPaginatedOrdersOfBusiness($businessID, $statusID, $tableNumber, $perPage = 10, $currentPage = 1) {
        $query = self::getQueryOfOrders(
            NULL,
            [$businessID],
            $statusID,
            $tableNumber,
        );

        return Utils::paginate($query, $perPage, $currentPage);
    }
}