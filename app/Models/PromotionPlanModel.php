<?php

namespace App\Models;

use CodeIgniter\Model;

class PromotionPlanModel extends Model {
    protected $table      = 'promotionPlans';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'promotionName',
        'promotionAmount',
        'promotionDetails',
        'promotionType',
        'promotionDaysValidity',
        'created_dt'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_dt';
    protected $updatedField  = '';
    protected $deletedField  = '';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function getPromotionPlanById($id) {
        return $this->find($id);
    }

    public function insertPromotionData($promtionData) {
        return $this->db
            ->table('dealer_promotion')
            ->insert($promtionData);
    }
    
    public function getVehicleDetails($vehicleId) {
        $builder = $this->db->table('vehicles as v');
        $builder->select('v.*, b.name as branchName, vc.cmp_name as cmp_name, vcm.model_name as cmp_model_name, vcmv.name as variantName');
        $builder->join('branches as b', 'b.id = v.branch_id', 'left');
        $builder->join('vehiclecompanies as vc', 'vc.id = v.cmp_id', 'left');
        $builder->join('vehiclecompaniesmodels as vcm', 'vcm.id = v.model_id', 'left');
        $builder->join('vehiclecompaniesmodelvariants as vcmv', 'vcmv.id = v.variant_id', 'left');
        $builder->where('v.id', $vehicleId);

        $query = $builder->get();

        if (!$query) {
            log_message('error', 'Query failed: ' . $this->db->error());
            return false; // Return false instead of calling getRowArray on a boolean
        }

        return $query->getRowArray();
    }


    public function getShowroomDetails($showroomId) {
        $builder = $this->db->table('branches');
        $builder->select('branches.*, branches.name as branchName,
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

    public function checkVehiclePromoted($vehicleId) {
        $builder = $this->db->table('dealer_promotion');
        $builder->where('vehicleId', $vehicleId);
        $builder->where('is_active', 1);
        $builder->where('NOW() BETWEEN start_dt AND end_dt', null, false);
        $result = $builder->get()->getRowArray();
        return $result;
    }

    public function checkItemPromoted($itemId, $promotionUnder) {
        $builder = $this->db->table('dealer_promotion');
        $builder->where('promotionUnder', $promotionUnder);
        $builder->where('itemId', $itemId);
        $builder->where('is_active', 1);
        $builder->where('NOW() BETWEEN start_dt AND end_dt', null, false);
        $result = $builder->get()->getRowArray();
        return $result;
    }

    public function deletePromotion($promotionId) {
        $this->db->table('dealer_promotion')
            ->set(['is_active' => '3'])
            ->where('id', $promotionId)
            ->update();
        if ($this->db->affectedRows() > 0) {
            return true;
        }
        return false;
    }
}
