<?php

namespace App\Models;

use CodeIgniter\Model;

class VehicleModel extends Model {

    protected $DBGroup              = 'default';
    protected $table                = 'vehicles';
    protected $primaryKey           = 'id';
    protected $returnType           = 'array';
    protected $allowedFields        = ['unique_id', 'branch_id', 'vehicle_type', 'cmp_id', 'model_id', 'fuel_type', 'body_type', 'variant_id', 'mileage', 'kms_driven', 'owner', 'transmission_id', 'color_id', 'featured_status', 'search_keywords', 'onsale_status', 'onsale_percentage', 'manufacture_year', 'registration_year', 'registered_state_id', 'rto', 'insurance_type', 'insurance_validity', 'accidental_status', 'flooded_status', 'last_service_kms', 'last_service_date', 'car_no_of_airbags', 'car_central_locking', 'car_seat_upholstery', 'car_sunroof', 'car_integrated_music_system', 'car_rear_ac', 'car_outside_rear_view_mirrors', 'car_power_windows', 'car_engine_start_stop', 'car_headlamps', 'car_power_steering', 'bike_headlight_type', 'bike_odometer', 'bike_drl', 'bike_mobile_connectivity', 'bike_gps_navigation', 'bike_usb_charging_port', 'bike_low_battery_indicator', 'bike_under_seat_storage', 'bike_speedometer', 'bike_stand_alarm', 'bike_low_fuel_indicator', 'bike_low_oil_indicator', 'bike_start_type', 'bike_kill_switch', 'bike_break_light', 'bike_turn_signal_indicator', 'regular_price', 'selling_price', 'pricing_type', 'emi_option', 'avg_interest_rate', 'reservation_amt', 'tenure_months', 'thumbnail_url', 'is_active', 'created_by', 'created_datetime', 'updated_by', 'updated_datetime'];

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;

    /* // Dates */
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    /* Brands / vehicle companies filter */
    public function getDistinctBrands() {
        $query = $this->db->query('SELECT DISTINCT id, cmp_name FROM vehiclecompanies where is_active="1"');
        return $query->getResultArray();
    }

    /* vehicle companies models filter */
    public function getDistinctModelsByBrand_old($id) {
        $query = $this->db->query('SELECT DISTINCT id, cmp_name FROM vehiclecompanies where is_active="1";');
        return $query->getResultArray();
    }

    /* listing the showroom / branches of a dealer */
    public function getShowroomList($dealerId) {
        $query = $this->db->query('SELECT * FROM branches where dealer_id=' . $dealerId . ' AND is_active="1";');
        return $query->getResultArray();
    }

    public function getVehicleCountByBranch($dealerId) {

        $builder = $this->db->table($this->table);
        $builder->select('branch_id, MONTH(created_datetime) as month, YEAR(created_datetime) as year, COUNT(*) as vehicle_count');
        $builder->join('branches', 'branches.id = vehicles.branch_id');
        $builder->where('branches.dealer_id', $dealerId);
        $builder->groupBy('branch_id, month, year');

        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getVehicleDetails($vehicleId) {
        $builder = $this->db->table('vehicles as v');
        $builder->select('v.*, vc.cmp_name as cmp_name, vcm.model_name as cmp_model_name, vcmv.name as variantName, fueltypes.name as fuelTypeName, indiarto.rto_state_code as indiarto_rto_state_code, b.id as branch_id, b.name as branch_name, b.address as branch_address, CONCAT(countries.name, "," , states.name , "," , cities.name) AS branch_location , t.title as transmission_name');
        $builder->join('vehiclecompanies as vc', 'vc.id = v.cmp_id', 'left');
        $builder->join('vehiclecompaniesmodels as vcm', 'vcm.id = v.model_id', 'left');
        $builder->join('vehiclecompaniesmodelvariants as vcmv', 'vcmv.id = v.variant_id', 'left');
        $builder->join('fueltypes', 'fueltypes.id = v.fuel_type', 'left');
        $builder->join('indiarto', 'indiarto.id = v.rto', 'left');
        $builder->join('branches as b', 'b.id = v.branch_id', 'left');
        $builder->join('transmissions as t', 't.id = v.transmission_id', 'left');
        $builder->join('countries', 'countries.id = b.country_id', 'left');
        $builder->join('states', 'states.id = b.state_id', 'left');
        $builder->join('cities', 'cities.id = b.city_id', 'left');
        $builder->where('v.id', $vehicleId);
        $result = $builder->get()->getRowArray();
        return $result;
    }

    public function getVehicleImagesDetails($vehicleId) {
        $query = $this->db->table('vehicleimages')->select('*')->where('vehicle_id', $vehicleId);
        return $query->get()->getRowArray();
    }

    /* fetch the list  of vehicle of the dealer */
    public function getAllVehiclesByBranch($branchId, $limit, $offset, $vehicleTypeId, $vehicleBrandId, $vehicleModelId, $vehicleVariantId) {
        $builder = $this->db->table('vehicles as v');
        $builder->select('vc.cmp_name, vcm.model_name, vcmv.name as variantName, ft.name as fuel_type, vbt.title as bodytype, vt.title as vehicletransmission, v.id, v.vehicle_type, v.unique_id, v.mileage, v.kms_driven, v.owner, v.onsale_status, v.onsale_percentage, v.registration_year,
        st.name as statename, st.short_code, rto.rto_state_code, v.insurance_type, v.insurance_validity, v.regular_price, v.selling_price, v.thumbnail_url, v.manufacture_year, dp.promotionUnder,
        CASE WHEN dp.end_dt >= NOW() THEN 1 ELSE 0 END as is_promoted, dp.end_dt as promotion_end_date');
        $builder->join('vehiclecompanies as vc', 'vc.id = v.cmp_id', 'left');
        $builder->join('vehiclecompaniesmodels as vcm', 'vcm.id = v.model_id', 'left');
        $builder->join('fueltypes as ft', 'v.fuel_type = ft.id', 'left');
        $builder->join('vehiclebodytypes as vbt', 'v.body_type = vbt.id', 'left');
        $builder->join('transmissions as vt', 'v.transmission_id = vt.id', 'left');
        $builder->join('vehiclecompaniesmodelvariants as vcmv', 'vcmv.id = v.variant_id', 'left');
        $builder->join('states as st', 'v.registered_state_id = st.id', 'left');
        $builder->join('indiarto as rto', 'v.rto = rto.id', 'left');
        $builder->join('dealer_promotion as dp', 'dp.itemId = v.id AND dp.promotionUnder = "vehicle" AND dp.is_active = 1', 'left');
        $builder->where('v.branch_id', $branchId);
        $builder->where('v.is_active', 1);

        if ($vehicleTypeId != '0') {
            $builder->where($vehicleTypeId == 3 ? 'v.vehicle_type IN (1, 2)' : 'v.vehicle_type = ' . $vehicleTypeId);
        }
        if ($vehicleBrandId != '0') {
            $builder->where('v.cmp_id', $vehicleBrandId);
        }
        if ($vehicleModelId != '0') {
            $builder->where('v.model_id', $vehicleModelId);
        }
        if ($vehicleVariantId != '0') {
            $builder->where('v.variant_id', $vehicleVariantId);
        }

        $builder->limit($limit, $offset);
        $builder->orderBy('dp.end_dt');
        $builder->orderBy('v.id', 'DESC');

        return $builder->get()->getResultArray();
    }

    public function updateData($id, $data) {
        return $this->update($id, $data);
    }

    public function deleteVehicle($vehicleId) {
        return $this->update($vehicleId, ['is_active' => 3]);
    }

    public function getBrandsByVehicleType($vehicle_type) {
        $condition = '';
        if ($vehicle_type == 1) {
            $condition = 'WHERE cmp_cat IN (1, 3)';
        } elseif ($vehicle_type == 2) {
            $condition = 'WHERE cmp_cat IN (2, 3)';
        }

        $query = $this->db->query('SELECT DISTINCT id, cmp_name FROM vehiclecompanies ' . $condition . ' ORDER BY cmp_name');

        return $query->getResultArray();
    }

    public function getModelsByBrand($brandId, $vehicleType) {
        $query = $this->db->query('SELECT DISTINCT id, model_name FROM vehiclecompaniesmodels WHERE cmp_id=' . $brandId . ' AND cmp_cat=' . $vehicleType . ' ORDER BY model_name');
        return $query->getResultArray();
    }

    public function getVariantsByModel($modelId) {
        $query = $this->db->query('SELECT DISTINCT id, name FROM vehiclecompaniesmodelvariants where model_id=' . $modelId . ' ORDER BY name');
        return $query->getResultArray();
    }

    /* get list of reserved vehicle for loggined in dealer */
    public function getReservedVehiclesByBranch($branchId, $limit, $offset) {
        $builder = $this->db->table('vehicles as v');
        $builder->select('vc.cmp_name, vcm.model_name, vcmv.name as variantName, ft.name as fuel_type, vbt.title as bodytype, vt.title as vehicletransmission, v.*, c.id as customer_id, c.name as customer_name, b.name as branchName');
        $builder->join('vehiclecompanies as vc', 'vc.id = v.cmp_id', 'left');
        $builder->join('vehiclecompaniesmodels as vcm', 'vcm.id = v.model_id', 'left');
        $builder->join('fueltypes as ft', 'v.fuel_type = ft.id', 'left');
        $builder->join('vehiclebodytypes as vbt', 'v.body_type = vbt.id', 'left');
        $builder->join('transmissions as vt', 'v.transmission_id = vt.id', 'left');
        $builder->join('vehiclecompaniesmodelvariants as vcmv', 'vcmv.id = v.variant_id', 'left');
        $builder->join('vehicle_reservations as vr', 'vr.vehicle_id = v.id', 'left');
        $builder->join('customers as c', 'vr.customer_id = c.id', 'left');
        $builder->join('branches as b', 'v.branch_id = b.id', 'left');
        $builder->where('v.branch_id', $branchId);
        $builder->where('v.is_active', 1);
        $builder->where('vr.is_active', 1);

        $builder->limit($limit, $offset);

        return  $result = $builder->get()->getResultArray();

        // // Output the last executed query
        // $lastQuery = $this->db->getLastQuery();
        // echo $lastQuery;
        // die;
    }


    public function insertVehicle($formDataArray) {
        $builder = $this->db->table('vehicles');

        // Insert a single row
        $success = $builder->insert($formDataArray);

        if ($success) {
            // Insertion was successful
            // You can optionally get the inserted ID
            $insertedID = $this->db->insertID();
            return $insertedID;
        } else {
            // Insertion failed
            // You can log an error or handle it in some other way
            // Print the last executed query for debugging purposes
            // $lastQuery = $this->db->getLastQuery();
            // echo $lastQuery; exit;
            return false;
        }
    }
}
