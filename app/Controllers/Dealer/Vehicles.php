<?php

namespace App\Controllers\dealer;

use App\Controllers\BaseController;
use App\Models\BranchModel;
use App\Models\VehicleModel;
use App\Models\CommonModel;
use App\Models\UserModel;
use App\Models\VehicleImagesModel;

/**
 * All Vehicles related methods defined in vehicles class 
 */
class Vehicles extends BaseController {

	protected $branchModel;
	protected $vehicleModel;
	protected $userModel;
	protected $commonModel;
	protected $vehicleImagesModel;

	public function __construct() {
		$this->commonModel = new CommonModel();
		$this->branchModel  = new BranchModel();
		$this->userModel  = new UserModel();
		$this->vehicleModel = new VehicleModel();
		$this->vehicleImagesModel = new VehicleImagesModel();
	}

	public function index() {
		$data = array();
		echo view('dealer/vehicles/list-vehicles', $data);
	}

	public function getAllVehicles($vehicleTypeId, $vehicleBrandId, $vehicleModelId, $vehicleVariantId) {
		$limit = $this->request->getVar('limit');
		$offset = $this->request->getVar('start');
		$dealerId = session()->get('userId');

		$branches = $this->branchModel->where('dealer_id', $dealerId)->findAll();

		$dealerVehiclesHtml = '';

		foreach ($branches as $branch) {

			$vehicles = $this->vehicleModel->getAllVehiclesByBranch($branch['id'], $limit, $offset, $vehicleTypeId, $vehicleBrandId, $vehicleModelId, $vehicleVariantId);

			foreach ($vehicles as $vehicle) {
				$dealerVehiclesHtml .= '
                <div class="col-md-6 col-lg-4 vehicle-card-' . $vehicle['id'] . '">
                    <div class="card card-box mb-3 position-relative">
                        <img class="card-img-top vehicle-image" src="' . WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_thumbnails/' . $vehicle['thumbnail_url'] . '" alt="' . $vehicle['unique_id'] . '" />
                        <div class="card-body">
                            <h5 class="card-title weight-500">' . $vehicle['cmp_name'] . ' ' . $vehicle['model_name'] . ' ' . $vehicle['variantName'] . '</h5>
                            <p class="card-text"></p>
                            <div class="d-flex vehicle-overview">
                                <div class="overview-badge">
                                    <h6>Year</h6>
                                    <h5>' . $vehicle['manufacture_year'] . '</h5>
                                </div>
                                <div class="overview-badge">
                                    <h6>Driven</h6>
                                    <h5>' . $vehicle['kms_driven'] . 'km</h5>
                                </div>
                                <div class="overview-badge">
                                    <h6>Fuel Type</h6>
                                    <h5>' . $vehicle['fuel_type'] . '</h5>
                                </div>
                                <div class="overview-badge">
                                    <h6>Owner</h6>
                                    <h5>' . ordinal($vehicle['owner']) . '</h5>
                                </div>
                                <!-- Add other overview badges based on your data -->
                                <div class="wishlist">
                                    <i class="icofont-heart"></i>
                                </div>
                            </div>
							<h5 class="card-title mt-3">' . $branch['name'] . '</h5> <h6 class="mb-10">' . VEHICLE_TYPE[$vehicle['vehicle_type']] . '</h6>
                            <a href="' . base_url() . 'dealer/promote-vehicle/' . $vehicle['id'] . '" class="btn btn-primary mt-3 btn-block">Promote</a>
                            <div class="option-btn">
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="' . base_url() . 'dealer/single-vehicle-info/' . $vehicle['id'] . '"><i class="dw dw-eye"></i> View</a>
                                        <a class="dropdown-item" href="' . base_url() . 'dealer/edit-vehicle/' . $vehicle['id'] . '"><i class="dw dw-edit2"></i> Edit</a>
                                        <a class="dropdown-item sa-params delete-vehicle" data-vehicle-id="' . $vehicle['id'] . '" href="#"><i class="dw dw-delete-3"></i> Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
			}
		}

		echo $dealerVehiclesHtml;
	}

	public function add_vehicle() {

		$dealerId = session()->get('userId');

		$data['showroomList'] = $this->vehicleModel->getShowroomList($dealerId);
		$data['cmpList'] = $this->vehicleModel->getDistinctBrands();

		$data['fuelTypeList'] = $this->commonModel->get_fuel_types();
		$data['fuelVariantList'] = $this->commonModel->get_fuel_variants();
		$data['transmissionList'] = $this->commonModel->get_vehicle_transmissions();
		$data['bodyTypeList'] = $this->commonModel->get_vehicle_body_types();

		$data['colorList'] = $this->commonModel->get_vehicle_colors();
		$data['stateList'] = $this->commonModel->get_country_states(101);
		echo view('dealer/vehicles/add-vehicle', $data);
	}

	public function save_new_vehicle() {
		$db = db_connect();
		$db->transBegin();

		try {
			$validation = \Config\Services::validation();

			$validation->setRules([
				'branch_id'         => 'required',
				'vehicle_type'      => 'required',
				'cmp_id'            => 'required',
				'model_id'          => 'required',
				'fuel_type'         => 'required',
				'body_type'         => 'required',
				'mileage'           => 'required',
				'kms_driven'        => 'required',
				'owner'             => 'required',
				'transmission_id'   => 'required',
				'color_id'          => 'required',

				'manufacture_year'      => 'required',
				'registration_year'     => 'required',
				'registered_state_id'   => 'required',
				'rto'                   => 'required',

				'insurance_type'      => 'required',
				'insurance_validity'     => 'required',

				'accidental_status'  => 'required',
				'flooded_status'     => 'required',
				'last_service_kms'   => 'required',
				'last_service_date'  => 'required',

				'regular_price'  => 'required',
				'selling_price'  => 'required',
				'pricing_type'   => 'required'
			]);

			// Run the validation
			if (!$validation->withRequest($this->request)->run()) {
				// Validation failed, return errors in JSON format
				$errors = $validation->getErrors();
				return $this->response->setJSON(['success' => false, 'errors' => $errors]);
			}

			// Get the form input values
			$unique_id          = uniqid();
			$branch_id          = $this->request->getPost('branch_id');
			$vehicle_type       = $this->request->getPost('vehicle_type');
			$cmp_id             = $this->request->getPost('cmp_id');
			$model_id           = $this->request->getPost('model_id');
			$fuel_type          = $this->request->getPost('fuel_type');
			$body_type          = $this->request->getPost('body_type');
			$variant_id         = $this->request->getPost('variant_id');
			$mileage            = $this->request->getPost('mileage', FILTER_SANITIZE_STRING);
			$kms_driven         = $this->request->getPost('kms_driven', FILTER_SANITIZE_STRING);
			$owner              = $this->request->getPost('owner');
			$transmission_id    = $this->request->getPost('transmission_id');
			$color_id           = $this->request->getPost('color_id');
			$featured_status    = $this->request->getPost('featured_status');
			$search_keywords    = $this->request->getPost('search_keywords');

			// Get the form input values
			$manufacture_year       = $this->request->getPost('manufacture_year');
			$registration_year      = $this->request->getPost('registration_year');
			$registered_state_id    = $this->request->getPost('registered_state_id');
			$rto                    = $this->request->getPost('rto');

			// Get the form input values
			$insurance_type       = $this->request->getPost('insurance_type');
			$insurance_validity      = $this->request->getPost('insurance_validity');

			// Get the form input values
			$accidental_status   = $this->request->getPost('accidental_status');
			$flooded_status      = $this->request->getPost('flooded_status');
			$last_service_kms    = $this->request->getPost('last_service_kms');
			$last_service_date   = $this->request->getPost('last_service_date');

			// Get the form input values
			$car_no_of_airbags              = $this->request->getPost('car_no_of_airbags');
			$car_central_locking            = $this->request->getPost('car_central_locking');
			$car_seat_upholstery            = $this->request->getPost('car_seat_upholstery');
			$car_sunroof                    = $this->request->getPost('car_sunroof');
			$car_integrated_music_system    = $this->request->getPost('car_integrated_music_system');
			$car_rear_ac                    = $this->request->getPost('car_rear_ac');
			$car_outside_rear_view_mirrors  = $this->request->getPost('car_outside_rear_view_mirrors');
			$car_power_windows              = $this->request->getPost('car_power_windows');
			$car_engine_start_stop          = $this->request->getPost('car_engine_start_stop');
			$car_headlamps                  = $this->request->getPost('car_headlamps');
			$car_power_steering             = $this->request->getPost('car_power_steering');

			// Get the form input values
			$bike_headlight_type            = $this->request->getPost('bike_headlight_type');
			$bike_odometer                  = $this->request->getPost('bike_odometer');
			$bike_drl                       = $this->request->getPost('bike_drl');
			$bike_mobile_connectivity       = $this->request->getPost('bike_mobile_connectivity');
			$bike_gps_navigation            = $this->request->getPost('bike_gps_navigation');
			$bike_usb_charging_port         = $this->request->getPost('bike_usb_charging_port');
			$bike_low_battery_indicator     = $this->request->getPost('bike_low_battery_indicator');
			$bike_under_seat_storage        = $this->request->getPost('bike_under_seat_storage');
			$bike_speedometer               = $this->request->getPost('bike_speedometer');
			$bike_stand_alarm               = $this->request->getPost('bike_stand_alarm');
			$bike_low_fuel_indicator        = $this->request->getPost('bike_low_fuel_indicator');
			$bike_low_oil_indicator         = $this->request->getPost('bike_low_oil_indicator');
			$bike_start_type                = $this->request->getPost('bike_start_type');
			$bike_kill_switch               = $this->request->getPost('bike_kill_switch');
			$bike_break_light               = $this->request->getPost('bike_break_light');
			$bike_turn_signal_indicator     = $this->request->getPost('bike_turn_signal_indicator');

			// Get the form input values
			$onsale_status      = $this->request->getPost('onsale_status');
			$onsale_percentage  = $this->request->getPost('onsale_percentage');
			$regular_price   = $this->request->getPost('regular_price');
			$selling_price   = $this->request->getPost('selling_price');
			$pricing_type    = $this->request->getPost('pricing_type');
			$emi_option      = $this->request->getPost('emi_option');
			$avg_interest_rate  = $this->request->getPost('avg_interest_rate');
			$tenure_months      = $this->request->getPost('tenure_months');
			$created_by      = session()->get('userId');
			$created_datetime = date("Y-m-d H:i:s");

			// Prepare the data to be inserted
			$formData = [
				'unique_id'         => substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 10),
				'branch_id'         => $branch_id,
				'vehicle_type'      => $vehicle_type,
				'cmp_id'            => $cmp_id,
				'model_id'          => $model_id,
				'fuel_type'         => $fuel_type,
				'body_type'        => $body_type,
				'variant_id'        => $variant_id,
				'mileage'           => $mileage,
				'kms_driven'        => $kms_driven,
				'owner'             => $owner,
				'transmission_id'   => $transmission_id,
				'color_id'          => $color_id,
				'featured_status'   => $featured_status,
				'search_keywords'   => $search_keywords,
				'onsale_status'     => $onsale_status,
				'onsale_percentage' => $onsale_percentage,

				'manufacture_year'      => $manufacture_year,
				'registration_year'     => $registration_year,
				'registered_state_id'   => $registered_state_id,
				'rto'                   => $rto,

				'insurance_type'      => $insurance_type,
				'insurance_validity'  => date("Y-m-d", strtotime($insurance_validity)),

				'accidental_status' => $accidental_status,
				'flooded_status'    => $flooded_status,
				'last_service_kms'  => $last_service_kms,
				'last_service_date' => date("Y-m-d", strtotime($last_service_date)),

				'car_no_of_airbags'             => isset($car_no_of_airbags) ? $car_no_of_airbags : '',
				'car_central_locking'           => isset($car_central_locking) ? $car_central_locking : '',
				'car_seat_upholstery'           => isset($car_seat_upholstery) ? $car_seat_upholstery : '',
				'car_sunroof'                   => isset($car_sunroof) ? $car_sunroof : '',
				'car_integrated_music_system'   => isset($car_integrated_music_system) ? $car_integrated_music_system : '',
				'car_rear_ac'                   => isset($car_rear_ac) ? $car_rear_ac : '',
				'car_outside_rear_view_mirrors' => isset($car_outside_rear_view_mirrors) ? $car_outside_rear_view_mirrors : '',
				'car_power_windows'             => isset($car_power_windows) ? $car_power_windows : '',
				'car_engine_start_stop'         => isset($car_engine_start_stop) ? $car_engine_start_stop : '',
				'car_headlamps'                 => isset($car_headlamps) ? $car_headlamps : '',
				'car_power_steering'            => isset($car_power_steering) ? $car_power_steering : '',

				'bike_headlight_type'           => isset($bike_headlight_type) ? $bike_headlight_type : '',
				'bike_odometer'                 => isset($bike_odometer) ? $bike_odometer : '',
				'bike_drl'                      => isset($bike_drl) ? $bike_drl : '',
				'bike_mobile_connectivity'      => isset($bike_mobile_connectivity) ? $bike_mobile_connectivity : '',
				'bike_gps_navigation'           => isset($bike_gps_navigation) ? $bike_gps_navigation : '',
				'bike_usb_charging_port'        => isset($bike_usb_charging_port) ? $bike_usb_charging_port : '',
				'bike_low_battery_indicator'    => isset($bike_low_battery_indicator) ? $bike_low_battery_indicator : '',
				'bike_under_seat_storage'       => isset($bike_under_seat_storage) ? $bike_under_seat_storage : '',
				'bike_speedometer'              => isset($bike_speedometer) ? $bike_speedometer : '',
				'bike_stand_alarm'              => isset($bike_stand_alarm) ? $bike_stand_alarm : '',
				'bike_low_fuel_indicator'       => isset($bike_low_fuel_indicator) ? $bike_low_fuel_indicator : '',
				'bike_low_oil_indicator'        => isset($bike_low_oil_indicator) ? $bike_low_oil_indicator : '',
				'bike_start_type'               => isset($bike_start_type) ? $bike_start_type : '',
				'bike_kill_switch'              => isset($bike_kill_switch) ? $bike_kill_switch : '',
				'bike_break_light'              => isset($bike_break_light) ? $bike_break_light : '',
				'bike_turn_signal_indicator'    => isset($bike_turn_signal_indicator) ? $bike_turn_signal_indicator : '',

				'regular_price' => $regular_price,
				'selling_price' => $selling_price,
				'pricing_type'  => $pricing_type,
				'emi_option' => $emi_option,
				'avg_interest_rate' => $avg_interest_rate,
				'tenure_months' => $tenure_months,
				'is_active' => 1,
				'created_by'    => $created_by,
				'created_datetime' => $created_datetime
			];

			// Insert the data into the database table
			$lastInsertedId = $this->vehicleModel->insertVehicle($formData);
			if ($lastInsertedId == false) {
				// Return a JSON response
				return $this->response->setJSON(['errors' => true, 'message' => 'Error occurred while inserting data.']);
			}

			// Commit the transaction if all insertions were successful
			$db->transCommit();

			// Return a success JSON response
			return $this->response->setJSON(['success' => true, 'message' => 'Vehicle added successfully.', 'vehicleId' => $lastInsertedId, 'vehicle_type' => $vehicle_type]);
		} catch (\Exception $e) {
			// An error occurred, rollback the transaction
			$db->transRollback();

			// Error handling and logging
			$logger = \Config\Services::logger();
			$logger->error('Error occurred while inserting vehicle information: ' . $e->getMessage());

			// Throw or handle the exception as needed
			throw $e;
		}
	}

	public function edit_vehicle($vehicleId) {

		$dealerId = session()->get('userId');

		$data['vehicleDetails'] =  $this->vehicleModel->getVehicleDetails($vehicleId);

		// echo "<pre>";
		// print_r($data);
		// die;

		/*$data['vehicleImagesDetails'] = $this->vehicleModel->getVehicleImagesDetails($vehicleId);*/
		$data['vehicleImagesDetailsArray'] = $this->vehicleModel->getVehicleImagesDetails($vehicleId);

		$vehicle_type = $data['vehicleDetails']['vehicle_type'];

		if ($vehicle_type == '1') {
			/* cars */
			$imageFields = [
				/* car exterior maim images + */
				'exterior_main_front_img',
				'exterior_main_right_img',
				'exterior_main_back_img',
				'exterior_main_left_img',
				'exterior_main_roof_img',
				'exterior_main_bonetopen_img',
				'exterior_main_engine_img',
				/* car exterior maim images - */

				/* car exterior diagonal image + */
				'exterior_diagnoal_right_front_img',
				'exterior_diagnoal_right_back_img',
				'exterior_diagnoal_left_back_img',
				'exterior_diagnoal_left_front_img',
				/* car exterior diagonal image + */

				/*car exterior wheel images + */
				'exterior_wheel_right_front_img',
				'exterior_wheel_right_back_img',
				'exterior_wheel_left_back_img',
				'exterior_wheel_left_front_img',
				'exterior_wheel_spare_img',
				/*car exterior wheel images - */

				/* car exterior tyre thred images + */
				'exterior_tyrethread_right_front_img',
				'exterior_tyrethread_right_back_img',
				'exterior_tyrethread_left_back_img',
				'exterior_tyrethread_left_front_img',
				/* car exterior tyre thred images - */

				/* car exterior underbody images + */
				'exterior_underbody_front_img',
				'exterior_underbody_rear_img',
				'exterior_underbody_right_img',
				'exterior_underbody_left_img',
				/* car exterior underbody images - */

				/* car interior images + */
				'interior_dashboard_img',
				'interior_infotainment_system_img',
				'interior_steering_wheel_img',
				'interior_odometer_img',
				'interior_gear_lever_img',
				'interior_pedals_img',
				'interior_front_cabin_img',
				'interior_mid_cabin_img',
				'interior_rear_cabin_img',
				'interior_driver_side_door_panel_img',
				'interior_driver_side_adjustment_img',
				'interior_boot_inside_img',
				'interior_boot_door_open_img',
				/* car interior images - */

				/* car other images + */
				'others_keys_img',
				/* car other images - */
			];
		} elseif ($vehicle_type == '2') {
			/* bikes */
			$imageFields = [
				/* bike exterior main images + */
				'exterior_main_front_img',
				'exterior_main_right_img',
				'exterior_main_back_img',
				'exterior_main_left_img',
				'exterior_main_tank_img',
				'exterior_main_handlebar_img',
				'exterior_main_headlight_img',
				'exterior_main_tail_light_img',
				'exterior_main_speedometer_img',
				'exterior_main_exhaust_img',
				'exterior_main_seat_img',
				'exterior_main_engine_img',
				/* bike exterior main images - */

				/* bike exterior Diagonal images + */
				'exterior_diagnoal_right_front_img',
				'exterior_diagnoal_right_back_img',
				'exterior_diagnoal_left_back_img',
				'exterior_diagnoal_left_front_img',
				/* bike exterior Diagonal images - */

				/* bike exterior wheel images - */
				'exterior_wheel_front_img',
				'exterior_wheel_rear_img',
				/* bike exterior wheel images - */

				/* bike tyre thred images - */
				'exterior_tyrethread_front_img',
				'exterior_tyrethread_back_img',
				/* bike tyre thred images - */

				/* bike exterior underbody images + */
				'exterior_underbody_front_img',
				'exterior_underbody_rear_img',
				/* bike exterior underbody images - */

			];
		}
		/* // Initialize the vehicleImagesDetailsArray with null values for all imageFields */
		$initializedImagesDetails = array_fill_keys($imageFields, null);

		/* // Merge with the existing images details from the database */
		if (!empty($data['vehicleImagesDetailsArray'])) {
			$data['vehicleImagesDetailsArray'] = array_merge($initializedImagesDetails, $data['vehicleImagesDetailsArray']);
		} else {
			$data['vehicleImagesDetailsArray'] = $initializedImagesDetails;
		}

		$exterior_main = [];
		$exterior_diagnoal = [];
		$exterior_wheel = [];
		$exterior_tyrethread = [];
		$exterior_underbody = [];
		$interior = [];
		$others = [];
		$meta = [];

		if (!empty($data['vehicleImagesDetailsArray'])) {
			foreach ($data['vehicleImagesDetailsArray'] as $key => $value) {
				if (strpos($key, 'exterior_main') !== false) {
					$exterior_main[$key] = $value;
				} elseif (strpos($key, 'exterior_diagnoal') !== false) {
					$exterior_diagnoal[$key] = $value;
				} elseif (strpos($key, 'exterior_wheel') !== false) {
					$exterior_wheel[$key] = $value;
				} elseif (strpos($key, 'exterior_tyrethread') !== false) {
					$exterior_tyrethread[$key] = $value;
				} elseif (strpos($key, 'exterior_underbody') !== false) {
					$exterior_underbody[$key] = $value;
				} elseif (strpos($key, 'interior') !== false) {
					$interior[$key] = $value;
				} elseif (strpos($key, 'others') !== false) {
					$others[$key] = $value;
				} else {
					$meta[$key] = $value;
				}
			}

			$data['vehicleImagesDetails'] = [
				'meta' => $meta,
				'exterior_main' => $exterior_main,
				'exterior_diagnoal' => $exterior_diagnoal,
				'exterior_wheel' => $exterior_wheel,
				'exterior_tyrethread' => $exterior_tyrethread,
				'exterior_underbody' => $exterior_underbody,
				'interior' => $interior,
				'others' => $others
			];
		} else {
			$data['vehicleImagesDetails'] = [];
		}

		$data['showroomList'] = $this->vehicleModel->getShowroomList($dealerId);
		$data['cmpList'] = $this->vehicleModel->getBrandsByVehicleType($data['vehicleDetails']['vehicle_type']);
		$data['cmpModelList'] = $this->vehicleModel->getModelsByBrand($data['vehicleDetails']['cmp_id'], $data['vehicleDetails']['vehicle_type']);
		$data['variantList'] = $this->vehicleModel->getVariantsByModel($data['vehicleDetails']['model_id']);

		$data['fuelTypeList'] = $this->commonModel->get_fuel_types();
		$data['fuelVariantList'] = $this->commonModel->get_fuel_variants();
		$data['transmissionList'] = $this->commonModel->get_vehicle_transmissions();
		$data['colorList'] = $this->commonModel->get_vehicle_colors();
		$data['stateList'] = $this->commonModel->get_country_states(101);
		if (isset($data['vehicleDetails']['cmp_id']) && !empty($data['vehicleDetails']['cmp_id'])) {
			$data['vehicleRegRtoList'] = $this->commonModel->get_registered_state_rto($data['vehicleDetails']['registered_state_id']);
		}
		$data['bodyTypeList'] = $this->commonModel->get_vehicle_body_types();

		echo view('dealer/vehicles/edit-vehicle', $data);
	}

	public function update_vehicle() {

		try {
			// Load the form validation library
			$validation = \Config\Services::validation();

			// Set validation rules for each form field
			$validation->setRules([
				'branch_id'         => 'required',
				'vehicle_type'      => 'required',
				'cmp_id'            => 'required',
				'model_id'          => 'required',
				'fuel_type'         => 'required',
				'body_type'         => 'required',
				'variant_id'        => 'required',
				'mileage'           => 'required',
				'kms_driven'        => 'required',
				'owner'             => 'required',
				'transmission_id'   => 'required',
				'color_id'          => 'required',

				'manufacture_year'      => 'required',
				'registration_year'     => 'required',
				'registered_state_id'   => 'required',
				'rto'                   => 'required',

				'insurance_type'      => 'required',
				'insurance_validity'     => 'required',

				'accidental_status'  => 'required',
				'flooded_status'     => 'required',
				'last_service_kms'   => 'required',
				'last_service_date'  => 'required',

				'regular_price'  => 'required',
				'selling_price'  => 'required',
				'pricing_type'   => 'required',
			]);

			// Run the validation
			if (!$validation->withRequest($this->request)->run()) {
				// Validation failed, return errors in JSON format
				$errors = $validation->getErrors();
				return $this->response->setJSON(['success' => false, 'errors' => $errors]);
			}

			// Get the form input values
			$vehicleId      = $this->request->getPost('vehicleId');
			$branch_id      = $this->request->getPost('branch_id');
			$vehicle_type   = $this->request->getPost('vehicle_type');
			$cmp_id         = $this->request->getPost('cmp_id');
			$model_id       = $this->request->getPost('model_id');
			$fuel_type      = $this->request->getPost('fuel_type');
			$body_type      = $this->request->getPost('body_type');
			$variant_id     = $this->request->getPost('variant_id');
			$mileage        = $this->request->getPost('mileage', FILTER_SANITIZE_STRING);
			$kms_driven     = $this->request->getPost('kms_driven', FILTER_SANITIZE_STRING);
			$owner          = $this->request->getPost('owner');
			$transmission_id = $this->request->getPost('transmission_id');
			$color_id       = $this->request->getPost('color_id');
			$featured_status    = $this->request->getPost('featured_status');
			$search_keywords    = $this->request->getPost('search_keywords');
			$onsale_status      = $this->request->getPost('onsale_status');
			$onsale_percentage  = $this->request->getPost('onsale_percentage');

			// Get the form input values
			$manufacture_year       = $this->request->getPost('manufacture_year');
			$registration_year      = $this->request->getPost('registration_year');
			$registered_state_id    = $this->request->getPost('registered_state_id');
			$rto                    = $this->request->getPost('rto');

			// Get the form input values
			$insurance_type         = $this->request->getPost('insurance_type');
			$insurance_validity     = $this->request->getPost('insurance_validity');

			// Get the form input values
			$accidental_status   = $this->request->getPost('accidental_status');
			$flooded_status      = $this->request->getPost('flooded_status');
			$last_service_kms    = $this->request->getPost('last_service_kms');
			$last_service_date   = $this->request->getPost('last_service_date');

			// Get the form input values
			$car_no_of_airbags              = $this->request->getPost('car_no_of_airbags');
			$car_central_locking            = $this->request->getPost('car_central_locking');
			$car_seat_upholstery            = $this->request->getPost('car_seat_upholstery');
			$car_sunroof                    = $this->request->getPost('car_sunroof');
			$car_integrated_music_system    = $this->request->getPost('car_integrated_music_system');
			$car_rear_ac                    = $this->request->getPost('car_rear_ac');
			$car_outside_rear_view_mirrors  = $this->request->getPost('car_outside_rear_view_mirrors');
			$car_power_windows              = $this->request->getPost('car_power_windows');
			$car_engine_start_stop          = $this->request->getPost('car_engine_start_stop');
			$car_headlamps                  = $this->request->getPost('car_headlamps');
			$car_power_steering             = $this->request->getPost('car_power_steering');

			// Get the form input values
			$bike_headlight_type            = $this->request->getPost('bike_headlight_type');
			$bike_odometer                  = $this->request->getPost('bike_odometer');
			$bike_drl                       = $this->request->getPost('bike_drl');
			$bike_mobile_connectivity       = $this->request->getPost('bike_mobile_connectivity');
			$bike_gps_navigation            = $this->request->getPost('bike_gps_navigation');
			$bike_usb_charging_port         = $this->request->getPost('bike_usb_charging_port');
			$bike_low_battery_indicator     = $this->request->getPost('bike_low_battery_indicator');
			$bike_under_seat_storage        = $this->request->getPost('bike_under_seat_storage');
			$bike_speedometer               = $this->request->getPost('bike_speedometer');
			$bike_stand_alarm               = $this->request->getPost('bike_stand_alarm');
			$bike_low_fuel_indicator        = $this->request->getPost('bike_low_fuel_indicator');
			$bike_low_oil_indicator         = $this->request->getPost('bike_low_oil_indicator');
			$bike_start_type                = $this->request->getPost('bike_start_type');
			$bike_kill_switch               = $this->request->getPost('bike_kill_switch');
			$bike_break_light               = $this->request->getPost('bike_break_light');
			$bike_turn_signal_indicator     = $this->request->getPost('bike_turn_signal_indicator');

			// Get the form input values
			$regular_price = $this->request->getPost('regular_price');
			$selling_price = $this->request->getPost('selling_price');
			$pricing_type = $this->request->getPost('pricing_type');
			$emi_option = $this->request->getPost('emi_option');
			$avg_interest_rate = $this->request->getPost('avg_interest_rate');
			$tenure_months = $this->request->getPost('tenure_months');
			$updated_by = session()->get('userId');
			$updated_datetime = date("Y-m-d H:i:s");

			// Prepare the data to be updated
			$formData = [
				'branch_id'         => $branch_id,
				'vehicle_type'      => $vehicle_type,
				'cmp_id'            => $cmp_id,
				'model_id'          => $model_id,
				'fuel_type'         => $fuel_type,
				'body_type'         => $body_type,
				'variant_id'        => $variant_id,
				'mileage'           => $mileage,
				'kms_driven'        => $kms_driven,
				'owner'             => $owner,
				'transmission_id'   => $transmission_id,
				'color_id'          => $color_id,
				'featured_status'   => $featured_status,
				'search_keywords'   => $search_keywords,
				'onsale_status'     => $onsale_status,
				'onsale_percentage' => $onsale_percentage,

				'manufacture_year'      => $manufacture_year,
				'registration_year'     => $registration_year,
				'registered_state_id'   => $registered_state_id,
				'rto'                   => $rto,

				'insurance_type'      => $insurance_type,
				'insurance_validity'  => date("Y-m-d", strtotime($insurance_validity)),

				'accidental_status' => $accidental_status,
				'flooded_status'    => $flooded_status,
				'last_service_kms'  => $last_service_kms,
				'last_service_date' => date("Y-m-d", strtotime($last_service_date)),

				'car_no_of_airbags'             => isset($car_no_of_airbags) ? $car_no_of_airbags : '',
				'car_central_locking'           => isset($car_central_locking) ? $car_central_locking : '',
				'car_seat_upholstery'           => isset($car_seat_upholstery) ? $car_seat_upholstery : '',
				'car_sunroof'                   => isset($car_sunroof) ? $car_sunroof : '',
				'car_integrated_music_system'   => isset($car_integrated_music_system) ? $car_integrated_music_system : '',
				'car_rear_ac'                   => isset($car_rear_ac) ? $car_rear_ac : '',
				'car_outside_rear_view_mirrors' => isset($car_outside_rear_view_mirrors) ? $car_outside_rear_view_mirrors : '',
				'car_power_windows'             => isset($car_power_windows) ? $car_power_windows : '',
				'car_engine_start_stop'         => isset($car_engine_start_stop) ? $car_engine_start_stop : '',
				'car_headlamps'                 => isset($car_headlamps) ? $car_headlamps : '',
				'car_power_steering'            => isset($car_power_steering) ? $car_power_steering : '',

				'bike_headlight_type'           => isset($bike_headlight_type) ? $bike_headlight_type : '',
				'bike_odometer'                 => isset($bike_odometer) ? $bike_odometer : '',
				'bike_drl'                      => isset($bike_drl) ? $bike_drl : '',
				'bike_mobile_connectivity'      => isset($bike_mobile_connectivity) ? $bike_mobile_connectivity : '',
				'bike_gps_navigation'           => isset($bike_gps_navigation) ? $bike_gps_navigation : '',
				'bike_usb_charging_port'        => isset($bike_usb_charging_port) ? $bike_usb_charging_port : '',
				'bike_low_battery_indicator'    => isset($bike_low_battery_indicator) ? $bike_low_battery_indicator : '',
				'bike_under_seat_storage'       => isset($bike_under_seat_storage) ? $bike_under_seat_storage : '',
				'bike_speedometer'              => isset($bike_speedometer) ? $bike_speedometer : '',
				'bike_stand_alarm'              => isset($bike_stand_alarm) ? $bike_stand_alarm : '',
				'bike_low_fuel_indicator'       => isset($bike_low_fuel_indicator) ? $bike_low_fuel_indicator : '',
				'bike_low_oil_indicator'        => isset($bike_low_oil_indicator) ? $bike_low_oil_indicator : '',
				'bike_start_type'               => isset($bike_start_type) ? $bike_start_type : '',
				'bike_kill_switch'              => isset($bike_kill_switch) ? $bike_kill_switch : '',
				'bike_break_light'              => isset($bike_break_light) ? $bike_break_light : '',
				'bike_turn_signal_indicator'    => isset($bike_turn_signal_indicator) ? $bike_turn_signal_indicator : '',

				'regular_price' => $regular_price,
				'selling_price' => $selling_price,
				'pricing_type'  => $pricing_type,
				'emi_option' => $emi_option,
				'avg_interest_rate' => $avg_interest_rate,
				'tenure_months' => $tenure_months,
				'updated_by'    => $updated_by,
				'updated_datetime' => $updated_datetime
			];

			// Update the data into the database table
			$result = $this->vehicleModel->updateData($vehicleId, $formData);

			if (!$result) {
				// Return a JSON response
				return $this->response->setJSON(['errors' => true, 'message' => 'Error occurred while inserting data.']);
			}

			// Return a success JSON response
			return $this->response->setJSON(['success' => true, 'message' => 'Vehicle updated successfully.']);
		} catch (\Exception $e) {
			// Error handling and logging
			$logger = \Config\Services::logger();
			$logger->error('Error occurred while updating vehicle information: ' . $e->getMessage());

			// Throw or handle the exception as needed
			throw $e;
		}
	}

	public function single_vehicle_info($vehicleId) {

		$dealerId = session()->get('userId');

		$data['vehicleDetails'] =  $this->vehicleModel->getVehicleDetails($vehicleId);

		$data['vehicleImagesDetailsArray'] = array_filter($this->vehicleModel->getVehicleImagesDetails($vehicleId));

		$exterior_main = [];
		$exterior_diagnoal = [];
		$exterior_wheel = [];
		$exterior_tyrethread = [];
		$exterior_underbody = [];
		$interior = [];
		$others = [];
		$meta = [];

		if (!empty($data['vehicleImagesDetailsArray'])) {
			foreach ($data['vehicleImagesDetailsArray'] as $key => $value) {
				if (strpos($key, 'exterior_main') !== false) {
					$exterior_main[$key] = $value;
				} elseif (strpos($key, 'exterior_diagnoal') !== false) {
					$exterior_diagnoal[$key] = $value;
				} elseif (strpos($key, 'exterior_wheel') !== false) {
					$exterior_wheel[$key] = $value;
				} elseif (strpos($key, 'exterior_tyrethread') !== false) {
					$exterior_tyrethread[$key] = $value;
				} elseif (strpos($key, 'exterior_underbody') !== false) {
					$exterior_underbody[$key] = $value;
				} elseif (strpos($key, 'interior') !== false) {
					$interior[$key] = $value;
				} elseif (strpos($key, 'others') !== false) {
					$others[$key] = $value;
				} else {
					$meta[$key] = $value;
				}
			}
			$data['vehicleImagesDetails'] = [
				'meta' => $meta,
				'exterior_main' => $exterior_main,
				'exterior_diagnoal' => $exterior_diagnoal,
				'exterior_wheel' => $exterior_wheel,
				'exterior_tyrethread' => $exterior_tyrethread,
				'exterior_underbody' => $exterior_underbody,
				'interior' => $interior,
				'others' => $others
			];
		} else {
			$data['vehicleImagesDetails'] = [];
		}

		$data['showroomList'] = $this->vehicleModel->getShowroomList($dealerId);
		$data['cmpList'] = $this->vehicleModel->getBrandsByVehicleType($data['vehicleDetails']['vehicle_type']);
		$data['cmpModelList'] = $this->vehicleModel->getModelsByBrand($data['vehicleDetails']['cmp_id'], $data['vehicleDetails']['vehicle_type']);
		$data['variantList'] = $this->vehicleModel->getVariantsByModel($data['vehicleDetails']['model_id']);

		$data['fuelTypeList'] = $this->commonModel->get_fuel_types();
		$data['fuelVariantList'] = $this->commonModel->get_fuel_variants();
		$data['transmissionList'] = $this->commonModel->get_vehicle_transmissions();
		$data['colorList'] = $this->commonModel->get_vehicle_colors();
		$data['stateList'] = $this->commonModel->get_country_states(101);
		if (isset($data['vehicleDetails']['cmp_id']) && !empty($data['vehicleDetails']['cmp_id'])) {
			$data['vehicleRegRtoList'] = $this->commonModel->get_registered_state_rto($data['vehicleDetails']['registered_state_id']);
		}
		$data['bodyTypeList'] = $this->commonModel->get_vehicle_body_types();

		echo view('dealer/vehicles/single-vehicle-info', $data);
	}

	public function delete($vehicleId) {

		$vehicle = $this->vehicleModel->find($vehicleId);

		if ($vehicle) {
			// Update the deleted_status to 1 (deleted) in the database
			$this->vehicleModel->deleteVehicle($vehicleId);

			return $this->response->setJSON(['status' => 'success']);
		} else {
			// Return a JSON response indicating failure
			return $this->response->setJSON(['status' => 'error', 'message' => 'Vehicle not found']);
		}
	}

	public function upload_thumbnail() {

		// Check if the thumbnail image file was uploaded successfully
		if ($thumbnailImage = $this->request->getFile('thumbnailImage')) {
			// Generate a new name for the thumbnail image to prevent name conflicts
			$newName = $thumbnailImage->getRandomName();

			$uploadFolderPath = realpath($_SERVER['DOCUMENT_ROOT'] . '/../../production/');
			$destinationPath = $uploadFolderPath . '/public/uploads/vehicle_thumbnails/';
			$newName = $thumbnailImage->getRandomName();

			try {
				$thumbnailImage->move($destinationPath, $newName);
			} catch (\Exception $e) {
				return $this->response->setJSON(['status' => 'error', 'message' => 'Error moving file: ' . $e->getMessage()]);
			}

			/*// Get the thumbnail image URL to display in the preview */
			/*$thumbnailUrl = $destinationPath . $newName; */
			$thumbnailUrl = WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_thumbnails/' . $newName;

			$id = $this->request->getPost('vehicleId');
			if (isset($id) && !empty($id)) {
				// Update the thumbnail URL in the database
				$data = [
					'thumbnail_url' => $newName
				];

				// Check if there is valid data to update
				if (isset($data) && !empty($id)) {
					// Update the database record
					$this->vehicleModel->update($id, $data);

					// Return the URL to be used by the jQuery success function
					return $this->response->setJSON(['status' => 'success', 'message' => 'Thumbnail Uploaded Successfully.', 'thumbnail_url' => $thumbnailUrl]);
				} else {
					// Return an error message if there is no data to update or if the ID is not provided
					return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update thumbnail URL.']);
				}
			} else {
				return $this->response->setJSON(['status' => 'error', 'message' => 'Vehicle ID Required.']);
			}
		} else {
			// Return an error message if the upload failed
			return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to upload thumbnail image.']);
		}
	}

	public function upload_exterior_main_vehicle_images_old() {
		$vehicleId = $this->request->getPost('vehicleId');
		$vehicle_type = $this->request->getPost('vehicle_type');

		if (empty($vehicleId)) {
			return $this->response->setJSON(['status' => 'error', 'message' => 'Vehicle ID Required.']);
		}

		$data = [
			'vehicle_id' => $vehicleId,
			'vehicle_type' => $vehicle_type
		];
		$message = '';

		if ($vehicle_type == '1') {
			/* cars */
			$imageFields = [
				/* car exterior maim images + */
				'exterior_main_front_img',
				'exterior_main_right_img',
				'exterior_main_back_img',
				'exterior_main_left_img',
				'exterior_main_roof_img',
				'exterior_main_bonetopen_img',
				'exterior_main_engine_img',
				/* car exterior maim images - */

				/* car exterior diagonal image + */
				'exterior_diagnoal_right_front_img',
				'exterior_diagnoal_right_back_img',
				'exterior_diagnoal_left_back_img',
				'exterior_diagnoal_left_front_img',
				/* car exterior diagonal image + */

				/*car exterior wheel images + */
				'exterior_wheel_right_front_img',
				'exterior_wheel_right_back_img',
				'exterior_wheel_left_back_img',
				'exterior_wheel_left_front_img',
				'exterior_wheel_spare_img',
				/*car exterior wheel images - */

				/* car exterior tyre thred images + */
				'exterior_tyrethread_right_front_img',
				'exterior_tyrethread_right_back_img',
				'exterior_tyrethread_left_back_img',
				'exterior_tyrethread_left_front_img',
				/* car exterior tyre thred images - */

				/* car exterior underbody images + */
				'exterior_underbody_front_img',
				'exterior_underbody_rear_img',
				'exterior_underbody_right_img',
				'exterior_underbody_left_img',
				/* car exterior underbody images - */

				/* car interior images + */
				'interior_dashboard_img',
				'interior_infotainment_system_img',
				'interior_steering_wheel_img',
				'interior_odometer_img',
				'interior_gear_lever_img',
				'interior_pedals_img',
				'interior_front_cabin_img',
				'interior_mid_cabin_img',
				'interior_rear_cabin_img',
				'interior_driver_side_door_panel_img',
				'interior_driver_side_adjustment_img',
				'interior_boot_inside_img',
				'interior_boot_door_open_img',
				/* car interior images - */

				/* car other images + */
				'others_keys_img',
				/* car other images - */
			];
		} elseif ($vehicle_type == '2') {
			/* bikes */
			$imageFields = [
				/* bike exterior main images + */
				'exterior_main_front_img',
				'exterior_main_right_img',
				'exterior_main_back_img',
				'exterior_main_left_img',
				'exterior_main_tank_img',
				'exterior_main_handlebar_img',
				'exterior_main_headlight_img',
				'exterior_main_tail_light_img',
				'exterior_main_speedometer_img',
				'exterior_main_exhaust_img',
				'exterior_main_seat_img',
				'exterior_main_engine_img',
				/* bike exterior main images - */

				/* bike exterior Diagonal images + */
				'exterior_diagnoal_right_front_img',
				'exterior_diagnoal_right_back_img',
				'exterior_diagnoal_left_back_img',
				'exterior_diagnoal_left_front_img',
				/* bike exterior Diagonal images - */

				/* bike exterior wheel images - */
				'exterior_wheel_front_img',
				'exterior_wheel_rear_img',
				/* bike exterior wheel images - */

				/* bike tyre thred images - */
				'exterior_tyrethread_front_img',
				'exterior_tyrethread_back_img',
				/* bike tyre thred images - */

				/* bike exterior underbody images + */
				'exterior_underbody_front_img',
				'exterior_underbody_rear_img',
				/* bike exterior underbody images - */

			];
		}

		foreach ($imageFields as $field) {
			if (!empty($_FILES[$field]['name'])) {
				$data[$field] = uploadImage($field);
			}
		}

		$existingRecord = $this->vehicleImagesModel->where('vehicle_id', $vehicleId)->first();
		if ($existingRecord) {
			$this->vehicleImagesModel->update($existingRecord['id'], $data);
			$message = 'Vehicle Exterior Images Updated Successfully';
		} else {
			$this->vehicleImagesModel->insert($data);
			$message = 'Vehicle Exterior Images Added Successfully';
		}

		return $this->response->setJSON(['status' => 'success', 'message' => $message]);
	}

	public function upload_vehicle_images() {

		$vehicleId = $this->request->getPost('vehicle_id');
		$vehicle_type = $this->request->getPost('vehicle_type');
		$formId = $this->request->getPost('formId');

		$inputFields = $this->request->getFiles();

		$uploadedFiles = [];

		foreach ($inputFields as $fieldId => $file) {
			if ($file->isValid()) {
				$newName = uploadImage($fieldId, $file, UPLOAD_IMG_WIDTH, UPLOAD_IMG_HEIGHT); /*// Example resize to 80%*/

				if ($newName) {
					$uploadedFiles[$fieldId] = $newName;
				} else {
					$uploadedFiles[$fieldId] = 'default-img.png';
				}
			} else {
				$uploadedFiles[$fieldId] = 'No file uploaded';
			}
		}

		/* appending the vehicle id to the data to be sent for insert */
		$uploadedFiles['vehicle_id'] = $vehicleId;

		$existingRecord = $this->vehicleImagesModel->where('vehicle_id', $vehicleId)->first();
		if ($existingRecord) {
			$this->vehicleImagesModel->update($existingRecord['id'], $uploadedFiles);
			$message = ucwords(str_replace('_', ' ', $formId)) . ' Images Updated Successfully';
		} else {
			$this->vehicleImagesModel->insert($uploadedFiles);
			$message = ucwords(str_replace('_', ' ', $formId)) . ' Images Added Successfully';
		}

		return $this->response->setJSON(['status' => 'success', 'message' => $message]);
	}

	public function update_vehicle_image() {

		$newVehicleImg = $this->request->getFile('newVehicleImg');
		if ($newVehicleImg && $newVehicleImg->isValid() && !$newVehicleImg->hasMoved()) {
			$colNamenPath = $this->request->getPost('colName');

			// Use the helper function to move and process the file
			$newName = uploadImage($colNamenPath, $newVehicleImg, UPLOAD_IMG_WIDTH, UPLOAD_IMG_HEIGHT);

			if ($newName === false) {
				return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to upload and process image.']);
			}

			// Get the image URL to display in the preview
			$img_url = WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_' . $colNamenPath . '/' . $newName;

			$vehicleId = $this->request->getPost('vehicleId');
			if (!empty($vehicleId)) {
				// Prepare data to update the database
				$data = [
					$colNamenPath => $newName
				];

				/* // Debugging output to check data being updated */
				log_message('debug', 'Vehicle ID: ' . $vehicleId);
				log_message('debug', 'Data to update: ' . json_encode($data));

				/* // Update the database record*/
				try {
					$this->vehicleImagesModel->update_vehicle_image($vehicleId, $data);
				} catch (\Exception $e) {
					return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update database: ' . $e->getMessage()]);
				}

				/* // Return the URL to be used by the jQuery success function */
				return $this->response->setJSON(['status' => 'success', 'message' => 'Image Updated Successfully.', 'img_url' => $img_url]);
			} else {
				return $this->response->setJSON(['status' => 'error', 'message' => 'Vehicle ID Required.']);
			}
		} else {
			// Return an error message if the upload failed
			return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to upload image.']);
		}
	}

	public function load_brands() {
		$vehicle_type = $this->request->getPost('vehicle_type');

		// Load models based on the selected brand (Replace 'your_model' with your actual model name)
		$brands = $this->vehicleModel->getBrandsByVehicleType($vehicle_type);

		// Return HTML options for models dropdown
		$html = '';
		$html .= '<option value="0">Brands</option>';
		if (!empty($brands)) {
			foreach ($brands as $brand) {
				$html .= "<option value=\"{$brand['id']}\">{$brand['cmp_name']}</option>";
			}
		} else {
			$html .= '<option>No Data Found</option>';
		}
		echo $html;
	}

	public function load_models() {
		$brandId = $this->request->getPost('brand_id');
		$vehicleType = $this->request->getPost('vehicle_type');

		// Load models based on the selected brand (Replace 'your_model' with your actual model name)
		$models = $this->vehicleModel->getModelsByBrand($brandId, $vehicleType);

		// Return HTML options for models dropdown
		$html = '';
		$html .= '<option value="0">Models</option>';
		if (!empty($models)) {
			foreach ($models as $model) {
				$html .= "<option value=\"{$model['id']}\">{$model['model_name']}</option>";
			}
		} else {
			$html .= '<option>No Data Found</option>';
		}

		echo $html;
	}

	public function load_variants() {
		$modelId = $this->request->getPost('model_id');

		// Load variants based on the selected model (Replace 'your_model' with your actual model name)
		$variants = $this->vehicleModel->getVariantsByModel($modelId);

		// Return HTML options for variants dropdown
		$html = '';
		$html .= '<option value="0">Variants</option>';
		if (!empty($variants)) {
			foreach ($variants as $variant) {
				$html .= "<option value=\"{$variant['id']}\">{$variant['name']}</option>";
			}
		} else {
			$html .= '<option>No Data Found</option>';
		}
		echo $html;
	}

	public function load_staterto() {
		$stateId = $this->request->getPost('stateId');

		$rtoList = $this->commonModel->get_registered_state_rto($stateId);

		$html = '';
		$html .= '<option value="0">Choose RTO</option>';
		if (!empty($rtoList)) {
			foreach ($rtoList as $rtolist) {
				$html .= "<option value=\"{$rtolist['id']}\">{$rtolist['rto_state_code']}</option>";
			}
		} else {
			$html .= '<option>No Data Found</option>';
		}
		echo $html;
	}

	public function load_vehicle_step_fields() {
		$vehicle_type = $this->request->getPost('vehicle_type');

		// Return HTML options for models dropdown
		$vehicleFeaturesHtmlContent = '';
		$vehicleExteriorImagesHtmlContent = '';

		if ($vehicle_type == 1) {
			/* cars */
			$vehicleFeaturesHtmlContent = view('dealer/vehicles/form_includes/cars/add-car-step5');
			$vehicleExteriorImagesHtmlContent = view('dealer/vehicles/form_includes/cars/add-car-img-form');
		} else if ($vehicle_type == 2) {
			/* bikes */
			$vehicleFeaturesHtmlContent = view('dealer/vehicles/form_includes/bikes/add-bike-step5');
			$vehicleExteriorImagesHtmlContent = view('dealer/vehicles/form_includes/bikes/add-bike-img-form');
			/* bike form images end */
		}

		return $this->response->setJSON(['status' => 'success', 'vehicle_form_feilds' => $vehicleFeaturesHtmlContent, 'vehicle_image_fields' => $vehicleExteriorImagesHtmlContent]);
	}

	public function promote_vehicle($vehicleId) {
		$dealerId = session()->get('userId');

		$data['vehicleDetails'] =  $this->vehicleModel->getVehicleDetails($vehicleId);
		$data['vehicleImagesDetails'] = $this->vehicleModel->getVehicleImagesDetails($vehicleId);
		//echo "<pre>"; print_r($data['vehicleDetails']);die;
		echo view('dealer/vehicles/promote-vehicle', $data);
	}
}
