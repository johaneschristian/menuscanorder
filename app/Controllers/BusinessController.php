<?php

namespace App\Controllers;

use App\Services\BusinessService;

class BusinessController extends BaseController 
{
    public function categoryList() {
        return view('business/business-menu-category-list');
    }

    public function categoryCreate() {
        $user = auth()->user();
        $categoryData = $this->request->getPost();
        BusinessService::handleCategoryCreation($user, $categoryData);
        return redirect()->to('/business/categories');
    }

    public function menuList() {
        return view('business/business-menu-page');
    }

    public function menuCreate() {
        return view('business/business-menu-edit');
    }

    public function menuEdit($menuId) {
        return view('business/business-menu-edit');
    }

    public function orderList() {
        return view('business/business-order-list');
    }

    public function orderKitchenView() {
        return view('business/kitchen-view');
    }

    public function orderDetails($orderId) {
        return view('business/business-order-details');
    }

    public function profileEdit() {
        return view('business/business-profile-edit');
    }

    public function seatManagement() {
        return view('business/seat-management');
    }
}
