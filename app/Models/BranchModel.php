<?php
// app/Models/BranchModel.php

namespace App\Models;

use CodeIgniter\Model;

class BranchModel extends Model {
    protected $table      = 'branches';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $allowedFields = ['dealer_id', 'name', 'branch_banner1', 'branch_banner2', 'branch_banner3', 'branch_thumbnail', 'branch_logo', 'branch_type', 'branch_supported_vehicle_type', 'branch_services', 'country_id', 'state_id', 'city_id', 'address', 'contact_number', 'whatsapp_no', 'email', 'short_description', 'branch_map', 'map_latitude', 'map_longitude', 'map_city', 'map_district', 'map_state', 'is_active'];

    public function getAllBranchByDealerId(
        $dealerId,
        $countryId = NULL,
        $stateId = NULL,
        $cityId = NULL,
        $branchType = NULL,
        $limit = NULL,
        $offset = NULL,
        $is_promoted = NULL
    ) {

        $builder = $this->db->table('branches as b');
        $builder->select('b.*, s.name as state, c.name as city, 
            CASE WHEN b.branch_type = 1 THEN "Main Branch" WHEN b.branch_type = 2 THEN "Sub-branch" ELSE "Unknown" END as branch_type_label, 
            rating_data.average_rating as branch_rating,
            rating_data.rating_count as branch_review_count,
            dp.id as promotion_id, dp.start_dt as promotion_start_date, dp.end_dt as promotion_end_date,
            CASE WHEN dp.end_dt >= NOW() THEN 1 ELSE 0 END as is_promoted', false);

        // Joins
        $builder->join('countries', 'countries.id = b.country_id', 'left');
        $builder->join('states as s', 's.id = b.state_id', 'left');
        $builder->join('cities as c', 'c.id = b.city_id', 'left');
        $builder->join(
            '(SELECT branch_id, AVG(rating) as average_rating, COUNT(*) as rating_count 
            FROM branch_ratings GROUP BY branch_id) as rating_data',
            'rating_data.branch_id = b.id',
            'left'
        );
        //$builder->join('dealer_promotion as dp', 'dp.itemId = b.id AND dp.promotionUnder = "showroom" AND dp.is_active = 1', 'left');

        $builder->join(
            '(SELECT dp1.* FROM dealer_promotion dp1
              WHERE dp1.is_active = 1 AND dp1.promotionUnder = "showroom"
              AND dp1.id = (SELECT MAX(dp2.id) FROM dealer_promotion dp2 
                            WHERE dp2.itemId = dp1.itemId 
                            AND dp2.is_active = 1 
                            AND dp2.promotionUnder = "showroom")
            ) as dp',
            'dp.itemId = b.id',
            'left'
        );


        // Conditions
        $builder->where('b.dealer_id', $dealerId);

        // Handle the is_promoted filter
        if ($is_promoted === '1') {
            $builder->having('is_promoted', 1);
        }

        // Apply optional filters if values are provided
        if ($countryId !== NULL && $countryId !== '0') {
            $builder->where('b.country_id', $countryId);
        }

        if ($stateId !== NULL && $stateId !== '0') {
            $builder->where('b.state_id', $stateId);
        }

        if ($cityId !== NULL && $cityId !== '0') {
            $builder->where('b.city_id', $cityId);
        }

        if ($branchType !== NULL && $branchType !== '0') {
            $builder->where('b.branch_type', $branchType);
        }

        // Sorting logic
        $builder->orderBy('is_promoted, created_at', 'DESC'); // Prioritize promoted branches
        $builder->orderBy('CASE WHEN is_promoted = 1 THEN dp.end_dt ELSE NULL END', 'DESC', false); // Promotion end date (descending)
        $builder->orderBy('b.branch_type', 'DESC'); // Main branch first
        $builder->orderBy('b.id', 'DESC'); // Remaining by ID (descending)

        // Pagination
        if ($limit !== NULL && $offset !== NULL) {
            $builder->limit($limit, $offset);
        }

        // Execute query and get results
        $result = $builder->get()->getResultArray();

        // Count total branches and promoted branches
        $totalBranches = count($result);
        $promotedBranches = array_sum(array_column($result, 'is_promoted'));

        return [
            'total_showrooms' => $totalBranches,
            'promoted_showrooms' => $promotedBranches,
            'data' => $result
        ];
    }

    public function getStoreDetails($showroomId) {
        $builder = $this->db->table('branches');
        $builder->select('branches.*, 
            (CASE 
                WHEN branches.branch_type = 1 THEN "Main Branch" 
                WHEN branches.branch_type = 2 THEN "Sub-branch" 
                ELSE "Unknown" 
            END) as branch_type_label, 
            AVG(branch_ratings.rating) AS avg_rating, 
            COUNT(DISTINCT branch_ratings.id) AS review_count, 
            COUNT(vehicles.branch_id) AS vehicle_count, 
            users.name as owner_name, 
            users.email as owner_email, 
            users.contact_no as owner_contact_no, 
            rating_data.average_rating as branch_rating,
            rating_data.rating_count as branch_review_count,
            countries.name as countryName, 
            s.name as stateName, 
            c.name as cityName');
        $builder->join('branch_ratings', 'branches.id = branch_ratings.branch_id', 'left');
        $builder->join('vehicles', 'vehicles.branch_id = branches.id', 'left');
        $builder->join('users', 'users.id = branches.dealer_id', 'left');
        $builder->join('countries', 'countries.id = branches.country_id', 'left');
        $builder->join('states as s', 's.id = branches.state_id', 'left');
        $builder->join('cities as c', 'c.id = branches.city_id', 'left');
        $builder->join(
            '(SELECT branch_id, AVG(rating) as average_rating, COUNT(*) as rating_count 
            FROM branch_ratings GROUP BY branch_id) as rating_data',
            'rating_data.branch_id = branches.id',
            'left'
        );
        $builder->where('branches.id', $showroomId);
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
