<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\ConnectionInterface;

class CommonModel extends Model {

    protected $DBGroup              = 'default';
    protected $table                = 'Common';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = 'array';
    protected $useSoftDelete        = false;
    protected $protectFields        = true;
    protected $allowedFields        = [];

    // Dates
    protected $useTimestamps        = false;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks       = true;
    protected $beforeInsert         = [];
    protected $afterInsert          = [];
    protected $beforeUpdate         = [];
    protected $afterUpdate          = [];
    protected $beforeFind           = [];
    protected $afterFind            = [];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];


    protected $db;

    public function __construct() {
        parent::__construct();

        $this->db = \Config\Database::connect();
    }

    public function get_all_country_data() {
        $query = $this->db->query('select * from countries order by name asc');
        return $query->getResultArray();
    }

    public function get_all_state_data() {
        $query = $this->db->query('select * from states order by name asc');
        return $query->getResultArray();
    }

    public function get_all_city_data() {
        $query = $this->db->query('select * from cities order by name asc');
        return $query->getResultArray();
    }

    public function update_data($id, $table, $data = array()) {
        $this->db->table($table)->update($data, array(
            "id" => $id,
        ));
        return $this->db->affectedRows();
    }

    public function get_country_states($country_id) {
        $query = $this->db->query('select * from states where country_id=' . $country_id . ' order by name asc');
        return $query->getResultArray();
    }

    public function get_state_cities($state_id) {
        $query = $this->db->query('select * from cities where state_id=' . $state_id . ' order by name asc');
        return $query->getResultArray();
    }

    public function get_fuel_types() {
        $query = $this->db->query('select * from fueltypes order by name asc');
        return $query->getResultArray();
    }

    public function get_vehicle_body_types() {
        $builder = $this->db->table('vehiclebodytypes');
        $builder->select('*');
        $builder->where('is_active', 1);
        $builder->orderBy('title', 'asc');
        return $builder->get()->getResultArray();
    }

    public function get_fuel_variants() {
        $query = $this->db->query('select * from vehiclecompaniesmodelvariants order by id asc');
        return $query->getResultArray();
    }

    public function get_vehicle_transmissions() {
        $query = $this->db->query('select * from transmissions order by id asc');
        return $query->getResultArray();
    }

    public function get_vehicle_colors() {
        $query = $this->db->query('select * from colors order by name asc');
        return $query->getResultArray();
    }

    public function get_registered_state_rto($state_id) {
        $query = $this->db->query('select * from indiarto where state_id=' . $state_id . ' order by rto_state_code asc');
        return $query->getResultArray();
    }

    public function getCustomerReservedVehicles($customerId) {
        $builder = $this->db->table('vehicle_reservations as vr');
        $builder->select('v.*, vc.cmp_name as cmp_name, vcm.model_name as cmp_model_name, fueltypes.name as fuelTypeName, indiarto.rto_state_code as indiarto_rto_state_code, b.id as branch_id, b.name as branch_name, b.address as branch_address, CONCAT(countries.name, "," , states.name , "," , cities.name) AS branch_location , t.title as transmission_name');
        $builder->join('vehicles as v', 'v.id = vr.vehicle_id', 'left');
        $builder->join('vehiclecompanies as vc', 'vc.id = v.cmp_id', 'left');
        $builder->join('vehiclecompaniesmodels as vcm', 'vcm.id = v.model_id', 'left');
        $builder->join('fueltypes', 'fueltypes.id = v.fuel_type', 'left');
        $builder->join('indiarto', 'indiarto.id = v.rto', 'left');
        $builder->join('branches as b', 'b.id = v.branch_id', 'left');
        $builder->join('transmissions as t', 't.id = v.transmission_id', 'left');
        $builder->join('countries', 'countries.id = b.country_id', 'left');
        $builder->join('states', 'states.id = b.state_id', 'left');
        $builder->join('cities', 'cities.id = b.city_id', 'left');
        $builder->where('vr.customer_id', $customerId);
        $result = $builder->get()->getResultArray();
        return $result;
    }

    public function getCustomerWishlistVehicles($customerId) {
        $builder = $this->db->table('wishlistvehicles as w');
        $builder->select('v.*, vc.cmp_name as cmp_name, vcm.model_name as cmp_model_name, fueltypes.name as fuelTypeName, indiarto.rto_state_code as indiarto_rto_state_code, b.id as branch_id, b.name as branch_name, b.address as branch_address, CONCAT(countries.name, "," , states.name , "," , cities.name) AS branch_location , t.title as transmission_name');
        $builder->join('vehicles as v', 'v.id = w.vehicle_id', 'left');
        $builder->join('vehiclecompanies as vc', 'vc.id = v.cmp_id', 'left');
        $builder->join('vehiclecompaniesmodels as vcm', 'vcm.id = v.model_id', 'left');
        $builder->join('fueltypes', 'fueltypes.id = v.fuel_type', 'left');
        $builder->join('indiarto', 'indiarto.id = v.rto', 'left');
        $builder->join('branches as b', 'b.id = v.branch_id', 'left');
        $builder->join('transmissions as t', 't.id = v.transmission_id', 'left');
        $builder->join('countries', 'countries.id = b.country_id', 'left');
        $builder->join('states', 'states.id = b.state_id', 'left');
        $builder->join('cities', 'cities.id = b.city_id', 'left');
        $builder->where('w.customer_id', $customerId);
        $result = $builder->get()->getResultArray();
        return $result;
    }

    public function add_vehicle_wishlist($data) {
        // Insert the data into a table
        $status = $this->db->table('wishlistvehicles')->insert($data);
        if ($status == true) {
            return true;
        } else {
            return false;
        }
    }

    public function remove_vehicle_wishlist($condition) {

        $this->db->table('wishlistvehicles')->where($condition)->delete();
        $affectedRows = $this->db->affectedRows();
        if ($affectedRows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getWishlistStatus($customerId, $vehicleId) {
        $query = $this->db->query('select * from wishlistvehicles where customer_id=' . $customerId . ' and vehicle_id=' . $vehicleId . ' ');
        $result = $query->getResultArray();
        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }

    public function public_search_result_count($searchParam, $vtype) {
        $searchParam = json_decode($searchParam, true);
        $builder = $this->db->table('vehicles');
        $builder->select('vehicles.*, vehiclecompanies.cmp_name as makeName, vehiclecompaniesmodels.model_name as makeModelName, fueltypes.name as fuelTypeName');
        $builder->join('vehiclecompanies', 'vehiclecompanies.id = vehicles.cmp_id', 'left');
        $builder->join('vehiclecompaniesmodels', 'vehiclecompaniesmodels.id = vehicles.model_id', 'left');
        $builder->join('fueltypes', 'fueltypes.id = vehicles.fuel_type', 'left');
        $builder->join('branches', 'branches.id = vehicles.branch_id', 'left');
        $builder->join('countries', 'countries.id = branches.country_id', 'left');
        $builder->join('states', 'states.id = branches.state_id', 'left');
        $builder->join('cities', 'cities.id = branches.city_id', 'left');
        if ($vtype <> 3) {
            $builder->where('vehicles.vehicle_type', $vtype);
        }
        // Apply filters based on criteria

        // Add min_value and max-value conditions
        if (isset($searchParam['min_value']) && !empty($searchParam['min_value'])) {
            $minValue = $this->parseAmount($searchParam['min_value']);
            $builder->where('vehicles.selling_price >=', $minValue);
        }
        if (isset($searchParam['max_value']) && !empty($searchParam['max_value'])) {
            $maxValue = $this->parseAmount($searchParam['max_value']);
            $builder->where('vehicles.selling_price <=', $maxValue);
        }

        // Add cmp_id and model_id conditions
        if (isset($searchParam['cmp_id']) && !empty($searchParam['cmp_id'])) {
            $builder->where('vehicles.cmp_id', (int)$searchParam['cmp_id']);
        }
        if (isset($searchParam['model_id']) && !empty($searchParam['model_id'])) {
            $builder->where('vehicles.model_id', (int)$searchParam['model_id']);
        }

        // Add registration_year condition 
        if (isset($searchParam['registration_year']) && !empty($searchParam['registration_year'])) {
            $builder->where('vehicles.registration_year >= ', (int)$searchParam['registration_year']);
        }

        // Add kms_driven condition 
        if (isset($searchParam['kms_driven']) && !empty($searchParam['kms_driven'])) {
            $builder->where('vehicles.kms_driven <=', (int)$searchParam['kms_driven']);
        }

        // Add fuel_type conditions
        if (isset($searchParam['fuel_type']) && !empty($searchParam['fuel_type'])) {
            $builder->whereIn('vehicles.fuel_type', $searchParam['fuel_type']);
        }

        // Add body_type conditions
        if (isset($searchParam['body_type']) && !empty($searchParam['body_type'])) {
            $builder->whereIn('vehicles.body_type', $searchParam['body_type']);
        }

        // Add transmission_id conditions
        if (isset($searchParam['transmission_id']) && !empty($searchParam['transmission_id'])) {
            $builder->whereIn('vehicles.transmission_id', $searchParam['transmission_id']);
        }

        // Add owner conditions
        if (isset($searchParam['owner']) && !empty($searchParam['owner'])) {
            $builder->whereIn('vehicles.owner', $searchParam['owner']);
        }

        // Add section condition 
        if (isset($searchParam['section']) && !empty($searchParam['section'])) {
            switch ($searchParam['section']) {
                case "onsale":
                    $builder->where('vehicles.onsale_status', 1);
                    break;
                case "featured":
                    $builder->where('vehicles.featured_status', 1);
                    break;
            }
        }

        // Add location conditions
        if (isset($searchParam['location']) && !empty($searchParam['location'])) {
            $builder->like('branches.address', $searchParam['location']);
        }

        $builder->where('vehicles.is_active', 1);
        // Add relevance condition
        if (isset($searchParam['relevance']) && !empty($searchParam['relevance'])) {
            if ($searchParam['relevance'] == 1) {
                $builder->orderBy('vehicles.selling_price', 'asc');
            } elseif ($searchParam['relevance'] == 2) {
                $builder->orderBy('vehicles.selling_price', 'desc');
            } elseif ($searchParam['relevance'] == 3) {
                $builder->orderBy('vehicles.kms_driven', 'asc');
            } elseif ($searchParam['relevance'] == 4) {
                $builder->orderBy('vehicles.kms_driven', 'desc');
            }
        }

        return $builder->get()->getResultArray();
    }

    public function load_more_public_search_result($searchParam, $vtype, $limit, $start) {
        $searchParam = json_decode($searchParam, true);

        $builder = $this->db->table('vehicles');
        $builder->select('vehicles.*, vehiclecompanies.cmp_name as makeName, vehiclecompaniesmodels.model_name as makeModelName, fueltypes.name as fuelTypeName');
        $builder->join('vehiclecompanies', 'vehiclecompanies.id = vehicles.cmp_id', 'left');
        $builder->join('vehiclecompaniesmodels', 'vehiclecompaniesmodels.id = vehicles.model_id', 'left');
        $builder->join('fueltypes', 'fueltypes.id = vehicles.fuel_type', 'left');
        $builder->join('branches', 'branches.id = vehicles.branch_id', 'left');
        $builder->join('countries', 'countries.id = branches.country_id', 'left');
        $builder->join('states', 'states.id = branches.state_id', 'left');
        $builder->join('cities', 'cities.id = branches.city_id', 'left');
        if ($vtype <> 3) {
            $builder->where('vehicles.vehicle_type', $vtype);
        }
        // Apply filters based on criteria

        // Add min_value and max-value conditions
        if (isset($searchParam['min_value']) && !empty($searchParam['min_value'])) {
            $minValue = $this->parseAmount($searchParam['min_value']);
            $builder->where('vehicles.selling_price >=', $minValue);
        }
        if (isset($searchParam['max_value']) && !empty($searchParam['max_value'])) {
            $maxValue = $this->parseAmount($searchParam['max_value']);
            $builder->where('vehicles.selling_price <=', $maxValue);
        }

        // Add cmp_id and model_id conditions
        if (isset($searchParam['cmp_id']) && !empty($searchParam['cmp_id'])) {
            $builder->where('vehicles.cmp_id', (int)$searchParam['cmp_id']);
        }
        if (isset($searchParam['model_id']) && !empty($searchParam['model_id'])) {
            $builder->where('vehicles.model_id', (int)$searchParam['model_id']);
        }

        // Add registration_year condition 
        if (isset($searchParam['registration_year']) && !empty($searchParam['registration_year'])) {
            $builder->where('vehicles.registration_year >= ', (int)$searchParam['registration_year']);
        }

        // Add kms_driven condition 
        if (isset($searchParam['kms_driven']) && !empty($searchParam['kms_driven'])) {
            $builder->where('vehicles.kms_driven <=', (int)$searchParam['kms_driven']);
        }

        // Add fuel_type conditions
        if (isset($searchParam['fuel_type']) && !empty($searchParam['fuel_type'])) {
            $builder->whereIn('vehicles.fuel_type', $searchParam['fuel_type']);
        }

        // Add body_type conditions
        if (isset($searchParam['body_type']) && !empty($searchParam['body_type'])) {
            $builder->whereIn('vehicles.body_type', $searchParam['body_type']);
        }

        // Add transmission_id conditions
        if (isset($searchParam['transmission_id']) && !empty($searchParam['transmission_id'])) {
            $builder->whereIn('vehicles.transmission_id', $searchParam['transmission_id']);
        }

        // Add owner conditions
        if (isset($searchParam['owner']) && !empty($searchParam['owner'])) {
            $builder->whereIn('vehicles.owner', $searchParam['owner']);
        }

        // Add section condition 
        if (isset($searchParam['section']) && !empty($searchParam['section'])) {
            switch ($searchParam['section']) {
                case "onsale":
                    $builder->where('vehicles.onsale_status', 1);
                    break;
                case "featured":
                    $builder->where('vehicles.featured_status', 1);
                    break;
            }
        }

        // Add location conditions
        if (isset($searchParam['location']) && !empty($searchParam['location'])) {
            $builder->like('branches.address', $searchParam['location']);
        }

        $builder->where('vehicles.is_active', 1);
        // Add relevance condition
        if (isset($searchParam['relevance']) && !empty($searchParam['relevance'])) {
            if ($searchParam['relevance'] == 1) {
                $builder->orderBy('vehicles.selling_price', 'asc');
            } elseif ($searchParam['relevance'] == 2) {
                $builder->orderBy('vehicles.selling_price', 'desc');
            } elseif ($searchParam['relevance'] == 3) {
                $builder->orderBy('vehicles.kms_driven', 'asc');
            } elseif ($searchParam['relevance'] == 4) {
                $builder->orderBy('vehicles.kms_driven', 'desc');
            }
        }
        $builder->limit($limit, $start);
        return $builder->get()->getResultArray();
    }

    public function getSuggestions($query, $vtype) {
        $builder = $this->db->table('vehicles');
        $builder->select('vehicles.*,branches.name as branchName, vehiclecompanies.cmp_name as makeName, vehiclecompaniesmodels.model_name as makeModelName, fueltypes.name as fuelTypeName');
        $builder->distinct();
        $builder->join('vehiclecompanies', 'vehiclecompanies.id = vehicles.cmp_id', 'left');
        $builder->join('vehiclecompaniesmodels', 'vehiclecompaniesmodels.id = vehicles.model_id', 'left');
        $builder->join('fueltypes', 'fueltypes.id = vehicles.fuel_type', 'left');
        $builder->join('branches', 'branches.id = vehicles.branch_id', 'left');
        $builder->join('countries', 'countries.id = branches.country_id', 'left');
        $builder->join('states', 'states.id = branches.state_id', 'left');
        $builder->join('cities', 'cities.id = branches.city_id', 'left');
        if ($vtype <> 3) {
            $builder->where('vehicles.vehicle_type', $vtype);
        }
        // Apply filters based on criteria

        $builder->like('vehiclecompanies.cmp_name', $query);
        $builder->orLike('vehiclecompaniesmodels.model_name', $query);
        $builder->orLike('branches.name', $query);
        $builder->orLike('branches.address', $query);
        // $builder->orLike('countries.name', $query);
        // $builder->orLike('states.name', $query);
        $builder->orLike('cities.name', $query);
        return $builder->get()->getResultArray();
    }

    // Function to parse currency amount
    function parseAmount($amount) {
        // Assuming the currency symbol is '₹' and ',' is used as a thousands separator
        $amount = str_replace('₹', '', $amount);
        $amount = str_replace(',', '', $amount);
        return (float)$amount;
    }
}
