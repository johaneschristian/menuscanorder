<?php

namespace App\Controllers;

use App\Services\AuthService;
use Exception;

const CUSTOMER_PROFILE_PATH = '/customer/profile';
const LOGIN_PATH = '/login';

/**
 * Controller for handling user authentication related operations.
 */
class AuthController extends BaseController
{
    /**
     * Display the landing page.
     *
     * @return string The landing page.
     */
    public function index()
    {
        return view('landing-page');
    }

    /**
     * Handler for user login.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|string The login page or redirect on successful login.
     */
    public function login()
    {
        // Logout any existing user session
        auth()->logout();

        if ($this->request->getMethod() === 'post') {
            try {
                // Attempt login with provided credentials
                $userData = $this->request->getPost();
                AuthService::handleLogin($userData);

                // Redirect user after successful login
                if (!is_null(session()->get('redirect_url'))) {
                    // Get redirect URL if user was previously redirected to login page
                    $redirectURL = session()->get('redirect_url');
                    session()->remove('redirect_url');
                    return redirect()->to($redirectURL);

                } else {
                    return redirect()->to('/');
                }

            } catch (Exception $exception) {
                // Set error message if login fails
                session()->setFlashdata('error', $exception->getMessage());
            }
        }

        // Render login page
        return view('login-page');
    }

    /**
     * Handler for user registration.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|string The registration page or redirect when registration is successful.
     */
    public function register()
    {
        if ($this->request->getMethod() === 'post') {
            try {
                // Attempt user registration
                $requestData = $this->request->getPost();
                AuthService::handleRegister($requestData);

                // Set success message upon successful registration and redirect to login
                session()->setFlashdata('success', 'User is created successfully');
                return redirect()->to(LOGIN_PATH);

            } catch (Exception $exception) {
                // Set error message if registration fails
                session()->setFlashdata('error', $exception->getMessage());
            }
        }

        // Render registration page
        return view('register-page');
    }

    /**
     * Handler for user logout.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect to home page upon logout.
     */
    public function logout()
    {
        // Logout user and redirect to home page
        auth()->logout();
        return redirect()->to('/');
    }

    /**
     * Handler for changing user password.
     *
     * @return \CodeIgniter\HTTP\RedirectResponse Redirect to customer profile when successful/failing.
     */
    public function changePassword()
    {
        try {
            $user = auth()->user();
            $requestData = $this->request->getPost();

            // Change password of authenticated user based on new password data
            AuthService::handleChangePassword($user, $requestData);

            // Set success message upon successful password change
            session()->setFlashdata('success', 'Password is changed successfully');
            return redirect()->to(CUSTOMER_PROFILE_PATH);
            
        } catch (Exception $exception) {
            // Set error message if password change fails
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to(CUSTOMER_PROFILE_PATH);
        }
    }
}
