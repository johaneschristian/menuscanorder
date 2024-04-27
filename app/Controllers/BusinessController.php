<?php

namespace App\Controllers;

use App\Services\BusinessService;

class BusinessController extends BaseController 
{
    public function categoryList() {
        $user = auth()->user();
        $search = $this->request->getGet('search');
        $categoriesData = BusinessService::handleGetCategoryList($user, $search);
        $data = [
            ...$categoriesData,
            'search' => $search,
        ];

        return view('business/business-menu-category-list', $data);
    }

    public function categoryCreate() {
        $user = auth()->user();
        $categoryData = $this->request->getPost();
        BusinessService::handleCategoryCreation($user, $categoryData);
        return redirect()->to('/business/categories');
    }

    public function categoryUpdate() {
        $user = auth()->user();
        $categoryData = $this->request->getPost();
        BusinessService::handleUpdateCategory($user, $categoryData);
        return redirect()->to('/business/categories');
    }

    public function menuList() {
        $user = auth()->user();
        $requestData = $this->request->getGet();
        $menus = BusinessService::handleBusinessMenuList($user, $requestData);
        $data = [
            ...$menus,
            'search' => $requestData['menu_name'] ?? '',
            'category_id' => $requestData['category_id'] ?? '',
        ];
        return view('business/business-menu-page', $data);
    }

    public function menuCreate() {
        $user = auth()->user();
        if ($this->request->getMethod() === "post") {
            $imageFile = $this->request->getFile("product_image");
            $menuData = $this->request->getPost();
            BusinessService::handleMenuCreation($user, $menuData, $imageFile);
            return redirect()->to('business/menu/');
        }

        $categoriesData = BusinessService::handleGetCategoryList($user, "");
        $data = [
            ...$categoriesData,
            'is_create' => TRUE,
        ];
        return view('business/business-menu-edit', $data);
    }

    public function menuEdit($menuId) {
        $user = auth()->user();

        if ($this->request->getMethod() === "post") {
            $imageFile = $this->request->getFile("product_image");
            $menuData = $this->request->getPost();
            BusinessService::handleMenuEdit($user, $menuId, $menuData, $imageFile);
            return redirect()->to('business/menu/');
        }

        $menuData = BusinessService::handleBusinessGetMenuData($user, $menuId);
        $data = [
            ...$menuData,
            'is_create' => FALSE,
        ];
        
        return view('business/business-menu-edit', $data);
    }

    public function menuGetImage($menuId) {
        try {
            $menuImageFile = BusinessService::handleMenuGetImage($menuId);
            return $this->response
                    ->setHeader('Content-Type', $menuImageFile->getMimeType())
                    ->setHeader('Content-disposition', 'inline; filename="' . $menuImageFile->getBasename() . '"')
                    ->setStatusCode(200)
                    ->setBody(readfile($menuImageFile->getRealPath()));

        } catch (\Exception) {
            return "";
        }
    }

    public function profileEdit() {
        return view('business/business-profile-edit');
    }

    public function seatManagement() {
        $user = auth()->user();
        
        if($this->request->getMethod() === "post") {
            $capacityData = $this->request->getPost();
            BusinessService::handleUpdateBusinessTableCapacity($user, $capacityData);
            return redirect()->to('business/seat-management');
        }

        $requestData = $this->request->getGet();
        $businessData = BusinessService::handleGetBusinessTableData($user, $requestData);
        return view('business/seat-management', $businessData);
    }

    public function getTableQRCode($businessID, $tableNumber) {
        return BusinessService::handleGetTableQR($businessID, $tableNumber);
    }
}
