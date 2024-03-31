<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    public function userList(): string
    {
        return view('admin/admin-view-user-list');
    }

    public function userDetails($userId): string
    {
        return view('admin/admin-view-user-details');
    }

    public function userCreate(): string
    {
        return view('admin/admin-create-user');
    }

    public function userEdit($userId): string
    {
        return view('admin/admin-edit-user-details');
    }
}