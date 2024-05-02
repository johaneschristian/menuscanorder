<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::index');
$routes->match(['get', 'post'], '/login', 'AuthController::login');
$routes->match(['get', 'post'], '/register', 'AuthController::register');
$routes->get('/logout', 'AuthController::logout');

$routes->group('admin', ['filter' => 'admin'], function ($routes) {
    $routes->group('users', function ($routes) {
        $routes->get('/', 'AdminController::userList');
        $routes->match(['get', 'post'], 'create', 'AdminController::userCreate');
        $routes->get('(:segment)', 'AdminController::userDetails/$1');
        $routes->match(['get', 'post'], '(:segment)/edit', 'AdminController::userEdit/$1');
    });
});

$routes->group('business', ['filter' => 'business'], function ($routes) {
    $routes->get('categories', 'BusinessController::categoryList');
    $routes->post('categories', 'BusinessController::categoryCreate');
    $routes->post('categories/update', 'BusinessController::categoryUpdate');

    $routes->group('menu', function ($routes) {
        $routes->get('/', 'BusinessController::menuList');
        $routes->match(['get', 'post'], 'create', 'BusinessController::menuCreate');
        $routes->match(['get', 'post'], '(:segment)/edit', 'BusinessController::menuEdit/$1');
        $routes->get('(:segment)/image', 'BusinessController::menuGetImage/$1');
    });
    $routes->group('orders', function ($routes) {
        $routes->get('/', 'OrderController::businessOrderList');
        $routes->post('item/update-status/', 'OrderController::businessUpdateOrderItemStatus');
        $routes->post('complete/', 'OrderController::businessCompleteOrder');
        $routes->get('kitchen-view/', 'OrderController::businessOrderKitchenView');
        $routes->get('kitchen-view/data/', 'OrderController::businessGetOrderKitchenViewData');
        $routes->get('detail/(:segment)', 'OrderController::businessOrderDetails/$1');
    });
    $routes->match(['get', 'post'], 'profile', 'BusinessController::profileEdit');

    $routes->group('seat-management', function ($routes) {
        $routes->match(['get', 'post'], '/', 'BusinessController::seatManagement');
        $routes->get('generate-qr/(:segment)/(:segment)', 'BusinessController::getTableQRCode/$1/$2');
    });
    
});

$routes->group('customer', function ($routes) {
    $routes->group('orders', function ($routes) {
        $routes->get('/', 'OrderController::customerOrderList');
        $routes->get('detail/(:segment)', 'OrderController::customerOrderDetail/$1');
        $routes->get('menu/(:segment)/(:segment)', 'OrderController::orderMenu/$1/$2');
        $routes->post('submit/', 'OrderController::orderCreate');
    });
    $routes->get('profile', 'CustomerController::profileEdit');
    $routes->match(['get', 'post'], 'business', 'BusinessController::businessRegistration');
});
