<?php

namespace App\Controllers\dealer;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\BranchModel;
use App\Models\VehicleModel;

/**
 * 
 */
class Dashboard extends BaseController {

	protected $branchModel;
	protected $userModel;
	protected $vehicleModel;

	protected $userSesData;
	protected $planDetails;

	public function __construct() {
		$this->branchModel  = new BranchModel();
		$this->userModel  = new UserModel();
		$this->vehicleModel = new VehicleModel();

		/* // Retrieve session */
		$this->userSesData = session()->get();

		// /* // Retrieve plan details of logged user */
		$planDetails = $this->userModel->getPlanDetailsBYId(session()->get('userId'));
		//echo '<pre>'; print_r($planDetails); die;
		// Assign plan details if found, otherwise set to null
		$this->planDetails = !empty($planDetails) ? $planDetails[0] : null;
		
	}

	public function index() {

		$data['userData'] = $this->userSesData;
		// Handle plan details
		if (!empty($this->planDetails)) {
			$data['planData'] = $this->planDetails;
		}

		try {
			$dealerId = session()->get('userId');

			if (is_null($dealerId)) {
				throw new \Exception("Invalid Request.");
			}

			$data['mainBranchData'] = $this->branchModel->getAllBranchByDealerId($dealerId, '0', '0', '0', '0', '0', '0');
			if (empty($data['mainBranchData'])) {
				$data['mainBranch'] = null;
			} else {
				$data['mainBranch'] = $data['mainBranchData']['data'];
			}

			/* // total promoted vehicles under showroom & vehicle */
			$branchVehicleInsights = $this->vehicleModel->getVehicleInsights($dealerId);
			$data['branchVehicleInsights'] = !empty($branchVehicleInsights) ? $branchVehicleInsights[0] : '0';

			/* // total active & in-active vehicles */
			$PromotedInsight = $this->vehicleModel->getPromotedInsight($dealerId);
			$data['vehiclePromoteCount'] = !empty($PromotedInsight['promotionUnderVehicle']) ? $PromotedInsight['promotionUnderVehicle'] : '0';
			$data['showroomPromoteCount'] = !empty($PromotedInsight['promotionUnderShowroom']) ? $PromotedInsight['promotionUnderShowroom'] : '0';

			/* // total test drive requests in pending */
			$testDriveRequestsCount = $this->vehicleModel->fetchTestDriveDataCount($dealerId);
			$data['testDriveRequestsCount']  = !empty($testDriveRequestsCount) ? $testDriveRequestsCount[0] : '0';

			/*  get promoted vechiles start */
			// Get branches for the dealer
			$branches = $this->branchModel->where('dealer_id', $dealerId)->getAllBranchByDealerId($dealerId, NULL, NULL, NULL, NULL, NULL, NULL, TRUE);
			$promotedVehicles = [];

			foreach ($branches['data'] as $branch) {
				$branchPromotedVehicles = $this->vehicleModel->getAllVehiclesByBranch($branch['id'], NULL, NULL, NULL, NULL, NULL, NULL, TRUE);

				if (!empty($branchPromotedVehicles)) {
					$promotedVehicles = array_merge($promotedVehicles, $branchPromotedVehicles['data']);
				}
			}

			// Remove duplicates by vehicle ID
			$promotedVehicles = array_values(array_unique($promotedVehicles, SORT_REGULAR));
			$data['dealerPromotedVehicles'] = !empty($promotedVehicles) ? $promotedVehicles : array();
			//echo "<pre>"; print_r($data['dealerPromotedVehicles']); die;
			/*  get promoted vechiles end */

			/* get promoted showrooms start */
			$promotedShowrooms = array_filter($branches['data'], function ($record) {
				return $record['is_promoted'] == 1;
			});

			$data['dealerPromotedShowrooms'] = !empty($promotedShowrooms) ? $promotedShowrooms : array();
			/* get promoted showrooms end */

			/* get all test drive requests */
			$testDriveRequests = $this->vehicleModel->fetchTestDriveData($dealerId, '', '', 'desc', '', 5);
			// Sort the array by 'formatted_created_at' in descending order
			usort($testDriveRequests, function ($a, $b) {
				return strtotime($b['formatted_created_at']) - strtotime($a['formatted_created_at']);
			});
			// Get the last five records (after sorting)
			$data['testDriveRequests'] = array_slice($testDriveRequests, 0, 5);

			echo view('dealer/dashboard/index', $data);
		} catch (\Exception $e) {
			log_message('error', $e->getMessage());
			return $this->response->setStatusCode(500)->setBody('An error occurred: ' . $e->getMessage());
		}
	}

	public function updatePlanPreference() {
		try {

			$validation = \Config\Services::validation();

			$validation->setRules([
				'vehicle_type' => 'required',
			]);

			// Run the validation
			if (!$validation->withRequest($this->request)->run()) {
				// Validation failed, return errors in JSON format
				$errors = $validation->getErrors();
				return $this->response->setJSON(['success' => false, 'errors' => $errors]);
			}
			/* // Retrieve data from POST request */
			$data = [
				'dealer_id' => $this->request->getPost('dealerId'),
				'transaction_id' => $this->request->getPost('transactionId'),
				'activePlan' => $this->request->getPost('activePlan'),
				'vehicle_type' => $this->request->getPost('vehicle_type'),
			];

			/* // Load the model and update the data in the database */
			$result = $this->userModel->updatePlanPreference($data);

			if (!$result) {
				return $this->response->setJSON([
					'errors' => true,
					'message' => 'Error occurred while updating data.'
				]);
			} else {
				// Return the URL to be used by the jQuery success function
				return $this->response->setJSON(['status' => 'success', 'message' => 'Information Updated Successfully.']);
			}
		} catch (\Exception $e) {
			$logger = \Config\Services::logger();
			$logger->error('Error occurred while updating vehicle information: ' . $e->getMessage());

			return $this->response->setJSON([
				'errors' => true,
				'message' => 'An error occurred. Please try again later.'
			]);
		}
	}

	public function loadTestDriveChartData() {
		$data['userData'] = $this->userSesData;
		$data['planData'] = $this->planDetails;

		$dealerId = $data['userData']['userId'];

		if ($this->request->isAJAX()) {
			$resultData = $this->vehicleModel->fetchTestDriveData($dealerId, '', '', 'desc', '', '');

			$chartData = [];
			$statuses = ['pending', 'accepted', 'rejected', 'completed']; // Default statuses

			// Process the data to group by vehicle and status
			foreach ($resultData as $entry) {
				$vehicle = $entry['cmp_name'] . ' ' . $entry['model_name'];
				$status = $entry['status'];

				// Initialize the vehicle's status counts
				if (!isset($chartData[$vehicle])) {
					$chartData[$vehicle] = array_fill_keys($statuses, 0);
				}

				// Increment the status count for the vehicle
				$chartData[$vehicle][$status]++;
			}

			// Prepare the data for the chart
			$vehicles = array_keys($chartData); // Vehicle names for the x-axis
			$series = [];
			foreach ($statuses as $status) {
				$statusData = [];
				foreach ($vehicles as $vehicle) {
					$statusData[] = $chartData[$vehicle][$status];
				}
				$series[] = [
					'name' => $status, // Status name
					'data' => $statusData, // Count of each status per vehicle
				];
			}

			return $this->response->setJSON([
				'vehicles' => $vehicles, // Vehicle names for x-axis
				'series' => $series,    // Status counts for each vehicle
			]);
		}

		throw new \CodeIgniter\Exceptions\PageNotFoundException('Page not found');
	}
}
