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
			//echo "<pre>"; print_r($data['planData']); die;
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
		//echo "<pre>"; print_r($this->request->getPost()); die;
		try {

			// Retrieve data from POST request
			$data = [
				'dealer_id' => $this->request->getPost('dealerId'),
				'transaction_id' => $this->request->getPost('transactionId'),
				'activePlan' => $this->request->getPost('activePlan'),
				'vehicle_type' => $this->request->getPost('vehicle_type'),
			];

			// Load the model and update the data in the database
			$result = $this->userModel->updatePlanPreference($data);

			/*echo $this->userModel->db->getLastQuery(); die;*/

			if (!$result) {
				// Return a JSON response if the update fails
				return $this->response->setJSON([
					'errors' => true,
					'message' => 'Error occurred while updating data.'
				]);
			}
			return redirect()->to('dealer/dashboard');
		} catch (\Exception $e) {
			// Error handling and logging
			$logger = \Config\Services::logger();
			$logger->error('Error occurred while updating vehicle information: ' . $e->getMessage());

			// Return a JSON response with the error message
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
