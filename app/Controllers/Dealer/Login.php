<?php

namespace App\Controllers\dealer;

use App\Controllers\BaseController;
use App\Models\UserModel;

/**
 * 
 */
class Login extends BaseController {

	public function index() {
		echo view('dealer/auth/login');
	}

	public function auth() {
		$session = session();
		$model = new UserModel();
		$username = $this->request->getPost('username');
		$password = $this->request->getPost('password');

		$data = $model->chkUserCredentials($username, $password);
		/*
		$lastQuery = $model->getLastQuery();
		echo $lastQuery;
		*/
		if ($data) {
			$ses_data = [
				'userId'	=> $data['userId'],
				'role_id'	=> $data['role_id'],
				'username' 	=> $data['email'],
				'user_code' => $data['user_code'],
				'email'    	=> $data['email'],
				'is_active'	=> $data['is_active'],
				'logged_in' => TRUE
			];

			$session->set($ses_data);
			return redirect()->to('./dealer/dashboard');
		} else {
			$session->setFlashdata('msg', 'Invalid Username / Password');
			return redirect()->to('./dealer/login');
		}
	}
}
