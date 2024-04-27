<?php

namespace App\Controllers;

use App\Services\OrderService;
use CodeIgniter\Controller;

class OrderController extends Controller {
    public function orderMenu($businessId, $tableNumber) {
        $menuData = OrderService::handleGetBusinessMenus($businessId);
        return view('customer/order-page', $menuData);
    }

    public function orderCreate() {
        $user = auth()->user();
        $orderData = $this->request->getJSON(true);
        OrderService::handleCreateOrder($user, $orderData);
        return $this->response->setStatusCode(200)->setBody(['message' => 'Order is created successfully']);
    }

    public function customerOrderList() {
        $user = auth()->user();
        $requestData = $this->request->getGet();
        $customerOrders = OrderService::handleCustomerOrderList($user, $requestData);
        return view('customer/customer-order-list', $customerOrders);
    }

    public function customerOrderDetail($orderId) {
        $user = auth()->user();
        $orderData = OrderService::handleCustomerOrderDetail($user, $orderId);
        return view('customer/customer-order-details', $orderData);
    }

    public function businessOrderList() {
        $user = auth()->user();
        $requestData = $this->request->getGet();
        $businessOrders = OrderService::handleBusinessOrderList($user, $requestData);
        return view('business/business-order-list', $businessOrders);
    }

    public function businessOrderKitchenView() {
        return view('business/kitchen-view');
    }

    public function businessOrderDetails($orderId) {
        return view('business/business-order-details');
    }
}