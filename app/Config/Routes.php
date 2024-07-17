<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->get('/', 'Dealer\Login::index');

$routes->group('dealer', ['namespace' => 'App\Controllers\Dealer'], function ($routes) {

    $routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);
    $routes->match(['get', 'post'], 'updatePlanPreference', 'Dashboard::updatePlanPreference', ['filter' => 'auth']);

    $routes->match(['get', 'post'], 'forgot-password', 'UserPassword::index');
    $routes->match(['get', 'post'], 'send-reset-password-link', 'UserPassword::sendPwdResetLink');
    $routes->match(['get', 'post'], 'logout', 'Logout::index');
    $routes->match(['get', 'post'], 'reset-password/(:any)', 'UserPassword::reset_password/$1');
    $routes->match(['get', 'post'], 'update-password', 'UserPassword::update_password');
    $routes->match(['get', 'post'], 'profile', 'UserPassword::dealer_profile', ['filter' => 'auth']);
    $routes->match(['get', 'post'], 'update-profile-details', 'UserPassword::update_profile_details', ['filter' => 'auth']);


    $routes->get('list-vehicles', 'Vehicles::index', ['filter' => 'auth']);
    $routes->match(['get', 'post'], 'add-vehicle', 'Vehicles::add_vehicle', ['filter' => 'auth']);
    $routes->match(['get', 'post'], 'save-new-vehicle', 'Vehicles::save_new_vehicle');
    $routes->match(['get', 'post'], 'edit-vehicle/(:num)', 'Vehicles::edit_vehicle/$1', ['filter' => 'auth']);
    $routes->match(['get', 'post'], 'single-vehicle-info/(:num)', 'Vehicles::single_vehicle_info/$1', ['filter' => 'auth']);

    $routes->match(['get', 'post'], 'update-vehicle', 'Vehicles::update_vehicle', ['filter' => 'auth']);
    $routes->match(['get', 'post'], 'upload-exterior-main-vehicle-images', 'Vehicles::upload_exterior_main_vehicle_images');
    $routes->match(['get', 'post'], 'upload-thumbnail', 'Vehicles::upload_thumbnail');
    $routes->match(['get', 'post'], 'upload-vehicle-images', 'Vehicles::upload_vehicle_images');
    $routes->match(['get', 'post'], 'update-vehicle-image', 'Vehicles::update_vehicle_image');
    $routes->match(['get', 'post'], 'upload-interior-vehicle-images', 'Vehicles::upload_interior_vehicle_images');
    $routes->match(['get', 'post'], 'upload-others-vehicle-images', 'Vehicles::upload_others_vehicle_images');
    $routes->match(['get', 'post'], 'deleteVehicle/(:num)', 'Vehicles::delete/$1');
    $routes->match(['get', 'post'], 'getbranchvehicles/(:num)/(:num)/(:num)/(:num)', 'Vehicles::getAllVehicles/$1/$2/$3/$4', ['filter' => 'auth']);

    $routes->match(['get', 'post'], 'load_brands', 'Vehicles::load_brands');
    $routes->match(['get', 'post'], 'load_models', 'Vehicles::load_models');
    $routes->match(['get', 'post'], 'load_variants', 'Vehicles::load_variants');
    $routes->match(['get', 'post'], 'load_staterto', 'Vehicles::load_staterto');
    $routes->match(['get', 'post'], 'load_vehicle_step_fields', 'Vehicles::load_vehicle_step_fields');

    $routes->get('list-branches', 'Branches::index', ['filter' => 'auth']);
    $routes->match(['get', 'post'], 'add-branch', 'Branches::add_branch', ['filter' => 'auth']);
    $routes->match(['get', 'post'], 'save-branch', 'Branches::save_branch', ['filter' => 'auth']);
    $routes->match(['get', 'post'], 'edit-branch/(:num)', 'Branches::edit_branch_details/$1');
    $routes->match(['get', 'post'], 'edit-update-branch-details', 'Branches::edit_update_branch_details');
    $routes->match(['get', 'post'], 'single-branch-info/(:num)', 'Branches::single_branch_info/$1', ['filter' => 'auth']);
    $routes->match(['get', 'post'], 'branch-review/(:num)', 'Branches::load_branch_reviews/$1', ['filter' => 'auth']);

    $routes->get('list-reserved-vehicles', 'Reservation::index', ['filter' => 'auth']);
    $routes->match(['get', 'post'], 'getReseredVehicles/(:num)/(:num)/(:num)/(:num)', 'Reservation::getReservedVehicles/$1/$2/$3/$4', ['filter' => 'auth']);

    $routes->match(['get', 'post'], 'load_states', 'Branches::load_states');
    $routes->match(['get', 'post'], 'load_cities', 'Branches::load_cities');
    $routes->match(['get', 'post'], 'getdealerbranches/(:num)/(:num)/(:num)/(:num)', 'Branches::getAllBranches/$1/$2/$3/$4', ['filter' => 'auth']);
    $routes->match(['get', 'post'], 'deleteBranch/(:num)', 'Branches::delete/$1');

    $routes->match(['get', 'post'], 'promote-vehicle/(:num)', 'PromotionController::index/$1', ['filter' => 'auth']);
    $routes->match(['get', 'post'], 'promotionPlanProcess', 'PromotionController::promotionPlanProcess', ['filter' => 'auth']);
});

/* Razorpay Routes */
$routes->match(['get', 'post'], 'create-rzp-order', 'RazorpayController::create_rzp_order', ['filter' => 'auth']);
$routes->match(['get', 'post'], 'payment-response', 'RazorpayController::callbackUrlRzp');

$routes->match(['get', 'post'], 'payment-success', 'RazorpayController::payment_success');
$routes->match(['get', 'post'], 'payment-canceled', 'RazorpayController::payment_canceled');
$routes->match(['get', 'post'], 'payment-failed', 'RazorpayController::payment_failed');
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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
