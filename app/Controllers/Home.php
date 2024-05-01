<?php

namespace App\Controllers;
use App\Models\AppUser;
use CodeIgniter\Shield\Entities\User;


class Home extends BaseController
{
    public function index(): string
    {
        // print_r(auth()->user());
        // $users = auth()->getProvider();

        // $user = new User([
        //     'username' => NULL,
        //     'email' => 'admin6@admin.com',
        //     'password' => 'Password1',
        //     'name' => 'Admin 5',
        // ]);
        // $users->save($user);
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
        auth()->logout();

        if ($this->request->getMethod() === 'post') {
            $request_data = $this->request->getPost();
            $loginAttempt = auth()->remember()->attempt([
                'email' => $request_data['email'],
                'password' => $request_data['password']
            ]);

            if (!$loginAttempt->isOK()) {
                return redirect()->back()->with('error', $loginAttempt->reason());
            }

            $users = auth()->getProvider();
            
            $login_user = $users->findByCredentials(['email' => $request_data['email']]);
            session()->set([
                'user_id' => $login_user->id,
                'email' => $login_user->email,
                'is_admin' => $login_user->is_admin, 
            ]);

            return redirect()->to('/');
        }

        return view('login-page');
    }

    public function register() {        
        if ($this->request->getMethod() === 'post') {
            $request_data = $this->request->getPost();
            $users = auth()->getProvider();
            $user = new User([
               'username' => $request_data['email'],
               'email' => $request_data['email'],
               'password' => $request_data['password'],
               'name' => $request_data['name']
            ]);

            $users->save($user);

            return redirect()->to('/login');
        }

        return view('register-page');
    }

    public function logout() {
        auth()->logout();
        return redirect()->to('/');
    }
}
