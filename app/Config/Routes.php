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
    $routes->match(['get', 'post'], 'business', 'CustomerController::businessRegistration');
});
