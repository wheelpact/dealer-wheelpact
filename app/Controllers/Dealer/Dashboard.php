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
		//$db = \Config\Database::connect();
		//$model = new UserModel();
		$session = session();
		$data['username'] = $session->get('user_name');
		$data['session'] = \Config\Services::session();

		$dealerId = session()->get('userId');
		$data['mainBranchData'] = $this->branchModel->getAllBranchByDealerId($dealerId, '0', '0', '0', '0', '0', '0');
		$data['mainBranch'] = $data['mainBranchData'][0];
		
		echo view('dealer/dashboard/index', $data);
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
