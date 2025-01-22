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

$routes->GET('/', 'Dealer\Login::index');

$routes->group('dealer', ['namespace' => 'App\Controllers\Dealer'], function ($routes) {

    $routes->GET('dashboard', 'Dashboard::index', ['filter' => 'auth']);
    $routes->match(['GET', 'POST'], 'updatePlanPreference', 'Dashboard::updatePlanPreference', ['filter' => 'auth']);

    $routes->match(['GET', 'POST'], 'forgot-password', 'UserPassword::index');
    $routes->match(['GET', 'POST'], 'send-reset-password-link', 'UserPassword::sendPwdResetLink');
    $routes->match(['GET', 'POST'], 'logout', 'Logout::index');
    $routes->match(['GET', 'POST'], 'reset-password/(:any)', 'UserPassword::reset_password/$1');
    $routes->match(['GET', 'POST'], 'update-password', 'UserPassword::update_password');
    $routes->match(['GET', 'POST'], 'profile', 'UserPassword::dealer_profile', ['filter' => 'auth']);
    $routes->match(['GET', 'POST'], 'update-profile-details', 'UserPassword::update_profile_details', ['filter' => 'auth']);

    $routes->GET('list-vehicles', 'Vehicles::index', ['filter' => 'auth']);
    $routes->match(['GET', 'POST'], 'add-vehicle', 'Vehicles::add_vehicle', ['filter' => 'auth']);
    $routes->match(['GET', 'POST'], 'save-new-vehicle', 'Vehicles::save_new_vehicle');
    $routes->match(['GET', 'POST'], 'edit-vehicle/(:num)', 'Vehicles::edit_vehicle/$1', ['filter' => 'auth']);
    $routes->match(['GET', 'POST'], 'single-vehicle-info/(:num)', 'Vehicles::single_vehicle_info/$1', ['filter' => 'auth']);

    $routes->match(['GET', 'POST'], 'update-vehicle', 'Vehicles::update_vehicle', ['filter' => 'auth']);
    $routes->match(['GET', 'POST'], 'upload-exterior-main-vehicle-images', 'Vehicles::upload_exterior_main_vehicle_images');
    $routes->match(['GET', 'POST'], 'upload-thumbnail', 'Vehicles::upload_thumbnail');
    $routes->match(['GET', 'POST'], 'upload-vehicle-images', 'Vehicles::upload_vehicle_images');
    $routes->match(['GET', 'POST'], 'update-vehicle-image', 'Vehicles::update_vehicle_image');
    $routes->match(['GET', 'POST'], 'upload-interior-vehicle-images', 'Vehicles::upload_interior_vehicle_images');
    $routes->match(['GET', 'POST'], 'upload-others-vehicle-images', 'Vehicles::upload_others_vehicle_images');
    $routes->match(['GET', 'POST'], 'deleteVehicle/(:num)', 'Vehicles::delete/$1');
    $routes->match(['GET', 'POST'], 'getbranchvehicles/(:num)/(:num)/(:num)/(:num)', 'Vehicles::getAllVehicles/$1/$2/$3/$4', ['filter' => 'auth']);
    
    $routes->match(['GET', 'POST'], 'test-drive-requests', 'Vehicles::test_drive_view', ['filter' => 'auth']);
    $routes->match(['GET', 'POST'], 'fetch-test-drive-request', 'Vehicles::fetch_test_drive_request', ['filter' => 'auth']);
    $routes->match(['GET', 'POST'], 'update-test-drive-status', 'Vehicles::update_test_drive_status');
    $routes->post('update-vehicle-sold-status', 'Vehicles::updateVehicleSoldStatus');

    $routes->match(['GET', 'POST'], 'load_brands', 'Vehicles::load_brands');
    $routes->match(['GET', 'POST'], 'load_models', 'Vehicles::load_models');
    $routes->match(['GET', 'POST'], 'load_variants', 'Vehicles::load_variants');
    $routes->match(['GET', 'POST'], 'load_staterto', 'Vehicles::load_staterto');
    $routes->match(['GET', 'POST'], 'load_vehicle_step_fields', 'Vehicles::load_vehicle_step_fields');

    $routes->GET('list-branches', 'Branches::index', ['filter' => 'auth']);
    $routes->match(['GET', 'POST'], 'add-branch', 'Branches::add_branch', ['filter' => 'auth']);
    $routes->match(['GET', 'POST'], 'save-branch', 'Branches::save_branch', ['filter' => 'auth']);
    $routes->match(['GET', 'POST'], 'edit-branch/(:num)', 'Branches::edit_branch_details/$1');
    $routes->match(['GET', 'POST'], 'edit-update-branch-details', 'Branches::edit_update_branch_details');
    $routes->match(['GET', 'POST'], 'single-branch-info/(:num)', 'Branches::single_branch_info/$1', ['filter' => 'auth']);
    $routes->match(['GET', 'POST'], 'branch-review/(:num)', 'Branches::load_branch_reviews/$1', ['filter' => 'auth']);

    $routes->GET('list-reserved-vehicles', 'Reservation::index', ['filter' => 'auth']);
    $routes->match(['GET', 'POST'], 'getReseredVehicles/(:num)/(:num)/(:num)/(:num)', 'Reservation::getReservedVehicles/$1/$2/$3/$4', ['filter' => 'auth']);

    $routes->match(['GET', 'POST'], 'load_states', 'Branches::load_states');
    $routes->match(['GET', 'POST'], 'load_cities', 'Branches::load_cities');
    $routes->match(['GET', 'POST'], 'getdealerbranches/(:num)/(:num)/(:num)/(:num)', 'Branches::getAllBranches/$1/$2/$3/$4', ['filter' => 'auth']);
    $routes->match(['GET', 'POST'], 'deleteBranch/(:num)', 'Branches::delete/$1');
    $routes->match(['GET', 'POST'], 'enable_disable_branch', 'Branches::enable_disable_branch');

    $routes->match(['GET', 'POST'], 'promote-vehicle/(:num)', 'PromotionController::promoteVehicle/$1', ['filter' => 'auth']);
    $routes->match(['GET', 'POST'], 'promote-showroom/(:num)', 'PromotionController::promoteShowroom/$1', ['filter' => 'auth']);
    $routes->match(['GET', 'POST'], 'promotionPlanProcess', 'PromotionController::promotionPlanProcess', ['filter' => 'auth']);
});

/* Razorpay Routes */
$routes->match(['GET', 'POST'], 'create-rzp-order', 'RazorpayController::create_rzp_order', ['filter' => 'auth']);
$routes->match(['GET', 'POST'], 'payment-response', 'RazorpayController::callbackUrlRzp');

$routes->match(['GET', 'POST'], 'payment-success', 'RazorpayController::payment_success');
$routes->match(['GET', 'POST'], 'payment-canceled', 'RazorpayController::payment_canceled');
$routes->match(['GET', 'POST'], 'payment-failed', 'RazorpayController::payment_failed');
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
