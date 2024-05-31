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
}
