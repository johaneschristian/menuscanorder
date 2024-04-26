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
        return $this->response->setStatusCode(200)->setBody(json_encode($orderData));
    }

    public function customerOrderList() {
        return view('customer/customer-order-list');
    }

    public function customerOrderDetail($orderId) {
        return view('customer/customer-order-details');
    }
}