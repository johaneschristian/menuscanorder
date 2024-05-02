<?php

namespace App\Controllers;

use App\Services\AuthService;
use Exception;

class AuthController extends BaseController
{
    public function index()
    {
        return view('landing-page');
    }

    public function login()
    {
        auth()->logout();

        if ($this->request->getMethod() === 'post') {
            $userData = $this->request->getPost();
            AuthService::handleLogin($userData);

            if (!is_null(session()->get('redirect_url'))) {
                $redirectURL = session()->get('redirect_url');
                session()->remove('redirect_url');
                return redirect()->to($redirectURL);
            } else {
                return redirect()->to('/');
            }
        }

        return view('login-page');
    }

    public function register()
    {
        if ($this->request->getMethod() === 'post') {
            try {
                $requestData = $this->request->getPost();
                AuthService::handleRegister($requestData);
                session()->setFlashdata('message', 'User is created successfully');
                return redirect()->to('/login');
            } catch (Exception $exception) {
                session()->setFlashdata('error', $exception->getMessage());
            }
        }

        return view('register-page');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->to('/');
    }

    public function changePassword()
    {
        try {
            $user = auth()->user();
            $requestData = $this->request->getPost();
            AuthService::handleChangePassword($user, $requestData);
            session()->setFlashdata('success', 'Password is changed successfully');
            return redirect()->to('/customer/profile');
        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/customer/profile');
        }
    }
}
