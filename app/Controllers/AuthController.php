<?php

namespace App\Controllers;

use App\Services\AuthService;
use Exception;

const HOME_PATH = '/';
const CUSTOMER_PROFILE_PATH = '/customer/profile';
const LOGIN_PATH = '/login';

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
            try {
                $userData = $this->request->getPost();
                AuthService::handleLogin($userData);

                if (!is_null(session()->get('redirect_url'))) {
                    $redirectURL = session()->get('redirect_url');
                    session()->remove('redirect_url');
                    return redirect()->to($redirectURL);
                } else {
                    return redirect()->to(LOGIN_PATH);
                }
            } catch (Exception $exception) {
                session()->setFlashdata('error', $exception->getMessage());
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
                session()->setFlashdata('success', 'User is created successfully');
                return redirect()->to(LOGIN_PATH);
            } catch (Exception $exception) {
                session()->setFlashdata('error', $exception->getMessage());
            }
        }

        return view('register-page');
    }

    public function logout()
    {
        auth()->logout();
        return redirect()->to(HOME_PATH);
    }

    public function changePassword()
    {
        try {
            $user = auth()->user();
            $requestData = $this->request->getPost();
            AuthService::handleChangePassword($user, $requestData);
            session()->setFlashdata('success', 'Password is changed successfully');
            return redirect()->to(CUSTOMER_PROFILE_PATH);
        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(CUSTOMER_PROFILE_PATH);
        }
    }
}
