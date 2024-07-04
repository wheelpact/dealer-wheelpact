<?php
// app/Models/BranchModel.php

namespace App\Models;

use CodeIgniter\Model;

class BranchModel extends Model {
    protected $table      = 'branches';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $allowedFields = ['dealer_id', 'name', 'branch_banner1', 'branch_banner2', 'branch_banner3', 'branch_thumbnail', 'branch_logo', 'branch_type', 'branch_supported_vehicle_type', 'branch_services', 'country_id', 'state_id', 'city_id', 'address', 'contact_number', 'email', 'short_description', 'is_active'];

    public function getAllBranchByDealerId($dealerId, $countryId, $stateId, $cityId, $branchType, $limit, $offset) {

        $builder = $this->db->table('branches as b');
        $builder->select('b.*, s.name as state, c.name as city, CASE WHEN b.branch_type = 1 THEN "Main Branch" WHEN b.branch_type = 2 THEN "Sub-branch" ELSE "Unknown" END as branch_type_label, 
        rating_data.average_rating as branch_rating,
        rating_data.rating_count as branch_review_count', false);
        $builder->join('countries', 'countries.id = b.country_id', 'left');
        $builder->join('states as s', 's.id = b.state_id', 'left');
        $builder->join('cities as c', 'c.id = b.city_id', 'left');
        $builder->join(
            '(SELECT branch_id, AVG(rating) as average_rating, COUNT(*) as rating_count 
            FROM branch_ratings GROUP BY branch_id) as rating_data',
            'rating_data.branch_id = b.id',
            'left'
        );
        $builder->where('b.dealer_id', $dealerId);
        $builder->where('b.is_active', 1);

        /* // Include conditions for city_id and state_id only if they are not equal to 0 */
        if ($countryId !== '0') {
            $builder->where('b.country_id', $countryId);
        }

        if ($stateId !== '0') {
            $builder->where('b.state_id', $stateId);
        }

        if ($cityId !== '0') {
            $builder->where('b.city_id', $cityId);
        }

        if ($branchType !== '0') {
            $builder->where('b.branch_type', $branchType);
        }

        /* Order by branch_type with "Main Branch" first */
        $builder->orderBy('b.branch_type', 'DESC');
        $builder->groupBy('b.id', 'DESC');

        $builder->limit($limit, $offset);

        return $builder->get()->getResultArray();
    }

    public function getStoreDetails($storeId) {
        $builder = $this->db->table('branches');
        $builder->select('branches.*, AVG(branch_ratings.rating) AS avg_rating, COUNT(DISTINCT branch_ratings.id) AS review_count, COUNT(vehicles.branch_id) AS vehicle_count, users.name as owner_name, users.email as owner_email, users.contact_no as owner_contact_no');
        $builder->join('branch_ratings', 'branches.id = branch_ratings.branch_id', 'left');
        $builder->join('vehicles', 'vehicles.branch_id = branches.id', 'left');
        $builder->join('users', 'users.id = branches.dealer_id', 'left');
        $builder->where('branches.id', $storeId);
        $builder->groupBy('branches.id');
        return $builder->get()->getRowArray();
    }

    public function getBranchReviews($branch_id) {
        $query = $this->db->table('branch_ratings as br')
            ->select('br.*, c.name as userName')
            ->join('customers as c', 'c.id = br.customer_id')
            ->where('br.branch_id', $branch_id);
        return $query->get()->getResultArray();
    }

    public function get_branch_deliverable_imgs($branch_id) {
        $builder = $this->db->table('branch_deliverable_images');
        $builder->where('branch_id', $branch_id);
        return $builder->get()->getResultArray();
    }

    public function insert_deliverablesImg($data) {
        return $this->db
            ->table('branch_deliverable_images')
            ->insert($data);
    }

    public function deleteBranch($branchId) {
        return $this->update($branchId, ['is_active' => 3]);
    }

    public function updateData($id, $data) {
        return $this->update($id, $data);
    }

    /*    // Get total branches by user ID (dealer_id) */
    public function getBranchCountByUser($dealerId) {

        $builder = $this->db->table($this->table);
        $builder->select('COUNT(*) as branch_count');
        $builder->where('branches.dealer_id', $dealerId);

        $query = $builder->get();
        return $query->getResultArray();
    }
}
