<?php 

namespace App\Controllers;
use App\Services\CustomerService;


class CustomerController extends BaseController
{
    public function profileEdit() {
        return view('customer/customer-profile-edit');
    }

    public function businessRegistration() {
        $user = auth()->user();

        if ($this->request->getMethod() === 'post') {
            $request_data = $this->request->getPost();
            CustomerService::handleBusinessRegistration($user, $request_data);
        }

        return view('customer/customer-business-registration');
    }
}