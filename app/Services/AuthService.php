<?php

namespace App\Services;

use App\CustomExceptions\InvalidRegistrationException;
use App\CustomExceptions\NotAuthorizedException;
use App\Repositories\BusinessRepository;
use App\Repositories\UserRepository;
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
        self::loginUser($userData['email'] ?? '', $userData['password']);
        $user = UserRepository::getUserByEmail($userData['email']);
        $userBusiness = BusinessRepository::getBusinessByUserId($user->id);
        self::validateUserCanLogin($user);

        session()->set([
            'user_id' => $user->id,
            'email' => $user->email,
            'is_admin' => $user->is_admin,
        ]);
    }

    private static function validateUserRegisterData($userData, $forCreate = TRUE) {
        // TODO: Implement
    }

    public static function validatePassword($userData) {
        $rules = [
            'password' => 'required|max_length[255]|min_length[6]',
            'password_confirmation' => 'required|matches[password]',
        ];

        $validationResult = Validator::validate($rules, [], $userData);

        if ($validationResult !== TRUE) {
            throw new InvalidRegistrationException($validationResult);
        }
    }

    private static function transformUserCreateRequest($userData, $forCreate = TRUE) {
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
 
    public static function handleRegister($userData) {
        self::validateUserRegisterData($userData);
        self::validatePassword($userData);
        $transformedRequestData = self::transformUserCreateRequest($userData, TRUE);
        UserRepository::createUser($transformedRequestData);
    }
}