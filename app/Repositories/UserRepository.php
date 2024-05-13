<?php

namespace App\Repositories;

use App\CustomExceptions\InvalidRequestException;
use App\CustomExceptions\ObjectNotFoundException;
use App\Models\AppUser;
use App\Utils\Utils;
use CodeIgniter\CodeIgniter;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\Shield\Entities\User;
use Exception;

/**
 * Repository to deal with database insertion, retrieval, and update for user model (AppUser)
 */
class UserRepository {
    /**
     * Generate search conditions for filtering AppUser records.
     *
     * @param string $search The search string to filter records.
     * @return string The generated search conditions.
     */
    private static function generateSearchConditions($search) {
        $model = new AppUser();

        // Initialize an empty array to store search conditions
        $conditions = [];

        // Loop through each allowed field in the AppUser model
        foreach ($model->allowedFields as $field) {
            // Generate a search condition for each field using the LIKE operator
            // The condition checks if the value of the field contains the search string
            $conditions[] = "{$model->table}.$field LIKE '%$search%'";
        }

        // Combine all search conditions using OR operator and return as a string
        return implode(' OR ', $conditions);
    }

    /**
     * Retrieve paginated user records from the database.
     *
     * @param string|null $search The search term to filter users (optional).
     * @param int $perPage Number of items per page (optional).
     * @param int $currentPage Current page number (optional).
     * @return array An array of paginated user records.
     */
    public static function getPaginatedUsers($search = NULL, $perPage = 10, $currentPage = 1)
    {
        $model = new AppUser();

        // Build the query to retrieve user records, joining auth_identities to retrieve email
        $query = $model->select('users.id, auth_identities.secret AS email, users.name, users.is_admin, users.is_archived, COUNT(businesses.business_id) > 0 AS has_business')
                       ->join('auth_identities', 'auth_identities.user_id=users.id')
                       ->join('businesses', 'businesses.owning_user_id=users.id', 'left')
                       ->groupBy('users.id, auth_identities.secret, users.is_admin, users.is_archived');
        
        // If a search term is provided, add search conditions to the query
        if (!is_null($search)) {
            $searchCondition = self::generateSearchConditions($search);
            $query = $query->where($searchCondition);
        }

        // Paginate the query results
        return Utils::paginate($query, $perPage, $currentPage);
    }

    /**
     * Retrieve a user record from the database by its ID.
     *
     * @param int $userID The ID of the user to retrieve.
     * @return mixed The user record if found, otherwise NULL.
     */
    public static function getUserByID($userID)
    {
        $model = new AppUser();
        
        // Build the query to retrieve the user record by ID, joining auth_identities to retrieve email
        $query = $model->select('users.id, auth_identities.secret AS email, users.name, users.is_admin, users.is_archived')
                       ->join('auth_identities', 'auth_identities.user_id=users.id')
                       ->where('users.id', $userID);

        // Execute the query and retrieve the first result
        return $query->first();
    }

    /**
     * Retrieve a user record from the database by email.
     *
     * @param string $userEmail The email of the user to retrieve.
     * @return mixed The user record if found, otherwise NULL.
     */
    public static function getUserByEmail($userEmail)
    {
        $users = auth()->getProvider();
        
        // Find the user by their email
        return $users->findByCredentials(['email' => $userEmail]);
    }

    /**
     * Retrieve a user record from the database by its ID or throw an exception if not found.
     *
     * @param int $userID The ID of the user to retrieve.
     * @return object The user record if found.
     * @throws ObjectNotFoundException If the user with the specified ID does not exist.
     */
    public static function getUserByIDOrThrowException($userID)
    {
        $foundUser = self::getUserByID($userID);

        // If the user is not found, throw an exception
        if (is_null($foundUser)) {
            throw new ObjectNotFoundException("User with ID $userID does not exist");

        } else {
            return $foundUser;
        }
    }

    /**
     * Create a new user in the database.
     *
     * @param array $userData The data for the new user.
     * @return int The ID of the newly created user if successful.
     * @throws InvalidRequestException If the user with the provided email is already registered.
     * @throws Exception|DatabaseException If an error occurs during user creation.
     */
    public static function createUser($userData)
    {
        try {
            $users = auth()->getProvider();

            // Create a new User with the provided user data
            $user = new User($userData);

            // Insert the user into the database
            return $users->insert($user, TRUE);

        } catch (Exception | DatabaseException $exception) {
            // Check if the exception message indicates a duplicate entry error
            if ($exception->getMessage() === 'Attempt to read property "id" on null' || str_contains($exception->getMessage(), 'Duplicate')) {
                throw new InvalidRequestException("User with email {$userData['email']} is already registered");
            }

            // If it's not a duplicate entry error, rethrow the original exception
            throw $exception;
        }
    }

    /**
     * Update an existing user in the database.
     *
     * @param int $userID The ID of the user to update.
     * @param array $userData The updated data for the user.
     * @return void
     */
    public static function updateUser($userID, $userData)
    {
        try {
            $users = auth()->getProvider();
        
            // Find the user by their ID
            $matchingUser = $users->findById($userID);
            
            // Fill the user's data with the provided updated data
            $matchingUser->fill($userData);
            
            // Save the updated user data
            $users->save($matchingUser);

        } catch (\Throwable $exception) {
            // Check if the exception message indicates a entry not found
            if ($exception->getMessage() === 'Call to a member function fill() on null') {
                throw new InvalidRequestException("User with ID $userID does not exist");
            }

            throw $exception;
        }
    }
}
