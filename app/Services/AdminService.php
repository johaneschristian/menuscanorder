<?php 

namespace App\Services;

use App\CustomExceptions\InvalidRegistrationException;
use App\Repositories\BusinessRepository;
use App\Repositories\UserRepository;
use App\Utils\Utils;
use App\Utils\Validator;
use Exception;

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
        $affiliatedBusiness = BusinessRepository::getBusinessByUserId($user->id);
        $user->has_business = !is_null($affiliatedBusiness);
        
        if (!is_null($affiliatedBusiness)) {
            $user->business = $affiliatedBusiness;
        }

        return $user;
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

    private static function transformUserData($requestData, $forCreate = TRUE) {
        $transformedRequest = [
            'name' => $requestData['name'],
            'is_admin' => $requestData['account_type'] === "admin",
            'is_archived' => $requestData['subscription_status'] === "archived",
        ];

        if ($forCreate) {
            $transformedRequest['username'] = $requestData['email'];
            $transformedRequest['email'] = $requestData['email'];
            $transformedRequest['password'] = $requestData['password'];
        }

        return $transformedRequest;
    }

    private static function transformBusinessCreateRequest($requestData) {
        return [
            'business_name' => $requestData['business_name'],
            'num_of_tables' => $requestData['num_of_tables'],
            'address' => $requestData['address'],
            'business_is_archived' => $requestData['business_subscription_status'] === "archived",
        ];
    }

    public static function handleAdminCreateUser($requestData) {
        $db = \Config\Database::connect();
        $db->transStart();

        $requestData = Utils::trimAllString($requestData);
        self::validateBaseRequestData($requestData, TRUE);
        AuthService::validatePassword($requestData);
        $transformedUserRequestData = self::transformUserData($requestData, TRUE);
        $createdUserID = UserRepository::createUser($transformedUserRequestData);

        if (!is_null($requestData['business_name'] ?? NULL) && !empty($requestData['business_name'])) {
            BusinessService::validateBusinessData($requestData);
            $transformedBusinessRequestData = self::transformBusinessCreateRequest($requestData);
            BusinessRepository::createBusiness($createdUserID, $transformedBusinessRequestData);
        }

        $db->transComplete();
    }

    public static function handleEditUser($updatedUserID, $requestData) {
        $db = \Config\Database::connect();
        $db->transStart();

        $requestData = Utils::trimAllString($requestData);
        self::validateBaseRequestData($requestData, FALSE);
        $transformedUserRequestData = self::transformUserData($requestData, FALSE);
        UserRepository::updateUser($updatedUserID, $transformedUserRequestData);

        if (!is_null($requestData['business_name'] ?? NULL) && !empty($requestData['business_name'])) {
            BusinessService::validateBusinessData($requestData);
            $transformedBusinessRequestData = self::transformBusinessCreateRequest($requestData);

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