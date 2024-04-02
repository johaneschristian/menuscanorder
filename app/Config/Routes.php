<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
// $routes->get('/login', 'Home::login');
service('auth')->routes($routes);

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
    $routes->group('menu', function ($routes) {
        $routes->get('/', 'BusinessController::menuList');
        $routes->get('create', 'BusinessController::menuCreate');
        $routes->get('(:segment)/edit', 'BusinessController::menuEdit/$1');
    });
    $routes->group('orders', function ($routes) {
        $routes->get('/', 'BusinessController::orderList');
        $routes->get('kitchen-view/', 'BusinessController::orderKitchenView');
        $routes->get('detail/(:segment)', 'BusinessController::orderDetails/$1');
    });
    $routes->get('profile', 'BusinessController::profileEdit');
    $routes->get('seat-management', 'BusinessController::seatManagement');
});

$routes->group('customer', function ($routes) {
    $routes->group('orders', function ($routes) {
        $routes->get('/', 'CustomerController::orderList');
        $routes->get('detail/(:segment)', 'CustomerController::orderDetail/$1');
        $routes->get('menu/(:segment)', 'CustomerController::orderCreate/$1');
    });
    $routes->get('profile', 'CustomerController::profileEdit');
    $routes->get('business', 'CustomerController::businessRegistration');
});
