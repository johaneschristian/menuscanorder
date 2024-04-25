<?php

namespace App\Repositories;

use App\CustomExceptions\ObjectNotFoundException;
use App\Models\BusinessModel;
use App\Utils\Utils;

class BusinessRepository
{
    public static function getBusinessByUserId($owningUserId) {
        $businesses = new BusinessModel();
        return $businesses->where('owning_user_id', $owningUserId)->first();
    }

    public static function getBusinessByUserIdOrThrowException($owningUserId) {
        $foundBusiness = self::getBusinessByUserId($owningUserId);
        if(is_null($foundBusiness)) {
            throw new ObjectNotFoundException(sprintf("Business belonging to user with ID % does not exist", $owningUserId));

        } else {
            return $foundBusiness;
        }
    }

    public static function createBusiness($creatingUser, $businessData) {
        $businesses = new BusinessModel();
        $businesses->insert([
            'business_id' => Utils::generateUUID(),
            'owning_user_id' => $creatingUser->id,
            'business_name' => $businessData['business_name'],
            'num_of_tables' => $businessData['num_of_tables'],
            'address' => $businessData['address'],
            'is_open' => FALSE,
            'business_is_archived' => FALSE,
        ]);
    } 

    public static function updateBusinessData($businessID, $businessData) {
        $business = new BusinessModel();
        $business->where('business_id', $businessID)
                 ->set($businessData)
                 ->update();
    }
}