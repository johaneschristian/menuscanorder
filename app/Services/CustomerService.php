<?php 

namespace App\Services;

use App\Repositories\BusinessRepository;
use App\CustomExceptions\InvalidRegistrationException;
use App\Utils\Validator;

class CustomerService 
{
    private static function validateBusinessData($creatingUser, $businessData) {
        $rules = [
            'business_name' => 'required|min_length[3]|max_length[255]',
            'num_of_tables' => 'required|is_natural',
            'address' => 'required'
        ];
        $errors = [
            'business_name' => [
                'required' => 'Business name is required.',
                'min_length' => 'Business name length must be between 3 and 255',
                'max_length' => 'Business name length must be between 3 and 255',
            ],
            'num_of_tables' => [
                'required' => 'Number of table is required.',
                'is_natural' => 'Number of table must be greater than 0',
            ],
            'address' => [
                'required' => 'Business address is required.'
            ]
        ];

        $validationResult = Validator::validate($rules, $errors, $businessData);
        
        if(!($validationResult === TRUE)) {
            throw new InvalidRegistrationException($validationResult);

        } else if (!is_null(BusinessRepository::getBusinessByUserId($creatingUser->id))) {
            throw new InvalidRegistrationException("A user can only have one business.");
        }
    }

    public static function handleBusinessRegistration($user, $businessData) {
        self::validateBusinessData($user, $businessData);
        BusinessRepository::createBusiness($user, $businessData);
    }
}

