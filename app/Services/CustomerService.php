<?php

namespace App\Services;

use App\CustomExceptions\InvalidRegistrationException;
use App\Repositories\UserRepository;
use App\Utils\Utils;
use App\Utils\Validator;

class CustomerService
{
    public static function handleGetProfile($user) {
        $userData = UserRepository::getUserByID($user->id);
        return [
            'user' => $userData
        ];
    }

    private static function validateUserData($userData) {
        $rules = [
            'name' => 'required|string|min_length[3]|max_length[255]'
        ];

        $validationResult = Validator::validate($rules, [], $userData);

        if ($validationResult !== TRUE) {
            throw new InvalidRegistrationException($validationResult);
        }
    }

    private static function transformUserData($userData) {
        return [
            'name' => $userData['name'],
        ];
    }

    public static function handleUpdateProfile($user, $requestData) {
        $requestData = Utils::trimAllString($requestData);
        self::validateUserData($requestData);
        $transformedUserData = self::transformUserData($requestData);
        UserRepository::updateUser($user->id, $transformedUserData);
    }
}
