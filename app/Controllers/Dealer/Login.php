<?php

namespace App\Controllers\dealer;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Login extends BaseController {

	public function index() {

		$data = [];

		// Check if "Remember Me" JWT exists
		$rememberMeCookie = $this->request->getCookie('remember_me');
		if ($rememberMeCookie) {
			// Decode the JWT
			$decodedData = decode_jwt($rememberMeCookie, WP_DEALER_JWT_TOKEN);

			if ($decodedData && isset($decodedData['username'])) {
				$data['username'] = $decodedData['username'];
				$data['password'] = "************"; // Mask the password
			}
		}

		return view('dealer/auth/login', $data);
	}

	public function auth() {
		$session = session();
		$model = new UserModel();

		// Validate input
		if (!$this->validate([
			'username' => 'required',
			'password' => 'permit_empty' // Allow empty password if "Remember Me" is used
		])) {
			$session->setFlashdata('msg', 'Username and Password are required.');
			return redirect()->to('./dealer/login')->withInput();
		}

		// Get username and password from POST
		$username = $this->request->getPost('username');
		$rememberMe = $this->request->getPost('remember'); // Get "Remember Me" checkbox value

		// Check if "Remember Me" cookie exists
		$rememberMeCookie = $this->request->getCookie('remember_me');

		if ($rememberMeCookie) {
			try {
				// Decode the JWT
				$decodedData = decode_jwt($rememberMeCookie, WP_DEALER_JWT_TOKEN);
				
				// Ensure the decoded data contains the expected fields
				if (isset($decodedData['username'], $decodedData['password'])) {
					// Use cookie password only if the username matches
					if ($decodedData['username'] === $username) {
						$password = $decodedData['password'];
					} else {
						// Username mismatch - fallback to POST password
						$username = $this->request->getPost('username');
						$password = $this->request->getPost('password');
						log_message('info', 'Remember Me cookie username mismatch. Using POST password.');
					}
				} else {
					// Invalid JWT payload structure
					log_message('error', 'Invalid Remember Me cookie structure: ' . json_encode($decodedData));
					$session->setFlashdata('msg', 'Invalid Remember Me cookie.');
					return redirect()->to('./dealer/login');
				}
			} catch (\Exception $e) {
				// Log decoding error
				log_message('error', 'JWT Decode Error: ' . $e->getMessage());
				$session->setFlashdata('msg', 'Invalid Remember Me cookie.');
				return redirect()->to('./dealer/login');
			}
		} else {
			// No cookie present, use the POST password
			$password = $this->request->getPost('password');
		}

		try {
			// Attempt to retrieve user data
			$data = $model->chkUserCredentials($username, $password);

			if ($data) {
				// Set session data
				$ses_data = [
					'userId'   => $data['userId'],
					'role_id'  => $data['role_id'],
					'username' => $data['email'],
					'dealerName' => $data['dealerName'],
					'user_code' => $data['user_code'],
					'email'    => $data['email'],
					'is_active' => $data['is_active'],
					'logged_in' => TRUE
				];
				$session->set($ses_data);

				// Handle "Remember Me" functionality with JWT
				if ($rememberMe) {
					$rememberData = [
						'username' => $username,
						'password' => $password
					];

					// Generate JWT with 1 day expiration
					$jwt = generate_jwt($rememberData, WP_DEALER_JWT_TOKEN);

					// Set the JWT token in a cookie for 1 day
					setcookie('remember_me', $jwt, time() + 86400, '/'); // Cookie valid for 1 day
				} else {
					// Clear the JWT cookie if "Remember Me" is not selected
					setcookie('remember_me', '', time() - 3600, '/');
				}

				// Save login logs
				$this->logLoginAttempt();

				return redirect()->to('./dealer/dashboard');
			} else {
				$session->setFlashdata('msg', 'Invalid Username / Password');
				return redirect()->to('./dealer/login');
			}
		} catch (\Exception $e) {
			// Log the error message
			log_message('error', $e->getMessage());
			// Set flash data for error message
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
