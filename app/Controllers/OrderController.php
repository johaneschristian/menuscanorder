<?php

namespace App\Controllers;

use App\CustomExceptions\InvalidRegistrationException;
use App\CustomExceptions\NotAuthorizedException;
use App\CustomExceptions\ObjectNotFoundException;
use App\Services\OrderService;
use CodeIgniter\Controller;
use Exception;

class OrderController extends Controller {
    public function getOrderMenu($businessId, $tableNumber) {
        try {
            $menuData = OrderService::handleGetBusinessMenus($businessId);
            return view('customer/order-page', $menuData);

        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/customer/orders');
        }
    }

    public function createOrder() {
        try {
            $user = auth()->user();
            $orderData = $this->request->getJSON(true);
            OrderService::handleCreateOrder($user, $orderData);
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

    public function customerGetOrderList() {
        try {
            $user = auth()->user();
            $requestData = $this->request->getGet();
            $customerOrders = OrderService::handleCustomerGetOrderList($user, $requestData);
            return view('customer/customer-order-list', $customerOrders);   
            
        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/');
        }
    }

    public function customerGetOrderDetail($orderId) {
        try {
            $user = auth()->user();
            $orderData = OrderService::handleCustomerGetOrderDetail($user, $orderId);
            return view('customer/customer-order-details', $orderData);

        } catch (ObjectNotFoundException | NotAuthorizedException $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('customer/orders');

        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/');
        }
    }

    public function businessGetOrderList() {
        try {
            $user = auth()->user();
            $user->business_id = session()->get('business_id');
            $requestData = $this->request->getGet();

            $businessOrders = OrderService::handleBusinessGetOrderList($user, $requestData);
            $data = [
                ...$businessOrders,
                'business_name' => session()->get('business_name'),
            ];

            return view('business/business-order-list', $data);

        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/');
        }
    }

    public function businessOrderDetails($orderId) {
        try {
            $user = auth()->user();
            $user->business_id = session()->get('business_id');
            
            $orderData = OrderService::handleBusinessGetOrderDetails($user, $orderId);
            $data = [
                ...$orderData,
                'business_name' => session()->get('business_name'),
            ];
            
            return view('business/business-order-details', $data);

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
            $user->business_id = session()->get('business_id');
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

    public function businessOrderGetKitchenView() {
        try {
            $data = [
                'business_name' => session()->get('business_name'),
            ];

            return view('business/kitchen-view', $data);

        } catch (Exception $exception) {
            session()->setFlashdata('error', $exception->getMessage());
            return redirect()->to('/');
        }        
    }

    public function businessGetOrderKitchenViewData() {
        try {
            $user = auth()->user();
            $user->business_id = session()->get('business_id');
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
            $user->business_id = session()->get('business_id');
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