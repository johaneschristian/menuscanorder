<?php

namespace App\Repositories;

use App\CustomExceptions\ObjectNotFoundException;
use App\Models\OrderItemModel;
use App\Models\OrderItemStatusModel;
use App\Utils\Utils;

/**
 * Repository to deal with database insertion, retrieval, and update for order item and related model (order item status model)
 */
class OrderItemRepository
{
    /**
     * Retrieve all order item statuses from the database.
     *
     * @return array An array containing all order item statuses.
     */
    public static function getAllOrderItemStatus() {
        $orderItemStatus = new OrderItemStatusModel();
        return $orderItemStatus->findAll();
    }

    /**
     * Retrieve an order item status from the database by its ID.
     *
     * @param int $orderItemStatusID The ID of the order item status to retrieve.
     * @return mixed The order item status record if found, otherwise NULL.
     */
    public static function getOrderItemStatusByID($orderItemStatusID) {
        $orderItemStatus = new OrderItemStatusModel();
        return $orderItemStatus->where('id', $orderItemStatusID);
    }

    /**
     * Retrieve an order item status from the database by its ID or throw an exception if not found.
     *
     * @param int $orderItemStatusID The ID of the order item status to retrieve.
     * @return object The order item status record if found.
     * @throws ObjectNotFoundException If the order item status with the specified ID does not exist.
     */
    public static function getOrderItemStatusByIDOrThrowException($orderItemStatusID) {
        // Retrieve the order item status by ID
        $foundOrderItemStatus = self::getOrderItemStatusByID($orderItemStatusID);

        // If the order item status is not found, throw an exception
        if (is_null($foundOrderItemStatus)) {
            throw new ObjectNotFoundException("Order item status with ID $orderItemStatusID does not exist");

        } else {
            return $foundOrderItemStatus;
        }
    }

    /**
     * Retrieve an order item status from the database by its name.
     *
     * @param string $statusName The name of the order item status to retrieve.
     * @return mixed The order item status record if found, otherwise NULL.
     */
    public static function getOrderItemStatusByName($statusName) {
        $orderItemStatus = new OrderItemStatusModel();
        return $orderItemStatus->where('status', $statusName)->first();
    }

    /**
     * Create a new order item model with the provided data.
     *
     * @param array $orderItemData The order item data.
     * @return string The ID of the created order item.
     */
    public static function createOrderItemModel($orderItemData) {
        $orderItem = new OrderItemModel();
        
        // Retrieve the order item status with the name 'received'
        $receivedOrderItemStatus = self::getOrderItemStatusByName('received');
        
        // Generate a UUID for the new order item
        $orderItemID = Utils::generateUUID();
        
        // Insert the order item data into the database
        $orderItem->insert([
            ...$orderItemData,
            'order_item_id' => $orderItemID,
            'item_order_time' => Utils::getCurrentTime(),
            'order_item_status_id' => $receivedOrderItemStatus->id,
        ]);

        // Return the ID of the created order item
        return $orderItemID;
    }

    /**
     * Retrieve an order item from the database by its ID.
     *
     * @param string $orderItemID The ID of the order item to retrieve.
     * @return mixed The order item record if found, otherwise NULL.
     */
    public static function getOrderItemsByID($orderItemID) {
        $orderItem = new OrderItemModel();
        return $orderItem->where('order_item_id', $orderItemID)->first();
    }

    /**
     * Retrieve an order item from the database by its ID or throw an exception if not found.
     *
     * @param string $orderItemID The ID of the order item to retrieve.
     * @return object The order item record if found.
     * @throws ObjectNotFoundException If the order item with the specified ID does not exist.
     */
    public static function getOrderItemByIDOrThrowException($orderItemID) {
        $foundOrderItem = self::getOrderItemsByID($orderItemID);

        // If the order item is not found, throw an exception
        if (is_null($foundOrderItem)) {
            throw new ObjectNotFoundException("Order Item with ID $orderItemID does not exist");

        } else {
            return $foundOrderItem;
        }
    }

    /**
     * Update an order item in the database with new data.
     *
     * @param string $orderItemID The ID of the order item to update.
     * @param array $newData The data order item.
     * @return void
     */
    public static function updateOrderItem($orderItemID, $newData) {
        $orderItem = new OrderItemModel();
        $orderItem->update($orderItemID, $newData);
    }

    /**
     * Retrieve order items of an order from the database.
     *
     * @param string $orderID The ID of the order.
     * @param bool $ordered If true, order the results by item order time in descending order.
     * @param bool $completeData If true, retrieve complete data including menu item name and status name.
     * @return array An array containing order item records.
     */
    public static function getOrderItemsOfOrder($orderID, $ordered = FALSE, $completeData = FALSE) {
        $orderItem = new OrderItemModel();
        $query = $orderItem->where('order_id', $orderID);

        // If completeData is true, include menu items and corresponding order status data
        if ($completeData) {
            $query = $query->select('order_items.order_item_id, order_items.num_of_items, order_items.item_order_time, order_items.order_item_status_id, order_items.notes, order_items.order_id, order_items.menu_item_id, order_items.price_when_bought, menu_items.name AS menu_item_name, order_item_statuses.status AS status_name')
                           ->join('menu_items', 'order_items.menu_item_id=menu_items.menu_item_id', 'left')
                           ->join('order_item_statuses', 'order_items.order_item_status_id=order_item_statuses.id');
        }
        
        // If ordered is true, order the results by item order time in descending order
        if ($ordered) {
            $query = $query->orderBy('item_order_time', 'DESC');
        }

        // Execute the query and retrieve all results
        return $query->findAll();
    }

    /**
     * Retrieve distinct combinations of menu item ID and price of order items of an order
     * for order summary. Price is required to distinguish same items ordered at different price
     * (when changed mid-dining).
     *
     * @param string $orderID The ID of the order.
     * @return array An array containing distinct combinations of menu item ID, price, and menu item name.
     */
    public static function getDistinctCombinationOfMenuItemIDAndPriceOfOrder($orderID) {
        $orderItem = new OrderItemModel();
        
        // Build query to retrieve distinct combinations of menu item ID and price
        $query = $orderItem->select('order_items.menu_item_id, order_items.price_when_bought, menu_items.name AS menu_item_name')
                           ->join('menu_items', 'order_items.menu_item_id=menu_items.menu_item_id', 'left')
                           ->where('order_id', $orderID)
                           ->distinct();

        // Execute the query and retrieve all results
        return $query->findAll();
    }

    /**
     * Retrieve order items of an order that match the given menu item ID and price.
     *
     * @param string $orderID The ID of the order.
     * @param string $menuItemID The ID of the menu item.
     * @param double $price The price of the menu item when bought.
     * @return array An array containing order item records matching the criteria.
     */
    public static function getOrderItemsOfOrderMatchingMenuItemIDAndPrice($orderID, $menuItemID, $price) {
        $orderItem = new OrderItemModel();
        
        // Build the query to retrieve order items matching the given criteria
        $query = $orderItem->where('order_id', $orderID)
                           ->where('menu_item_id', $menuItemID)
                           ->where('price_when_bought', $price);

        // Execute the query and retrieve all results
        return $query->findAll();
    }

    /**
     * Retrieve a summary of order items of orders received by a business.
     *
     * @param string $businessID The ID of the business.
     * @return array An array containing a summary of order items, including status ID, status name, and total quantity.
     */
    public static function getOrderItemsSummaryOfBusiness($businessID) {
        $orderItem = new OrderItemModel();
        
        // Build the query to retrieve the summary of order items
        $query = $orderItem->select('order_items.order_item_status_id, order_item_statuses.status AS status_name, SUM(order_items.num_of_items) AS total_quantity')
                           ->join('orders', 'order_items.order_id=orders.order_id')
                           ->join('order_item_statuses', 'order_items.order_item_status_id=order_item_statuses.id')
                           ->where('orders.receiving_business_id', $businessID)
                           ->groupBy('order_items.order_item_status_id, order_item_statuses.status');

        // Execute the query and retrieve all results
        return $query->findAll();
    }

    /**
     * Retrieve order items of orders received by a business, excluding those with status 'served'.
     *
     * @param string $businessID The ID of the business.
     * @return array An array containing order item records.
     */
    public static function getOrderItemsOfBusiness($businessID) {
        $orderItem = new OrderItemModel();
        
        // Build the query to retrieve order items of the business
        $query = $orderItem->select('order_items.order_item_id, order_items.num_of_items, order_items.item_order_time, order_items.order_item_status_id, order_item_statuses.status AS status_name, order_items.notes, order_items.order_id, orders.table_number, order_items.menu_item_id, menu_items.name AS menu_item_name, order_items.price_when_bought')
                           ->join('orders', 'order_items.order_id=orders.order_id')
                           ->join('order_item_statuses', 'order_items.order_item_status_id=order_item_statuses.id')
                           ->join('menu_items', 'order_items.menu_item_id=menu_items.menu_item_id', 'left')
                           ->where('orders.receiving_business_id', $businessID)
                           ->whereNotIn('order_item_statuses.status', ['served'])
                           ->orderBy('order_items.item_order_time', 'DESC');
        
        // Execute the query and retrieve all results
        return $query->findAll();
    }
}
