<?php

namespace App\Controllers;

use App\CustomExceptions\InvalidRegistrationException;
use App\CustomExceptions\ObjectNotFoundException;
use App\Services\BusinessService;
use Exception;

class BusinessController extends BaseController
{
    public function registerBusiness() {
        try {
            $user = auth()->user();

            if ($this->request->getMethod() === 'post') {
                try {
                    $requestData = $this->request->getPost();
                    BusinessService::handleRegisterBusiness($user, $requestData);
                    session()->setFlashData('success', 'Business is created successfully');
                    return redirect()->to('/business/orders/');

                } catch (InvalidRegistrationException $exception) {
                    session()->setFlashData('error', $exception->getMessage()); 
                }
            }

            if (BusinessService::userHasBusiness($user)) {
                return redirect()->to('business/orders/');
                
            } else {
                return view('customer/customer-business-registration');
            }
                    
        } catch (Exception $exception) {
            session()->setFlashData('error', $exception->getMessage()); 
            return redirect()->to('/');
        }
    }

    public function getCategoryList()
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

    public function createCategory()
    {
        try {
            $user = auth()->user();
            $user->business_id = session()->get('business_id');
            $requestData = $this->request->getPost();

            BusinessService::handleCreateCategory($user, $requestData);
            session()->setFlashdata('success', 'Category is created successfully');
        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
        }

        return redirect()->back();
    }

    public function updateCategory()
    {
        try {
            $user = auth()->user();
            $user->business_id = session()->get('business_id');
            $requestData = $this->request->getPost();

            BusinessService::handleUpdateCategory($user, $requestData);
            session()->setFlashdata('success', 'Category is updated successfully');
        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
        }

        return redirect()->back();
    }

    public function deleteCategory() {
        try {
            $user = auth()->user();
            $user->business_id = session()->get('business_id');
            $requestData = $this->request->getPost();

            BusinessService::handleDeleteCategory($user, $requestData);
            session()->setFlashdata('success', 'Category is deleted successfully');
        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
        }

        return redirect()->back();
    }

    public function getMenuList()
    {
        try {
            $user = auth()->user();
            $user->business_id = session()->get('business_id');
            $requestData = $this->request->getGet();

            $menus = BusinessService::handleGetMenuList($user, $requestData);
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

    public function createMenu()
    {
        try {
            $user = auth()->user();
            $user->business_id = session()->get('business_id');

            if ($this->request->getMethod() === "post") {
                try {
                    $imageFile = $this->request->getFile("product_image");
                    $requestData = $this->request->getPost();
                    BusinessService::handleCreateMenu($user, $requestData, $imageFile);
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

    public function editMenu($menuID)
    {
        try {
            $user = auth()->user();
            $user->business_id = session()->get('business_id');

            if ($this->request->getMethod() === "post") {
                try {
                    $imageFile = $this->request->getFile("product_image");
                    $requestData = $this->request->getPost();
                    BusinessService::handleEditMenu($user, $menuID, $requestData, $imageFile);
                    session()->setFlashdata('success', 'Menu is updated successfully');
                    return redirect()->to('business/menu/');
                } catch (InvalidRegistrationException $exception) {
                    session()->setFlashdata('error', $exception->getMessage());
                }
            }

            $menuData = BusinessService::handleGetMenuData($user, $menuID);
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

    public function deleteMenu() {
        try {
            $user = auth()->user();
            $user->business_id = session()->get('business_id');
            $requestData = $this->request->getPost();

            BusinessService::handleDeleteMenu($user, $requestData);
            session()->setFlashdata('success', 'Menu item is deleted successfully');

        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
        }

        return redirect()->back();
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

    public function editProfile()
    {
        $user = auth()->user();
        $user->business_id = session()->get('business_id');

        if ($this->request->getMethod() === "post") {
            try {
                $requestData = $this->request->getPost();
                BusinessService::handleBusinessEditProfile($user, $requestData);
                session()->setFlashdata('success', 'Business is updated successfully');
            } catch (Exception $exception) {
                session()->setFlashdata('error', $exception->getMessage());
            }
        }

        $businessData = BusinessService::handleGetBusinessProfile($user);
        return view('business/business-profile-edit', $businessData);
    }

    public function seatManagement()
    {
        try {
            $user = auth()->user();
            $user->business_id = session()->get('business_id');

            if ($this->request->getMethod() === "post") {
                try {
                    $requestData = $this->request->getPost();
                    BusinessService::handleUpdateBusinessTableCapacity($user, $requestData);
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
