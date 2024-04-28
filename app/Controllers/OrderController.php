<?php

namespace App\Controllers;

use App\CustomExceptions\InvalidRegistrationException;
use App\CustomExceptions\NotAuthorizedException;
use App\CustomExceptions\ObjectNotFoundException;
use App\Services\BusinessService;
use App\Services\OrderService;
use CodeIgniter\Controller;
use Exception;

class OrderController extends Controller {
    public function orderMenu($businessId, $tableNumber) {
        try {
            $menuData = OrderService::handleGetBusinessMenus($businessId);
            return view('customer/order-page', $menuData);

        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/customer/orders');
        }
    }

    public function orderCreate() {
        try {
            $user = auth()->user();
            $orderData = $this->request->getJSON(true);
            OrderService::handleCreateOrder($user, $orderData);
            throw new InvalidRegistrationException("HELLO WORLD");
            session()->setFlashdata('success', 'Order is created successfully');
            return $this->response->setContentType('application/json')
                                  ->setStatusCode(200)
                                  ->setBody(json_encode(['message' => 'Order is created successfully']));

        } catch (InvalidRegistrationException $exception) {
            return $this->response->setContentType('application/json')
                                  ->setStatusCode(400)
                                  ->setBody(json_encode(['message' => $exception->getMessage()]));

        } catch (ObjectNotFoundException $exception) {
            return $this->response->setContentType('application/json')
                                  ->setStatusCode(404)
                                  ->setBody(json_encode(['message' => $exception->getMessage()]));

        } catch (Exception $exception) {
            return $this->response->setContentType('application/json')
                                  ->setStatusCode(500)
                                  ->setBody(json_encode(['message' => $exception->getMessage()]));
        }
    }

    public function customerOrderList() {
        try {
            $user = auth()->user();
            $requestData = $this->request->getGet();
            $customerOrders = OrderService::handleCustomerOrderList($user, $requestData);
            return view('customer/customer-order-list', $customerOrders);   
            
        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/');
        }
    }

    public function customerOrderDetail($orderId) {
        try {
            $user = auth()->user();
            $orderData = OrderService::handleCustomerOrderDetail($user, $orderId);
            return view('customer/customer-order-details', $orderData);

        } catch (ObjectNotFoundException | NotAuthorizedException $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('customer/orders');

        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/');
        }
    }

    public function businessOrderList() {
        try {
            $user = auth()->user();
            $requestData = $this->request->getGet();
            $businessOrders = OrderService::handleBusinessOrderList($user, $requestData);
            return view('business/business-order-list', $businessOrders);

        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/');
        }
    }

    public function businessOrderDetails($orderId) {
        try {
            $user = auth()->user();
            $orderData = OrderService::handleBusinessOrderDetails($user, $orderId);
            return view('business/business-order-details', $orderData);

        } catch (ObjectNotFoundException | NotAuthorizedException $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/business/orders');
        
        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/');
        }
    }

    public function businessCompleteOrder() {
        try {
            $user = auth()->user();
            $requestData = $this->request->getPost();
            OrderService::handleBusinessCompleteOrder($user, $requestData);
            session()->setFlashdata('success', 'Order status is updated successfully');
            return redirect()->to('/business/orders/');

        } catch (ObjectNotFoundException | NotAuthorizedException $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/business/orders');
        
        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/');
        }
    }

    public function businessOrderKitchenView() {
        try {
            $user = auth()->user();
            $userBusiness = BusinessService::getBusinessByUserOrNonAuthorized($user);
            return view('business/kitchen-view');

        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/');
        }        
    }

    public function businessGetOrderKitchenViewData() {
        try {
            $user = auth()->user();
            $responseData = OrderService::handleBusinessGetOrderKitchenData($user);
            return $this->response
                        ->setContentType('application/json')
                        ->setStatusCode(200)
                        ->setBody(json_encode($responseData));

        } catch (NotAuthorizedException $exception) {
            return $this->response
                        ->setContentType('application/json')
                        ->setStatusCode(403)
                        ->setBody(json_encode(['message' => $exception->getMessage()]));

        } catch (Exception $exception) {
            return $this->response
                        ->setContentType('application/json')
                        ->setStatusCode(500)
                        ->setBody(json_encode(['message' => $exception->getMessage()]));
        }
    }

    public function businessUpdateOrderItemStatus() {
        try {
            $user = auth()->user();
            $updateData = $this->request->getJSON(true);
            OrderService::handleBusinessUpdateOrderItemStatus($user, $updateData);
            return $this->response
                    ->setContentType('application/json')
                    ->setStatusCode(200)
                    ->setBody(json_encode(['message' => 'Order item was successfully updated']));

        } catch (NotAuthorizedException $exception) {
            return $this->response
                        ->setContentType('application/json')
                        ->setStatusCode(403)
                        ->setBody(json_encode(['message' => $exception->getMessage()]));

        } catch (ObjectNotFoundException $exception) {
            return $this->response
                        ->setContentType('application/json')
                        ->setStatusCode(404)
                        ->setBody(json_encode(['message' => $exception->getMessage()]));

        } catch (InvalidRegistrationException $exception) {
            return $this->response
                        ->setContentType('application/json')
                        ->setStatusCode(400)
                        ->setBody(json_encode(['message' => $exception->getMessage()]));

        } catch (Exception $exception) {
            return $this->response
                        ->setContentType('application/json')
                        ->setStatusCode(500)
                        ->setBody(json_encode(['message' => $exception->getMessage()]));
        }
    }
}