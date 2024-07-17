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
        $builder->select('v.*, vc.cmp_name as cmp_name, vcm.model_name as cmp_model_name, vcmv.name as variantName');
        $builder->join('vehiclecompanies as vc', 'vc.id = v.cmp_id', 'left');
        $builder->join('vehiclecompaniesmodels as vcm', 'vcm.id = v.model_id', 'left');
        $builder->join('vehiclecompaniesmodelvariants as vcmv', 'vcmv.id = v.variant_id', 'left');
        $builder->where('v.id', $vehicleId);
        $result = $builder->get()->getRowArray();
        return $result;
    }

    public function checkVehiclePromoted($vehicleId) {
        $builder = $this->db->table('dealer_promotion');
        $builder->where('vehicleId', $vehicleId);
        $builder->where('is_active', 1);
        $builder->where('NOW() BETWEEN start_dt AND end_dt', null, false);
        $result = $builder->get()->getRowArray();
        return $result;
    }
}
