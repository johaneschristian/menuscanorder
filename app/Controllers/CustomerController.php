<?php 

namespace App\Controllers;

use App\CustomExceptions\InvalidRegistrationException;
use App\Services\CustomerService;
use Exception;

class CustomerController extends BaseController
{
    public function updateProfile() {
        $user = auth()->user();

        if ($this->request->getMethod() === "post") {
            try {
                $requestData = $this->request->getPost();
                CustomerService::handleUpdateProfile($user, $requestData);
                session()->setFlashdata('success', 'User is updated successfully');
            } catch (Exception $exception) {
                session()->setFlashdata('error', $exception->getMessage());
            }
        }

        $userData = CustomerService::handleGetProfile($user);
        return view('customer/customer-profile-edit', $userData);
    }
}