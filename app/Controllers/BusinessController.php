<?php

namespace App\Controllers;

use App\CustomExceptions\InvalidRegistrationException;
use App\CustomExceptions\ObjectNotFoundException;
use App\Services\BusinessService;
use Exception;

class BusinessController extends BaseController
{
    public function categoryList()
    {
        try {
            $user = auth()->user();
            $user->business_id = session()->get('business_id');
            $search = $this->request->getGet('search');
            
            $categoriesData = BusinessService::handleGetCategoryList($user, $search);
            $data = [
                ...$categoriesData,
                'business_name' => session()->get('business_name'),
            ];

            return view('business/business-menu-category-list', $data);
        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/');
        }
    }

    public function categoryCreate()
    {
        try {
            $user = auth()->user();
            $user->business_id = session()->get('business_id');
            $categoryData = $this->request->getPost();

            BusinessService::handleCategoryCreation($user, $categoryData);
            session()->setFlashdata('success', 'Category is created successfully');
        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
        }

        return redirect()->to('/business/categories');
    }

    public function categoryUpdate()
    {
        try {
            $user = auth()->user();
            $user->business_id = session()->get('business_id');
            $categoryData = $this->request->getPost();

            BusinessService::handleUpdateCategory($user, $categoryData);
            session()->setFlashdata('success', 'Category is updated successfully');
        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
        }

        return redirect()->to('/business/categories');
    }

    public function menuList()
    {
        try {
            $user = auth()->user();
            $user->business_id = session()->get('business_id');
            $requestData = $this->request->getGet();

            $menus = BusinessService::handleBusinessMenuList($user, $requestData);
            $data = [
                ...$menus,
                'business_name' => session()->get('business_name'),
            ];

            return view('business/business-menu-page', $data);
        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/');
        }
    }

    public function menuCreate()
    {
        try {
            $user = auth()->user();
            $user->business_id = session()->get('business_id');

            if ($this->request->getMethod() === "post") {
                try {
                    $imageFile = $this->request->getFile("product_image");
                    $menuData = $this->request->getPost();
                    BusinessService::handleMenuCreation($user, $menuData, $imageFile);
                    return redirect()->to('business/menu/');
                } catch (InvalidRegistrationException $exception) {
                    session()->setFlashdata('error', $exception->getMessage());
                }
            }

            $categoriesData = BusinessService::handleGetCategoryList($user, "");
            $data = [
                ...$categoriesData,
                'is_create' => TRUE,
                'business_name' => session()->get('business_name'),
            ];

            return view('business/business-menu-edit', $data);
        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/');
        }
    }

    public function menuEdit($menuID)
    {
        try {
            $user = auth()->user();
            $user->business_id = session()->get('business_id');

            if ($this->request->getMethod() === "post") {
                try {
                    $imageFile = $this->request->getFile("product_image");
                    $menuData = $this->request->getPost();
                    BusinessService::handleMenuEdit($user, $menuID, $menuData, $imageFile);
                    session()->setFlashdata('success', 'Menu is updated successfully');
                    return redirect()->to('business/menu/');
                } catch (InvalidRegistrationException $exception) {
                    session()->setFlashdata('error', $exception->getMessage());
                }
            }

            $menuData = BusinessService::handleBusinessGetMenuData($user, $menuID);
            $data = [
                ...$menuData,
                'is_create' => FALSE,
                'business_name' => session()->get('business_name'),
            ];

            return view('business/business-menu-edit', $data);
        } catch (ObjectNotFoundException $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('business/menu/');
        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/');
        }
    }

    public function menuGetImage($menuID)
    {
        try {
            $menuImageData = BusinessService::handleMenuGetImage($menuID);
            return $this->response
                ->setHeader('Content-Type', $menuImageData['mime_type'])
                ->setHeader('Content-disposition', 'inline; filename="' . $menuImageData['base_name'] . '"')
                ->setStatusCode(200)
                ->setBody($menuImageData['content']);
        } catch (Exception) {
            return "";
        }
    }

    public function profileEdit()
    {
        return view('business/business-profile-edit');
    }

    public function seatManagement()
    {
        try {
            $user = auth()->user();
            $user->business_id = session()->get('business_id');

            if ($this->request->getMethod() === "post") {
                try {
                    $capacityData = $this->request->getPost();
                    BusinessService::handleUpdateBusinessTableCapacity($user, $capacityData);
                    session()->setFlashdata('success', 'Business capacity is updated successfully');
                    return redirect()->to('business/seat-management');
                } catch (InvalidRegistrationException $exception) {
                    session()->setFlashdata('error', $exception->getMessage());
                }
            }
            
            $requestData = $this->request->getGet();
            $businessData = BusinessService::handleGetBusinessTableData($user, $requestData);
            $data = [
                ...$businessData,
                'business_name' => session()->get('business_name'),
            ];

            return view('business/seat-management', $data);
        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/');
        }
    }

    public function getTableQRCode($businessID, $tableNumber)
    {
        return BusinessService::handleGetTableQR($businessID, $tableNumber);
    }
}
