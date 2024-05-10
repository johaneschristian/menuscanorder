<?php

namespace App\Services;

use App\CustomExceptions\InvalidRegistrationException;
use App\Repositories\BusinessRepository;
use App\Repositories\UserRepository;
use App\Utils\Utils;
use App\Utils\Validator;

class AdminService {
    private static function transformMenuListRequestData($requestData) {
        $search = empty($requestData['search']) ? NULL : $requestData['search'];
        $page = (int) ($requestData['page'] ?? 1);

        return [
            'search' => $search ,
            'page' => $page,
        ];
    }

    public static function handleGetUserList($requestData) {
        $transformedRequestData = self::transformMenuListRequestData($requestData);
        $paginatedUsers = UserRepository::getPaginatedUsers(
            $transformedRequestData['search'],
            10,
            $transformedRequestData['page']
        );

        return [
            'users' => $paginatedUsers['result'],
            'pager' => $paginatedUsers['pager'],
            'search' => $transformedRequestData['search'],
        ];
    }

    private static function appendAffiliatedBusinessDataToUser($user) {
        $formattedUser = clone $user;
        $affiliatedBusiness = BusinessRepository::getBusinessByUserId($user->id);
        $formattedUser->has_business = !is_null($affiliatedBusiness);
        
        if (!is_null($affiliatedBusiness)) {
            $formattedUser->business = $affiliatedBusiness;
        }

        return $formattedUser;
    }

    public static function handleGetUserDetails($userID) {
        $user = UserRepository::getUserByIDOrThrowException($userID);
        return [
            'user' => self::appendAffiliatedBusinessDataToUser($user),
        ];
    }

    public static function validateBaseRequestData($requestData, $forCreate = TRUE) {
        $rules = [
            'name' => 'required|string|min_length[3]|max_length[255]',
            'account_type' => 'required|in_list[user,admin]',
            'subscription_status' => 'required|in_list[active,archived]',
        ];

        if ($forCreate) {
            $rules['email'] = 'required|valid_email|max_length[255]';
        }

        $validationResult = Validator::validate($rules, [], $requestData);

        if ($validationResult !== TRUE) {
            throw new InvalidRegistrationException($validationResult);
        }
    }

    public static function handleAdminCreateUser($requestData) {
        $db = \Config\Database::connect();
        $db->transStart();

        $requestData = Utils::trimAllString($requestData);
        self::validateBaseRequestData($requestData, TRUE);
        AuthService::validatePassword($requestData);
        $transformedUserRequestData = AuthService::transformUserData($requestData, TRUE, TRUE);
        $createdUserID = UserRepository::createUser($transformedUserRequestData);

        if (!is_null($requestData['business_name'] ?? NULL) && !empty($requestData['business_name'])) {
            BusinessService::validateBusinessData($requestData);
            $transformedBusinessRequestData = BusinessService::transformBusinessData($requestData, TRUE);
            BusinessRepository::createBusiness($createdUserID, $transformedBusinessRequestData);
        }

        $db->transComplete();
    }

    public static function handleEditUser($updatedUserID, $requestData) {
        $db = \Config\Database::connect();
        $db->transStart();

        $requestData = Utils::trimAllString($requestData);
        self::validateBaseRequestData($requestData, FALSE);
        $transformedUserRequestData = AuthService::transformUserData($requestData, FALSE, TRUE);
        UserRepository::updateUser($updatedUserID, $transformedUserRequestData);

        if (!is_null($requestData['business_name'] ?? NULL) && !empty($requestData['business_name'])) {
            BusinessService::validateBusinessData($requestData);
            $transformedBusinessRequestData = BusinessService::transformBusinessData($requestData, TRUE);

            $userBusiness = BusinessRepository::getBusinessByUserId($updatedUserID);
            if (!is_null($userBusiness)) {
                BusinessRepository::updateBusiness($userBusiness->business_id, $transformedBusinessRequestData);

            } else {
                BusinessRepository::createBusiness($updatedUserID, $transformedBusinessRequestData);
            }
        }

        $db->transComplete();
    }

    public static function handleChangeUserPassword($userID, $requestData) {
        AuthService::validatePassword($requestData);
        UserRepository::getUserByIDOrThrowException($userID);
        $transformedPasswordData = AuthService::transformPasswordData($requestData);
        UserRepository::updateUser($userID, $transformedPasswordData);
    }
}
