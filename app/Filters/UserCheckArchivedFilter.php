<?php

namespace App\Filters;

use App\Repositories\UserRepository;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class UserCheckArchivedFilter implements FilterInterface
{
    /**
     * Checks whether the authenticated user is archived.
     * This will be used to prevent archived users from accessing the system.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|void Redirects to home page when user is archived.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $user = auth()->user();

        if ($user->is_archived) {
            session()->setFlashData("error", "User is archived.");
            return redirect()->to('/logout');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the controller method is executed
    }
}
