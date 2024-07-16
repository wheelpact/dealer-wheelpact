<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\HTTPException;
use App\Services\OTPService;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\Response;

use App\Models\UserModel;
use App\Models\RazorpayModel;
use App\Models\PromotionPlanModel;

use App\Controllers\ApiController;

class RazorpayController extends BaseController {

    protected $ApiController;

    protected $VehicleModel;
    protected $BranchModel;
    protected $CommonModel;
    protected $PromotionPlanModel;

    protected $RazorpayModel;
    protected $UserModel;

    protected $userSesData;
    protected $planDetails;

    public function __construct() {
        $this->ApiController = new ApiController();

        $this->UserModel = new UserModel();
        $this->RazorpayModel = new RazorpayModel();
        $this->PromotionPlanModel = new PromotionPlanModel();

        /* // Retrieve session */
        $this->userSesData = session()->get();

        /* // Retrieve plan details of logged user */
        $planDetails = $this->UserModel->getPlanDetailsBYId(session()->get('userId'));
        $this->planDetails = $planDetails[0];
    }

    /* razor pay start */
    /* call to api's */

    public function create_rzp_order() {
        try {
            $postData = $this->request->getPost();
            $amount = $postData['promotion-amount-radio'];
            /* covert in Razorpay amount */
            $amountRzp = (int) $amount * 100;
            $promotionPlanId = $postData['promotionPlanId'];
            $promotionUnder = $postData['promotionType'];
            $promotedVehicleId = $postData['vehicleId'];

            $promotionPlanDetails = $this->PromotionPlanModel->getPromotionPlanById($promotionPlanId);

            /* // Fetch user session data and plan details */
            $delearData = $this->UserModel->getDealerDetailsById($this->userSesData['userId']);

            if (is_null($delearData) || empty($delearData) || !isset($delearData['email'])) {
                /* Invalid Request: Dealer data is missing or incomplete.*/
                throw new \Exception('Invalid Request: User Information Missing.');
            }

            $orderData = array(
                "amount" => $amountRzp,
                "currency" => 'INR',
                "receipt" => (string) generateUniqueNumericId(16, 'WP-PROM-'),
                "partial_payment" => false,
                "notes" => array(
                    "DealerID" => $delearData['id'],
                    "PromotedVehicleId" => $promotedVehicleId,
                    "PromotionPlanId" => $promotionPlanId,
                    "PromotionPlanName" => $promotionPlanDetails['promotionName'],
                    "PromotionUnder" => PROMTION_TYPE[$promotionUnder],
                    "PromotionPlanAmount" => $amount,
                    "PromotionDuration" => $promotionPlanDetails['promotionDaysValidity'],
                    "DiscountInPercent" => ''
                )
            );

            $razorpayOrder = $this->ApiController->callRazorpayApi('orders', json_encode($orderData, JSON_NUMERIC_CHECK));

            if (isset($razorpayOrder['error'])) {
                throw new \Exception('Razorpay API Error: ' . $razorpayOrder['error']['description']);
            }

            $orderInsertData = array(
                'orderId' => $razorpayOrder['id'],
                'dealerUserId' => $razorpayOrder['notes']['DealerID'],
                'planId' => $razorpayOrder['notes']['PromotionPlanId'],
                'amount' => $razorpayOrder['notes']['PromotionPlanAmount'],
                'currency' => $razorpayOrder['currency'],
                'receipt' => $razorpayOrder['receipt'],
                'orderNotes' => serialize($razorpayOrder['notes']),
                'transactionFor' => 'promotion'
            );

            $this->RazorpayModel->save($orderInsertData);

            $prefillName = $delearData['name'] ?? '';
            $prefillEmail = $delearData['email'] ?? '';
            $prefillContact = $delearData['contact_no'] ?? '';

            $paymentForm = '<button type="button" id="rzp-promotion-button" class="btn btn-primary mt-3 btn-block">Pay ₹<span>' . $amount . '</span></button>
            <script src="' . base_url() . 'assets/vendors/scripts/rzp_checkout.js"></script>
            <script>
                var options = {
                    "key": "' . RZP_KEY . '",
                    "amount": "' . $amountRzp . '",
                    "currency": "INR",
                    "name": "ParastoneGlobal Pvt Limtited",
                    "description": "",
                    "image": "' . SERVER_ROOT_PATH_ASSETS . 'vendors/images/logo-bg.png",
                    "order_id": "' . $razorpayOrder['id'] . '",
                    "handler": function (response){
                    $.ajax({
                            url: "' . base_url() . 'payment-response",
                            type: "POST",
                            data: {
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_signature: response.razorpay_signature,
                                dealer_id: "' . $delearData['id'] . '",
                                promoted_vehicle_id: "' . $promotedVehicleId . '",
                                promotion_plan_id: "' . $promotionPlanId . '",
                                promotion_plan_name: "' . $promotionPlanDetails['promotionName'] . '",
                                promotion_under: "' . PROMTION_TYPE[$promotionUnder] . '",
                                promotion_plan_amount: "' . $amount . '",
                                promotion_duration: "' . $promotionPlanDetails['promotionDaysValidity'] . '"
                            },
                            success: function(data) {
                                if(data.code == "200"){
                                    showSuccessAlert(data.message);      
                                    $("#rzp-promotion-button").hide();                         
                                    setTimeout(function () {
                                        window.location.href = data.redirectURL;
                                    }, 9000);
                                }else{
                                    setTimeout(function () {
                                            location.reload();
                                    }, 3000);        
                                }
                            },
                            error: function(xhr, status, error) {
                                showErrorAlert("An error occurred while saving payment details: " + error);
                            }
                        });
                    },
                    "prefill": {
                        "name": "' . $prefillName . '", 
                        "email": "' . $prefillEmail . '",
                        "contact": "' . $prefillContact . '" 
                    },
                    "notes": {
                        "DealerID": "' . $delearData['id'] . '",
                        "PromotedVehicleId": "' . $promotedVehicleId . '",
                        "PromotionPlanId": "' . $promotionPlanId . '",
                        "PromotionPlanName": "' . $promotionPlanDetails['promotionName'] . '",
                        "PromotionUnder": "' . PROMTION_TYPE[$promotionUnder] . '",
                        "PromotionPlanAmount": "' . $amount . '",
                        "PromotionDuration": "' . $promotionPlanDetails['promotionDaysValidity'] . '",
                        "PromotionOrder_id": "' . $razorpayOrder['id'] . '",
                        "DiscountInPercent": ""
                    },
                    "theme": {
                        "color": "#3399cc"
                    }
                };
                var rzp_promtion = new Razorpay(options);
                rzp_promtion.on("payment.failed", function (response){
                    showErrorAlert(response.error.description);
                    /*
                    //alert(response.error.code);
                    //alert(response.error.source);
                    //alert(response.error.step);
                    //alert(response.error.reason);
                    //alert(response.error.metadata.order_id);
                    //alert(response.error.metadata.payment_id);
                    */
                    $.ajax({
                            url: "' . base_url() . 'payment-response",
                            type: "POST",
                            data: {
                                razorpay_payment_id: response.error.metadata.payment_id,
                                razorpay_order_id: response.error.metadata.order_id,
                                error_code: response.error.code,
                                error_source: response.error.source,
                                error_step: response.error.step,
                                error_reason: response.error.reason,
                                dealer_id: "' . $delearData['id'] . '",
                                promoted_vehicle_id: "' . $promotedVehicleId . '",
                                promotion_plan_id: "' . $promotionPlanId . '",
                                promotion_plan_name: "' . $promotionPlanDetails['promotionName'] . '",
                                promotion_under: "' . PROMTION_TYPE[$promotionUnder] . '",
                                promotion_plan_amount: "' . $amount . '",
                                promotion_duration: "' . $promotionPlanDetails['promotionDaysValidity'] . '"
                            },
                            success: function(data) {
                                if(data.code == "400"){
                                    showSuccessAlert(data.message);      
                                    $("#rzp-promotion-button").hide();                         
                                    setTimeout(function () {
                                        window.location.href = data.redirectURL;
                                    }, 5000);
                                }else{
                                    setTimeout(function () {
                                            location.reload();
                                    }, 3000);        
                                }
                            },
                            error: function(xhr, status, error) {
                                showErrorAlert("An error occurred while saving payment details: " + error);
                            }
                        });
                });
                document.getElementById("rzp-promotion-button").onclick = function(e){
                    rzp_promtion.open();
                    e.preventDefault();
                }
            </script>';

            $response = array(
                'status' => 'success',
                'responseCode' => 200,
                'responseMessage' => '',
                'responseData' => $orderData,
                'paymentForm' => $paymentForm
            );
        } catch (\Exception $e) {
            $response = array(
                'responseCode' => 400,
                'responseMessage' => $e->getMessage(),
                'redirectUrl' => base_url('/list-vehicles')
            );

            // Optionally log the error message
            log_message('error', $e->getMessage());
        }

        return $this->response->setJSON($response);
    }

    /* callback URL RazorPay*/
    public function callbackUrlRzp() {
        try {
            /* // Retrieve the POST data */
            $postData = $this->request->getPost();

            /* // Check if response has any error */
            if (array_key_exists('error_code', $postData)) {

                /*
                    [razorpay_payment_id] => pay_OZS4lexmyFw4y4
                    [razorpay_order_id] => order_OZS2t9Rot1vk2h
                    [error_code] => BAD_REQUEST_ERROR
                    [error_source] => bank
                    [error_step] => payment_authorization
                    [error_reason] => payment_failed
                    [dealer_id] => 7
                    [promoted_vehicle_id] => 40
                    [promotion_plan_id] => 2
                    [promotion_plan_name] => Gold Plan
                    [promotion_under] => Featured
                    [promotion_plan_amount] => 149
                    [promotion_duration] => 15
                */
                
                /* // Get created order details from razorpay_order_id to verify signature */
                $orderDetails = $this->RazorpayModel->where('orderId',  $postData['razorpay_order_id'])->first();

                if (!$orderDetails) {
                    throw new \Exception('Order details not found.');
                }

                $orderInsertData = array(
                    'payment_status' => 'failed',
                    'payment_response' => serialize($postData),
                    'updated_dt' => date("Y-m-d H:i:s")
                );

                /* // Insert response to the razorpay transaction table */
                $orderInsert = $this->RazorpayModel->update($orderDetails['id'], $orderInsertData);

                $response = array(
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'Payment Failed / Cancled',
                    'redirectURL' => base_url('dealer/list-vehicles')
                );
                return $this->response->setJSON($response);

                // $flashData = [
                //     'id' => '',
                //     'description' => $postData['error']['description'],
                //     'razorpay_order_id' => $postData['razorpay_order_id']
                // ];

                // session()->setFlashdata('flashData', $flashData);

                // /* // Redirect with flashdata */
                // return redirect()->to('/payment-failed');
            } else {
                /* // Response as success */
                $razorpay_signature = $postData['razorpay_signature'];
                $razorpay_order_id = $postData['razorpay_order_id'];
                $razorpay_payment_id = $postData['razorpay_payment_id'];
                $dealer_id = $postData['dealer_id'];
                $promoted_vehicle_id = $postData['promoted_vehicle_id'];
                $promotion_plan_id = $postData['promotion_plan_id'];
                $promotion_plan_name = $postData['promotion_plan_name'];
                $promotion_under = $postData['promotion_under'];
                $promotion_plan_amount = $postData['promotion_plan_amount'];
                $promotion_duration = $postData['promotion_duration'];

                /* // Get created order details from razorpay_order_id to verify signature */
                $orderDetails = $this->RazorpayModel->where('orderId', $razorpay_order_id)->first();

                if (!$orderDetails) {
                    throw new \Exception('Order details not found.');
                }

                /* // generate signature with internal table values for signature */
                $generated_signature = hash_hmac('sha256', $orderDetails['orderId'] . "|" . $razorpay_payment_id, RZP_SECRET);

                /* // Verify payment signature */
                if ($generated_signature == $razorpay_signature) {
                    $orderInsertData = array(
                        'payment_response' => serialize($this->request->getPost()),
                        'razorpay_payment_id' => $razorpay_payment_id,
                        'razorpay_order_id' => $razorpay_order_id,
                        'razorpay_signature' => $razorpay_signature,
                        'payment_status' => 'success',
                        'updated_dt' => date("Y-m-d H:i:s")
                    );

                    /* // Insert response to the razorpay transaction table */
                    $orderInsert = $this->RazorpayModel->update($orderDetails['id'], $orderInsertData);

                    if ($orderInsert) {

                        $promtionData = [
                            'transactionsrazorpay_id' => $orderDetails['id'],
                            'promotionPlanId' => $promotion_plan_id,
                            'dealerId' => $dealer_id,
                            'vehicleId' => $promoted_vehicle_id,
                            'start_dt' => date('Y-m-d H:i:s'),
                            'end_dt' => date('Y-m-d H:i:s', strtotime('+' . $promotion_duration . ' days')),
                            'old_current' => 1,
                            'is_active' => 1,
                            'auto_renew' => 1,
                            'payment_status' => 'success',
                            'created_dt' => date('Y-m-d H:i:s'),
                            'updated_by' => '',
                            'updated_dt' => date('Y-m-d H:i:s')
                        ];

                        $promtionSDataInsert = $this->PromotionPlanModel->insertPromotionData($promtionData);

                        $partnerInfo = $this->UserModel->where('id', $dealer_id)->first();
                        /* // Fetch all active plan by id */
                        $planDetails = $this->PromotionPlanModel->where('id', $promotion_plan_id)->first();
                        $vehicleDetails = $this->PromotionPlanModel->getVehicleDetails($promoted_vehicle_id);

                        $viewData['orderDetails'] = $orderDetails;
                        $viewData['partnerInfo'] = $partnerInfo;
                        $viewData['planDetails'] = $planDetails;
                        $viewData['vehicleDetails'] = $vehicleDetails;
                        $viewData['promtionData'] = $promtionData;

                        // /* email promtional details to dealer */
                        // /* // Load the invoice view with the data */
                        // $html = view('web/invoice/invoice_template',);
                        // /* // Generate the PDF */
                        // $filename = str_replace("/", "_", $orderDetails['receipt']) . '.pdf';
                        // $pdfPath = generatePDF($html, $filename, false);

                        $to = $partnerInfo['email'];
                        $subject = 'Vehicle Promotion Details - Wheelpact';
                        $toName = $partnerInfo['name'];
                        $body = view('dealer/email_templates/partner_promotion_mail', $viewData);

                        $mailResult = sendEmail($to, $toName, $subject, $body);
                        // if (!$mailResult) {
                        //     $response = array(
                        //         'responseCode'   => 500,
                        //         'responseMessage' => 'Error sending mail',
                        //         'responseData' => $mailResult
                        //     );
                        //     return $this->response->setJSON($response);
                        // }
                        $response = array(
                            'code' => 200,
                            'status' => 'success',
                            'message' => 'Vehicle Promotion Successful',
                            'redirectURL' => base_url('dealer/list-vehicles')
                        );
                        return $this->response->setJSON($response);
                    }
                } else {
                    throw new \Exception('Payment verification failed.');
                    /* // Optionally, you can set flashdata to inform the user about the error */
                    session()->setFlashdata('error', 'Payment verification failed.');
                }
                /* // Redirect with flashdata */
                return redirect()->to('/list-vehicles');
            }
        } catch (\Exception $e) {
            /* // Log the error message */
            log_message('error', $e->getMessage());

            /* // Optionally, you can set flashdata to inform the user about the error */
            session()->setFlashdata('error', 'An error occurred during the payment process. Please try again.');

            /* // Redirect to a generic error page */
            return redirect()->to('/payment-failed');
        }
    }

    public function payment_success() {

        $postData = $this->request->getPost();
        echo "<pre>";
        print_r($postData);
        die;

        try {
            /* // Retrieve flashdata */
            $flashData = session()->getFlashdata('Orderdata');

            if (empty($flashData) || !isset($flashData['id']) || !isset($flashData['razorpay_order_id'])) {
                /*
                //throw new \Exception('Order data is missing or incomplete.');
                // If the post is not found, show 404 error
                */
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }

            $orderDetails = $this->RazorpayModel->where('id', $flashData['id'])->where('orderid', $flashData['razorpay_order_id'])->first();

            if (!$orderDetails) {
                throw new \Exception('Order details not found.');
            }

            /* // Fetch all active plan by id */
            $planDetails = $this->PlanModel->where('id', $orderDetails['planId'])->first();

            if (!$planDetails) {
                throw new \Exception('Plan details not found.');
            }

            $planNotes = unserialize($orderDetails['orderNotes']);
            if (!isset($planNotes['Duration'])) {
                throw new \Exception('Plan duration not found in order notes.');
            }
            $planDuration = $planNotes['Duration'];

            /* // Subscription end date calculation */
            $orderDetails['endDate'] = calculate_end_date($planDuration);

            /* mail to dealer user with the details dealer portal */
            $partnerInfo = $this->UserModel->where('id', $orderDetails['dealerUserId'])->first();
            if (!$partnerInfo) {
                throw new \Exception('Partner details not found.');
            }
            /* check if already details inserted */
            $checkDataExists = $this->DealerSubscriptionModel->where('transactionsrazorpay_id', $orderDetails['id'])->where('dealerId', $orderDetails['dealerUserId'])->first();

            if (!$checkDataExists) {
                /* // Insert plan subscription details in dealerSubscriptions table */
                $insertData = array(
                    'planId' => $orderDetails['planId'],
                    'transactionsrazorpay_id' => $orderDetails['id'],
                    'dealerId' => $orderDetails['dealerUserId'],
                    'start_dt' => date("Y-m-d H:i:s"),
                    'end_dt' => $orderDetails['endDate'],
                    'old_current' => '1',
                    'payment_status' => $orderDetails['payment_status'],
                );
                $this->DealerSubscriptionModel->save($insertData);
            }

            $this->pageData['orderDetails'] = $orderDetails;
            $this->pageData['partnerInfo'] = $partnerInfo;
            $this->pageData['planDetails'] = $planDetails;
            $this->pageData['planNotes'] = $planNotes;

            /* // Load the invoice view with the data */
            $html = view('web/invoice/invoice_template', $this->pageData);

            /* // Generate the PDF */
            $filename = str_replace("/", "_", $orderDetails['receipt']) . '.pdf';
            $pdfPath = generatePDF($html, $filename, false);

            $to = $partnerInfo['email'];
            $subject = 'Welcome to WheelPact - Our New Partner!';
            $toName = $partnerInfo['name'];
            $body = view('web/email_templates/partner_welcome_mail', ['partner' => $partnerInfo]);

            $mailResult = sendEmail($to, $toName, $subject, $body, $pdfPath);
            if (!$mailResult) {
                $response = array(
                    'responseCode'   => 500,
                    'responseMessage' => 'Error sending mail',
                    'responseData' => $mailResult
                );
                return $this->response->setJSON($response);
            }

            /* // Unset partner data */
            session()->remove('partnerData');
            return view('web/pages/payment/success', $this->pageData);
        } catch (\CodeIgniter\Exceptions\PageNotFoundException $e) {
            /* // Handle other exceptions */
            $this->pageData['message'] = $e->getMessage();
            return redirect()->to('/invalidRequest');
        } catch (\Exception $e) {
            $this->pageData['message'] = $e->getMessage();
            return redirect()->to('/page404');
        }
    }
    /* razor pay end */
}
