<?php

namespace App\Controllers\dealer;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CommonModel;
use App\Models\VehicleModel;
use App\Models\BranchModel;

/**
 * End session controller
 */
class Reservation extends BaseController {

	protected $userModel;
	protected $commonModel;
	protected $vehicleModel;
	protected $branchModel;

	protected $userSesData;
	protected $planDetails;

	public function __construct() {
		$this->userModel = new UserModel();
		$this->commonModel = new CommonModel();
		$this->vehicleModel = new VehicleModel();
		$this->branchModel  = new BranchModel();

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

		echo view('dealer/vehicles/list-reserved-vehicles.php', $data);
	}


	public function getReservedVehicles() {
		$limit = $this->request->getVar('limit');
		$offset = $this->request->getVar('start');
		$dealerId = session()->get('userId');

		$branches = $this->branchModel->where('dealer_id', $dealerId)->findAll();

		$dealerReservedVehiclesHtml = '';

		foreach ($branches as $branch) {

			$reservedVehicles = $this->vehicleModel->getReservedVehiclesByBranch($branch['id'], $limit, $offset);

			foreach ($reservedVehicles as $reservedVehicle) {

				/* // Unserialize orderNotes */
				$orderNotesData = unserialize($reservedVehicle['orderNotes']);

				// echo "<pre>";
				// print_r($reservedVehicles); 
				// die;

				$dealerReservedVehiclesHtml .= '
				<div class="col-md-6 col-lg-4 reserved-vehicle-card-' . $reservedVehicle['id'] . '">
					<div class="card card-box mb-3 position-relative">
						<img class="card-img-top vehicle-image" src="' . WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'vehicle_thumbnails/' . $reservedVehicle['thumbnail_url'] . '" alt="' . $reservedVehicle['unique_id'] . '" />
						<div class="card-body">
							<h5 class="card-title weight-500">' . $reservedVehicle['cmp_name'] . ' ' . $reservedVehicle['model_name'] . ' <br/>(' . $reservedVehicle['branchName'] . ')</h5>
							<p class="card-text"></p>
							<div class="d-flex vehicle-overview">
								<div class="overview-badge">
									<h6>Year</h6>
									<h5>' . ($reservedVehicle['manufacture_year'] ?? 'N/A') . '</h5>
								</div>
								<div class="overview-badge">
									<h6>Driven</h6>
									<h5>' . ($reservedVehicle['kms_driven'] ?? 'N/A') . '</h5>
								</div>
								<div class="overview-badge">
									<h6>Fuel Type</h6>
									<h5>' .  ($reservedVehicle['fuel_type'] ?? 'N/A') . '</h5>
								</div>
								<div class="overview-badge">
									<h6>Owner</h6>
									<h5>' . ordinal($reservedVehicle['owner']) . '</h5>
								</div>
								<!-- Add other overview badges based on your data -->
								<div class="wishlist">
									<i class="icofont-heart"></i>
								</div>
							</div>
							<hr/>
							<h5 class="card-title weight-500">Reservation Details</h5>
							<p class="card-text"></p>
							<div class="d-flex vehicle-overview pd-10">
								<div class="overview-badge">
									<h6>Resveration Id</h6>
									<h5>#' .  ($reservedVehicle['reservation_id'] ?? 'N/A') . '</h5>
								</div>
								<div class="overview-badge">
									<h6>Resveration Date</h6>
									<h5>' . date("d-m-Y h:i a", strtotime($reservedVehicle['created_datetime'])) . '</h5>
								</div>
							</div>
							
							<div class="d-flex vehicle-overview pd-10">
								<div class="overview-badge">
									<h6>Date of Visit</h6>
									<h5>' . date("d-m-Y", strtotime($reservedVehicle['dateOfVisit'])) . '</h5>
								</div>
								<div class="overview-badge">
									<h6>TIme of Visit</h6>
									<h5>' . date("h:i a", strtotime($reservedVehicle['timeOfVisit'])) . '</h5>
								</div>
							</div>

							<div class="d-flex vehicle-overview pd-10">
								<div class="overview-badge">
									<h6>Reserved By</h6>
									<h5>' . ($reservedVehicle['customer_name'] ?? 'N/A') . '</h5>
								</div>
								<div class="overview-badge">
									<h6>Customer Contact</h6>
									<h5>' . ($reservedVehicle['contact_no'] ?? 'N/A') . '</h5>
								</div>
							</div>

							<div class="d-flex vehicle-overview pd-10">
								<div class="overview-badge">
									<h6>Order Id</h6>
									<h5>' . ($reservedVehicle['orderId'] ?? 'N/A') . '</h5>
								</div>
								<div class="overview-badge">
									<h6>Reservation Amount Paid</h6>
									<h5>' . ($orderNotesData['reservationAmt'] ?? 'N/A') . '</h5>
								</div>
							</div>
							
							<div class="option-btn">
								<div class="dropdown">
									<a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
										<i class="dw dw-more"></i>
									</a>
									<div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
										<a class="dropdown-item" href="' . base_url() . 'dealer/single-vehicle-info/' . $reservedVehicle['id'] . '"><i class="dw dw-eye"></i> View</a>
										<a class="dropdown-item" href="' . base_url() . 'dealer/edit-vehicle/' . $reservedVehicle['id'] . '"><i class="dw dw-edit2"></i> Edit</a>
										<a class="dropdown-item sa-params delete-vehicle" data-vehicle-id="' . $reservedVehicle['id'] . '" href="#"><i class="dw dw-delete-3"></i> Delete</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>';
			}
		}

		echo $dealerReservedVehiclesHtml;
	}
}
