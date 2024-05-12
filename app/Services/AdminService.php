<?php

namespace App\Services;

use App\CustomExceptions\InvalidRequestException;
use App\Repositories\BusinessRepository;
use App\Repositories\UserRepository;
use App\Utils\Utils;
use App\Utils\Validator;
use App\CustomExceptions\ObjectNotFoundException;

/**
 * Service to deal with business logic of admin done operations
 */
class AdminService {
    /**
     * Standardize request data for processing.
     *
     * @param array $requestData The array containing the request data.
     * @return array Transformed request data.
     */
    private static function transformUserListRequestData($requestData) {
        // Extract search parameter, set to NULL if empty
        $search = empty($requestData['search']) ? NULL : $requestData['search'];
        
        // Extract page parameter, default to 1 if not provided
        $page = (int) ($requestData['page'] ?? 1);

        // Return transformed request data
        return [
            'search' => $search,
            'page' => $page,
        ];
    }

    /**
     * Handle the retrieval of a paginated user list based on request data.
     *
     * @param array $requestData The request data containing search and page parameters.
     * @return array Data to be displayed, containing paginated user list, pager, and search parameter.
     */
    public static function handleGetUserList($requestData) {
        // Transform request data for standardization
        $transformedRequestData = self::transformUserListRequestData($requestData);
        
        // Retrieve paginated users based on transformed request data
        $paginatedUsers = UserRepository::getPaginatedUsers(
            $transformedRequestData['search'],
            10,
            $transformedRequestData['page']
        );

        // Return paginated user list, pager information, and search parameter
        return [
            'users' => $paginatedUsers['result'],
            'pager' => $paginatedUsers['pager'],
            'search' => $transformedRequestData['search'],
        ];
    }

    /**
     * Append affiliated business data to a user object.
     *
     * @param object $user The base user object.
     * @return object A formatted user object with appended affiliated business data.
     */
    private static function appendAffiliatedBusinessDataToUser($user) {
        // Clone the user object to prevent modification of the original
        $formattedUser = clone $user;
        
        // Retrieve affiliated business for the user
        $affiliatedBusiness = BusinessRepository::getBusinessByUserID($user->id);
        
        // Check if the user has an affiliated business
        $formattedUser->has_business = !is_null($affiliatedBusiness);
        
        // If the user has an affiliated business, append it to the formatted user object
        if (!is_null($affiliatedBusiness)) {
            $formattedUser->business = $affiliatedBusiness;
        }

        return $formattedUser;
    }

    /**
     * Handle the retrieval of user details including affiliated business data.
     *
     * @param int $userID The ID of the user whose details are to be retrieved.
     * @return array An array containing user details.
     * @throws ObjectNotFoundException if user with ID is not found.
     */
    public static function handleGetUserDetails($userID) {
        // Retrieve user details by user ID or throw an exception if not found
        $user = UserRepository::getUserByIDOrThrowException($userID);
        
        // Append affiliated business data to the user details
        return [
            'user' => self::appendAffiliatedBusinessDataToUser($user),
        ];
    }

    /**
     * Validate base request data.
     *
     * @param array $requestData An associative array containing request data to be validated.
     * @param bool $forCreate Indicates whether the validation is for creation (optional, default is TRUE).
     * @throws InvalidRequestException When validation fails, this exception is thrown with the validation errors.
     */
    public static function validateBaseRequestData($requestData, $forCreate = TRUE)
    {
        // Define base validation rules
        $rules = [
            'name' => 'required|string|min_length[3]|max_length[255]',
            'account_type' => 'required|in_list[user,admin]',
            'subscription_status' => 'required|in_list[active,archived]',
        ];

        // Add email validation rule if validation is for user creation
        if ($forCreate) {
            $rules['email'] = 'required|valid_email|max_length[255]';
        }

        // Perform validation
        $validationResult = Validator::validate($rules, [], $requestData);
        if ($validationResult !== TRUE) {
            throw new InvalidRequestException($validationResult);
        }
    }


    /**
     * Handle the creation of a new user by an admin.
     *
     * @param array $requestData The request data containg the user details.
     * @throws InvalidRequestException If request data validation fails.
     */
    public static function handleAdminCreateUser($requestData) {
        // Start a database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        // Trim whitespace from all string values in the request data
        $requestData = Utils::trimAllString($requestData);
        
        // Validate base request data for user creation
        self::validateBaseRequestData($requestData, TRUE);
        
        // Validate password in the request data
        AuthService::validatePassword($requestData);
        
        // Transform request data key and values to repository expected format
        $transformedUserRequestData = AuthService::transformUserData($requestData, TRUE, TRUE);
        
        // Create user
        $createdUserID = UserRepository::createUser($transformedUserRequestData);

        // Check if business data is provided and create a business if necessary
        if (!is_null($requestData['business_name'] ?? NULL) && !empty($requestData['business_name'])) {
            
            // Validate business data
            BusinessService::validateBusinessData($requestData);
            
            // Transform business data to repository expected format
            $transformedBusinessRequestData = BusinessService::transformBusinessData($requestData, TRUE);
            
            // Create business associated with the created user
            BusinessRepository::createBusiness($createdUserID, $transformedBusinessRequestData);
        }

        // Complete the database transaction
        $db->transComplete();
    }

    /**
     * Handle the editing of a user's details by Admin.
     *
     * @param int $updatedUserID The ID of the user to be updated.
     * @param array $requestData The request data containing updated user details.
     * @throws InvalidRequestException If admin submitted data validation fails.
     */
    public static function handleAdminEditUser($updatedUserID, $requestData) {
        // Start a database transaction
        $db = \Config\Database::connect();
        $db->transStart();

        // Trim whitespace from all string values in the request data
        $requestData = Utils::trimAllString($requestData);
        
        // Validate base request data for user editing
        self::validateBaseRequestData($requestData, FALSE);
        
        // Transform user data to repository expected format for editing
        $transformedUserRequestData = AuthService::transformUserData($requestData, FALSE, TRUE);
        
        // Update user details
        UserRepository::updateUser($updatedUserID, $transformedUserRequestData);

        // Check if business data is provided and update/create business accordingly
        if (!is_null($requestData['business_name'] ?? NULL) && !empty($requestData['business_name'])) {
            
            // Validate business data
            BusinessService::validateBusinessData($requestData);
            
            // Transform business data to repository expected format for creation/update
            $transformedBusinessRequestData = BusinessService::transformBusinessData($requestData, TRUE);

            // Check if user already has a business associated
            $userBusiness = BusinessRepository::getBusinessByUserID($updatedUserID);
            
            // If user has a business, update it; otherwise, create a new business
            if (!is_null($userBusiness)) {
                BusinessRepository::updateBusiness($userBusiness->business_id, $transformedBusinessRequestData);

            } else {
                BusinessRepository::createBusiness($updatedUserID, $transformedBusinessRequestData);
            }
        }

        // Complete the database transaction
        $db->transComplete();
    }

    /**
     * Handle the change of a user's password.
     *
     * @param int $userID The ID of the user whose password is to be changed.
     * @param array $requestData The request data containing the new password.
     * @throws InvalidRequestException If the password data is invalid or does not match.
     * @throws ObjectNotFoundException If the user with ID does not exist.
     */
    public static function handleAdminChangeUserPassword($userID, $requestData) {
        // Validate the new password
        AuthService::validatePassword($requestData);
        
        // Retrieve the user by ID or throw an exception if not found
        UserRepository::getUserByIDOrThrowException($userID);
        
        // Transform the password data to repository expected format for updating
        $transformedPasswordData = AuthService::transformPasswordData($requestData);
        
        // Update the user's password
        UserRepository::updateUser($userID, $transformedPasswordData);
    }
}
