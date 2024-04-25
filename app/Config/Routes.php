<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->match(['get', 'post'], '/login', 'Home::login');
$routes->match(['get', 'post'], '/register', 'Home::register');

$routes->group('admin', function ($routes) {
    $routes->group('users', function ($routes) {
        $routes->get('/', 'AdminController::userList');
        $routes->get('create', 'AdminController::userCreate');
        $routes->get('(:segment)', 'AdminController::userDetails/$1');
        $routes->get('(:segment)/edit', 'AdminController::userEdit/$1');
    });
});

$routes->group('business', function ($routes) {
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
        $routes->get('/', 'BusinessController::orderList');
        $routes->get('kitchen-view/', 'BusinessController::orderKitchenView');
        $routes->get('detail/(:segment)', 'BusinessController::orderDetails/$1');
    });
    $routes->get('profile', 'BusinessController::profileEdit');

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
    $routes->match(['get', 'post'], 'business', 'CustomerController::businessRegistration');
});
