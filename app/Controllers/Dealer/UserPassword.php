<?php

namespace App\Controllers\dealer;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\CommonModel;

/**
 * End session controller
 */
class UserPassword extends BaseController {

	protected $userModel;
	protected $commonModel;

	public function __construct() {
		$this->userModel = new UserModel();
		$this->commonModel = new CommonModel();
	}

	public function index() {
		echo view('dealer/auth/forgot-password.php');
	}

	public function dealer_profile() {
		$dealerId = session()->get('userId');

		$dealerDetails = $this->userModel->getDealerDetailsById($dealerId);

		if ($dealerDetails) {
			$data['countryList'] = $this->commonModel->get_all_country_data();
		} else {
			echo "User not found.";
		}

		$data['userData'] = $dealerDetails;

		echo view('dealer/auth/profile.php', $data);
	}

	public function update_profile_details() {
		// echo "<pre>";
		// print_r($this->request->getPost());
		// die;
		try {
			/* form validation start */
			$validation = \Config\Services::validation();

			$fieldsToValidate = [
				// 'profile_image' => [
				// 	'rules' => 'required',
				// 	'errors' => [
				// 		'required' => 'Please choose an image for profile',
				// 	],
				// ],
				'email' => [
					'rules' => 'required|valid_email|is_unique[users.email,id,{user_id}]',
					'errors' => [
						'required' => 'Please enter an email address.',
						'valid_email' => 'Please enter a valid email address.',
						'is_unique' => 'The email address is already in use.'
					],
				],
				'dealerName' => [
					'rules' => 'required',
					'errors' => [
						'required' => 'Please enter a name.'
					],
				],
				'gender' => [
					'rules' => 'required',
					'errors' => [
						'required' => 'Select Gender.'
					],
				],
				'zipcode' => [
					'rules' => 'required',
					'errors' => [
						'required' => 'Enter Postal Code.'
					],
				],
				'date_of_birth' => [
					'rules' => 'required',
					'errors' => [
						'required' => 'Choose DOB'
					],
				],
				'contact_no' => [
					'rules' => 'required',
					'errors' => [
						'required' => 'Enter Contact Number.'
					],
				],

				'addr_residential' => [
					'rules' => 'required',
					'errors' => [
						'required' => 'Enter Residential Address.'
					],
				],
				// 'addr_permanent' => [
				// 	'rules' => 'required',
				// 	'errors' => [
				// 		'required' => 'Enter Permanent Address.'
				// 	],
				// ],
				'chooseCountry' => [
					'rules' => 'required|not_equals[0]',
					'errors' => [
						'required' => 'Please choose a country.',
						'not_equals' => 'Please choose a valid country.'
					],
				],
				'chooseState' => [
					'rules' => 'required|not_equals[0]',
					'errors' => [
						'required' => 'Please choose a state.',
						'not_equals' => 'Please choose a valid state.'
					],
				],
				'chooseCity' => [
					'rules' => 'required|not_equals[0]',
					'errors' => [
						'required' => 'Please choose a city.',
						'not_equals' => 'Please choose a valid city.'
					],
				],


			];

			foreach ($fieldsToValidate as $fieldName => $rules) {
				$validation->reset(); // Reset validation rules for each field
				$validation->setRules([$fieldName => $rules]);

				if (!$validation->withRequest($this->request)->run()) {
					$errors = $validation->getErrors();

					// Convert the errors to HTML format
					$errorString = '';
					foreach ($errors as $field => $error) {
						$errorString .= $error;
					}

					// Return the errors as a JSON response
					return $this->response->setJSON(['status' => 'error', 'message' => $errorString, 'field' => $fieldName]);
				}
			}

			/* form validation ends */
			$dealerId = session()->get('userId');

			$formData = [
				'name' => $this->request->getPost('dealerName'),
				'gender' => $this->request->getPost('gender'),
				'date_of_birth' => $this->request->getPost('date_of_birth'),
				'contact_no' => $this->request->getPost('contact_no'),
				'addr_residential' => $this->request->getPost('addr_residential'),
				'addr_permanent' => $this->request->getPost('addr_permanent'),
				'email' => $this->request->getPost('email'),
				'zipcode' => $this->request->getPost('zipcode'),
				'country_id' => $this->request->getPost('chooseCountry'),
				'state_id' => $this->request->getPost('chooseState'),
				'city_id' => $this->request->getPost('chooseCity'),
				'social_fb_link' => $this->request->getPost('social_fb_link'),
				'social_twitter_link' => $this->request->getPost('social_twitter_link'),
				'social_linkedin_link' => $this->request->getPost('social_linkedin_link'),
				'social_skype_link' => $this->request->getPost('social_skype_link')
			];

			$uploadFolderPath = realpath($_SERVER['DOCUMENT_ROOT'] . '/../../production/');
			$destinationPath = $uploadFolderPath . '/public/uploads/';

			if (isset($_FILES['profile_image']['name']) && !empty($_FILES['profile_image']['name'])) {
				$file = $this->request->getFile('profile_image');
				$newName = $file->getRandomName();
				try {
					$file->move($destinationPath . 'user_profile_img', $newName);
					//echo 'File moved successfully.';
				} catch (\Exception $e) {
					return $this->response->setJSON(['errors' => true, 'message' => 'Error in Updating Profile Imabe.']);
				}
				$formData['profile_image'] = $newName;
			}

			// Update the data into the database table
			$result = $this->userModel->updateData($dealerId, $formData);

			if ($result) {
				return $this->response->setJSON(['status' => 'success', 'redirect' => 'dealer/profile', 'message' => 'Details Updated Successfully.']);
			} else {
				return $this->response->setJSON(['errors' => true, 'message' => 'Error in Updating data.']);
			}
		} catch (\Exception $e) {
			// Error handling and logging
			$logger = \Config\Services::logger();
			$logger->error('Error occurred while updating Details: ' . $e->getMessage());

			// Throw or handle the exception as needed
			throw $e;
		}
	}

	public function sendPwdResetLink() {
		$email = $this->request->getPost('email');

		$validation = \Config\Services::validation();

		$validation->setRules([
			'email' => [
				'rules' => 'required|valid_email',
				'errors' => [
					'required' => 'Please enter an email address.',
					'valid_email' => 'Please enter a valid email address.',
				],
			],
		]);

		if (!$validation->withRequest($this->request)->run()) {
			/* If validation fails, return the first error message */
			$errors = $validation->getErrors();
			$fieldName = key($errors); // Get the first field name with an error
			$errorMessage = reset($errors); // Get the first error message

			/* Return the error as a JSON response */
			return $this->response->setJSON(['status' => 'error', 'message' => $errorMessage, 'field' => $fieldName]);
		}

		/* Check if the email exists in the users table */
		$user = $this->userModel->where('email', $email)->where('role_id', '2')->first();
		/* Get the last executed query */
		if ($user) {

			/* Check if there is already a valid token for the user */
			if ($user['reset_token'] && strtotime($user['token_expiration']) > time()) {
				/* Valid token exists, inform the user */
				return $this->response->setJSON(['status' => 'error', 'message' => 'Request has been already raised, Please check your email or Please try after some time']);
			}

			/* Generate and store a reset token in the database */
			$resetToken = bin2hex(random_bytes(16));
			$expirationTime = date('Y-m-d H:i:s', strtotime('+1 hour'));
			$this->userModel->update($user['id'], ['reset_token' => $resetToken, 'token_expiration' => $expirationTime]);

			/* Send reset link to the user's email using the helper method */
			$subject = 'Password Reset Link';
			$message = "Click the following link to reset your password: " . site_url("dealer/reset-password/{$resetToken}");
			$sender = 'no-replay@wheelpact.com'; // Replace with the actual sender's email address
			$receiver = $email;

			/* Send reset link to the user's email */
			if (sendEmail($subject, $message, $sender, $receiver)) {
				return $this->response->setJSON(['status' => 'success', 'message' => 'Reset link sent to your email.']);
			} else {
				return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to send the reset link.']);
			}
		} else {
			return $this->response->setJSON(['status' => 'error', 'message' => 'User not Found, Please Enter Registered Email.']);
		}
	}

	public function reset_password($resetToken) {
		$data = [];
		$data['resetToken'] = $resetToken;
		/* Validate the reset token */
		if (!$resetToken || !ctype_xdigit($resetToken)) {
			/* Invalid token format */
			echo view('errors/html/error_404', $data);
		}

		/* Check if the token exists in the database */

		$user = $this->userModel->where('reset_token', $resetToken)->first();

		if (!$user || strtotime($user['token_expiration']) < time()) {
			/* Token not found or expired */
			echo view('dealer/auth/reset-password.php', $data);
		} else {
			echo view('dealer/auth/reset-password.php', $data);
		}
	}

	public function update_password() {
		if ($this->request->getPost('resetToken')) {
			/* password reset from forgot password flow */
			$resetToken = $this->request->getPost('resetToken');
			$newPassword = $this->request->getPost('password');

			/* Check if the token exists in the database */
			$user = $this->userModel->where('reset_token', $resetToken)->first();

			if (!$user || strtotime($user['token_expiration']) < time()) {
				/* Token not found or expired */
				return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Request or expired token.']);
			}

			$updatePass = $this->userModel->updatePassword($user['id'], password_hash($newPassword, PASSWORD_BCRYPT));
		} else {
			/* Password updated from profile page */

			// Get input data
			$oldPassword = $this->request->getPost('old_password');
			$newPassword = $this->request->getPost('new_pwd');
			$confirmPassword = $this->request->getPost('confirm_password');

			$dealerId = session()->get('userId');
			// get user details
			$userData = $this->userModel->where('id', $dealerId)->first();
		
			// Validate old password		
			$oldPwdCheck = $this->userModel->chkUserCredentials($userData['email'], $oldPassword);
		
			if ($oldPwdCheck) {
				$updatePass = $this->userModel->updatePassword($dealerId, password_hash($newPassword, PASSWORD_BCRYPT));
			} else {
				return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Old Password, Please try Again']);
			}
		}
		if ($updatePass) {
			return $this->response->setJSON(['status' => 'success', 'message' => 'Password updated successfully.']);
		} else {
			return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid Request / Link Expired.']);
		}
	}
}
