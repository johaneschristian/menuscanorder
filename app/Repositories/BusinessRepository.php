<?php

namespace App\Repositories;

use App\CustomExceptions\ObjectNotFoundException;
use App\Models\BusinessModel;
use App\Utils\Utils;

/**
 * Repository to deal with database insertion, retrieval, and update for business model
 */
class BusinessRepository
{
    /**
     * Retrieve a business record from the database by its ID.
     *
     * @param string $businessId The ID of the business to retrieve.
     * @return mixed The business record if found, otherwise NULL.
     */
    public static function getBusinessById($businessId) {
        $businesses = new BusinessModel();
        return $businesses->where('business_id', $businessId)->first();
    }

    /**
     * Retrieve a business record by ID or throw an exception if not found.
     *
     * @param string $businessId The ID of the business to retrieve.
     * @return object The business record if found.
     * @throws ObjectNotFoundException If the business with the specified ID does not exist.
     */
    public static function getBusinessByIdOrThrowException($businessId)
    {
        // Retrieve the business record by ID
        $foundBusiness = self::getBusinessById($businessId);

        // Check if the business was found, if business is not found, throw an ObjectNotFoundException
        if (is_null($foundBusiness)) {
            throw new ObjectNotFoundException(sprintf("Business with ID %s does not exist", $businessId));
        } else {
            return $foundBusiness;
        }
    }

    /**
     * Retrieve a business record from the database by its owning user ID.
     *
     * @param int $owningUserId The ID of the owning user.
     * @return mixed The business record if found, otherwise NULL.
     */
    public static function getBusinessByUserId($owningUserId)
    {
        $businesses = new BusinessModel();
        return $businesses->where('owning_user_id', $owningUserId)->first();
    }

    /**
     * Create a new business record in the database.
     *
     * @param int $creatingUserID The ID of the user creating the business.
     * @param array $businessData The data for the new business.
     * @return string The ID of the newly created business.
     */
    public static function createBusiness($creatingUserID, $businessData)
    {
        $businesses = new BusinessModel();
        
        // Generate a new UUID for the business ID
        $businessID = Utils::generateUUID();
        
        // Insert the new business data into the database
        $businesses->insert([
            'business_id' => $businessID,
            'owning_user_id' => $creatingUserID,
            'business_name' => $businessData['business_name'],
            'num_of_tables' => $businessData['num_of_tables'],
            'address' => $businessData['address'],
            'is_open' => FALSE,
            'business_is_archived' => $businessData['business_is_archived'] ?? FALSE,
        ]);

        return $businessID;
    }

    /**
     * Update an existing business record in the database.
     *
     * @param string $businessID The ID of the business to update.
     * @param array $businessData The updated data for the business.
     * @return void
     */
    public static function updateBusiness($businessID, $businessData)
    {
        $business = new BusinessModel();
        $business->update($businessID, $businessData);
    }

    /**
     * Retrieve business records from the database that match a given business name pattern.
     *
     * @param string $businessName The name pattern to match.
     * @return array An array of business records matching the name pattern.
     */
    public static function getBusinessesMatchingName($businessName)
    {
        $business = new BusinessModel();
        return $business->like('business_name', $businessName, 'both', NULL, TRUE)->findAll();
    }
}
