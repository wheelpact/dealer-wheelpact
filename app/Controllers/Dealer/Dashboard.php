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

	public function __construct() {
		$this->branchModel  = new BranchModel();
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

			echo view('dealer/dashboard/index', $data);
		} catch (\Exception $e) {
			log_message('error', $e->getMessage());
			return $this->response->setStatusCode(500)->setBody('An error occurred: ' . $e->getMessage());
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
