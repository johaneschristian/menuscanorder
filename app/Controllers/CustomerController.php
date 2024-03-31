<?php 

namespace App\Controllers;

class CustomerController extends BaseController
{
    public function orderList() {
        return view('customer/customer-order-list');
    }

    public function orderDetail($orderId) {
        return view('customer/customer-order-details');
    }

    public function orderCreate($businessId) {
        return view('customer/order-page');
    }

    public function profileEdit() {
        return view('customer/customer-profile-edit');
    }

    public function businessRegistration() {
        return view('customer/customer-business-registration');
    }
}