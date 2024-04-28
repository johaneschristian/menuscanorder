<?php 

namespace App\Controllers;

use App\CustomExceptions\InvalidRegistrationException;
use App\Services\CustomerService;
use Exception;

class CustomerController extends BaseController
{
    public function profileEdit() {
        return view('customer/customer-profile-edit');
    }

    public function businessRegistration() {
        $user = auth()->user();

        if ($this->request->getMethod() === 'post') {
            try {
                $request_data = $this->request->getPost();
                CustomerService::handleBusinessRegistration($user, $request_data);
                session()->setFlashData('success', "Business is created successfully");
                return redirect()->to('/business/orders/');

            } catch (InvalidRegistrationException $exception) {
                session()->setFlashData('error', $exception->getMessage()); 
            }
        }
                
        return view('customer/customer-business-registration');
    }
}