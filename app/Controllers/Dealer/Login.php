<?php

namespace App\Controllers\dealer;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Login extends BaseController {

	public function index() {
		echo view('dealer/auth/login');
	}

	public function auth() {
		$session = session();
		$model = new UserModel();

		/* // Validate input */
		if (!$this->validate([
			'username' => 'required',
			'password' => 'required'
		])) {
			$session->setFlashdata('msg', 'Username and Password are required.');
			return redirect()->to('./dealer/login')->withInput();
		}

		$username = $this->request->getPost('username');
		$password = $this->request->getPost('password');

		try {
			/* // Attempt to retrieve user data */
			$data = $model->chkUserCredentials($username, $password);

			if ($data) {
				$ses_data = [
					'userId'   => $data['userId'],
					'role_id'  => $data['role_id'],
					'username' => $data['email'],
					'user_code' => $data['user_code'],
					'email'    => $data['email'],
					'is_active' => $data['is_active'],
					'logged_in' => TRUE
				];

				$session->set($ses_data);

				/* save login logs */
				$this->logLoginAttempt();
			
				return redirect()->to('./dealer/dashboard');
			} else {
				$session->setFlashdata('msg', 'Invalid Username / Password');
				return redirect()->to('./dealer/login');
			}
		} catch (\Exception $e) {
			/* // Log the error message */
			log_message('error', $e->getMessage());
			/* // Set flash data for error message */
			$session->setFlashdata('msg', 'An error occurred during login. Please try again.');
			return redirect()->to('./dealer/login');
		}
	}

	private function logLoginAttempt() {
		$UserModel = new \App\Models\UserModel();
		$UserModel->saveLoginLog([
			'userId' => session()->get('userId'),
			'userRole' => '2',
			'loginTime' => date('Y-m-d H:i:s'),
			'ipAddress' => $this->request->getIPAddress(),
			'userAgent' => $this->request->getUserAgent()->__toString(),
			'sessionVar' => json_encode(session()->get())
		]);

		// Store the ID of the log entry in the session
		$sessionLogId = $UserModel->db->insertID();
		session()->set('login_log_id', $sessionLogId);
	}
}
