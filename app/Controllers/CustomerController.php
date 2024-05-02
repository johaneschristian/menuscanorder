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
}