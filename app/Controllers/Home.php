<?php

namespace App\Controllers;

use App\Models\AppUser;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Events\Events;
use CodeIgniter\Shield\Entities\User;


class Home extends BaseController
{
    public function index(): string
    {
        $users = auth()->getProvider();

        $user = new User([
            'username' => NULL,
            'email' => 'admin6@admin.com',
            'password' => 'Password1',
            'name' => 'Admin 5',
        ]);
        $users->save($user);
        // print_r($users->allowedFields);
        // $users->insert([
        //     'username' => NULL,
        //     'email' => 'admin3@admin.com',
        //     'password' => 'Password1',
        //     'name' => 'Admin 3',
        // ]);
        // $user = new UserModel([
        //     'username' => 'NULL',
        //     'email' => 'admin5@admin.com',
        //     'password' => 'Password1',
        //     'name' => 'Admin 5',
        // ]);
        // $users->save($user);

        // $user = $users->findById($users->getInsertID());
        // $users->addToDefaultGroup($user);

        // $users = new AppUser();
        // $users->insert([
        //     'username' => NULL,
        //     'email' => 'admin4@admin.com',
        //     'password' => 'Password1',
        //     'name' => 'Admin 4'
        // ]);
        
        

        // auth()->logout();
        return view('landing-page');
    }

    public function login() {
        $loginAttempt = auth()->remember()->attempt([
            'email' => 'admin5@admin.com',
            'password' => 'Password1',
        ]);

        print_r($loginAttempt);

        return view('login-page');
    }
}
