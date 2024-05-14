<?php

namespace App\Services;

use App\CustomExceptions\InvalidRequestException;
use App\CustomExceptions\NotAuthorizedException;
use App\CustomExceptions\ObjectNotFoundException;
use App\Repositories\UserRepository;
use App\Utils\Utils;
use App\Utils\Validator;

/**
 * Service to deal with business logic for authentication related operations
 */
class AuthService
{
    /**
     * Attempt to log in a user with the provided email and password.
     *
     * @param string $email The email of the user attempting to log in.
     * @param string $password The password of the user attempting to log in.
     * @throws NotAuthorizedException If the login attempt fails.
     */
    private static function loginUser($email, $password)
    {
        // Attempt to log in the user
        $loginAttempt = auth()->remember()->attempt([
            'email' => $email,
            'password' => $password,
        ]);

        // If not successful, throw a NotAuthorizedException with the reason for the failure
        if (!$loginAttempt->isOK()) {
            throw new NotAuthorizedException($loginAttempt->reason());
        }
    }

    /**
     * Validate if a user can login.
     *
     * @param object $user The user object to validate.
     * @throws NotAuthorizedException If the user is archived.
     */
    private static function validateUserCanLogin($user)
    {
        // Throw an exception indicating that the user is not authorized to log in because they are archived
        if (!is_null($user) && $user->is_archived) {
            throw new NotAuthorizedException("User is archived");
        }
    }

    /**
     * Handle user login process.
     *
     * @param array $userData Array containing user email and password.
     * @throws NotAuthorizedException If the user is not allowed to log in or password is incorrect.
     */
    public static function handleLogin($userData)
    {
        // Retrieve user data based on email
        $user = UserRepository::getUserByEmail($userData['email']);

        // Validate if the user can login
        self::validateUserCanLogin($user);

        // Attempt to login the user using provided email and password
        self::loginUser($userData['email'] ?? '', $userData['password']);
    }

    /**
     * Validate user registration data.
     *
     * @param array $userData An associative array containing user registration data to be validated.
     * @throws InvalidRequestException When validation fails, this exception is thrown with the validation errors.
     */
    private static function validateUserRegisterData($userData)
    {
        // Define validation rules for user registration data
        $rules = [
            'name' => 'required|string|min_length[3]|max_length[255]',
            'email' => 'required|valid_email',
        ];

        // Perform validation
        $validationResult = Validator::validate($rules, [], $userData);
        if ($validationResult !== TRUE) {
            throw new InvalidRequestException($validationResult);
        }
    }

    /**
     * Validate password and password confirmation data.
     *
     * @param array $passwordData An associative array containing password data to be validated.
     * @throws InvalidRequestException When validation fails, this exception is thrown with the validation errors.
     */
    public static function validatePassword($passwordData)
    {
        // Define validation rules for password data
        $rules = [
            'password' => 'required|string|min_length[6]|max_length[255]',
            'password_confirmation' => 'required|string|matches[password]',
        ];

        // Perform validation
        $validationResult = Validator::validate($rules, [], $passwordData);
        if ($validationResult !== TRUE) {
            throw new InvalidRequestException($validationResult);
        }
    }

    /**
     * Transform user data request data keys to model related fields.
     * Also prevents user from embedding additional field that can modify
     * database record directly.
     *
     * @param array $userData Array containing user data.
     * @param bool $forCreate Whether the transformation is for user creation.
     * @param bool $isModifiedByAdmin Whether the user data is modified by an admin.
     * @return array Transformed user data.
     */
    public static function transformUserData($userData, $forCreate = TRUE, $isModifiedByAdmin = FALSE)
    {
        // Initialize the transformed request array with basic user data.
        $transformedRequest = [
            'name' => $userData['name'],
        ];

        // If user data is modified by an admin, include additional fields.
        if ($isModifiedByAdmin) {
            $transformedRequest['is_admin'] = $userData['account_type'] === "admin";
            $transformedRequest['is_archived'] = $userData['subscription_status'] === "archived";
        }

        // If transformation is for user creation, include username, email, and password.
        if ($forCreate) {
            $transformedRequest['username'] = $userData['email'];
            $transformedRequest['email'] = $userData['email'];
            $transformedRequest['password'] = $userData['password'];
        }

        // Return the transformed user data.
        return $transformedRequest;
    }

    /**
     * Handle user registration process.
     *
     * @param array $requestData Array containing registration data.
     * @throws InvalidRequestException If the registration data is invalid.
     */
    public static function handleRegister($requestData)
    {
        // Trim all string values in the request data
        $requestData = Utils::trimAllString($requestData);

        // Validate the registration data
        self::validateUserRegisterData($requestData);

        // Validate the password in the registration data
        self::validatePassword($requestData);

        // Transform the registration data for user creation to match repository expected format
        $transformedRequestData = self::transformUserData($requestData, TRUE);

        // Create user using transformed registration data
        UserRepository::createUser($transformedRequestData);
    }

    /**
     * Validate user's current password.
     *
     * @param string $email The email of the user.
     * @param string $password The current password of the user.
     * @throws InvalidRequestException When the provided password is incorrect.
     */
    private static function validateUserCurrentPassword($email, $password)
    {
        // Check authentication using provided email and password
        $authCheck = auth()->check([
            'email' => $email,
            'password' => $password
        ]);

        // If authentication check fails, throw an exception indicating incorrect password
        if (!$authCheck->isOK()) {
            throw new InvalidRequestException("Password is incorrect");
        }
    }

    /**
     * Transform password data to prevent modification of additional fields.
     *
     * @param array $passwordData Array containing password data.
     * @return array Sanitized password data.
     */
    public static function transformPasswordData($passwordData)
    {
        // Return password data containing only the password field
        return [
            'password' => $passwordData['password'],
        ];
    }

    /**
     * Helper class to change user password.
     *
     * @param int $userID The ID of the user whose password wants to be changed.
     * @param array $passwordData Array containing password data.
     * @param bool $checkOldPassword Whether to check old password before changing password (optional).
     * @throws ObjectNotFoundException If the user with ID does not exist.
     * @throws InvalidRequestException If the password data is invalid or the current password is incorrect.
     */
    public static function changePassword($userID, $passwordData, $checkOldPassword = TRUE)
    {
        // Check if user exists and throw an exception if not found
        $userData = UserRepository::getUserByIDOrThrowException($userID);

        // Validate the new password data
        self::validatePassword($passwordData);

        if ($checkOldPassword) {
            // Validate the current password
            self::validateUserCurrentPassword($userData->email, $passwordData['old_password'] ?? '');
        }

        // Sanitize the new password data to prevent unrelated data modification
        $transformedPasswordData = self::transformPasswordData($passwordData);

        // Update user's password using transformed password data
        UserRepository::updateUser($userID, $transformedPasswordData);
    }

    /**
     * Handle changing user password.
     *
     * @param object $user The user object representing the logged-in user, changing the password.
     * @param array $passwordData Array containing password data.
     * @throws InvalidRequestException If the password data is invalid or the current password is incorrect.
     */
    public static function handleChangePassword($user, $passwordData)
    {
        // Change user password by first checking old password
        self::changePassword($user->id, $passwordData);
    }
}
