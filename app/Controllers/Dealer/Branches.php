<?php

namespace App\Controllers\dealer;

use App\Controllers\BaseController;

use App\Models\BranchModel;
use App\Models\VehicleModel;
use App\Models\UserModel;
use App\Models\CommonModel;

/**
 * All Vehicles related methods defined in vehicles class 
 */
class Branches extends BaseController {

	protected $commonModel;
	protected $branchModel;
	protected $userModel;
	protected $vehicleModel;

	protected $userSesData;
	protected $planDetails;

	public function __construct() {
		$this->commonModel = new CommonModel();
		$this->branchModel  = new BranchModel();
		$this->userModel = new UserModel();
		$this->vehicleModel = new VehicleModel();

		/* // Retrieve session */
		$this->userSesData = session()->get();

		/* // Retrieve plan details of logged user */
		$planDetails = $this->userModel->getPlanDetailsBYId(session()->get('userId'));
		$this->planDetails = $planDetails[0];
	}

	public function index() {
		$data = array();
		$data['countryList'] = $this->commonModel->get_all_country_data();

		/* // Fetch user session data and plan details */
		$data['userData'] = $this->userSesData;
		$data['planData'] = $this->planDetails;

		echo view('dealer/branches/list-branches', $data);
	}

	public function add_branch() {
		/* // Fetch user session data and plan details */
		$data['userData'] = $this->userSesData;
		$data['planData'] = $this->planDetails;

		/* // Get total branches by user */
		$totalBranchesRes = $this->branchModel->getBranchCountByUser($data['userData']['userId']);
		$totalBranches = $totalBranchesRes[0]['branch_count'];

		/* // Get the allowed number of branches from the user's plan */
		$maxBranchesAllowed = $data['planData']['max_showroom_branches'];

		/* // Compare the current number of branches with the allowed number */
		if ($totalBranches < $maxBranchesAllowed) {
			$data['countryList'] = $this->commonModel->get_all_country_data();
		} else {
			$data['maxBranchesAllowed'] = $maxBranchesAllowed;
			$data['limitExceeded'] = true;
		}

		return view('dealer/branches/add_branch', $data);
	}

	public function save_branch() {
		$db = \Config\Database::connect();
		$db->transStart();

		try {
			/* form validation start */
			$validation = \Config\Services::validation();

			$fieldsToValidate = [
				'branchName' => [
					'rules' => 'required',
					'errors' => [
						'required' => 'Please Enter ShowRoom Name'
					],
				],
				'branchType' => [
					'rules' => 'required|not_equals[0]',
					'errors' => [
						'required' => 'Please choose Showroom-Type.',
						'not_equals' => 'Please choose Showroom-Type.'
					],
				],
				'branchSupportedVehicleType' => [
					'rules' => 'required|not_equals[0]',
					'errors' => [
						'required' => 'Please Select Vehicle Type',
						'not_equals' => 'Please Select Vehicle Type'
					],
				],
				'branchThumbnail' => [
					'rules' => 'uploaded[branchThumbnail]|max_size[branchThumbnail,1024]|is_image[branchThumbnail]|mime_in[branchThumbnail,image/jpeg,image/png,image/gif]',
					'errors' => [
						'uploaded' => 'Please choose Showroom Thumbnail image to upload.',
						'max_size' => 'The image size should not exceed 1 MB.',
						'is_image' => 'The uploaded file is not a valid image.',
						'mime_in' => 'Only JPEG, PNG, and GIF images are allowed.',
					],
				],
				'branchLogo' => [
					'rules' => 'uploaded[branchLogo]|max_size[branchLogo,1024]|is_image[branchLogo]|mime_in[branchLogo,image/jpeg,image/png,image/gif]',
					'errors' => [
						'uploaded' => 'Please choose Showroom Logo image to upload.',
						'max_size' => 'The image size should not exceed 1 MB.',
						'is_image' => 'The uploaded file is not a valid image.',
						'mime_in' => 'Only JPEG, PNG, and GIF images are allowed.',
					],
				],
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
						'required' => 'Please choose a State.',
						'not_equals' => 'Please choose a valid State.'
					],
				],
				'chooseCity' => [
					'rules' => 'required|not_equals[0]',
					'errors' => [
						'required' => 'Please choose a City.',
						'not_equals' => 'Please choose a valid City.'
					],
				],

				'address' => 'required',
				'contactNumber' => [
					'rules' => 'required|regex_match[/^[0-9]{10}$/]',
					'errors' => [
						'required' => 'Please enter a contact number.',
						'regex_match' => 'Please enter a valid 10-digit number.',
					],
				],
				'whatsapp_no' => [
					'rules' => 'required|regex_match[/^[0-9]{10}$/]',
					'errors' => [
						'required' => 'Please enter whatsapp number.',
						'regex_match' => 'Please enter a valid 10-digit number.',
					],
				],
				'email' => [
					'rules' => 'required|valid_email',
					'errors' => [
						'required' => 'Please enter an email address.',
						'valid_email' => 'Please enter a valid email address.',
					],
				],
				'shortDescription' => [
					'rules' => 'required',
					'errors' => [
						'required' => 'Please enter Short Description.'
					],
				],
				'map_latitude' => [
					'rules' => 'required',
					'errors' => [
						'required' => 'Please Add Location in Map'
					],
				]
			];

			foreach ($fieldsToValidate as $fieldName => $rules) {
				$validation->reset(); // Reset validation rules for each field
				$validation->setRules([$fieldName => $rules]);

				if (!$validation->withRequest($this->request)->run()) {
					$errors = $validation->getErrors();
					$errorString = "";
					foreach ($errors as $field => $error) {
						$errorString .= $error;
					}
					return $this->response->setJSON(['status' => 'error', 'message' => $errorString, 'field' => $fieldName]);
				}
			}
			/* form validation ends */

			/* validate contact number, email exist start */

			$checkContactNo = $this->request->getPost('contactNumber');
			$checkwhatsapp_no = $this->request->getPost('whatsapp_no');
			$checkEmail = $this->request->getPost('email');

			// Check if the contact whatsapp_no already exists
			$existingContact = $this->branchModel->where('contact_number', $checkContactNo)->first();

			if ($existingContact) {
				return $this->response->setJSON(['status' => 'error', 'message' => 'Contact number already exists.', 'field' => 'contactNumber']);
			}

			// Check if the whatsapp_no already exists
			$existingwhatsapp_no = $this->branchModel->where('whatsapp_no', $checkwhatsapp_no)->first();

			if ($existingwhatsapp_no) {
				return $this->response->setJSON(['status' => 'error', 'message' => 'Whatsapp No. already exists.', 'field' => 'whatsapp_no']);
			}

			// Check if the email already exists
			$existingEmail = $this->branchModel->where('email', $checkEmail)->first();

			if ($existingEmail) {
				return $this->response->setJSON(['status' => 'error', 'message' => 'Email already exists.', 'field' => 'email']);
			}

			/* validate contact number, email exist end */


			// Get the form input values
			$dealerId = session()->get('userId');
			$branchName = $this->request->getPost('branchName', FILTER_UNSAFE_RAW);

			$uploadFolderPath = realpath($_SERVER['DOCUMENT_ROOT'] . '/../../production/');
			$destinationPath = $uploadFolderPath . '/public/uploads/';

			$branchBanner1newName = '';
			$branchBanner2newName = '';
			$branchBanner3newName = '';
			$branchThumbnailnewName = '';
			$branchLogonewName = '';

			if ($branchBanner1 = $this->request->getFile('branchBanner1')) {
				// Generate a new name for the thumbnail image to prevent name conflicts
				$branchBanner1newName = $branchBanner1->getRandomName();

				try {
					$branchBanner1->move($destinationPath . 'branch_banners/', $branchBanner1newName);
				} catch (\Exception $e) {
					throw new \RuntimeException('Error moving branch Banner1 file: ' . $e->getMessage());
				}
			}
			if ($branchBanner2 = $this->request->getFile('branchBanner2')) {
				// Generate a new name for the thumbnail image to prevent name conflicts
				$branchBanner2newName = $branchBanner2->getRandomName();

				try {
					$branchBanner2->move($destinationPath . 'branch_banners/', $branchBanner2newName);
				} catch (\Exception $e) {
					throw new \RuntimeException('Error moving branch Banner2 file: ' . $e->getMessage());
				}
			}
			if ($branchBanner3 = $this->request->getFile('branchBanner3')) {
				// Generate a new name for the thumbnail image to prevent name conflicts
				$branchBanner3newName = $branchBanner3->getRandomName();

				try {
					$branchBanner3->move($destinationPath . 'branch_banners/', $branchBanner3newName);
				} catch (\Exception $e) {
					throw new \RuntimeException('Error moving branch Banner3 file: ' . $e->getMessage());
				}
			}

			if ($branchThumbnail = $this->request->getFile('branchThumbnail')) {
				// Generate a new name for the thumbnail image to prevent name conflicts
				$branchThumbnailnewName = $branchThumbnail->getRandomName();

				try {
					$branchThumbnail->move($destinationPath . 'branch_thumbnails/', $branchThumbnailnewName);
				} catch (\Exception $e) {
					throw new \RuntimeException('Error moving branch Thumbnail file: ' . $e->getMessage());
				}
			}

			if ($branchLogo = $this->request->getFile('branchLogo')) {
				// Generate a new name for the thumbnail image to prevent name conflicts
				$branchLogonewName = $branchLogo->getRandomName();

				try {
					$branchLogo->move($destinationPath . 'branch_logos/', $branchLogonewName);
				} catch (\Exception $e) {
					throw new \RuntimeException('Error moving branch Logo file: ' . $e->getMessage());
				}
			}

			$branchType = $this->request->getPost('branchType');
			$branchSupportedVehicleType = $this->request->getPost('branchSupportedVehicleType');
			$countryId = $this->request->getPost('chooseCountry');
			$stateId = $this->request->getPost('chooseState');
			$cityId = $this->request->getPost('chooseCity');
			$address = $this->request->getPost('address', FILTER_UNSAFE_RAW);
			$contactNumber = $this->request->getPost('contactNumber');
			$whatsapp_no = $this->request->getPost('whatsapp_no');
			$email = $this->request->getPost('email');
			$shortDescription = $this->request->getPost('shortDescription', FILTER_UNSAFE_RAW);
			//$branch_map = $this->request->getPost('branch_map');
			$map_latitude = $this->request->getPost('map_latitude');
			$map_longitude = $this->request->getPost('map_longitude');
			$map_city = $this->request->getPost('map_city');
			$map_district = $this->request->getPost('map_district');
			$map_state = $this->request->getPost('map_state');

			$branch_services = implode(', ', $this->request->getPost('branchServices'));

			// Prepare the data to be inserted
			$data = [
				'dealer_id' => $dealerId,
				'name' => $branchName,
				'branch_banner1' => $branchBanner1newName,
				'branch_banner2' => $branchBanner2newName,
				'branch_banner3' => $branchBanner3newName,
				'branch_thumbnail' => $branchThumbnailnewName,
				'branch_logo' => $branchLogonewName,
				'branch_type' => $branchType,
				'branch_supported_vehicle_type' => $branchSupportedVehicleType,
				'branch_services' => $branch_services,
				'country_id' => $countryId,
				'state_id' => $stateId,
				'city_id' => $cityId,
				'address' => $address,
				'contact_number' => $contactNumber,
				'whatsapp_no' => $whatsapp_no,
				'email' => $email,
				'short_description' => $shortDescription,
				//'branch_map' => $branch_map,
				'map_latitude' => $map_latitude,
				'map_longitude' => $map_longitude,
				'map_city' => $map_city,
				'map_district' => $map_district,
				'map_state' => $map_state,
				'is_active' => 1,
				'created_at' => ''
			];

			// Insert the data into the database table
			$result = $this->branchModel->insert($data);

			// Get the last inserted ID
			$branchLastInsertedId = $this->branchModel->getInsertID();

			if (!$result) {
				throw new \RuntimeException('Failed to save branch.');
			}

			/* inserting deliverableImg + */

			if (isset($_FILES['deliverableImg']['name']) && !empty($_FILES['deliverableImg']['name'])) {
				$totalFiles = count($_FILES['deliverableImg']['name']); // Get the total number of files
				for ($i = 0; $i < $totalFiles; $i++) { // Loop through each file
					$fileName = $_FILES['deliverableImg']['name'][$i];
					$fileTmpName = $_FILES['deliverableImg']['tmp_name'][$i];
					$fileType = $_FILES['deliverableImg']['type'][$i];
					$fileSize = $_FILES['deliverableImg']['size'][$i];
					$fileError = $_FILES['deliverableImg']['error'][$i];

					// Create a unique name for the file to avoid overwriting
					$newName = uniqid() . '_' . $fileName;

					// Full path to the file on the server
					$filePath = $destinationPath . 'branch_deliverables/' . $newName;

					// Check if the file was uploaded without errors
					if ($fileError === 0) {
						try {
							move_uploaded_file($fileTmpName, $filePath);
						} catch (\Exception $e) {
							throw new \RuntimeException('Error moving deliverableImg file: ' . $e->getMessage());
						}

						$data = [
							'branch_id' => $branchLastInsertedId,
							'img_name' =>  $newName,
							'type'  => $fileType
						];
						$this->branchModel->insert_deliverablesImg($data);
					} else {
						throw new \RuntimeException("Error uploading file: $fileName (Error code: $fileError)");
					}
				}
			}

			$db->transComplete();

			if ($db->transStatus() === false) {
				throw new \RuntimeException('Transaction failed.');
			}

			return $this->response->setJSON(['status' => 'success', 'redirect' => 'dealer/list-branches', 'message' => 'Branch Details Added Successfully.']);
			/* inserting deliverableImg - */
		} catch (\Exception $e) {
			$db->transRollback();
			// Error handling and logging
			$logger = \Config\Services::logger();
			$logger->error('Error occurred while adding dealer branch: ' . $e->getMessage());
			return $this->response->setJSON(['status' => 'error', 'message' => 'Error occurred while adding dealer branch: ' . $e->getMessage()]);
		}
	}

	public function edit_branch_details($branchId) {

		/* // Fetch user session data and plan details */
		$data['userData'] = $this->userSesData;
		$data['planData'] = $this->planDetails;

		$data['countryList'] = $this->commonModel->get_all_country_data();
		$data['branchDetails'] = $this->branchModel->getStoreDetails($branchId);
		$data['stateList'] = $this->commonModel->get_country_states($data['branchDetails']['country_id']);
		$data['cityList'] = $this->commonModel->get_state_cities($data['branchDetails']['state_id']);
		$data['branchDeliverableImgs'] = $this->branchModel->get_branch_deliverable_imgs($branchId);

		return view('dealer/branches/edit_branch_details', $data);
	}

	public function edit_update_branch_details() {

		try {

			/* form validation start */
			$validation = \Config\Services::validation();

			$fieldsToValidate = [
				// 'branchType' => [
				// 	'rules' => 'required|not_equals[0]',
				// 	'errors' => [
				// 		'required' => 'Please choose Showroom-Type.',
				// 		'not_equals' => 'Please choose Showroom-Type.'
				// 	],
				// ],
				// 'branchSupportedVehicleType' => [
				// 	'rules' => 'required|not_equals[0]',
				// 	'errors' => [
				// 		'required' => 'Please Select Vehicle Type',
				// 		'not_equals' => 'Please Select Vehicle Type'
				// 	],
				// ],
				// 'branchThumbnail' => [
				// 	'rules' => 'uploaded[branchThumbnail]|max_size[branchThumbnail,1024]|is_image[branchThumbnail]|mime_in[branchThumbnail,image/jpeg,image/png,image/gif]',
				// 	'errors' => [
				// 		'uploaded' => 'Please choose Showroom Thumbnail image to upload.',
				// 		'max_size' => 'The image size should not exceed 1 MB.',
				// 		'is_image' => 'The uploaded file is not a valid image.',
				// 		'mime_in' => 'Only JPEG, PNG, and GIF images are allowed.',
				// 	],
				// ],
				// 'branchLogo' => [
				// 	'rules' => 'uploaded[branchLogo]|max_size[branchLogo,1024]|is_image[branchLogo]|mime_in[branchLogo,image/jpeg,image/png,image/gif]',
				// 	'errors' => [
				// 		'uploaded' => 'Please choose Showroom Logo image to upload.',
				// 		'max_size' => 'The image size should not exceed 1 MB.',
				// 		'is_image' => 'The uploaded file is not a valid image.',
				// 		'mime_in' => 'Only JPEG, PNG, and GIF images are allowed.',
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
						'required' => 'Please choose a State.',
						'not_equals' => 'Please choose a valid State.'
					],
				],
				'chooseCity' => [
					'rules' => 'required|not_equals[0]',
					'errors' => [
						'required' => 'Please choose a City.',
						'not_equals' => 'Please choose a valid City.'
					],
				],

				'address' => 'required',
				// 'contactNumber' => [
				// 	'rules' => 'required|regex_match[/^[0-9]{10}$/]',
				// 	'errors' => [
				// 		'required' => 'Please enter a contact number.',
				// 		'regex_match' => 'Please enter a valid 10-digit contact number.',
				// 	],
				// ],
				// 'email' => [
				// 	'rules' => 'required|valid_email',
				// 	'errors' => [
				// 		'required' => 'Please enter an email address.',
				// 		'valid_email' => 'Please enter a valid email address.',
				// 	],
				// ],
				'whatsapp_no' => [
					'rules' => 'required|regex_match[/^[0-9]{10}$/]',
					'errors' => [
						'required' => 'Please enter whatsapp number.',
						'regex_match' => 'Please enter a valid 10-digit number.',
					],
				],
				'shortDescription' => [
					'rules' => 'required',
					'errors' => [
						'required' => 'Please enter Short Description.'
					],
				],
				'map_latitude' => [
					'rules' => 'required',
					'errors' => [
						'required' => 'Please Add Location in Map'
					],
				]
			];

			foreach ($fieldsToValidate as $fieldName => $rules) {
				$validation->reset(); // Reset validation rules for each field
				$validation->setRules([$fieldName => $rules]);

				if (!$validation->withRequest($this->request)->run()) {
					$errors = $validation->getErrors();

					// Convert the errors to HTML format
					$errorString = '<div class="alert alert-danger shadow"><ul>';
					foreach ($errors as $field => $error) {
						$errorString .= '<li>' . $error . '</li>';
					}
					$errorString .= '</ul></div>';

					// Return the errors as a JSON response
					return $this->response->setJSON(['status' => 'error', 'message' => $errorString, 'field' => $fieldName]);
				}
			}
			/* form validation ends */

			$branchId = $this->request->getPost('branchId');
			$formData = [
				//'branch_supported_vehicle_type' => $this->request->getPost('branchSupportedVehicleType'),
				'country_id' => $this->request->getPost('chooseCountry'),
				'state_id' => $this->request->getPost('chooseState'),
				'city_id' => $this->request->getPost('chooseCity'),
				'address' => $this->request->getPost('address'),
				//'contact_number' => $this->request->getPost('contactNumber'),
				'whatsapp_no' => $this->request->getPost('whatsapp_no'),
				//'email' => $this->request->getPost('email'),
				'short_description' => $this->request->getPost('shortDescription'),
				//'branch_map' => $this->request->getPost('branch_map'),
				'map_latitude' => $this->request->getPost('map_latitude'),
				'map_longitude' => $this->request->getPost('map_longitude'),
				'map_city' => $this->request->getPost('map_city'),
				'map_district' => $this->request->getPost('map_district'),
				'map_state' => $this->request->getPost('map_state')
			];

			$uploadFolderPath = realpath($_SERVER['DOCUMENT_ROOT'] . '/../../production/');
			$destinationPath = $uploadFolderPath . '/public/uploads/';

			if (isset($_FILES['branchBanner1']['name']) && !empty($_FILES['branchBanner1']['name'])) {
				$file = $this->request->getFile('branchBanner1');
				$newName = $file->getRandomName(); // Generate a new name for the image to prevent name conflicts
				$file->move($destinationPath . 'branch_banners', $newName); // Move the uploaded file to the public/uploads directory
				$formData['branch_banner1'] = $newName;
			}

			if (isset($_FILES['branchBanner2']['name']) && !empty($_FILES['branchBanner2']['name'])) {
				$file = $this->request->getFile('branchBanner2');
				$newName = $file->getRandomName(); // Generate a new name for the image to prevent name conflicts
				$file->move($destinationPath  . 'branch_banners', $newName); // Move the uploaded file to the public/uploads directory
				$formData['branch_banner2'] = $newName; // Get the image URL to display in the preview
			}

			if (isset($_FILES['branchBanner3']['name']) && !empty($_FILES['branchBanner3']['name'])) {
				$file = $this->request->getFile('branchBanner3');
				$newName = $file->getRandomName(); // Generate a new name for the image to prevent name conflicts
				$file->move($destinationPath . 'branch_banners', $newName); // Move the uploaded file to the public/uploads directory
				$formData['branch_banner3'] = $newName; // Get the image URL to display in the preview
			}

			if (isset($_FILES['branchThumbnail']['name']) && !empty($_FILES['branchThumbnail']['name'])) {
				$file = $this->request->getFile('branchThumbnail');
				$newName = $file->getRandomName(); // Generate a new name for the image to prevent name conflicts
				$file->move($destinationPath . 'branch_thumbnails', $newName); // Move the uploaded file to the public/uploads directory
				$formData['branch_thumbnail'] = $newName; // Get the image URL to display in the preview
			}

			if (isset($_FILES['branchLogo']['name']) && !empty($_FILES['branchLogo']['name'])) {
				$file = $this->request->getFile('branchLogo');
				$newName = $file->getRandomName(); // Generate a new name for the image to prevent name conflicts
				$file->move($destinationPath . 'branch_logos', $newName); // Move the uploaded file to the public/uploads directory
				$formData['branch_logo'] = $newName; // Get the image URL to display in the preview
			}

			// Update the data into the database table
			$result = $this->branchModel->updateData($branchId, $formData);

			if (!$result) {
				// Return a JSON response
				return $this->response->setJSON(['errors' => true, 'message' => 'Error in Updating data.']);
			}

			/* inserting deliverableImg + */
			if (isset($_FILES['deliverableImg']['name']) && !empty($_FILES['deliverableImg']['name'])) {
				$totalFiles = count($_FILES['deliverableImg']['name']);
				for ($i = 0; $i < $totalFiles; $i++) {
					$fileName = $_FILES['deliverableImg']['name'][$i];
					$fileTmpName = $_FILES['deliverableImg']['tmp_name'][$i];
					$fileType = $_FILES['deliverableImg']['type'][$i];
					$fileSize = $_FILES['deliverableImg']['size'][$i];
					$fileError = $_FILES['deliverableImg']['error'][$i];

					// Create a unique name for the file to avoid overwriting
					$newName = uniqid() . '_' . $fileName;

					// Full path to the file on the server
					$filePath = $destinationPath . 'branch_deliverables/' . $newName;

					// Check if the file was uploaded without errors
					if ($fileError === 0) {
						try {
							move_uploaded_file($fileTmpName, $filePath);
							echo '<br/> deliverableImg - ' . $i . ' File moved successfully.';
						} catch (\Exception $e) {
							echo '<br/> deliverableImg Error moving file: ' . $e->getMessage();
						}

						$data = [
							'branch_id' => $branchId,
							'img_name' =>  $newName,
							'type'  => $fileType
						];
						$this->branchModel->insert_deliverablesImg($data);
					}
				}
			}
			return $this->response->setJSON(['status' => 'success', 'redirect' => 'dealer/edit-branch/' . $branchId, 'message' => 'Branch Details Added Successfully.']);
			/* inserting deliverableImg - */
		} catch (\Exception $e) {
			// Error handling and logging
			$logger = \Config\Services::logger();
			$logger->error('Error occurred while updating Details: ' . $e->getMessage());

			// Throw or handle the exception as needed
			throw $e;
		}
	}

	public function single_branch_info($branchId) {

		/* // Fetch user session data and plan details */
		$data['userData'] = $this->userSesData;
		$data['planData'] = $this->planDetails;

		$data['countryList'] = $this->commonModel->get_all_country_data();
		$data['branchDetails'] = $this->branchModel->getStoreDetails($branchId);

		$data['stateList'] = $this->commonModel->get_country_states($data['branchDetails']['country_id']);
		$data['cityList'] = $this->commonModel->get_state_cities($data['branchDetails']['state_id']);
		$data['branchService'] = explode(",", $data['branchDetails']['branch_services']);
		$data['branchDeliverableImgs'] = $this->branchModel->get_branch_deliverable_imgs($branchId);

		return view('dealer/branches/single_branch_info', $data);
	}

	public function getAllBranches($countryId, $stateId, $cityId, $branchType) {

		$limit = $this->request->getVar('limit');
		$offset = $this->request->getVar('start');
		$dealerId = session()->get('userId');

		$branches = $this->branchModel->where('dealer_id', $dealerId)->getAllBranchByDealerId($dealerId, $countryId, $stateId, $cityId, $branchType, $limit, $offset);

		$dealerBranchHtml = '';

		foreach ($branches as $branch) {
			$dealerBranchHtml .= '
                <div class="col-md-6 col-lg-4 branch-card-' . $branch['id'] . '">
                    <div class="card card-box mb-3 position-relative">
                        <img class="card-img-top showroom-image" src="' . WHEELPACT_VEHICLE_UPLOAD_IMG_PATH . 'branch_thumbnails/' . $branch['branch_thumbnail'] . '" alt="' . $branch['name'] . '" />
                        <div class="card-body">
                            <h5 class="card-title weight-500">' . $branch['name'] . '</h5>
							<div class="showroom-location mb-2">
                                    <i class="icon-copy dw dw-pin-2"></i>
                                    <h6>' . $branch['city'] . ', ' . $branch['state'] . ' </h6>
                                </div>
                            <p class="card-text"></p>
                            <div class="d-flex vehicle-overview">
                                <div class="overview-badge">
                                    <h6>Branch</h6>
                                    <h5>' . $branch['branch_type_label'] . '</h5>
                                </div>
							</div>
							<div class="d-flex align-items-center">
								<div class="store-rating-icon">
									<i class="icofont-star"></i>
								</div>
							<div class="store-rating-count">' . round($branch['branch_rating'], 1) . '</div>
							<div class="store-reviews">
								<a class="view-reviews-link" href="#" data-branch-id="' . $branch['id'] . '">(' . $branch['branch_review_count'] . ' Reviews)</a>
							</div>
							</div>';
			if ($branch['is_promoted'] == 1) {
				$dealerBranchHtml .= '<a href="javascript:void(0);" class="btn btn-success mt-3 btn-block"> Promotion ends on: ' . date('Y-m-d', strtotime($branch['promotion_end_date'])) . '</a>';
			} else {
				$dealerBranchHtml .= '<a href="' . base_url() . 'dealer/promote-showroom/' . $branch['id'] . '" class="btn btn-primary mt-3 btn-block">Promote</a>';
			}

			$dealerBranchHtml .= '<div class="option-btn">
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="' . base_url() . 'dealer/single-branch-info/' . $branch['id'] . '"><i class="dw dw-eye"></i> View</a>
                                        <a class="dropdown-item" href="' . base_url() . 'dealer/edit-branch/' . $branch['id'] . '"><i class="dw dw-edit2"></i> Edit</a>';
			if ($branch['is_promoted'] != 1) {
				$dealerBranchHtml .= '<a class="dropdown-item sa-params delete-branch" data-branch-id="' . $branch['id'] . '" href="#"><i class="dw dw-delete-3"></i> Delete</a>';
			}
			$dealerBranchHtml .= '</div></div></div></div></div></div>';
		}

		echo $dealerBranchHtml;
	}

	public function delete($branchId) {

		$branch = $this->branchModel->find($branchId);

		if ($branch) {
			// Update the deleted_status to 1 (deleted) in the database
			$this->branchModel->deleteBranch($branchId);

			return $this->response->setJSON(['status' => 'success', 'message' => 'Branch Removed Sucessfully']);
		} else {
			// Return a JSON response indicating failure
			return $this->response->setJSON(['status' => 'error', 'message' => 'Branch not found/Error in Removing']);
		}
	}

	public function load_states() {
		$country_id = $this->request->getPost('country_id');

		$states = $this->commonModel->get_country_states($country_id);

		/* // Return HTML options for models dropdown */
		$html = '';
		$html .= '<option value="0">States</option>';
		if (!empty($states)) {
			foreach ($states as $state) {
				$html .= "<option value=\"{$state['id']}\">{$state['name']}</option>";
			}
		} else {
			$html .= '<option>No Data Found</option>';
		}
		echo $html;
	}

	public function load_cities() {
		$state_id = $this->request->getPost('state_id');


		$cityList = $this->commonModel->get_state_cities($state_id);

		// Return HTML options for models dropdown
		$html = '';
		$html .= '<option value="0">City</option>';
		if (!empty($cityList)) {
			foreach ($cityList as $city) {
				$html .= "<option value=\"{$city['id']}\">{$city['name']}</option>";
			}
		} else {
			$html .= '<option>No Data Found</option>';
		}
		echo $html;
	}

	public function load_branch_reviews($branchId) {
		$reviews = $this->branchModel->getBranchReviews($branchId);
		return $this->response->setJSON($reviews);
	}
}
