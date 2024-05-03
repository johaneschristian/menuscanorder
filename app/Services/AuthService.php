<?php

namespace App\Services;

use App\CustomExceptions\InvalidRegistrationException;
use App\CustomExceptions\NotAuthorizedException;
use App\Repositories\UserRepository;
use App\Utils\Utils;
use App\Utils\Validator;

class AuthService {
    private static function loginUser($email, $password) {
        $loginAttempt = auth()->remember()->attempt([
            'email' => $email,
            'password' => $password,
        ]);

        if (!$loginAttempt->isOK()) {
            throw new NotAuthorizedException($loginAttempt->reason());
        }
    }

    private static function validateUserCanLogin($user) {
        if ($user->is_archived) {
            throw new NotAuthorizedException("User is archived");
        }
    }

    public static function handleLogin($userData) {
        $user = UserRepository::getUserByEmail($userData['email']);
        self::validateUserCanLogin($user);
        self::loginUser($userData['email'] ?? '', $userData['password']);
    }

    private static function validateUserRegisterData($userData) {
        $rules = [
            'name' => 'required|string|min_length[3]|max_length[255]',
            'email' => 'required|valid_email',
        ];

        $validationResult = Validator::validate($rules, [], $userData);

        if ($validationResult !== TRUE) {
            throw new InvalidRegistrationException($validationResult);
        }
    }

    public static function validatePassword($passwordData) {
        $rules = [
            'password' => 'required|string|min_length[6]|max_length[255]',
            'password_confirmation' => 'required|string|matches[password]',
        ];

        $validationResult = Validator::validate($rules, [], $passwordData);

        if ($validationResult !== TRUE) {
            throw new InvalidRegistrationException($validationResult);
        }
    }

    private static function transformUserData($userData, $forCreate = TRUE) {
        $transformedRequest = [
            'name' => $userData['name'],
        ];

        if ($forCreate) {
            $transformedRequest['username'] = $userData['email'];
            $transformedRequest['email'] = $userData['email'];
            $transformedRequest['password'] = $userData['password'];
        }

        return $transformedRequest;
    }
 
    public static function handleRegister($requestData) {
        $requestData = Utils::trimAllString($requestData);
        self::validateUserRegisterData($requestData);
        self::validatePassword($requestData);
        $transformedRequestData = self::transformUserData($requestData, TRUE);
        UserRepository::createUser($transformedRequestData);
    }

    private static function validateUserCurrentPassword($email, $password) {
        $authCheck = auth()->check([
            'email' => $email,
            'password' => $password
        ]);

        if (!$authCheck->isOK()) {
            throw new InvalidRegistrationException("Password is incorrect");
        }
    }

    public static function transformPasswordData($passwordData) {
        return [
            'password' => $passwordData['password'],
        ];
    }

    public static function handleChangePassword($user, $passwordData) {
        $userData = UserRepository::getUserByID($user->id);
        self::validatePassword($passwordData);
        self::validateUserCurrentPassword($userData->email, $passwordData['old_password'] ?? '');
        $transformedPasswordData = self::transformPasswordData($passwordData);
        UserRepository::updateUser($user->id, $transformedPasswordData);
    }
}