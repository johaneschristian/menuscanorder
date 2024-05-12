<?php

namespace App\Services;

use App\CustomExceptions\InvalidRequestException;
use App\Repositories\UserRepository;
use App\Utils\Utils;
use App\Utils\Validator;

/**
 * Service to deal with business logic for customer profile management operations
 */
class CustomerService
{
    /**
     * Handle the retrieval of user profile data.
     *
     * @param object $user The user object representing the logged-in user.
     * @return array An array containing user profile data.
     */
    public static function handleGetProfile($user)
    {
        // Retrieve user data by their ID
        $userData = UserRepository::getUserByID($user->id);

        // Return the user profile data
        return [
            'user' => $userData
        ];
    }

    /**
     * Validate user data.
     *
     * @param array $userData An associative array containing user data to be validated.
     * @throws InvalidRequestException When validation fails, this exception is thrown with the validation errors.
     */
    private static function validateUserData($userData)
    {
        // Define validation rules for user data
        $rules = [
            'name' => 'required|string|min_length[3]|max_length[255]'
        ];

        // Perform validation
        $validationResult = Validator::validate($rules, [], $userData);
        if ($validationResult !== TRUE) {
            throw new InvalidRequestException($validationResult);
        }
    }

    /**
     * Transform user data to prevent modification of other fields.
     *
     * @param array $userData An associative array containing user data to be transformed.
     * @return array The transformed user data.
     */
    private static function transformUserData($userData)
    {
        // Extract and return the 'name' field from the user data
        return [
            'name' => $userData['name'],
        ];
    }


    /**
     * Handle the update of user profile.
     *
     * @param object $user The user object representing the logged-in user.
     * @param array $requestData The request data containing updated user profile details.
     * @throws InvalidRequestException If validation of user data fails during the update process.
     */
    public static function handleUpdateProfile($user, $requestData)
    {
        // Trim whitespace from all string values in the request data
        $requestData = Utils::trimAllString($requestData);
        
        // Validate the user data for update
        self::validateUserData($requestData);
        
        // Transform user data for update
        $transformedUserData = self::transformUserData($requestData);
        
        // Update the user profile details
        UserRepository::updateUser($user->id, $transformedUserData);
    }
}
