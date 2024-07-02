<?php

namespace App\Controllers\dealer;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\BranchModel;

/**
 * 
 */
class Dashboard extends BaseController {

	protected $branchModel;
	protected $userModel;

	public function __construct() {
		$this->branchModel  = new BranchModel();
		$this->userModel  = new UserModel();
	}

	public function index() {
		$session = session();
		$data['session'] = \Config\Services::session();
		$data['userData'] = $session->get();

		try {
			$dealerId = session()->get('userId');
			if (is_null($dealerId)) {
				throw new \Exception("User ID not found in session.");
			}

			$data['mainBranchData'] = $this->branchModel->getAllBranchByDealerId($dealerId, '0', '0', '0', '0', '0', '0');
			if (empty($data['mainBranchData'])) {
				$data['mainBranch'] = null;
			} else {
				$data['mainBranch'] = $data['mainBranchData'][0];
			}

			/* check for plan details if details to set allowed vehicle her can list for plan 1 & 2 i.e Free & Basic plan */
			$planDetails = $this->userModel->getPlanDetailsBYId($dealerId);
			$data['planData'] = $planDetails[0];
			if ($data['planData']['allowedVehicleListing'] == "0" && ($data['planData']['activePlan'] == '1' || $data['planData']['activePlan'] == '2')) {
				echo view('dealer/dashboard/updateDealerPlanDetails', $data);
				exit;
			}

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

	public function one() {
		$session = session();
		$data['session'] = \Config\Services::session();
		$data['username'] = $session->get('user_name');
		echo view('dealer/dashboard/index', $data);
	}

	public function two() {
		$session = session();
		$data['session'] = \Config\Services::session();
		$data['username'] = $session->get('user_name');
		echo view('dealer/dashboard/index2', $data);
	}
	public function three() {
		$session = session();
		$data['session'] = \Config\Services::session();
		$data['username'] = $session->get('user_name');
		echo view('dealer/dashboard/index3', $data);
	}
}
