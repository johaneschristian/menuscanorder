<?php

namespace App\Controllers;

use App\CustomExceptions\InvalidRequestException;
use App\CustomExceptions\NotAuthorizedException;
use App\CustomExceptions\ObjectNotFoundException;
use App\Services\OrderService;
use CodeIgniter\Controller;
use Exception;

const HOME_PATH = '/';
const BUSINESS_ORDERS_PATH = 'business/orders';
const CUSTOMER_ORDERS_PATH = 'customer/orders';
const JSON_CONTENT_TYPE = 'application/json';

/**
 * Controller for handling order related operations.
 */
class OrderController extends Controller {
    /**
     * Handler for retrieving menus for ordering.
     *
     * @param string $businessID The ID of the business whose menus want to be retrieved.
     * @param int $tableNumber The table number.
     * @return \CodeIgniter\HTTP\RedirectResponse|string The customer order page or redirect when failing.
     */
    public function getOrderMenu($businessID, $tableNumber)
    {
        try {
            // Retrieve menu data for the specified business
            $menuData = OrderService::handleGetBusinessMenus($businessID);

            // Render the order page view with the menu data
            return view('customer/order-page', $menuData);

        } catch (Exception $exception) {
            // Set error message if an exception occurs and redirect to customer orders page
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(CUSTOMER_ORDERS_PATH);
        }
    }

    /**
     * Handler for creating an order.
     *
     * @return \CodeIgniter\HTTP\Response Containing message representating the success/fail of the action.
     */
    public function createOrder()
    {
        try {
            // Retrieve authenticated user
            $user = auth()->user();

            // Get order data from request
            $requestData = $this->request->getJSON(true);

            // Create order based on submitted data

            OrderService::handleCreateOrder($user, $requestData);

            // Set success message upon successful order registration
            session()->setFlashdata('success', 'Order is created successfully');

            // Return success response
            return $this->response->setContentType(JSON_CONTENT_TYPE)
                                  ->setStatusCode(200)
                                  ->setBody(json_encode(['message' => 'Order is created successfully']));

        } catch (InvalidRequestException $exception) {
            // Return error response with status code 400 when (part of) the submitted data is invalid
            return $this->response->setContentType(JSON_CONTENT_TYPE)
                                  ->setStatusCode(400)
                                  ->setBody(json_encode(['message' => $exception->getMessage()]));

        } catch (ObjectNotFoundException $exception) {
            // Return error response with status code 404 when any submitted ID does not exist
            return $this->response->setContentType(JSON_CONTENT_TYPE)
                                  ->setStatusCode(404)
                                  ->setBody(json_encode(['message' => $exception->getMessage()]));

        } catch (Exception $exception) {
            // Return error response with status code 500 when an unexpected error occurred
            return $this->response->setContentType(JSON_CONTENT_TYPE)
                                  ->setStatusCode(500)
                                  ->setBody(json_encode(['message' => $exception->getMessage()]));
        }
    }

    /**
     * Handler for retrieving a list of orders for a customer.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|string The order list page or redirect when failing.
     */
    public function customerGetOrderList()
    {
        try {
            // Retrieve authenticated user
            $user = auth()->user();

            // Retrieve business name and order status as search params
            $requestData = $this->request->getGet();

            // Retrieve the list of orders for the customer, filtered with search params if provided
            $customerOrders = OrderService::handleCustomerGetOrderList($user, $requestData);

            // Render the customer order list view with the order data
            return view('customer/customer-order-list', $customerOrders);

        } catch (Exception $exception) {
            // Set error message if an unexpected exception occurs and redirect to home page
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(HOME_PATH);
        }
    }

    /**
     * Handler for retrieving details of a specific order for a customer.
     *
     * @param string $orderID The ID of the order
     * @return \CodeIgniter\HTTP\RedirectResponse|string The order details page or redirect when failing.
     */
    public function customerGetOrderDetail($orderID)
    {
        try {
            // Retrieve authenticated user
            $user = auth()->user();

            // Retrieve order details for the specified order ID
            $orderData = OrderService::handleGetOrderDetails($user, $orderID, FALSE);

            // Render the customer order details view with the order data
            return view('customer/customer-order-details', $orderData);

        } catch (ObjectNotFoundException | NotAuthorizedException $exception) {
            // Set error message if the order is not found or the user is not authorized, and redirect to customer orders page
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(CUSTOMER_ORDERS_PATH);

        } catch (Exception $exception) {
            // Set error message if an unexpected exception occurs and redirect to home page
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(HOME_PATH);
        }
    }

    /**
     * Handler for retrieving a list of orders for a business.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|string The business orders page or redirect when failing.
     */
    public function businessGetOrderList()
    {
        try {
            // Retrieve authenticated user and affiliated business ID (from middleware)
            $user = auth()->user();
            $user->business_id = session()->get('business_id');

            // Retrieve table number and status as search params
            $requestData = $this->request->getGet();

            // Retrieve the list of orders for the business, filtered by search params if provided
            $businessOrders = OrderService::handleBusinessGetOrderList($user, $requestData);

            // Prepare data for the view
            $data = [
                ...$businessOrders,
                'business_name' => session()->get('business_name'),
            ];

            // Render the business order list view with the order data
            return view('business/business-order-list', $data);

        } catch (Exception $exception) {
            // Set error message if an unexpected exception occurs and redirect to home page
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(HOME_PATH);
        }
    }

    /**
     * Handler for retrieving details of a specific order for a business.
     *
     * @param string $orderID The ID of the order
     * @return \CodeIgniter\HTTP\RedirectResponse|string The business' order details page or redirect when failing.
     */
    public function businessOrderDetails($orderID)
    {
        try {
            // Retrieve authenticated user and affiliated business ID (from middleware)
            $user = auth()->user();
            $user->business_id = session()->get('business_id');

            // Retrieve order details for the specified order ID
            $orderData = OrderService::handleGetOrderDetails($user, $orderID, TRUE);

            // Prepare data for the view
            $data = [
                ...$orderData,
                'business_name' => session()->get('business_name'),
            ];

            // Render the business order details view with the order data
            return view('business/business-order-details', $data);

        } catch (ObjectNotFoundException | NotAuthorizedException $exception) {
            // Set error message if the order is not found or the user is not authorized, and redirect to business orders page
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(BUSINESS_ORDERS_PATH);
        
        } catch (Exception $exception) {
            // Set error message if an unexpected exception occurs and redirect to home page
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(HOME_PATH);
        }
    }

    /**
     * Handler for completing an order from the business side.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect to business orders page when successful/failing.
     */
    public function businessCompleteOrder()
    {
        try {
            // Retrieve authenticated user and affiliated business ID (from middleware)
            $user = auth()->user();
            $user->business_id = session()->get('business_id');

            // Get completed order ID
            $requestData = $this->request->getPost();

            // Complete the order
            OrderService::handleBusinessCompleteOrder($user, $requestData);

            // Set success flashdata if order is completed successfully
            session()->setFlashdata('success', 'Order status is updated successfully');
            
            // Redirect to business orders page
            return redirect()->to(BUSINESS_ORDERS_PATH);

        } catch (ObjectNotFoundException | NotAuthorizedException $exception) {
            // Set error message if the order is not found or the user is not authorized, and redirect to business orders page
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(BUSINESS_ORDERS_PATH);
        
        } catch (Exception $exception) {
            // Set error message if an unexpected exception occurs and redirect to home page
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(HOME_PATH);
        }
    }

    /**
     * Handler for retrieving the kitchen view page for managing orders.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|string The kitchen items page or redirect when failing.
     */
    public function businessGetOrderKitchenView()
    {
        try {
            // Prepare data for the view
            $data = [
                'business_name' => session()->get('business_name'),
            ];

            // Render the kitchen view
            return view('business/kitchen-view', $data);

        } catch (Exception $exception) {
            // Set error message if an unexpected exception occurs and redirect to home page
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(HOME_PATH);
        }
    }

    /**
     * Handler for retrieving data for the kitchen view to manage orders.
     *
     * @return \CodeIgniter\HTTP\Response JSON of all active order items received by the business.
     */
    public function businessGetOrderKitchenViewData()
    {
        try {
            // Retrieve authenticated user and affiliated business ID
            $user = auth()->user();
            $user->business_id = session()->get('business_id');
            
            // Retrieve data for the kitchen view
            $responseData = OrderService::handleBusinessGetOrderKitchenData($user);
            
            // Return success response containing the retrieved data
            return $this->response
                        ->setContentType(JSON_CONTENT_TYPE)
                        ->setStatusCode(200)
                        ->setBody(json_encode($responseData));

        } catch (Exception $exception) {
            // Return error response containing the error message when an unexpected error occurred
            return $this->response
                        ->setContentType(JSON_CONTENT_TYPE)
                        ->setStatusCode(500)
                        ->setBody(json_encode(['message' => $exception->getMessage()]));
        }
    }

    /**
     * Handler for updating the status of an order item from the kitchen view.
     *
     * @return \CodeIgniter\HTTP\Response Containing message representating the success/fail of the action.
     */
    public function businessUpdateOrderItemStatus()
    {
        try {
            // Retrieve authenticated user and affiliated business ID
            $user = auth()->user();
            $user->business_id = session()->get('business_id');

            // Get order item and status data
            $requestData = $this->request->getJSON(true);

            // Update the order item status
            OrderService::handleBusinessUpdateOrderItemStatus($user, $requestData);
            
            // Return success response when order item status is successfully updated
            return $this->response
                        ->setContentType(JSON_CONTENT_TYPE)
                        ->setStatusCode(200)
                        ->setBody(json_encode(['message' => 'Order item was successfully updated']));

        } catch (NotAuthorizedException $exception) {
            // Return error response with status code 403 when order item does not belong to user
            return $this->response
                        ->setContentType(JSON_CONTENT_TYPE)
                        ->setStatusCode(403)
                        ->setBody(json_encode(['message' => $exception->getMessage()]));

        } catch (ObjectNotFoundException $exception) {
            // Return error response with status code 404 when order item does not exist
            return $this->response
                        ->setContentType(JSON_CONTENT_TYPE)
                        ->setStatusCode(404)
                        ->setBody(json_encode(['message' => $exception->getMessage()]));

        } catch (InvalidRequestException $exception) {
            // Return error response with status code 400 when request data is invalid
            return $this->response
                        ->setContentType(JSON_CONTENT_TYPE)
                        ->setStatusCode(400)
                        ->setBody(json_encode(['message' => $exception->getMessage()]));

        } catch (Exception $exception) {
            // Return error response with status code 500 when an unexpected error occurred
            return $this->response
                        ->setContentType(JSON_CONTENT_TYPE)
                        ->setStatusCode(500)
                        ->setBody(json_encode(['message' => $exception->getMessage()]));
        }
    }
}
