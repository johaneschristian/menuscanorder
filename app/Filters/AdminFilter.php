<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Config\Services;

class AdminFilter implements FilterInterface
{
    /**
     * Checks whether the authenticated user is an admin.
     * This will be used to guard admin-only endpoints.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|void Redirects to home page when user is not an admin.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Retrieve the currently authenticated user
        $user = auth()->user();

        // Check if the user is not an admin
        if (is_null($user) || !$user->is_admin) {
            // If not an admin, set flash data error message and redirect to home page
            session()->setFlashData("error", "User is not an admin or has not been authenticated.");
            return redirect()->to('/');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the controller method is executed
    }
}
