<?php

namespace App\Controllers\dealer;

use App\Controllers\BaseController;
use App\Controllers\ApiController;

use App\Models\PromotionPlanModel;
use App\Models\VehicleModel;
use App\Models\UserModel;
use App\Models\CommonModel;
use App\Models\RazorpayModel;
use App\Models\BranchModel;

class PromotionController extends BaseController {

    protected $apiController;

    protected $commonModel;
    protected $promotionPlanModel;
    protected $userModel;
    protected $vehicleModel;
    protected $razorpayModel;
    protected $branchModel;

    protected $userSesData;
    protected $planDetails;

    public function __construct() {
        $this->apiController = new ApiController();

        $this->promotionPlanModel = new PromotionPlanModel();
        $this->commonModel = new CommonModel();
        $this->userModel = new UserModel();
        $this->vehicleModel = new VehicleModel();
        $this->razorpayModel = new RazorpayModel();
        $this->branchModel = new BranchModel();

        /* // Retrieve session */
        $this->userSesData = session()->get();

        /* // Retrieve plan details of logged user */
        $planDetails = $this->userModel->getPlanDetailsBYId(session()->get('userId'));
        $this->planDetails = $planDetails[0];
    }

    public function promoteVehicle($vehicle_Id) {
        // Decrypt the vehicle ID
        $vehicleId = decryptData($vehicle_Id);

        // Fetch all promotion plans
        $data['promotionPlans'] = $this->promotionPlanModel->findAll();

        // Fetch user session data and plan details
        $data['userData'] = $this->userSesData;
        $data['planData'] = $this->planDetails;

        // Fetch vehicle and related images details
        $data['vehicleDetails'] = $this->vehicleModel->getVehicleDetails($vehicleId);
        $data['vehicleImagesDetails'] = $this->vehicleModel->getVehicleImagesDetails($vehicleId);

        // Check if the user is eligible for free promotion
        $PromotedInsight = $this->vehicleModel->getPromotedInsight($data['userData']['userId']);

        // Count promoted vehicles and showrooms
        $data['vehiclePromoteCount'] = $PromotedInsight['promotionUnderVehicle'];
        $data['showroomPromoteCount'] = $PromotedInsight['promotionUnderShowroom'];

        // Retrieve the free promotion limits from the user's plan
        $data['freeInventoryPromotions'] = $data['planData']['free_inventory_promotions'];
        $data['freeShowroomPromotions'] = $data['planData']['free_showroom_promotions'];

        // Calculate remaining free promotions for vehicles and showrooms
        $data['remainingVehiclePromotions'] = max(0, $data['freeInventoryPromotions'] - $data['vehiclePromoteCount']);
        $data['remainingShowroomPromotions'] = max(0, $data['freeShowroomPromotions'] - $data['showroomPromoteCount']);

        // Pass the data to the view
        echo view('dealer/vehicles/promote-vehicle', $data);
    }


    public function promoteShowroom($showroom_Id) {
        $showroomId = decryptData($showroom_Id);

        /*// Fetch all promotion plans */
        $data['promotionPlans'] = $this->promotionPlanModel->findAll();

        /* // Fetch user session data and plan details */
        $data['userData'] = $this->userSesData;
        $data['planData'] = $this->planDetails;

        $data['showroomDetails'] =   $this->branchModel->getStoreDetails($showroomId);

        echo view('dealer/vehicles/promote-showroom', $data);
    }

    public function promotionDetails($vehicleOrBranchID) {
        $vehicleOrBranchID = decryptData($vehicleOrBranchID);

        /* // Fetch user session data and plan details */
        $data['userData'] = $this->userSesData;
        $data['planData'] = $this->planDetails;

        $promotionDetails = $this->vehicleModel->getPromotiondetails($vehicleOrBranchID);
        $data['promotionDetails'] = $promotionDetails[0];
        if ($data['promotionDetails']['promotionUnder'] == 'vehicle') {
            $data['vehicleDetails'] =  $this->vehicleModel->getVehicleDetails($vehicleOrBranchID);
            echo view('dealer/vehicles/promote-vehicle-details', $data);
        } else {
            $data['showroomDetails'] =  $this->branchModel->getStoreDetails($vehicleOrBranchID);
            //echo '<pre>'; print_r($data); exit;
            echo view('dealer/branches/promote-showroom-details', $data);
        }
    }

    public function deletePromotion() {
        $promotionId = decryptData($this->request->getPost('promotion_id'));

        if ($promotionId) {
            $updated = $this->promotionPlanModel->deletePromotion($promotionId);

            if ($updated) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Promotion deleted successfully.']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Failed to delete promotion.']);
            }
        }

        return $this->response->setJSON(['status' => 'error']);
    }
}
