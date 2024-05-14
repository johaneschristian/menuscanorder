<?php

namespace App\Filters;

use App\Repositories\BusinessRepository;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class BusinessFilter implements FilterInterface
{
    /**
     * Checks whether the authenticated user owns a business.
     * This will be used to guard business-only endpoints and prevent archived business from accessing the system.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|void Redirects to home page when user does not own a business.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Retrieve the currently authenticated user
        $user = auth()->user();
        
        if (is_null($user)) {
            session()->setFlashdata('error', 'You must log in to access this page');
            return redirect()->to('/login');
        }

        // Retrieve the business associated with the user
        $userBusiness = BusinessRepository::getBusinessByUserID($user->id);

        // Check if the user is not associated with a business or if the business is archived
        if (is_null($userBusiness) || $userBusiness->business_is_archived) {
            // If not associated or archived, set flash data error message and redirect to home page
            session()->setFlashdata('error', 'User is not a business or affiliated business has been archived');
            return redirect()->to('/');
        }

        // Set session data for business ID and business name
        session()->set('business_id', $userBusiness->business_id);
        session()->set('business_name', $userBusiness->business_name);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the controller method is executed
    }
}
