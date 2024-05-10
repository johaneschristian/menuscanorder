<?php

namespace App\Controllers;

use App\CustomExceptions\InvalidRegistrationException;
use App\CustomExceptions\ObjectNotFoundException;
use App\Services\BusinessService;
use Exception;

const HOME_PATH = '/';
const BUSINESS_CATEGORIES_PATH = 'business/categories/';
const BUSINESS_MENU_PATH = 'business/menu/';
const BUSINESS_ORDERS_PATH = 'business/orders/';

/**
 * Controller for handling business related operations.
 */
class BusinessController extends BaseController
{
    /**
     * Handler for business registration.
     *
     * @return \CodeIgniter\HTTP\Response|void
     */
    public function registerBusiness() {
        try {
            // Retrieve authenticated user
            $user = auth()->user();

            if ($this->request->getMethod() === 'post') {
                try {
                    // Register new business for the authenticated user based on request data
                    $requestData = $this->request->getPost();
                    BusinessService::handleRegisterBusiness($user, $requestData);

                    // Set success message upon successful business registration
                    session()->setFlashData('success', 'Business is created successfully');
                    return redirect()->to(BUSINESS_ORDERS_PATH);

                } catch (InvalidRegistrationException $exception) {
                    // Set error message if registration fails
                    session()->setFlashData('error', $exception->getMessage());
                }
            }

            if (BusinessService::userHasBusiness($user)) {
                // Redirect user to business application (view) if the user owns a business
                return redirect()->to(BUSINESS_ORDERS_PATH);
                
            } else {
                // Render business registration page if user does not own a business
                return view('customer/customer-business-registration');
            }
                    
        } catch (Exception $exception) {
            // Set error message and redirect if exception not related to payload occurs
            session()->setFlashData('error', $exception->getMessage());
            return redirect()->to(HOME_PATH);
        }
    }

    /**
     * Handler for retrieving all categories of a business.
     *
     * @return \CodeIgniter\HTTP\Response|void
     */
    public function getCategoryList()
    {
        try {
            // Retrieve authenticated user and associated business ID (from middleware)
            $user = auth()->user();
            $user->business_id = session()->get('business_id');
            $search = $this->request->getGet('search');
            
            // Retrieve all categories of business, matching name if requested
            $categoriesData = BusinessService::handleGetCategoryList($user, $search);
            $data = [
                ...$categoriesData,
                'business_name' => session()->get('business_name'),
            ];

            // Render category page
            return view('business/business-menu-category-list', $data);

        } catch (Exception $exception) {
            // Set error message and redirect to home page if an unexpected exception occurs
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(HOME_PATH);
        }
    }

    /**
     * Handler for creating a new category.
     *
     * @return \CodeIgniter\HTTP\Response|void
     */
    public function createCategory()
    {
        try {
            // Retrieve authenticated user and associated business ID (from middleware)
            $user = auth()->user();
            $user->business_id = session()->get('business_id');
            $requestData = $this->request->getPost();

            // Create category for the business affiliated with authenticated user
            BusinessService::handleCreateCategory($user, $requestData);

            // Set success flashdata when creation is successful
            session()->setFlashdata('success', 'Category is created successfully');

        } catch (Exception $exception) {
            // Set error message if creation failed
            session()->setFlashdata('error', $exception->getMessage());
        }

        // Redirect to all categories path
        return redirect()->to(BUSINESS_CATEGORIES_PATH);
    }

    /**
     * Handler for updating an existing category.
     *
     * @param int $categoryId The ID of the category to update
     * @return \CodeIgniter\HTTP\Response|void
     */
    public function updateCategory(int $categoryId)
    {
        try {
            // Retrieve authenticated user and associated business ID (from middleware)
            $user = auth()->user();
            $user->business_id = session()->get('business_id');
            
            // Retrieve the category data to update
            $requestData = $this->request->getPost();

            // Update category owned by business affiliated with authenticated user
            BusinessService::handleUpdateCategory($user, $categoryId, $requestData);

            // Set success flashdata when update is successful
            session()->setFlashdata('success', 'Category is updated successfully');

        } catch (Exception $exception) {
            // Set error message if update failed
            session()->setFlashdata('error', $exception->getMessage());
        }

        // Redirect to all categories path
        return redirect()->to(BUSINESS_CATEGORIES_PATH);
    }

    /**
     * Handler for deleting a category.
     *
     * @return \CodeIgniter\HTTP\Response|void
     */
    public function deleteCategory()
    {
        try {
            // Retrieve authenticated user and associated business ID (from middleware)
            $user = auth()->user();
            $user->business_id = session()->get('business_id');

            // Retrieve deleted category ID
            $requestData = $this->request->getPost();

            // Delete category
            BusinessService::handleDeleteCategory($user, $requestData);

            // Set success flashdata when deletion is successful
            session()->setFlashdata('success', 'Category is deleted successfully');

        } catch (Exception $exception) {
            // Set error message if deletion failed
            session()->setFlashdata('error', $exception->getMessage());
        }

        // Redirect to all categories path regardless of success or failure
        return redirect()->to(BUSINESS_CATEGORIES_PATH);
    }


    /**
     * Handler for retrieving the menu list.
     *
     * @return \CodeIgniter\HTTP\Response|void
     */
    public function getMenuList()
    {
        try {
            // Retrieve authenticated user and associated business ID (from middleware)
            $user = auth()->user();
            $user->business_id = session()->get('business_id');

            // Retrieve additional data from the request if needed
            $requestData = $this->request->getGet();

            // Retrieve all (matching) menu owned by the business
            $menus = BusinessService::handleGetMenuList($user, $requestData);

            // Prepare data for the view
            $data = [
                ...$menus,
                'business_name' => session()->get('business_name'),
            ];

            // Render the business menu page view with the data
            return view('business/business-menu-page', $data);

        } catch (Exception $exception) {
            // Set error message if an unexpected exception occurs and redirect to home page
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(HOME_PATH);
        }
    }


    /**
     * Handler for creating a menu.
     *
     * @return \CodeIgniter\HTTP\Response|void
     */
    public function createMenu()
    {
        try {
            // Retrieve authenticated user and associated business ID (from middleware)
            $user = auth()->user();
            $user->business_id = session()->get('business_id');

            if ($this->request->getMethod() === "post") {
                try {
                    // Get the uploaded image file
                    $imageFile = $this->request->getFile("product_image");

                    // Get other form data
                    $requestData = $this->request->getPost();

                    // Create menu for affiliated business based on submitted data
                    BusinessService::handleCreateMenu($user, $requestData, $imageFile);

                    // Redirect to the business menu page upon successful creation
                    session()->setFlashdata('success', 'Menu is created successfully');
                    return redirect()->to(BUSINESS_MENU_PATH);

                } catch (InvalidRegistrationException $exception) {
                    // Set error message if menu creation fails
                    session()->setFlashdata('error', $exception->getMessage());
                }
            }

            // Prepare data for view
            $categoriesData = BusinessService::handleGetCategoryList($user, "");
            $data = [
                ...$categoriesData,
                'business_name' => session()->get('business_name'),
            ];

            // Render the business menu edit page view with the data
            return view('business/business-menu-edit', $data);

        } catch (Exception $exception) {
            // Set error message if an unexpected exception occurs and redirect to home page
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(HOME_PATH);
        }
    }

    /**
     * Handler for editing a menu.
     *
     * @param int $menuID The ID of the menu to edit
     * @return \CodeIgniter\HTTP\Response|void
     */
    public function editMenu($menuID)
    {
        try {
            // Retrieve authenticated user and associated business ID (from middleware)
            $user = auth()->user();
            $user->business_id = session()->get('business_id');

            if ($this->request->getMethod() === "post") {
                try {
                    // Get the uploaded image file
                    $imageFile = $this->request->getFile("product_image");

                    // Get other form data
                    $requestData = $this->request->getPost();
                    
                    // Edit menu based on submitted data
                    BusinessService::handleEditMenu($user, $menuID, $requestData, $imageFile);
                    
                    // Set success flashdata when update is successful
                    session()->setFlashdata('success', 'Menu is updated successfully');
                    
                    // Redirect to the business menu page upon successful update
                    return redirect()->to(BUSINESS_MENU_PATH);

                } catch (InvalidRegistrationException $exception) {
                    // Set error message if menu editing fails
                    session()->setFlashdata('error', $exception->getMessage());
                }
            }

            // Retrieve menu data for editing
            $menuData = BusinessService::handleGetMenuData($user, $menuID);
            $data = [
                ...$menuData,
                'business_name' => session()->get('business_name'),
            ];

            // Render the business menu edit page view with the data
            return view('business/business-menu-edit', $data);

        } catch (ObjectNotFoundException $exception) {
            // Set error message if menu data is not found and redirect to business menu page
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(BUSINESS_MENU_PATH);

        } catch (Exception $exception) {
            // Set error message if an unexpected exception occurs and redirect to home page
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(HOME_PATH);
        }
    }

    /**
     * Handler for deleting a menu item.
     *
     * @return \CodeIgniter\HTTP\Response|void
     */
    public function deleteMenu()
    {
        try {
            // Retrieve authenticated user and associated business ID (from middleware)
            $user = auth()->user();
            $user->business_id = session()->get('business_id');
            
            // Retrieve deleted menu ID
            $requestData = $this->request->getPost();

            // Handle menu item deletion
            BusinessService::handleDeleteMenu($user, $requestData);

            // Set success flashdata when deletion is successful
            session()->setFlashdata('success', 'Menu item is deleted successfully');

        } catch (Exception $exception) {
            // Set error message if deletion fails
            session()->setFlashdata('error', $exception->getMessage());
        }

        // Redirect to the business menu page
        return redirect()->to(BUSINESS_MENU_PATH);
    }

    /**
     * Handler for retrieving a menu item image.
     *
     * @param int $menuID The ID of the menu item
     * @return \CodeIgniter\HTTP\Response|string
     */
    public function menuGetImage($menuID)
    {
        try {
            // Retrieve menu image data from the BusinessService
            $menuImageData = BusinessService::handleMenuGetImage($menuID);

            // Return the image response
            return $this->response
                ->setHeader('Content-Type', $menuImageData['mime_type'])
                ->setHeader('Content-disposition', 'inline; filename="' . $menuImageData['base_name'] . '"')
                ->setStatusCode(200)
                ->setBody($menuImageData['content']);
                
        } catch (Exception) {
            // Return an empty string if an exception occurs
            return "";
        }
    }

    /**
     * Handler for editing business profile.
     *
     * @return \CodeIgniter\HTTP\Response|void
     */
    public function editProfile()
    {
        // Retrieve authenticated user and associated business ID (from middleware)
        $user = auth()->user();
        $user->business_id = session()->get('business_id');

        if ($this->request->getMethod() === "post") {
            try {
                // Get profile data
                $requestData = $this->request->getPost();

                // Update business profile
                BusinessService::handleBusinessEditProfile($user, $requestData);

                // Set success flashdata when update is successful
                session()->setFlashdata('success', 'Business is updated successfully');

            } catch (Exception $exception) {
                // Set error message if editing fails
                session()->setFlashdata('error', $exception->getMessage());
            }
        }

        // Retrieve business profile data
        $businessData = BusinessService::handleGetBusinessProfile($user);

        // Render the business profile edit page view with the data
        return view('business/business-profile-edit', $businessData);
    }


    /**
     * Handler for managing seat capacity.
     *
     * @return \CodeIgniter\HTTP\Response|void
     */
    public function seatManagement()
    {
        try {
            // Retrieve authenticated user and associated business ID (from middleware)
            $user = auth()->user();
            $user->business_id = session()->get('business_id');

            if ($this->request->getMethod() === "post") {
                try {
                    // Get updated table number data
                    $requestData = $this->request->getPost();

                    // Update affiliated business table capacity
                    BusinessService::handleUpdateBusinessTableCapacity($user, $requestData);

                    // Set success flashdata when update is successful
                    session()->setFlashdata('success', 'Business capacity is updated successfully');

                    // Redirect back to seat management page
                    return redirect()->to('business/seat-management');

                } catch (InvalidRegistrationException $exception) {
                    // Set error message if update fails
                    session()->setFlashdata('error', $exception->getMessage());
                }
            }
            
            // Retrieve searched table number
            $requestData = $this->request->getGet();

            // Retrieve paginated business table data
            $businessData = BusinessService::handleGetBusinessTableData($user, $requestData);

            // Prepare data for the view
            $data = [
                ...$businessData,
                'business_name' => session()->get('business_name'),
            ];

            // Render the seat management page view with the data
            return view('business/seat-management', $data);

        } catch (Exception $exception) {
            // Set error message if an unexpected exception occurs and redirect to home page
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(HOME_PATH);
        }
    }

    /**
     * Handler for generating a QR code for a specific table.
     *
     * @param int $businessID The ID of the business
     * @param int $tableNumber The table number
     * @return \CodeIgniter\HTTP\Response|void
     */
    public function getTableQRCode($businessID, $tableNumber)
    {
        // Retrieve QR code for table number in the facility of business corresponding to ID
        return BusinessService::handleGetTableQR($businessID, $tableNumber);
    }

}
