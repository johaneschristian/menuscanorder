<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LoginRedirectFilter implements FilterInterface
{
    /**
     * Record last intended URL before login page.
     * 
     * This middleware is used to allow the application to redirect to the user's
     * intended page after a successful login, if they were trying to access a page
     * that requires authentication without logging in.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return void.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Check if the current URL does not contain "login" or "register"
        if (!str_contains(current_url(), "login") && !str_contains(current_url(), "register")) {
            // If not, set the redirect URL in the session to the current URL
            session()->set('redirect_url', current_url());
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the controller method is executed
    }
}
