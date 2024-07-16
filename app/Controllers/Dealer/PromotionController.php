<?php

namespace App\Controllers\dealer;

use App\Controllers\BaseController;
use App\Controllers\ApiController;

use App\Models\PromotionPlanModel;
use App\Models\VehicleModel;
use App\Models\UserModel;
use App\Models\CommonModel;
use App\Models\RazorpayModel;


class PromotionController extends BaseController {

    protected $apiController;

    protected $commonModel;
    protected $promotionPlanModel;
    protected $userModel;
    protected $vehicleModel;
    protected $razorpayModel;

    protected $userSesData;
    protected $planDetails;


    public function __construct() {
        $this->apiController = new ApiController();

        $this->promotionPlanModel = new PromotionPlanModel();
        $this->commonModel = new CommonModel();
        $this->userModel = new UserModel();
        $this->vehicleModel = new VehicleModel();
        $this->razorpayModel = new RazorpayModel();


        /* // Retrieve session */
        $this->userSesData = session()->get();

        /* // Retrieve plan details of logged user */
        $planDetails = $this->userModel->getPlanDetailsBYId(session()->get('userId'));
        $this->planDetails = $planDetails[0];
    }

    public function index($vehicleId) {

        /*// Fetch all promotion plans */
        $data['promotionPlans'] = $this->promotionPlanModel->findAll();

        /* get subcription plan details */
        $data['planData'] = $this->planDetails;

        $data['vehicleDetails'] =  $this->vehicleModel->getVehicleDetails($vehicleId);

        $data['vehicleImagesDetails'] = $this->vehicleModel->getVehicleImagesDetails($vehicleId);
        echo view('dealer/vehicles/promote-vehicle', $data);
    }
}
