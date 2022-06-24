<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->group('admin', function($routes) {
    $routes->get('/', 'AdminController::index', ['namespace' => 'App\Controllers\Admin']);
    $routes->get('products', 'AdminController::products', ['namespace' => 'App\Controllers\Admin']);
    $routes->post('products/upload', 'AdminController::productsUpload', ['namespace' => 'App\Controllers\Admin']);
    $routes->post('products/store', 'AdminController::store', ['namespace' => 'App\Controllers\Admin']);
    $routes->get('products/(:any)/edit', 'AdminController::productsEdit/$1', ['namespace' => 'App\Controllers\Admin']);
    $routes->get('products/(:any)/delete', 'AdminController::productsDelete/$1', ['namespace' => 'App\Controllers\Admin']);
    $routes->post('products/init-datatables-products', 'AdminController::initDatatablesProducts', ['namespace' => 'App\Controllers\Admin']);
    $routes->get('init-total-products', 'AdminController::initTotalProducts', ['namespace' => 'App\Controllers\Admin']);
});
$routes->group('auth', function($routes) {
    $routes->get('/', 'AuthController::index', ['namespace' => 'App\Controllers']);
    $routes->get('logout', 'AuthController::logout', ['namespace' => 'App\Controllers']);
    $routes->post('post-login', 'AuthController::postLogin', ['namespace' => 'App\Controllers']);
    $routes->post('post-register', 'AuthController::postRegister', ['namespace' => 'App\Controllers']);
});
$routes->group('products', function($routes) {
    $routes->get('/', 'ProductController::index', ['namespace' => 'App\Controllers']);
    $routes->get('init-products', 'ProductController::initProducts', ['namespace' => 'App\Controllers']);
    $routes->get('(:any)', 'ProductController::detail/$1', ['namespace' => 'App\Controllers']);
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
