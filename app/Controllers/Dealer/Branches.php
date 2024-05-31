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

	public function __construct() {
		$this->commonModel = new CommonModel();
		$this->branchModel  = new BranchModel();
		$this->userModel = new UserModel();
		$this->vehicleModel = new VehicleModel();
	}

	public function index() {
		$data = array();
		$data['countryList'] = $this->commonModel->get_all_country_data();

		echo view('dealer/branches/list-branches', $data);
	}

	public function add_branch() {
		$data['countryList'] = $this->commonModel->get_all_country_data();
		return view('dealer/branches/add_branch', $data);
	}

	public function save_branch() {
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
						'regex_match' => 'Please enter a valid 10-digit contact number.',
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

			/* validate contact number, email exist start */

			$checkContactNo = $this->request->getPost('contactNumber');
			$checkEmail = $this->request->getPost('email');

			// Check if the contact number already exists
			$existingContact = $this->branchModel->where('contact_number', $checkContactNo)->first();

			if ($existingContact) {
				return $this->response->setJSON(['status' => 'error', 'message' => 'Contact number already exists.', 'field' => 'contactNumber']);
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

			if ($branchBanner1 = $this->request->getFile('branchBanner1')) {
				// Generate a new name for the thumbnail image to prevent name conflicts
				$branchBanner1newName = $branchBanner1->getRandomName();

				try {
					$branchBanner1->move($destinationPath . 'branch_banners/', $branchBanner1newName);
					echo '<br/> branch Banner1 File moved successfully.';
				} catch (\Exception $e) {
					echo '<br/> branch Banner1 Error moving file: ' . $e->getMessage();
				}
			}
			if ($branchBanner2 = $this->request->getFile('branchBanner2')) {
				// Generate a new name for the thumbnail image to prevent name conflicts
				$branchBanner2newName = $branchBanner2->getRandomName();

				try {
					$branchBanner2->move($destinationPath . 'branch_banners/', $branchBanner2newName);
					echo '<br/> branch Banner2 File moved successfully.';
				} catch (\Exception $e) {
					echo '<br/> branch Banner2 Error moving file: ' . $e->getMessage();
				}
			}
			if ($branchBanner3 = $this->request->getFile('branchBanner3')) {
				// Generate a new name for the thumbnail image to prevent name conflicts
				$branchBanner3newName = $branchBanner3->getRandomName();

				try {
					$branchBanner3->move($destinationPath . 'branch_banners/', $branchBanner3newName);
					echo '<br/> branch Banner3 File moved successfully.';
				} catch (\Exception $e) {
					echo '<br/> branch Banner3 Error moving file: ' . $e->getMessage();
				}
			}

			if ($branchThumbnail = $this->request->getFile('branchThumbnail')) {
				// Generate a new name for the thumbnail image to prevent name conflicts
				$branchThumbnailnewName = $branchThumbnail->getRandomName();

				try {
					$branchThumbnail->move($destinationPath . 'branch_thumbnails/', $branchThumbnailnewName);
					echo '<br/> branch Thumbnail File moved successfully.';
				} catch (\Exception $e) {
					echo '<br/> branch Thumbnail Error moving file: ' . $e->getMessage();
				}
			}

			if ($branchLogo = $this->request->getFile('branchLogo')) {
				// Generate a new name for the thumbnail image to prevent name conflicts
				$branchLogonewName = $branchLogo->getRandomName();

				try {
					$branchLogo->move($destinationPath . 'branch_logos/', $branchLogonewName);
					echo '<br/> branch logo File moved successfully.';
				} catch (\Exception $e) {
					echo '<br/> branch logo Error moving file: ' . $e->getMessage();
				}
			}

			$branchType = $this->request->getPost('branchType');
			$branchSupportedVehicleType = $this->request->getPost('branchSupportedVehicleType');
			$countryId = $this->request->getPost('chooseCountry');
			$stateId = $this->request->getPost('chooseState');
			$cityId = $this->request->getPost('chooseCity');
			$address = $this->request->getPost('address', FILTER_UNSAFE_RAW);
			$contactNumber = $this->request->getPost('contactNumber');
			$email = $this->request->getPost('email');
			$shortDescription = $this->request->getPost('shortDescription', FILTER_UNSAFE_RAW);
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
				'email' => $email,
				'short_description' => $shortDescription,
				'is_active' => 1,
				'created_at' => ''
			];

			// Insert the data into the database table
			$result = $this->branchModel->insert($data);

			// Get the last inserted ID
			$branchLastInsertedId = $this->branchModel->getInsertID();

			if (!$result) {
				//return redirect()->back()->with('error', 'Failed to save branch');
				return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to save branch.']);
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
							echo '<br/> deliverableImg - ' . $i . ' File moved successfully.';
						} catch (\Exception $e) {
							echo '<br/> deliverableImg Error moving file: ' . $e->getMessage();
						}

						$data = [
							'branch_id' => $branchLastInsertedId,
							'img_name' =>  $newName,
							'type'  => $fileType
						];
						$this->branchModel->insert_deliverablesImg($data);
					} else {
						echo "Error uploading file: $fileName (Error code: $fileError)<br>";
					}
				}
			}
			return $this->response->setJSON(['status' => 'success', 'redirect' => 'dealer/list-branches', 'message' => 'Branch Details Added Successfully.']);
			/* inserting deliverableImg - */
		} catch (\Exception $e) {
			// Error handling and logging
			$logger = \Config\Services::logger();
			$logger->error('Error occurred while adding dealer branch: ' . $e->getMessage());
			return $this->response->setJSON(['status' => 'error', 'message' => 'Error occurred while adding dealer branch' . $e->getMessage()]);
			throw $e;
		}
	}

	public function edit_branch_details($branchId) {

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
				'shortDescription' => [
					'rules' => 'required',
					'errors' => [
						'required' => 'Please enter Short Description.'
					],
				],
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
				'branch_supported_vehicle_type' => $this->request->getPost('branchSupportedVehicleType'),
				'country_id' => $this->request->getPost('chooseCountry'),
				'state_id' => $this->request->getPost('chooseState'),
				'city_id' => $this->request->getPost('chooseCity'),
				'address' => $this->request->getPost('address'),
				'contact_number' => $this->request->getPost('contactNumber'),
				'email' => $this->request->getPost('email'),
				'short_description' => $this->request->getPost('shortDescription')
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
                            </div>
                            <a href="promote.html" class="btn btn-primary mt-3 btn-block">Promote</a>
                            <div class="option-btn">
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item" href="' . base_url() . 'dealer/single-branch-info/' . $branch['id'] . '"><i class="dw dw-eye"></i> View</a>
                                        <a class="dropdown-item" href="' . base_url() . 'dealer/edit-branch/' . $branch['id'] . '"><i class="dw dw-edit2"></i> Edit</a>
                                        <a class="dropdown-item sa-params delete-branch" data-branch-id="' . $branch['id'] . '" href="#"><i class="dw dw-delete-3"></i> Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
		}

		echo $dealerBranchHtml;
	}

	public function edit_vehicle($vehicleId) {

		$dealerId = session()->get('userId');

		$data['vehicleDetails'] =  $this->vehicleModel->getVehicleDetails($vehicleId);
		$data['vehicleImagesDetails'] = $this->vehicleModel->getVehicleImagesDetails($vehicleId);

		$data['showroomList'] = $this->vehicleModel->getShowroomList($dealerId);
		$data['cmpList'] = $this->vehicleModel->getBrandsByVehicleType($data['vehicleDetails']['vehicle_type']);
		$data['cmpModelList'] = $this->vehicleModel->getModelsByBrand($data['vehicleDetails']['cmp_id']);
		$data['variantList'] = $this->vehicleModel->getVariantsByModel($data['vehicleDetails']['model_id']);

		$data['fuelTypeList'] = $this->commonModel->get_fuel_types();
		$data['fuelVariantList'] = $this->commonModel->get_fuel_variants();
		$data['transmissionList'] = $this->commonModel->get_vehicle_transmissions();
		$data['colorList'] = $this->commonModel->get_vehicle_colors();
		$data['stateList'] = $this->commonModel->get_country_states(101);
		if (isset($data['vehicleDetails']['cmp_id']) && !empty($data['vehicleDetails']['cmp_id'])) {
			$data['vehicleRegRtoList'] = $this->commonModel->get_registered_state_rto($data['vehicleDetails']['registered_state_id']);
		}
		$data['bodyTypeList'] = $this->commonModel->get_vehicle_body_types();

		echo view('dealer/vehicles/edit-vehicle', $data);
	}

	public function update_vehicle() {
		$db = db_connect();
		$db->transBegin();
		try {
			// Load the form validation library
			$validation = \Config\Services::validation();

			// Set validation rules for each form field
			$validation->setRules([
				'branch_id'         => 'required',
				'vehicle_type'      => 'required',
				'cmp_id'            => 'required',
				'model_id'          => 'required',
				'fuel_type'         => 'required',
				'body_type'         => 'required',
				'variant_id'        => 'required',
				'mileage'           => 'required',
				'kms_driven'        => 'required',
				'owner'             => 'required',
				'transmission_id'   => 'required',
				'color_id'          => 'required',

				'manufacture_year'      => 'required',
				'registration_year'     => 'required',
				'registered_state_id'   => 'required',
				'rto'                   => 'required',

				'insurance_type'      => 'required',
				'insurance_validity'     => 'required',

				'accidental_status'  => 'required',
				'flooded_status'     => 'required',
				'last_service_kms'   => 'required',
				'last_service_date'  => 'required',

				'regular_price'  => 'required',
				'selling_price'  => 'required',
				'pricing_type'   => 'required',
			]);

			// Run the validation
			if (!$validation->withRequest($this->request)->run()) {
				// Validation failed, return errors in JSON format
				$errors = $validation->getErrors();
				return $this->response->setJSON(['success' => false, 'errors' => $errors]);
			}

			// Get the form input values
			$vehicleId      = $this->request->getPost('vehicleId');
			$branch_id      = $this->request->getPost('branch_id');
			$vehicle_type   = $this->request->getPost('vehicle_type');
			$cmp_id         = $this->request->getPost('cmp_id');
			$model_id       = $this->request->getPost('model_id');
			$fuel_type      = $this->request->getPost('fuel_type');
			$body_type      = $this->request->getPost('body_type');
			$variant_id     = $this->request->getPost('variant_id');
			$mileage        = $this->request->getPost('mileage', FILTER_UNSAFE_RAW);
			$kms_driven     = $this->request->getPost('kms_driven', FILTER_UNSAFE_RAW);
			$owner          = $this->request->getPost('owner');
			$transmission_id = $this->request->getPost('transmission_id');
			$color_id       = $this->request->getPost('color_id');
			$featured_status    = $this->request->getPost('featured_status');
			$search_keywords    = $this->request->getPost('search_keywords');
			$onsale_status      = $this->request->getPost('onsale_status');
			$onsale_percentage  = $this->request->getPost('onsale_percentage');

			// Get the form input values
			$manufacture_year       = $this->request->getPost('manufacture_year');
			$registration_year      = $this->request->getPost('registration_year');
			$registered_state_id    = $this->request->getPost('registered_state_id');
			$rto                    = $this->request->getPost('rto');

			// Get the form input values
			$insurance_type         = $this->request->getPost('insurance_type');
			$insurance_validity     = $this->request->getPost('insurance_validity');

			// Get the form input values
			$accidental_status   = $this->request->getPost('accidental_status');
			$flooded_status      = $this->request->getPost('flooded_status');
			$last_service_kms    = $this->request->getPost('last_service_kms');
			$last_service_date   = $this->request->getPost('last_service_date');

			// Get the form input values
			$car_no_of_airbags              = $this->request->getPost('car_no_of_airbags');
			$car_central_locking            = $this->request->getPost('car_central_locking');
			$car_seat_upholstery            = $this->request->getPost('car_seat_upholstery');
			$car_sunroof                    = $this->request->getPost('car_sunroof');
			$car_integrated_music_system    = $this->request->getPost('car_integrated_music_system');
			$car_rear_ac                    = $this->request->getPost('car_rear_ac');
			$car_outside_rear_view_mirrors  = $this->request->getPost('car_outside_rear_view_mirrors');
			$car_power_windows              = $this->request->getPost('car_power_windows');
			$car_engine_start_stop          = $this->request->getPost('car_engine_start_stop');
			$car_headlamps                  = $this->request->getPost('car_headlamps');
			$car_power_steering             = $this->request->getPost('car_power_steering');

			// Get the form input values
			$bike_headlight_type            = $this->request->getPost('bike_headlight_type');
			$bike_odometer                  = $this->request->getPost('bike_odometer');
			$bike_drl                       = $this->request->getPost('bike_drl');
			$bike_mobile_connectivity       = $this->request->getPost('bike_mobile_connectivity');
			$bike_gps_navigation            = $this->request->getPost('bike_gps_navigation');
			$bike_usb_charging_port         = $this->request->getPost('bike_usb_charging_port');
			$bike_low_battery_indicator     = $this->request->getPost('bike_low_battery_indicator');
			$bike_under_seat_storage        = $this->request->getPost('bike_under_seat_storage');
			$bike_speedometer               = $this->request->getPost('bike_speedometer');
			$bike_stand_alarm               = $this->request->getPost('bike_stand_alarm');
			$bike_low_fuel_indicator        = $this->request->getPost('bike_low_fuel_indicator');
			$bike_low_oil_indicator         = $this->request->getPost('bike_low_oil_indicator');
			$bike_start_type                = $this->request->getPost('bike_start_type');
			$bike_kill_switch               = $this->request->getPost('bike_kill_switch');
			$bike_break_light               = $this->request->getPost('bike_break_light');
			$bike_turn_signal_indicator     = $this->request->getPost('bike_turn_signal_indicator');

			// Get the form input values
			$regular_price = $this->request->getPost('regular_price');
			$selling_price = $this->request->getPost('selling_price');
			$pricing_type = $this->request->getPost('pricing_type');
			$emi_option = $this->request->getPost('emi_option');
			$avg_interest_rate = $this->request->getPost('avg_interest_rate');
			$tenure_months = $this->request->getPost('tenure_months');
			$updated_by = session()->get('userId');
			$updated_datetime = date("Y-m-d H:i:s");

			// Prepare the data to be updated
			$formData = [
				'branch_id'         => $branch_id,
				'vehicle_type'      => $vehicle_type,
				'cmp_id'            => $cmp_id,
				'model_id'          => $model_id,
				'fuel_type'         => $fuel_type,
				'body_type'         => $body_type,
				'variant_id'        => $variant_id,
				'mileage'           => $mileage,
				'kms_driven'        => $kms_driven,
				'owner'             => $owner,
				'transmission_id'   => $transmission_id,
				'color_id'          => $color_id,
				'featured_status'   => $featured_status,
				'search_keywords'   => $search_keywords,
				'onsale_status'     => $onsale_status,
				'onsale_percentage' => $onsale_percentage,

				'manufacture_year'      => $manufacture_year,
				'registration_year'     => $registration_year,
				'registered_state_id'   => $registered_state_id,
				'rto'                   => $rto,

				'insurance_type'      => $insurance_type,
				'insurance_validity'  => date("Y-m-d", strtotime($insurance_validity)),

				'accidental_status' => $accidental_status,
				'flooded_status'    => $flooded_status,
				'last_service_kms'  => $last_service_kms,
				'last_service_date' => date("Y-m-d", strtotime($last_service_date)),

				'car_no_of_airbags'             => isset($car_no_of_airbags) ? $car_no_of_airbags : '',
				'car_central_locking'           => isset($car_central_locking) ? $car_central_locking : '',
				'car_seat_upholstery'           => isset($car_seat_upholstery) ? $car_seat_upholstery : '',
				'car_sunroof'                   => isset($car_sunroof) ? $car_sunroof : '',
				'car_integrated_music_system'   => isset($car_integrated_music_system) ? $car_integrated_music_system : '',
				'car_rear_ac'                   => isset($car_rear_ac) ? $car_rear_ac : '',
				'car_outside_rear_view_mirrors' => isset($car_outside_rear_view_mirrors) ? $car_outside_rear_view_mirrors : '',
				'car_power_windows'             => isset($car_power_windows) ? $car_power_windows : '',
				'car_engine_start_stop'         => isset($car_engine_start_stop) ? $car_engine_start_stop : '',
				'car_headlamps'                 => isset($car_headlamps) ? $car_headlamps : '',
				'car_power_steering'            => isset($car_power_steering) ? $car_power_steering : '',

				'bike_headlight_type'           => isset($bike_headlight_type) ? $bike_headlight_type : '',
				'bike_odometer'                 => isset($bike_odometer) ? $bike_odometer : '',
				'bike_drl'                      => isset($bike_drl) ? $bike_drl : '',
				'bike_mobile_connectivity'      => isset($bike_mobile_connectivity) ? $bike_mobile_connectivity : '',
				'bike_gps_navigation'           => isset($bike_gps_navigation) ? $bike_gps_navigation : '',
				'bike_usb_charging_port'        => isset($bike_usb_charging_port) ? $bike_usb_charging_port : '',
				'bike_low_battery_indicator'    => isset($bike_low_battery_indicator) ? $bike_low_battery_indicator : '',
				'bike_under_seat_storage'       => isset($bike_under_seat_storage) ? $bike_under_seat_storage : '',
				'bike_speedometer'              => isset($bike_speedometer) ? $bike_speedometer : '',
				'bike_stand_alarm'              => isset($bike_stand_alarm) ? $bike_stand_alarm : '',
				'bike_low_fuel_indicator'       => isset($bike_low_fuel_indicator) ? $bike_low_fuel_indicator : '',
				'bike_low_oil_indicator'        => isset($bike_low_oil_indicator) ? $bike_low_oil_indicator : '',
				'bike_start_type'               => isset($bike_start_type) ? $bike_start_type : '',
				'bike_kill_switch'              => isset($bike_kill_switch) ? $bike_kill_switch : '',
				'bike_break_light'              => isset($bike_break_light) ? $bike_break_light : '',
				'bike_turn_signal_indicator'    => isset($bike_turn_signal_indicator) ? $bike_turn_signal_indicator : '',

				'regular_price' => $regular_price,
				'selling_price' => $selling_price,
				'pricing_type'  => $pricing_type,
				'emi_option' => $emi_option,
				'avg_interest_rate' => $avg_interest_rate,
				'tenure_months' => $tenure_months,
				'updated_by'    => $updated_by,
				'updated_datetime' => $updated_datetime
			];

			// Update the data into the database table
			$result = $this->vehicleModel->updateData($vehicleId, $formData);

			if (!$result) {
				// Return a JSON response
				return $this->response->setJSON(['errors' => true, 'message' => 'Error occurred while inserting data.']);
			}

			// Commit the transaction if all updations were successful
			$db->transCommit();

			// Return a success JSON response
			return $this->response->setJSON(['success' => true, 'message' => 'Vehicle updated successfully.']);
		} catch (\Exception $e) {
			// An error occurred, rollback the transaction
			$db->transRollback();

			// Error handling and logging
			$logger = \Config\Services::logger();
			$logger->error('Error occurred while updating vehicle information: ' . $e->getMessage());

			// Throw or handle the exception as needed
			throw $e;
		}
	}

	public function single_vehicle_info($vehicleId) {

		$dealerId = session()->get('userId');

		$data['vehicleDetails'] =  $this->vehicleModel->getVehicleDetails($vehicleId);
		$data['vehicleImagesDetails'] = $this->vehicleModel->getVehicleImagesDetails($vehicleId);

		$data['showroomList'] = $this->vehicleModel->getShowroomList($dealerId);
		$data['cmpList'] = $this->vehicleModel->getBrandsByVehicleType($data['vehicleDetails']['vehicle_type']);
		$data['cmpModelList'] = $this->vehicleModel->getModelsByBrand($data['vehicleDetails']['cmp_id']);
		$data['variantList'] = $this->vehicleModel->getVariantsByModel($data['vehicleDetails']['model_id']);

		$data['fuelTypeList'] = $this->commonModel->get_fuel_types();
		$data['fuelVariantList'] = $this->commonModel->get_fuel_variants();
		$data['transmissionList'] = $this->commonModel->get_vehicle_transmissions();
		$data['colorList'] = $this->commonModel->get_vehicle_colors();
		$data['stateList'] = $this->commonModel->get_country_states(101);
		if (isset($data['vehicleDetails']['cmp_id']) && !empty($data['vehicleDetails']['cmp_id'])) {
			$data['vehicleRegRtoList'] = $this->commonModel->get_registered_state_rto($data['vehicleDetails']['registered_state_id']);
		}
		$data['bodyTypeList'] = $this->commonModel->get_vehicle_body_types();


		echo view('dealer/vehicles/single-vehicle-info', $data);
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
