<?php

namespace App\Controllers;

use App\Services\AdminService;
use Exception;

const ADMIN_USERS_PATH = '/admin/users';

/**
 * Controller for managing admin-related user operations.
 */
class AdminController extends BaseController
{
    /**
     * Handler for retrieving a list of users and display them.
     *
     * @return string The admin all users page.
     */
    public function getUserList()
    {
        // Retrieve request data
        $requestData = $this->request->getGet();

        // Retrieve all users data
        $usersData = AdminService::handleGetUserList($requestData);

        // Render view with user data
        return view('admin/admin-view-user-list', $usersData);
    }

    /**
     * Handler for retrieving details of a specific user and display them.
     *
     * @param int $userID ID of user whose details want to be viewed
     * @return \CodeIgniter\HTTP\RedirectResponse|string The user details page or redirect when fail.
     */
    public function getUserDetails($userID)
    {
        try {
            // Retrieve user details based on user ID
            $userData = AdminService::handleGetUserDetails($userID);

            // Render view with user details
            return view('admin/admin-view-user-details', $userData);

        } catch (Exception $exception) {
            // Display error message on flashdata and redirect to admin base page
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(ADMIN_USERS_PATH);
        }
    }

    /**
     * Handler for allowing admin to create or edit a user.
     *
     * @param int|null $userID ID of user whose details want to be updated (optional).
     * @return \CodeIgniter\HTTP\RedirectResponse|string The user edit page or redirect when successful/failing.
     */
    public function createOrEditUser($userID = NULL)
    {
        // Define whether operation is create or edit
        $isCreate = is_null($userID);

        try {
            if ($this->request->getMethod() === "post") {
                try {
                    // Retrieve user data
                    $requestData = $this->request->getPost();

                    // Create or update user based on values in request data
                    AdminService::handleAdminCreateEditUser($userID, $requestData);

                    // Set success flashdata and redirect upon successful create or edit
                    if ($isCreate) {
                        session()->setFlashdata('success', 'User is created successfully');

                    } else {
                        session()->setFlashdata('success', 'User is updated successfully');
                    }

                    return redirect()->to(ADMIN_USERS_PATH);

                } catch (Exception $exception) {
                    // Set error flashdata if invalid data is found
                    session()->setFlashdata('error', $exception->getMessage());
                }
            }

            if ($isCreate) {
                // Set user details to empty if creating
                $userData = [];

            } else {
                // Retrieve user details for editing
                $userData = AdminService::handleGetUserDetails($userID);
            }

            // Render view for editing user details
            return view('admin/admin-edit-user-details', $userData);

        } catch (Exception $exception) {
            // Set error flashdata and redirect if general exception occurs during edit
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(ADMIN_USERS_PATH);
        }
    }

    /**
     * Handler for changing password of a user.
     *
     * @param int $userID ID of user whose password want to be viewed
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect to last page when successful/failing.
     */
    public function changeUserPassword($userID)
    {
        try {
            // Retrieve request data for changing password
            $requestData = $this->request->getPost();

            // Change user matching ID's password based on request data
            AdminService::handleAdminChangeUserPassword($userID, $requestData);

            // Set success flashdata and redirect upon successful password change
            session()->setFlashdata('success', 'User password is changed successfully');
            return redirect()->back();
            
        } catch (Exception $exception) {
            // Set error flashdata and redirect if exception occurs during password change
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->back();
        }
    }
}
