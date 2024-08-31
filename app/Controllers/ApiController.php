<?php

namespace App\Controllers;

use App\Services\OTPService;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Models\UserModel;
use App\Models\VehicleModel;
use App\Models\CommonModel;
use App\Models\CompanyModelModel;
use App\Models\BranchModel;
use App\Models\CompanyModel;
use App\Models\PlanModel;

use CodeIgniter\API\ResponseTrait;

use App\Libraries\JwtLibrary;
use Razorpay\Api;

class ApiController extends BaseController {
    use ResponseTrait;

    protected $UserModel;
    protected $VehicleModel;
    protected $CommonModel;
    protected $BranchModel;

    private $razorpayKey = RZP_KEY;
    private $razorpaySecret = RZP_SECRET;

    public function __construct() {
        $this->UserModel = new UserModel();
        $this->VehicleModel = new VehicleModel();
        $this->CommonModel = new CommonModel();
        $this->BranchModel = new BranchModel();
    }

    /* razorpay calls start */
    /* 
    - using the hosted method for payment 
    - https://razorpay.com/docs/payments/payment-gateway/web-integration/hosted/build-integration/
    */
    public function callRazorpayApi($endpoint, $data) {

        $url = 'https://api.razorpay.com/v1/' . $endpoint;
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, RZP_KEY . ':' . RZP_SECRET);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return ['error' => true, 'message' => $error_msg];
        }

        curl_close($ch);
        return json_decode($response, true);
    }

    public function create_rzp_order_api() {

        $orderData =  $this->request->getPost('orderData');

        $apiResp = $this->callRazorpayApi('orders', json_encode($orderData, JSON_NUMERIC_CHECK));

        $response = array(
            'responseCode'   => 200,
            'responseMessage' => 'Create order Api Response',
            'responseData' => $apiResp
        );

        return $this->response->setJSON($response);
    }

    /* get payments details */
    public function getTransactionDetails($payId) {

        // Base64 encode the API key and secret
        $auth = base64_encode(RZP_KEY . ':' . RZP_SECRET);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.razorpay.com/v1/payments/' . $payId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic ' . $auth
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            //echo 'Error: ' . $error_msg;
            return json_decode($error_msg, true);
        } else {
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($http_code >= 200 && $http_code < 300) {
                return json_decode($response, true);
            } else {
                //echo "Request failed with HTTP code: " . $http_code;
                $response_data = json_decode($response, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    //echo "\nError Details: " . print_r($response_data, true);
                    return $response_data;
                }
            }
        }
    }

    /* razorpay calls end */
}
