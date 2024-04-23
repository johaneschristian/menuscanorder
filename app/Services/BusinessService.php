<?php

namespace App\Services;

use App\CustomExceptions\InvalidRegistrationException;
use App\Repositories\BusinessRepository;
use App\Repositories\CategoryRepository;
use App\Utils\Validator;

class BusinessService 
{
    private static function validateCategoryData($categoryData) {
        $rules = [
            'category_name' => 'required|min_length[3]|max_length[255]',
        ];

        $validationResult = Validator::validate($rules, [], $categoryData);

        if(!($validationResult === TRUE)) {
            throw new InvalidRegistrationException($validationResult);
        }
    }

    public static function handleCategoryCreation($user, $categoryData) {
        self::validateCategoryData($categoryData);
        $userBusiness = BusinessRepository::getBusinessByUserIdOrThrowException($user->id);
        CategoryRepository::createCategory($userBusiness, $categoryData);
    }
}