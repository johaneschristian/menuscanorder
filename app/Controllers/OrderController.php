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
        return view('customer/customer-order-details');
    }
}