<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::index');
$routes->match(['get', 'post'], '/login', 'AuthController::login');
$routes->match(['get', 'post'], '/register', 'AuthController::register');
$routes->get('/logout', 'AuthController::logout');
$routes->post('/change-password', 'AuthController::changePassword');

// Routes for admin-related functionalities
$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    $routes->group('users', function ($routes) {
        $routes->get('/', 'AdminController::getUserList');
        $routes->match(['get', 'post'], 'create', 'AdminController::createOrEditUser');
        $routes->get('(:segment)', 'AdminController::getUserDetails/$1');
        $routes->match(['get', 'post'], '(:segment)/edit', 'AdminController::createOrEditUser/$1');
        $routes->post('(:segment)/edit/password', 'AdminController::changeUserPassword/$1');
    });
});

// Routes for business-related functionalities
$routes->group('business', ['filter' => 'business'], function ($routes) {
    $routes->group('categories', function ($routes) {
        $routes->get('', 'BusinessController::getCategoryList');
        $routes->post('', 'BusinessController::createCategory');
        $routes->post('update/', 'BusinessController::updateCategory');
        $routes->post('delete/', 'BusinessController::deleteCategory');
    });

    $routes->group('menu', function ($routes) {
        $routes->get('/', 'BusinessController::getMenuList');
        $routes->match(['get', 'post'], 'create', 'BusinessController::createMenu');
        $routes->match(['get', 'post'], '(:segment)/edit', 'BusinessController::editMenu/$1');
        $routes->post('delete/', 'BusinessController::deleteMenu');
    });
    $routes->group('orders', function ($routes) {
        $routes->get('/', 'OrderController::businessGetOrderList');
        $routes->post('item/update-status/', 'OrderController::businessUpdateOrderItemStatus');
        $routes->post('complete/', 'OrderController::businessCompleteOrder');
        $routes->get('kitchen-view/', 'OrderController::businessGetOrderKitchenView');
        $routes->get('kitchen-view/data/', 'OrderController::businessGetOrderKitchenViewData');
        $routes->get('detail/(:segment)', 'OrderController::businessOrderDetails/$1');
    });
    $routes->match(['get', 'post'], 'profile', 'BusinessController::editProfile');

    $routes->group('seat-management', function ($routes) {
        $routes->match(['get', 'post'], '/', 'BusinessController::seatManagement');
        $routes->get('generate-qr/(:segment)/(:segment)', 'BusinessController::getTableQRCode/$1/$2');
    });
});

// Place outside of group to allow non-business user from accessing endpoint
$routes->get('business/menu/(:segment)/image', 'BusinessController::menuGetImage/$1');

// Routes for customer-related functionalities
$routes->group('customer', function ($routes) {
    $routes->group('orders', function ($routes) {
        $routes->get('/', 'OrderController::customerGetOrderList');
        $routes->get('detail/(:segment)', 'OrderController::customerGetOrderDetail/$1');
        $routes->get('menu/(:segment)/(:segment)', 'OrderController::getOrderMenu/$1/$2');
        $routes->post('submit/', 'OrderController::createOrder');
    });
    $routes->match(['get', 'post'], 'profile', 'CustomerController::updateProfile');
    $routes->match(['get', 'post'], 'business', 'BusinessController::registerBusiness');
});
