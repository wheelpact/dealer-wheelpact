<?php

namespace App\Controllers\dealer;

use App\Controllers\BaseController;
use App\Models\BranchModel;
use App\Models\VehicleModel;
use App\Models\CommonModel;
use App\Models\UserModel;
use App\Models\VehicleImagesModel;
use App\Models\PromotionPlanModel;

/**
 * All Vehicles related methods defined in vehicles class 
 */
class Vehicles extends BaseController {

	protected $branchModel;
	protected $vehicleModel;
	protected $userModel;
	protected $commonModel;
	protected $vehicleImagesModel;
	protected $promotionPlanModel;

	protected $dealerId;
	protected $userSesData;
	protected $planDetails;

	public function __construct() {
		$this->commonModel = new CommonModel();
		$this->branchModel  = new BranchModel();
		$this->userModel  = new UserModel();
		$this->vehicleModel = new VehicleModel();
		$this->vehicleImagesModel = new VehicleImagesModel();
		$this->promotionPlanModel = new promotionPlanModel();

		/* // Retrieve session */
		$this->userSesData = session()->get();

		/* // Retrieve plan details of logged user */
		$planDetails = $this->userModel->getPlanDetailsBYId(session()->get('userId'));
		$this->planDetails = $planDetails[0];
	}

	public function index() {
		$data = array();
		/* // Fetch user session data and plan details */
		$data['userData'] = $this->userSesData;
		$data['planData'] = $this->planDetails;

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
			//echo '<pre>'; print_r($vehicles); exit();
			foreach ($vehicles['data'] as $vehicle) {
				$dealerVehiclesHtml .= '
				<div class="col-md-6 col-lg-4 vehicle-card-' . $vehicle['id'] . '">
					<div class="card card-box mb-3 position-relative">
						<img class="card-img-top vehicle-image" src="' . WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_thumbnails/' . $vehicle['thumbnail_url'] . '" alt="' . $vehicle['unique_id'] . '" />
						<div class="card-body">
							<h5 class="card-title weight-500 text-blue">' . $vehicle['cmp_name'] . ' ' . $vehicle['model_name'] . ' ' . $vehicle['variantName'] . '</h5>
								<div class="d-flex vehicle-overview">
								<div class="overview-badge">
									<h6>Year</h6>
									<h5>' . $vehicle['manufacture_year'] . '</h5>
								</div>
								<div class="overview-badge">
									<h6>Driven</h6>
									<h5>' . number_format($vehicle['kms_driven'], 0, '.', ',') . ' km</h5>
								</div>
								<div class="overview-badge">
									<h6>Fuel Type</h6>
									<h5>' . $vehicle['fuel_type'] . '</h5>
								</div>
								<div class="overview-badge">
									<h6>Owner</h6>
									<h5>' . ordinal($vehicle['owner']) . '</h5>
								</div>
							</div>
							<h5 class="card-title mt-3">' . $branch['name'] . ' - ' . VEHICLE_TYPE[$vehicle['vehicle_type']] . '</h5>';

				if ($vehicle['is_active'] != 3) {
					if ($vehicle['is_promoted'] == 1 && $vehicle['is_active'] == 4) {
						// Show both Promotion and Sold buttons
						$dealerVehiclesHtml .= '<a href="javascript:void(0);" class="btn btn-success btn-block">Promotion ends on: ' . date('Y-m-d', strtotime($vehicle['promotion_end_date'])) . '</a>';
						$dealerVehiclesHtml .= '<a href="' . base_url() . 'dealer/vehicle-promotion-details/' . encryptData($vehicle['id']) . '" target="_blank" class="btn btn-info btn-block">Promotion Details</a>';
						$dealerVehiclesHtml .= '<a href="javascript:void(0);" class="btn btn-secondary btn-block">Sold</a>';
					} elseif ($vehicle['is_promoted'] == 1) {
						// Show promotion details if the vehicle is promoted
						$dealerVehiclesHtml .= '<a href="javascript:void(0);" class="btn btn-success btn-block">Promotion ends on: ' . date('Y-m-d', strtotime($vehicle['promotion_end_date'])) . '</a>';
						$dealerVehiclesHtml .= '<a href="' . base_url() . 'dealer/vehicle-promotion-details/' . encryptData($vehicle['id']) . '" target="_blank" class="btn btn-info btn-block">Promotion Details</a>';
					} elseif ($vehicle['is_active'] == 4) {
						// Show as Sold and make it non-clickable
						$dealerVehiclesHtml .= '<a href="javascript:void(0);" class="btn btn-secondary mt-3 btn-block" disabled>Sold</a>';
					} elseif ($vehicle['is_active'] != 2) {
						// Show Promote button
						$dealerVehiclesHtml .= '<a href="' . base_url() . 'dealer/promote-vehicle/' . encryptData($vehicle['id']) . '" class="btn btn-primary mt-3 btn-block">Promote</a>';
					}

					// Show "Mark as Sold" button if the vehicle is not already sold
					if ($vehicle['is_active'] != 4 && $vehicle['is_active'] != 2) {
						$dealerVehiclesHtml .= '<a href="#" class="btn btn-success mt-1 btn-block vehicleMarkSoldModal" data-vehicle-id=' . encryptData($vehicle['id']) . ' data-toggle="modal" data-target="#markSoldModal">Mark as Sold</a>';
					}

					// Option button dropdown
					$dealerVehiclesHtml .= '
				<div class="option-btn">
					<div class="dropdown">
						<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
							<i class="dw dw-more"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
							<a class="dropdown-item" href="' . base_url() . 'dealer/single-vehicle-info/' . encryptData($vehicle['id']) . '"><i class="dw dw-eye"></i> View</a>
							<a class="dropdown-item" href="' . base_url() . 'dealer/edit-vehicle/' . encryptData($vehicle['id']) . '"><i class="dw dw-edit2"></i> Edit</a>';

					if ($vehicle['is_promoted'] != 1 && $vehicle['is_active'] != 4) {
						//$dealerVehiclesHtml .= '<a class="dropdown-item sa-params enable-disable-vehicle" data-vehicle-id="' . $vehicle['id'] . '" href="#"><i class="dw dw-delete-3"></i> Enable </a>';
						if ($vehicle['is_active'] == 1) {
							// Active: Displayed on site
							$dealerVehiclesHtml .= '
							<a class="dropdown-item sa-params enable-disable-vehicle" data-vehicle-id="' . $vehicle['id'] . '" data-vehicle-flag="2" href="#">
							   <i class="dw dw-delete-3"></i> Disable
							</a>';
						} elseif ($vehicle['is_active'] == 2) {
							// Inactive: Hidden on site
							$dealerVehiclesHtml .= '
							<a class="dropdown-item sa-params enable-disable-vehicle" data-vehicle-id="' . $vehicle['id'] . '" data-vehicle-flag="1" href="#">
							   <i class="dw dw-delete-3"></i> Enable
							</a>';
						}
					}
					$dealerVehiclesHtml .= '</div></div></div>';
				}

				if ($vehicle['is_active'] === '1' || $vehicle['is_active'] === '4') {
					// Active: Displayed on site
					$dealerVehiclesHtml .= '
						<div class="card-status-badge">
							<span class="badge badge-success">Active</span>
						</div>';
				} elseif ($vehicle['is_active'] === '3') {
					// Deleted: Hidden on site, shown on dealer panel & superadmin
					$dealerVehiclesHtml .= '
						<div class="card-status-badge">
							<span class="badge badge-danger">Deleted</span>
						</div>';
				} elseif ($vehicle['is_active'] === '2') {
					// Inactive: Hidden on site, shown on dealer panel & superadmin
					$dealerVehiclesHtml .= '
						<div class="card-status-badge">
							<span class="badge badge-warning">Inactive</span>
						</div>';
				}

				$dealerVehiclesHtml .= '</div></div></div>';
			}
		}

		echo $dealerVehiclesHtml;
	}

	public function add_vehicle() {
		/* // Fetch user session data and plan details */
		$data['userData'] = $this->userSesData;
		$data['planData'] = $this->planDetails;

		/* // Get the vehicle counts grouped by branch for the dealer */
		$VehicleCountByBranch = $this->vehicleModel->getVehicleCountByBranch($data['userData']['userId']);

		/* // Filter the array for the current month */
		$currentMonth = date('m');
		$currentYear = date('Y');
		$currentMonthVehicleCounts = array_filter($VehicleCountByBranch, function ($count) use ($currentMonth, $currentYear) {
			return $count['month'] == $currentMonth && $count['year'] == $currentYear;
		});

		/* // Calculate the total vehicle count for the current month */
		$totalVehicleCountForCurrentMonth = array_sum(array_column($currentMonthVehicleCounts, 'vehicle_count'));

		/* // Check if adding another vehicle is within the plan limits */
		if ($totalVehicleCountForCurrentMonth < $data['planData']['max_vehicle_listing_per_month']) {
			/* // Fetch additional data needed for adding vehicles */
			$data['showroomList'] = $this->vehicleModel->getShowroomList($data['userData']['userId']);
			$data['fuelTypeList'] = $this->commonModel->get_fuel_types();
			$data['fuelVariantList'] = $this->commonModel->get_fuel_variants();
			$data['transmissionList'] = $this->commonModel->get_vehicle_transmissions();
			$data['bodyTypeList'] = $this->commonModel->get_vehicle_body_types();
			$data['colorList'] = $this->commonModel->get_vehicle_colors();
			$data['stateList'] = $this->commonModel->get_country_states(101);
		} else {
			/* // Display a message or handle the case where the limit is exceeded */
			$data['totalVehicleCountForCurrentMonth'] = $totalVehicleCountForCurrentMonth;
			$data['limitExceeded'] = true;
		}

		/* // Load the view with all necessary data */
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
				'pricing_type'   => 'required',
				'reservation_amt' => 'required'
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
			$mileage            = $this->request->getPost('mileage', FILTER_SANITIZE_SPECIAL_CHARS);
			$kms_driven         = $this->request->getPost('kms_driven', FILTER_SANITIZE_SPECIAL_CHARS);
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
			$reservation_amt = $this->request->getPost('reservation_amt');
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
				'reservation_amt' => $reservation_amt,
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

	public function edit_vehicle($vehicle_Id) {
		$vehicleId = decryptData($vehicle_Id);

		$dealerId = session()->get('userId');
		/* // Fetch user session data and plan details */
		$data['userData'] = $this->userSesData;
		$data['planData'] = $this->planDetails;

		$data['vehicleDetails'] =  $this->vehicleModel->getVehicleDetails($vehicleId);

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

		/* get plan details */
		$data['planData'] = $this->planDetails;

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
				'reservation_amt' => 'required'
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
			$mileage        = $this->request->getPost('mileage', FILTER_SANITIZE_SPECIAL_CHARS);
			$kms_driven     = $this->request->getPost('kms_driven', FILTER_SANITIZE_SPECIAL_CHARS);
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

			$insurance_type         = $this->request->getPost('insurance_type');
			$insurance_validity     = $this->request->getPost('insurance_validity');

			$accidental_status   = $this->request->getPost('accidental_status');
			$flooded_status      = $this->request->getPost('flooded_status');
			$last_service_kms    = $this->request->getPost('last_service_kms');
			$last_service_date   = $this->request->getPost('last_service_date');

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

			$regular_price = $this->request->getPost('regular_price');
			$selling_price = $this->request->getPost('selling_price');
			$pricing_type = $this->request->getPost('pricing_type');
			$emi_option = $this->request->getPost('emi_option');
			$avg_interest_rate = $this->request->getPost('avg_interest_rate');
			$tenure_months = $this->request->getPost('tenure_months');
			$reservation_amt = $this->request->getPost('reservation_amt');
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
				'reservation_amt' => $reservation_amt,
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

	public function single_vehicle_info($vehicle_Id) {
		$vehicleId = decryptData($vehicle_Id);
		$dealerId = session()->get('userId');
		/* // Fetch user session data and plan details */
		$data['userData'] = $this->userSesData;
		$data['planData'] = $this->planDetails;

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

		/* get plan details */
		$data['planData'] = $this->planDetails;

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

	public function toggleVehicleStatus() {
		$vehicleId = $this->request->getPost('vehicle_id');
		$vehicleFlag = $this->request->getPost('vehicle_flag'); // 1 for Activate, 2 for Deactivate

		// Validate inputs
		if (empty($vehicleId) || empty($vehicleFlag)) {
			return $this->response->setJSON([
				'status' => 'error',
				'message' => 'Invalid request data.'
			]);
		}


		// Check if the vehicle exists
		$vehicle = $this->vehicleModel->find($vehicleId);
		if (!$vehicle) {
			return $this->response->setJSON([
				'status' => 'error',
				'message' => 'Vehicle not found.'
			]);
		}

		// Update the status
		$data = [
			'is_active' => ($vehicleFlag == 1) ? 1 : 2, // 1 = Active, 2 = Inactive
		];

		if ($this->vehicleModel->update($vehicleId, $data)) {
			$statusText = ($vehicleFlag == 1) ? 'Enabled' : 'Disabled';
			return $this->response->setJSON([
				'status' => 'success',
				'message' => "Vehicle successfully $statusText."
			]);
		}

		// Error response
		return $this->response->setJSON([
			'status' => 'error',
			'message' => 'Failed to update the vehicle status.'
		]);
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

	public function test_drive_view() {
		$data = array();
		/* // Fetch user session data and plan details */
		$data['userData'] = $this->userSesData;
		$data['planData'] = $this->planDetails;

		echo view('dealer/vehicles/list-test-drive-request', $data);
	}

	public function fetch_test_drive_request() {
		// Fetch user session data and plan details
		$data['userData'] = $this->userSesData;
		$data['planData'] = $this->planDetails;

		$dealerId = $data['userData']['userId'];

		// Datatable request parameters
		$draw = $this->request->getPost('draw');
		$start = (int)$this->request->getPost('start'); // Cast to int
		$length = (int)$this->request->getPost('length'); // Cast to int
		$search = $this->request->getPost('search')['value'];
		$order = $this->request->getPost('order')[0];
		$columnIndex = $order['column']; // Column index
		$columnName = $this->request->getPost('columns')[$columnIndex]['data']; // Column name
		$columnSortOrder = $order['dir']; // ASC or DESC

		// Set the base query and get the result
		$resultData = $this->vehicleModel->fetchTestDriveData($dealerId, $search, $columnName, $columnSortOrder, '', $start, $length);

		// Calculate the total records based on the resultData array
		// Assuming 'data' is the key that contains the result records
		$totalRecords = isset($resultData) ? count($resultData) : 0;

		// Process the data only if it's an array
		if (isset($resultData) && is_array($resultData)) {
			// Process the records to include image URL for the license file
			$data = array_map(function ($row) {
				if (!empty($row['license_file_path'])) {
					// Concatenate the base URL with the license file path
					$row['license_file_path'] = WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'test_drive_data/license/' . $row['license_file_path'];
				} else {
					// Use a placeholder image if the key is missing or empty
					$row['license_file_path'] = NO_IMAGE_AVAILABLE;
				}
				return $row;
			}, $resultData);
		} else {
			// If the data field is not found or not an array, return empty data
			$data = [];
		}

		// Return JSON response with totalRecords, filtered data, and processed data
		return $this->response->setJSON([
			'draw' => intval($draw),
			'recordsTotal' => $totalRecords, // Use the total count of records from resultData
			'recordsFiltered' => $totalRecords, // Adjust based on filtering logic (you may need to implement filtered total if necessary)
			'data' => $data // Use processed data
		]);
	}

	public function update_test_drive_status() {
		$data['userData'] = $this->userSesData;
		$dealerId = $data['userData']['userId'];

		$updateData = array(
			'testDriveRequestId' => $this->request->getPost('testDriveRequestId'),
			'status' => $this->request->getPost('status'),
			'reason_selected' => $this->request->getPost('reason_selected'),
			'dealer_comments' => $this->request->getPost('dealer_comments'),
			'updated_by' => $dealerId,
			'updated_at' => date("Y-m-d H:i:s")
		);

		if (!$this->request->getPost('testDriveRequestId') || !$this->request->getPost('status')) {
			echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
			return;
		}

		if ($this->vehicleModel->update_test_drive_status($updateData)) {
			/* mail to user */

			$query = $this->vehicleModel->fetchTestDriveData('', '', '', '', $this->request->getPost('testDriveRequestId'));
			// Get paginated data
			$getTestDriveDataByIDTemp = $query->get()->getResultArray();
			$getTestDriveDataByID = $getTestDriveDataByIDTemp[0];

			$subject = 'Test Drive Status Update - ' . ucfirst($getTestDriveDataByID['status']);
			$body = view('dealer/email_templates/test_drive_reminder_mail', ['testDriveData' => $getTestDriveDataByID]);
			$mailResult = sendEmail($getTestDriveDataByID['customer_email'], $getTestDriveDataByID['customer_name'], $subject, $body);
			if ($mailResult <> true) {
				throw new \Exception('Failed to send email');
			} else {
				$response = array(
					'status' => 'success',
					'message' => 'Test Drive Status Updated Successfully',
					'responseData' => $mailResult
				);
			}
			return $this->response->setJSON($response);
		} else {
			return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to update status.']);
		}
	}

	public function updateVehicleSoldStatus() {

		$vehicleId = decryptData($this->request->getPost('vehicle_id'));

		$data['userData'] = $this->userSesData;
		$dealerId = $data['userData']['userId'];

		if (!$vehicleId || !$this->request->getPost('reason')) {
			return $this->response->setJSON(['error' => false, 'message' => 'Invalid request']);
		}

		$updateData = array(
			'is_active' => '4', //flag for sold in column is_active
			'soldReason' => $this->request->getPost('reason'),
			'updated_by' => $dealerId,
			'updated_datetime' => date("Y-m-d H:i:s")
		);

		$result = $this->vehicleModel->updateData($vehicleId, $updateData);

		if ($result) {
			return $this->response->setJSON(['success' => true, 'message' => 'Marked as Sold successfully.']);
		} else {
			return $this->response->setJSON(['success' => false, 'message' => 'Failed to update vehicle status.']);
		}
	}
}
