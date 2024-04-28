<?php 

namespace App\Repositories;

use App\CustomExceptions\ObjectNotFoundException;
use App\Models\UserModel;
use CodeIgniter\Shield\Entities\User;

class UserRepository {
    private static function generateSearchConditions($search) {
        $model = new UserModel();
        $conditions = [];

        // Loop through each allowed field in the UserModel
        foreach ($model->allowedFields as $field) {
            // Generate a search condition for each field using LIKE operator
            $conditions[] = "{$model->table}.$field LIKE '%$search%'";
        }

        return implode(' OR ', $conditions);
    }

    public static function getPaginatedUsers($search = NULL, $perPage = 10, $currentPage = 1) {
        $model = new UserModel();

        $query = $model->select('users.id, auth_identities.secret AS email, users.name, users.is_admin, users.is_archived, COUNT(businesses.business_id) > 0 AS has_business')
                       ->join('auth_identities', 'auth_identities.user_id=users.id')
                       ->join('businesses', 'businesses.owning_user_id=users.id', 'left')
                       ->groupBy('users.id, auth_identities.secret, users.is_admin, users.is_archived');
        
        if ($search !== NULL) {
            $searchCondition = self::generateSearchConditions($search);
            $query = $query->where($searchCondition);
        }

        return [
            'result' => $query->paginate($perPage, 'default', $currentPage),
            'pager' => $query->pager,
        ];
    }

    public static function getUserByID($userID) {
        $model = new UserModel();
        $query = $model->select('users.id, auth_identities.secret AS email, users.name, users.is_admin, users.is_archived')
                       ->join('auth_identities', 'auth_identities.user_id=users.id')
                       ->where('users.id', $userID);

        return $query->first();
    }

    public static function getUserByIDOrThrowException($userID) {
        $foundUser = self::getUserByID($userID);

        if ($foundUser === NULL) {
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
        $users->update($userID, $userData);
    }
}