<?php

namespace App\Services;

use App\Repositories\BusinessRepository;
use App\CustomExceptions\InvalidRegistrationException;
use App\Repositories\MenuRepository;
use App\Utils\Validator;

class CustomerService
{
    public static function validateBusinessData($businessData)
    {
        $rules = [
            'business_name' => 'required|string|min_length[3]|max_length[255]',
            'num_of_tables' => 'required|is_natural',
            'address' => 'required|string'
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

        if ($validationResult !== TRUE) {
            throw new InvalidRegistrationException($validationResult);
        }
    }

    public static function userHasBusiness($creatingUser) {
        return !is_null(BusinessRepository::getBusinessByUserId($creatingUser->id));
    }

    private static function validateUserBusinessEligibility($creatingUser)
    {
        if (self::userHasBusiness($creatingUser)) {
            throw new InvalidRegistrationException("A user can only have one business.");
        }
    }

    private static function transformBusinessData($businessData)
    {
        return [
            ...$businessData,
            'business_name' => trim($businessData['business_name']),
            'address' => trim($businessData['address']),
        ];
    }

    public static function handleBusinessRegistration($user, $businessData)
    {
        self::validateBusinessData($businessData);
        self::validateUserBusinessEligibility($user);
        $transformedBusinessData = self::transformBusinessData($businessData);
        BusinessRepository::createBusiness($user->id, $transformedBusinessData);
    }
}
