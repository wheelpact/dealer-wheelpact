<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model {
    protected $table = 'users';
    protected $allowedFields =  ['user_code', 'name', 'email', 'addr_residential', 'addr_permanent', 'date_of_birth', 'gender', 'profile_image', 'country_id', 'state_id', 'city_id', 'zipcode', 'contact_no', 'social_fb_link', 'social_twitter_link', 'social_linkedin_link', 'social_skype_link', 'role_id', 'otp', 'otp_status', 'is_active', 'created_at', 'reset_token', 'token_expiration', 'updated_at'];

    public function getuser() {
        return $this->findAll();
    }

    public function chkUserCredentials($email, $password) {

        // Retrieve the user record based on the email
        $user = $this->select(['u.id as userId', 'u.user_code', 'u.email', 'u.role_id', 'ur.role_name', 'u.is_active', 'uc.password'])
            ->from('users as u')
            ->join('userscredentials as uc', 'u.id = uc.user_id')
            ->join('userroles as ur', 'u.role_id = ur.id')
            ->where('u.email', $email)
            ->where('u.role_id', '2')
            ->where('u.is_active', '1')
            ->first();

        if ($user && password_verify($password, $user['password'])) {
            // Password is correct
            unset($user['password']); // Remove the password field from the user data before returning
            return $user;
        }

        // Invalid email or password
        return null;
    }

    public function updatePassword($userId, $newPassword) {
        $this->db->table('userscredentials')
            ->set(['password' => $newPassword])
            ->where('user_id', $userId)
            ->update();

        if ($this->db->affectedRows() > 0) {

            $this->db->table('users')
                ->set(['reset_token' => NULL])
                ->set(['token_expiration' => NULL])
                ->where('id', $userId)
                ->update();

            return true;
        } else {
            return false;
        }
    }

    public function getDealerDetailsById($userId) {
        $this->select('users.*, countries.name as country, states.name as state, cities.name as city');
        $this->join('countries', 'countries.id = users.country_id', 'left');
        $this->join('states', 'states.id = users.state_id', 'left');
        $this->join('cities', 'cities.id = users.city_id', 'left');
        $this->where('users.id', $userId);

        return $this->first();
    }

    public function updateData($id, $data) {
        return $this->update($id, $data);
    }

    public function getPlanDetailsBYId($dealerId) {
        $builder = $this->db->table('transactionsrazorpay as trp');
        $builder->select('trp.id as transactionId, trp.planId as activePlan, trp.orderId, trp. dealerUserId, trp.amount, trp.currency, trp.receipt, trp.orderNotes, trp.payment_status, trp.razorpay_payment_id, trp.razorpay_order_id, trp.razorpay_signature, ds.vehicle_type as allowedVehicleListing, ds.start_dt, ds.end_dt, ds.type, pl.planName, pl.planDesc, pl.monthly_price, pl.vehicle_type as planVehicleType, pl.max_vehicle_listing_per_month, pl.free_inventory_promotions, pl.free_showroom_promotions');
        $builder->join('plans as pl', 'pl.id = trp.planId', 'left');
        $builder->join('dealer_subscription as ds', ' ds.transactionsrazorpay_id = trp.id', 'left');
        $builder->where('trp.dealerUserId', $dealerId);
        $result = $builder->get()->getResultArray();
        return $result;
    }

    public function updatePlanPreference($data) {
        $this->db->table(' dealer_subscription')
            ->set(['vehicle_type' => $data['vehicle_type']])
            ->where('dealerId', $data['dealer_id'])
            ->where('transactionsrazorpay_id ', $data['transaction_id'])
            ->where('planId', $data['activePlan'])
            ->update();
        if ($this->db->affectedRows() > 0) {
            return true;
        }

        return false;
    }

    public function saveLoginLog($data) {
        $this->db->table('loginLogs')->insert($data);
    }

    public function updateloginLogs($id, $data) {
        $this->db->table('loginLogs')
            ->set(['logoutTime' => $data['logoutTime']])
            ->where('id', $id)
            ->update();

        if ($this->db->affectedRows() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
