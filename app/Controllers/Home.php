<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Events\Events;

class Home extends BaseController
{
    public function index(): string
    {
        $users = auth()->getProvider();

        $user = new UserModel([
            'username' => 'admin3@admin.com',
            'email' => 'admin3@admin.com',
            'password' => 'Password1',
            'name' => 'Admin 3',
        ]);
        $users->save($user);

        $user = $users->findById($users->getInsertID());
        $users->addToDefaultGroup($user);

        return view('landing-page');
    }
}
