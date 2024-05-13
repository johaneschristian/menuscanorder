<?php

namespace App\Controllers;

use App\CustomExceptions\InvalidRequestException;
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
     * @return \CodeIgniter\HTTP\RedirectResponse|string The business registration page or redirect when user owns a business.
     */
    public function registerBusiness() {
        try {
            // Retrieve authenticated user
            $user = auth()->user();

            if ($this->request->getMethod() === 'post') {
                try {
                    // Register new business for the authenticated user based on request data
                    $requestData = $this->request->getPost();

                    BusinessService::handleCreateOrEditBusiness($user, $requestData);

                    // Set success message upon successful business registration
                    session()->setFlashData('success', 'Business is created successfully');
                    return redirect()->to(BUSINESS_ORDERS_PATH);

                } catch (InvalidRequestException $exception) {
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
     * Handler for editing business profile.
     *
     * @return string The edit profile page.
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
                BusinessService::handleCreateOrEditBusiness($user, $requestData);

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
     * Handler for retrieving all categories of a business.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|string The category list page or redirect when fail.
     */
    public function getCategoryList()
    {
        try {
            // Retrieve authenticated user and associated business ID (from middleware)
            $user = auth()->user();
            $user->business_id = session()->get('business_id');
            $requestData = $this->request->getGet();
            
            // Retrieve all categories of business, matching name if requested
            $categoriesData = BusinessService::handleGetCategoryList($user, $requestData);
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
     * Handler for creating or updating an existing category.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect to all category page when successful/failing.
     */
    public function createOrEditCategory()
    {
        try {
            // Retrieve authenticated user and associated business ID (from middleware)
            $user = auth()->user();
            $user->business_id = session()->get('business_id');
            
            // Retrieve the category data to update
            $requestData = $this->request->getPost();

            // Determine whether operation is create or update
            $isCreate = !array_key_exists('category_id', $requestData);

            // Update category owned by business affiliated with authenticated user
            BusinessService::handleCreateOrEditCategory($user, $requestData);

            // Set success flashdata when update is successful
            if ($isCreate) {
                session()->setFlashdata('success', 'Category is created successfully');

            } else {
                session()->setFlashdata('success', 'Category is updated successfully');
            }

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
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect to all category page when successful/failing.
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
     * @return \CodeIgniter\HTTP\RedirectResponse|string The menu list page or redirect when failing.
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
     * Handler for editing a menu.
     *
     * @param string|null $menuID The ID of the menu to edit (optional).
     * @return \CodeIgniter\HTTP\RedirectResponse|string The menu edit page or redirect when successful/failing.
     */
    public function createOrEditMenu($menuID = NULL)
    {
        try {
            $isCreate = is_null($menuID);

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
                    BusinessService::handleCreateOrEditMenu($user, $menuID, $requestData, $imageFile);
                    
                    // Set success flashdata when successful
                    if ($isCreate) {
                        session()->setFlashdata('success', 'Menu is created successfully');

                    } else {
                        session()->setFlashdata('success', 'Menu is updated successfully');
                    }
                    
                    // Redirect to the business menu page upon successful update
                    return redirect()->to(BUSINESS_MENU_PATH);

                } catch (InvalidRequestException $exception) {
                    // Set error message if menu editing fails
                    session()->setFlashdata('error', $exception->getMessage());
                }
            }
            
            // Prepare data for view
            $menuData = $isCreate ? BusinessService::handleGetCategoryList($user, []) : BusinessService::handleGetMenuData($user, $menuID);
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
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect to all menu page when successful/failing.
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
     * @param string $menuID The ID of the menu item whose image want to be retrieved
     * @return \CodeIgniter\HTTP\Response|string The image data when successful or an empty string when failing.
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
                
        } catch (Exception $exception) {
            // Return an empty string if an exception occurs
            return "";
        }
    }

    /**
     * Handler for managing seat capacity.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|string The seat management page or redirect when successful/failing.
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

                } catch (InvalidRequestException $exception) {
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
     * @param string $businessID The ID of the business.
     * @param int $tableNumber The table number.
     * @return string The rendered QR png data.
     */
    public function getTableQRCode($businessID, $tableNumber)
    {
        // Retrieve QR code for table number in the facility of business corresponding to ID
        return BusinessService::handleGetTableQR($businessID, $tableNumber);
    }
}
