<?php

namespace App\Repositories;

use App\CustomExceptions\ObjectNotFoundException;
use App\Models\OrderModel;
use App\Models\OrderStatusModel;
use App\Utils\Utils;

/**
 * Repository to deal with database insertion, retrieval, and update for order and related model (order status)
 */
class OrderRepository
{
    /**
     * Retrieve all order statuses from the database.
     *
     * @return array An array containing all order statuses.
     */
    public static function getAllOrderStatus()
    {
        $orderStatus = new OrderStatusModel();
        return $orderStatus->findAll();
    }

    /**
     * Retrieve an order status from the database by its name.
     *
     * @param string $statusName The name of the order status to retrieve.
     * @return mixed The order status record if found, otherwise NULL.
     */
    public static function getOrderStatusByName($statusName)
    {
        $orderStatus = new OrderStatusModel();
        return $orderStatus->where('status', $statusName)->first();
    }

    /**
     * Retrieve an order status from the database by its ID.
     *
     * @param int $statusID The ID of the order status to retrieve.
     * @return mixed The order status record if found, otherwise NULL.
     */
    public static function getOrderStatusByID($statusID)
    {
        $orderStatus = new OrderStatusModel();
        return $orderStatus->where('id', $statusID)->first();
    }

    /**
     * Create a new order in the database.
     *
     * @param int $submittingUserID The ID of the user submitting the order.
     * @param string $receivingBusinessID The ID of the business receiving the order.
     * @param int $tableNumber The number of the table associated with the order.
     * @return string The ID of the created order.
     */
    public static function createOrder($submittingUserID, $receivingBusinessID, $tableNumber)
    {
        $order = new OrderModel();

        // Retrieve the order status with the name 'New Order'
        $newOrderStatus = self::getOrderStatusByName('New Order');

        // Generate a UUID for the new order
        $orderID = Utils::generateUUID();

        // Insert the order data into the database
        $order->insert([
            'order_id' => $orderID,
            'order_creation_time' => Utils::getCurrentTime(),
            'order_status_id' => $newOrderStatus->id,
            'table_number' => $tableNumber,
            'submitting_user_id' => $submittingUserID,
            'receiving_business_id' => $receivingBusinessID,
        ]);

        return $orderID;
    }

    /**
     * Retrieve an order from the database by its ID.
     *
     * @param string $orderID The ID of the order to retrieve.
     * @return mixed The order record if found, otherwise NULL.
     */
    public static function getOrderByID($orderID)
    {
        $order = new OrderModel();
        return $order->where('order_id', $orderID)->first();
    }

    /**
     * Retrieve an order from the database by its ID or throw an exception if not found.
     *
     * @param string $orderID The ID of the order to retrieve.
     * @return object The order record if found.
     * @throws ObjectNotFoundException If the order with the specified ID does not exist.
     */
    public static function getOrderByIDOrThrowException($orderID)
    {
        $foundOrder = self::getOrderByID($orderID);

        // If the order is not found, throw an exception
        if (is_null($foundOrder)) {
            throw new ObjectNotFoundException("Order with ID $orderID does not exist");
        } else {
            return $foundOrder;
        }
    }

    /**
     * Retrieve the latest uncompleted order of a customer in a specific business.
     *
     * @param int $submittingUserID The ID of the customer submitting the order.
     * @param string $businessID The ID of the business receiving the order.
     * @param int $tableNumber The number of the table where the order is made.
     * @return mixed The order record if found, otherwise NULL.
     */
    public static function getLatestUncompleteOrderOfCustomerInBusiness($submittingUserID, $businessID, $tableNumber)
    {
        $order = new OrderModel();

        // Retrieve the order status with the name 'Completed'
        $completedOrderStatus = self::getOrderStatusByName('Completed');

        // Retrieve the latest uncompleted order of the customer in the business
        return $order->where('submitting_user_id', $submittingUserID)
            ->where('receiving_business_id', $businessID)
            ->where('table_number', $tableNumber)
            ->whereNotIn('order_status_id', [$completedOrderStatus->id])
            ->orderBy('order_creation_time', 'DESC')
            ->first();
    }

    /**
     * Update an order in the database with new data.
     *
     * @param string $orderID The ID of the order to update.
     * @param array $orderData An associative array containing the new data for the order.
     * @return void
     */
    public static function updateOrder($orderID, $orderData)
    {
        $order = new OrderModel();
        $order->update($orderID, $orderData);
    }

    /**
     * Generate a base query for retrieving orders based on various criteria.
     *
     * @param string|null $submittingUserID The ID of the user submitting the order (optional).
     * @param array|null $businessesIDs An array of business IDs to filter orders by (optional).
     * @param int|null $statusID The ID of the order status to filter orders by (optional).
     * @param int|null $tableNumber The number of the table associated with the order (optional).
     * @return object The query with applied conditions.
     */
    private static function getQueryOfOrders($submittingUserID = null, $businessesIDs = null, $statusID = null, $tableNumber = null)
    {
        $query = new OrderModel();

        // Filter based on user ID if provided
        if (!is_null($submittingUserID)) {
            $query = $query->where('submitting_user_id', $submittingUserID);
        }

        // Filter based on business IDs if provided
        if (!is_null($businessesIDs) && !empty($businessesIDs)) {
            $query = $query->whereIn('receiving_business_id', $businessesIDs);
        } elseif (!is_null($businessesIDs) && empty($businessesIDs)) {
            // If business IDs is empty, need to use null array as empty array lead to an SQL error
            $query = $query->whereIn('receiving_business_id', [null]);
        }

        // Filter based on status ID if provided
        if (!is_null($statusID)) {
            $query = $query->where('order_status_id', $statusID);
        }

        // Filter based on table number if provided
        if (!is_null($tableNumber)) {
            $query = $query->where('table_number', $tableNumber);
        }

        // Order the results by order creation time in descending order
        return $query->orderBy('order_creation_time', 'DESC');
    }


    /**
     * Retrieve orders of a user from the database.
     *
     * @param int $submittingUserID The ID of the user whose orders are to be retrieved.
     * @param array|null $businessesID The IDs array of the businesses the orders are assigned to.
     * @param int|null $statusID The status ID of the orders to be retrieved.
     * @return array Array of orders.
     */
    public static function getOrdersOfUser($submittingUserID, $businessesID, $statusID)
    {
        $query = self::getQueryOfOrders(
            $submittingUserID,
            $businessesID,
            $statusID
        );

        return $query->findAll();
    }

    /**
     * Retrieve paginated orders of a user from the database.
     *
     * @param int $submittingUserID The ID of the user whose orders are to be retrieved.
     * @param array|null $businessesID The IDs of the business associated with the orders.
     * @param int|null $statusID The status ID of the orders to be retrieved.
     * @param int $perPage The number of orders per page. Defaults to 10 if not specified.
     * @param int $currentPage The current page of orders to retrieve. Defaults to the first page (1) if not specified.
     * @return array Array of paginated orders.
     */
    public static function getPaginatedOrdersOfUser($submittingUserID, $businessesID, $statusID, $perPage = 10, $currentPage = 1)
    {
        $query = self::getQueryOfOrders(
            $submittingUserID,
            $businessesID,
            $statusID
        );

        return Utils::paginate($query, $perPage, $currentPage);
    }

    /**
     * Retrieve paginated orders of a business from the database.
     *
     * @param string $businessID The ID of the business whose orders are to be retrieved.
     * @param int|null $statusID The status ID of the orders to be retrieved.
     * @param int|null $tableNumber The table number associated with the orders.
     * @param int $perPage The number of orders per page. Defaults to 10 if not specified (optional).
     * @param int $currentPage The current page of orders to retrieve (optional).
     * @return array Array of paginated orders.
     */
    public static function getPaginatedOrdersOfBusiness($businessID, $statusID, $tableNumber, $perPage = 10, $currentPage = 1)
    {
        $query = self::getQueryOfOrders(
            NULL,
            [$businessID],
            $statusID,
            $tableNumber
        );

        return Utils::paginate($query, $perPage, $currentPage);
    }
}
