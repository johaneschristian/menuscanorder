<?php

namespace App\Controllers;

use App\CustomExceptions\InvalidRegistrationException;
use App\Services\AdminService;
use Exception;

class AdminController extends BaseController
{
    public function userList()
    {
        $requestData = $this->request->getGet();
        $usersData = AdminService::handleUserList($requestData);
        return view('admin/admin-view-user-list', $usersData);
    }

    public function userDetails($userId)
    {
        try {
            $userData = AdminService::handleGetUserDetails($userId);
            return view('admin/admin-view-user-details', $userData);
        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/admin/users/');
        }
    }

    public function userCreate()
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

    public function userEdit($userId)
    {
        try {
            if ($this->request->getMethod() === "post") {
                try {
                    $updatedUserData = $this->request->getPost();
                    AdminService::handleAdminEditUser($userId, $updatedUserData);
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
}
