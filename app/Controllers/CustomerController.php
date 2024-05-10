<?php

namespace App\Controllers;

use App\Services\CustomerService;
use Exception;

/**
 * Controller for handling customer-specific related operations.
 */
class CustomerController extends BaseController
{
    /**
     * Handler for updating customer profile.
     *
     * @return \CodeIgniter\HTTP\Response|void
     */
    public function updateProfile()
    {
        // Retrieve authenticated user
        $user = auth()->user();

        if ($this->request->getMethod() === "post") {
            try {
                // Get the edited user data
                $requestData = $this->request->getPost();

                // Update profile based on new data
                CustomerService::handleUpdateProfile($user, $requestData);

                // Set success flashdata when update is successful
                session()->setFlashdata('success', 'User is updated successfully');

            } catch (Exception $exception) {
                // Set error message if update fails
                session()->setFlashdata('error', $exception->getMessage());
            }
        }

        // Retrieve user profile data
        $userData = CustomerService::handleGetProfile($user);
        
        // Render the customer profile edit page view with the data
        return view('customer/customer-profile-edit', $userData);
    }
}

