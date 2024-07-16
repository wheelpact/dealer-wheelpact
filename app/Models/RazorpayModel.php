<?php

namespace App\Models;

use CodeIgniter\Model;

class RazorpayModel extends Model {
    protected $table = 'transactionsrazorpay';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'orderId',
        'dealerUserId',
        'planId',
        'amount',
        'currency',
        'receipt',
        'orderNotes',
        'payment_status',
        'payment_response',
        'razorpay_payment_id',
        'razorpay_order_id',
        'razorpay_signature',
        'transactionFor',
        'created_dt',
        'updated_dt'
    ];

    protected $useTimestamps = false;
    protected $createdField = 'createdDt';
    protected $updatedField = '';
    protected $deletedField = '';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function updateTransaction($razorpay_order_id, $orderInsertData) {
        // Specify the condition
        $this->where('orderId', $razorpay_order_id);

        // Perform the update
        return $this->update($orderInsertData);
    }
}
