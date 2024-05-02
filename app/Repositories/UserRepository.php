<?php 

namespace App\Repositories;

use App\CustomExceptions\ObjectNotFoundException;
use App\Models\AppUser;
use App\Utils\Utils;
use CodeIgniter\Shield\Entities\User;

class UserRepository {
    private static function generateSearchConditions($search) {
        $model = new AppUser();
        $conditions = [];

        // Loop through each allowed field in the AppUser
        foreach ($model->allowedFields as $field) {
            // Generate a search condition for each field using LIKE operator
            $conditions[] = "{$model->table}.$field LIKE '%$search%'";
        }

        return implode(' OR ', $conditions);
    }

    public static function getPaginatedUsers($search = NULL, $perPage = 10, $currentPage = 1) {
        $model = new AppUser();

        $query = $model->select('users.id, auth_identities.secret AS email, users.name, users.is_admin, users.is_archived, COUNT(businesses.business_id) > 0 AS has_business')
                       ->join('auth_identities', 'auth_identities.user_id=users.id')
                       ->join('businesses', 'businesses.owning_user_id=users.id', 'left')
                       ->groupBy('users.id, auth_identities.secret, users.is_admin, users.is_archived');
        
        if (!is_null($search)) {
            $searchCondition = self::generateSearchConditions($search);
            $query = $query->where($searchCondition);
        }

        return Utils::paginate($query, $perPage, $currentPage);
    }

    public static function getUserByID($userID) {
        $model = new AppUser();
        $query = $model->select('users.id, auth_identities.secret AS email, users.name, users.is_admin, users.is_archived')
                       ->join('auth_identities', 'auth_identities.user_id=users.id')
                       ->where('users.id', $userID);

        return $query->first();
    }

    public static function getUserByEmail($userEmail) {
        $users = auth()->getProvider();
        return $users->findByCredentials(['email' => $userEmail]);
    }

    public static function getUserByIDOrThrowException($userID) {
        $foundUser = self::getUserByID($userID);

        if (is_null($foundUser)) {
            throw new ObjectNotFoundException("User with ID $userID does not exist");

        } else {
            return $foundUser;
        }
    }

    public static function createUser($userData) {
        $users = auth()->getProvider();
        $user = new User($userData);
        return $users->insert($user, TRUE);
    }

    public static function editUser($userID, $userData) {
        $users = auth()->getProvider();
        $matchingUser = $users->findById($userID);
        $matchingUser->fill($userData);
        $users->save($matchingUser);
    }
}