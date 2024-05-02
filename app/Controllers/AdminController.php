<?php

namespace App\Controllers;

use App\CustomExceptions\InvalidRegistrationException;
use App\Services\AdminService;
use Exception;

class AdminController extends BaseController
{
    public function getUserList()
    {
        $requestData = $this->request->getGet();
        $usersData = AdminService::handleGetUserList($requestData);
        return view('admin/admin-view-user-list', $usersData);
    }

    public function getUserDetails($userId)
    {
        try {
            $userData = AdminService::handleGetUserDetails($userId);
            return view('admin/admin-view-user-details', $userData);
        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/admin/users/');
        }
    }

    public function createUser()
    {
        try {
            if ($this->request->getMethod() === "post") {
                try {
                    $userData = $this->request->getPost();
                    AdminService::handleAdminCreateUser($userData);
                    session()->setFlashdata('success', 'User is created successfully');
                    return redirect()->to('/admin/users/');
                } catch (InvalidRegistrationException $exception) {
                    session()->setFlashdata('error', $exception->getMessage());
                }
            }

            return view('admin/admin-create-user');
        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/admin/users/');
        }
    }

    public function editUser($userId)
    {
        try {
            if ($this->request->getMethod() === "post") {
                try {
                    $updatedUserData = $this->request->getPost();
                    AdminService::handleEditUser($userId, $updatedUserData);
                    session()->setFlashdata('success', 'User is updated successfully');
                    return redirect()->to('/admin/users/');
                    
                } catch (InvalidRegistrationException $exception) {
                    session()->setFlashdata('error', $exception->getMessage());
                }
            }

            $userData = AdminService::handleGetUserDetails($userId);
            return view('admin/admin-edit-user-details', $userData);

        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/admin/users/');
        }
    }

    public function changeUserPassword($userID) {
        try {
            $requestData = $this->request->getPost();
            AdminService::handleChangeUserPassword($userID, $requestData);
            session()->setFlashdata('success', 'User password is changed successfully');
            return redirect()->back();

        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->back();
        }
    }
}
