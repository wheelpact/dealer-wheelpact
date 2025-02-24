<?php

namespace App\Models;

use CodeIgniter\Model;

class VehicleModel extends Model {

    protected $DBGroup              = 'default';
    protected $table                = 'vehicles';
    protected $primaryKey           = 'id';
    protected $returnType           = 'array';
    protected $allowedFields        = ['unique_id', 'branch_id', 'vehicle_type', 'cmp_id', 'model_id', 'fuel_type', 'body_type', 'variant_id', 'mileage', 'kms_driven', 'owner', 'transmission_id', 'color_id', 'featured_status', 'search_keywords', 'onsale_status', 'onsale_percentage', 'manufacture_year', 'registration_year', 'registered_state_id', 'rto', 'insurance_type', 'insurance_validity', 'accidental_status', 'flooded_status', 'last_service_kms', 'last_service_date', 'car_no_of_airbags', 'car_central_locking', 'car_seat_upholstery', 'car_sunroof', 'car_integrated_music_system', 'car_rear_ac', 'car_outside_rear_view_mirrors', 'car_power_windows', 'car_engine_start_stop', 'car_headlamps', 'car_power_steering', 'bike_headlight_type', 'bike_odometer', 'bike_drl', 'bike_mobile_connectivity', 'bike_gps_navigation', 'bike_usb_charging_port', 'bike_low_battery_indicator', 'bike_under_seat_storage', 'bike_speedometer', 'bike_stand_alarm', 'bike_low_fuel_indicator', 'bike_low_oil_indicator', 'bike_start_type', 'bike_kill_switch', 'bike_break_light', 'bike_turn_signal_indicator', 'regular_price', 'selling_price', 'pricing_type', 'emi_option', 'avg_interest_rate', 'reservation_amt', 'tenure_months', 'thumbnail_url', 'is_active', 'soldReason', 'created_by', 'created_datetime', 'updated_by', 'updated_datetime'];

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
        $builder->select('v.*, vc.cmp_name as cmp_name, vcm.model_name as cmp_model_name, vcmv.name as variantName, fueltypes.name as fuelTypeName, indiarto.rto_state_code as indiarto_rto_state_code, b.id as branch_id, b.name as branch_name, b.address as branch_address, CONCAT(cities.name, "," , states.name , "," , countries.name) AS branch_location , t.title as transmission_name');
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
        return $query->get()->getResultArray();
    }

    /* Fetch the list of vehicles for the dealer */
    public function getAllVehiclesByBranch($branchId, $limit = NULL, $offset = NULL, $vehicleTypeId = NULL, $vehicleBrandId = NULL, $vehicleModelId = NULL, $vehicleVariantId = NULL, $is_promoted = NULL) {
        $builder = $this->db->table('vehicles as v');
        $builder->select('
        vc.cmp_name, 
        b.name as branch_name,
        vcm.model_name, 
        vcmv.name as variantName, 
        ft.name as fuel_type, 
        vbt.title as bodytype, 
        vt.title as vehicletransmission, 
        v.id, 
        v.vehicle_type, 
        v.unique_id, 
        v.mileage, 
        v.kms_driven, 
        v.owner, 
        v.onsale_status, 
        v.onsale_percentage, 
        v.registration_year, 
        st.name as statename, 
        st.short_code, 
        rto.rto_state_code, 
        v.insurance_type, 
        v.insurance_validity, 
        v.regular_price, 
        v.selling_price, 
        v.thumbnail_url, 
        v.manufacture_year, 
        v.is_active, 
        v.is_admin_approved,
        dp.promotionUnder,
        CASE WHEN NOW() BETWEEN dp.start_dt AND dp.end_dt THEN 1 ELSE 0 END as is_promoted, 
        dp.start_dt as promotion_start_date,
        dp.end_dt as promotion_end_date
    ');

        $builder->join('vehiclecompanies as vc', 'vc.id = v.cmp_id', 'left');
        $builder->join('vehiclecompaniesmodels as vcm', 'vcm.id = v.model_id', 'left');
        $builder->join('fueltypes as ft', 'v.fuel_type = ft.id', 'left');
        $builder->join('vehiclebodytypes as vbt', 'v.body_type = vbt.id', 'left');
        $builder->join('branches as b', 'b.id = v.branch_id', 'left');
        $builder->join('transmissions as vt', 'v.transmission_id = vt.id', 'left');
        $builder->join('vehiclecompaniesmodelvariants as vcmv', 'vcmv.id = v.variant_id', 'left');
        $builder->join('states as st', 'v.registered_state_id = st.id', 'left');
        $builder->join('indiarto as rto', 'v.rto = rto.id', 'left');
        $builder->join(
            '(SELECT itemId, promotionUnder, is_active, MAX(start_dt) as start_dt, MAX(end_dt) as end_dt
              FROM dealer_promotion
              WHERE promotionUnder = "vehicle" AND is_active = 1
              GROUP BY itemId) as dp',
            'dp.itemId = v.id',
            'left'
        );

        $builder->where('v.branch_id', $branchId);
        /* $builder->where('v.is_admin_approved', '1'); */

        // Apply filters
        if (!empty($vehicleTypeId)) {
            $builder->where($vehicleTypeId == 3 ? 'v.vehicle_type IN (1, 2)' : 'v.vehicle_type', $vehicleTypeId);
        }

        if (!empty($vehicleBrandId)) {
            $builder->where('v.cmp_id', $vehicleBrandId);
        }

        if (!empty($vehicleModelId)) {
            $builder->where('v.model_id', $vehicleModelId);
        }

        if (!empty($vehicleVariantId)) {
            $builder->where('v.variant_id', $vehicleVariantId);
        }

        // Apply filter for promoted vehicles if $isPromoted is true
        if ($is_promoted) {
            $builder->having('is_promoted', 1); // Filter promoted vehicles
        }

        $builder->groupBy('v.id'); // Ensure unique rows by grouping by vehicle ID

        if (!is_null($limit) && !is_null($offset)) {
            $builder->limit($limit, $offset);
        }

        // Sorting: prioritize promoted vehicles, then fallback to ID
        $builder->orderBy('is_promoted, v.created_datetime, v.id', 'DESC');
        $builder->orderBy('v.is_active');
        $builder->orderBy('dp.end_dt', 'DESC', false);

        $vehicles = $builder->get()->getResultArray();

        // Total vehicle count
        $totalVehiclesQuery = $this->db->table('vehicles as v');
        $totalVehiclesQuery->select('COUNT(*) as total_count');
        $totalVehiclesQuery->where('v.branch_id', $branchId);
        $totalVehiclesQuery->where('v.is_admin_approved', '1');
        $totalVehicles = $totalVehiclesQuery->get()->getRowArray()['total_count'];

        // Promoted vehicle count
        $promotedVehiclesQuery = $this->db->table('vehicles as v');
        $promotedVehiclesQuery->select('COUNT(*) as promoted_count');
        $promotedVehiclesQuery->join(
            'dealer_promotion as dp',
            'dp.itemId = v.id AND dp.promotionUnder = "vehicle" AND dp.is_active = 1',
            'inner'
        );
        $promotedVehiclesQuery->where('v.branch_id', $branchId);
        $promotedVehiclesQuery->where('dp.end_dt >= NOW()');
        $promotedVehicles = $promotedVehiclesQuery->get()->getRowArray()['promoted_count'];

        return [
            'data' => $vehicles,
            'total_vehicles' => $totalVehicles,
            'promoted_vehicles' => $promotedVehicles,
        ];
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

    public function fetchTestDriveData($dealerId = '', $search = '', $columnName = 'created_at', $order = 'desc', $request_id = '', $limit = NULL) {
        $builder = $this->select('test_drive_request.*, b.name as branch_name, 
        vc.cmp_name, vcm.model_name, vcmv.name as variant_name, count(test_drive_request.id) as total_requests,
        DATE_FORMAT(test_drive_request.dateOfVisit, "%d-%m-%Y") as formatted_dateOfVisit,
        DATE_FORMAT(test_drive_request.created_at, "%d-%m-%Y") as formatted_created_at')
            ->from('test_drive_request')
            ->join('vehicles as v', 'test_drive_request.vehicle_id = v.id', 'left')
            ->join('branches as b', 'test_drive_request.branch_id = b.id', 'left');
        $builder->join('vehiclecompanies as vc', 'vc.id = v.cmp_id', 'left');
        $builder->join('vehiclecompaniesmodels as vcm', 'vcm.id = v.model_id', 'left');
        $builder->join('vehiclecompaniesmodelvariants as vcmv', 'vcmv.id = v.variant_id', 'left');

        if ($search != '') {
            $builder->groupStart()
                ->like('test_drive_request.customer_name', $search)
                ->orLike('test_drive_request.customer_phone', $search)
                ->orLike('b.name', $search)
                ->orLike('vc.cmp_name', $search)
                ->orLike('vcm.model_name', $search)
                ->orLike('vcmv.name', $search)
                ->groupEnd();
        }

        if (isset($dealerId) && !empty($dealerId)) {
            $builder->where('b.dealer_id', $dealerId);
        }

        // Apply request_id filter if provided
        if (!empty($request_id)) {
            $builder->where('test_drive_request.id', $request_id);
        }
        $builder->where('test_drive_request.is_active', '1');

        // Order by the formatted date and the column passed
        $builder->orderBy($columnName, $order);
        $builder->orderBy('test_drive_request.dateOfVisit', 'desc');

        // Group by original columns, not formatted ones
        $builder->groupBy(['test_drive_request.vehicle_id', 'test_drive_request.customer_id', 'test_drive_request.dateOfVisit', 'test_drive_request.timeOfVisit']);

        // Execute the query and return the results
        return $builder->get()->getResultArray();
    }

    public function fetchTestDriveDataCount($dealerId = '', $search = '', $columnName = 'created_at', $order = 'desc', $request_id = '') {
        // Build the query
        $builder = $this->db->table('branches b')
            ->select('
                COUNT(tdr.id) AS total_requests,
                SUM(CASE WHEN tdr.status = "pending" THEN 1 ELSE 0 END) AS pending_requests,
                SUM(CASE WHEN tdr.status = "accepted" THEN 1 ELSE 0 END) AS accepted_requests,
                SUM(CASE WHEN tdr.status = "rejected" THEN 1 ELSE 0 END) AS rejected_requests,
                SUM(CASE WHEN tdr.status = "completed" THEN 1 ELSE 0 END) AS completed_requests,
                SUM(CASE WHEN tdr.status = "canceled" THEN 1 ELSE 0 END) AS canceled_requests
            ')
            ->join('test_drive_request tdr', 'b.id = tdr.branch_id', 'left')
            ->where('b.is_active', 1); // Ensure the branch is active

        // Filter by dealer ID
        if (!empty($dealerId)) {
            $builder->where('b.dealer_id', $dealerId);
        }

        // Apply search filter
        if (!empty($search)) {
            $builder->groupStart()
                ->like('tdr.customer_name', $search)
                ->orLike('tdr.customer_phone', $search)
                ->orLike('b.name', $search)
                ->groupEnd();
        }

        // Filter by request ID
        if (!empty($request_id)) {
            $builder->where('tdr.id', $request_id);
        }

        // Order results by column name and branch name
        $builder->groupBy('b.dealer_id'); // Group by dealer ID
        $builder->orderBy('b.name', 'ASC'); // Order by branch name

        // Execute the query and return the results
        return $builder->get()->getResultArray();
    }

    public function update_test_drive_status($updateData) {
        $builder = $this->db->table('test_drive_request');
        $builder->where('id', $updateData['testDriveRequestId']);
        $builder->update([
            'status' => $updateData['status'],
            'reason_selected' => $updateData['reason_selected'],
            'dealer_comments'  => $updateData['dealer_comments'],
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->db->affectedRows() > 0;
    }

    // Function to get vehicle insights for a specific dealer using query builder
    public function getVehicleInsights($dealerId) {
        // Get the branch IDs related to the dealer
        $builder = $this->db->table('branches');
        $branches = $builder->select('id')
            ->where('dealer_id', $dealerId)
            ->where('is_active', 1) // Ensure active branches
            ->get()
            ->getResultArray();

        $branchIds = array_column($branches, 'id');

        // If no active branches, return an empty array
        if (empty($branchIds)) {
            return [];
        }

        // Query for vehicle insights
        $builder = $this->db->table('vehicles as v');

        $builder->select([
            // Total vehicles count
            'COUNT(v.id) AS total_vehicles',

            // Active vehicles count
            'SUM(CASE WHEN v.is_active = 1 THEN 1 ELSE 0 END) AS total_active_vehicles',

            // Inactive vehicles count
            'SUM(CASE WHEN v.is_active = 2 THEN 1 ELSE 0 END) AS total_inactive_vehicles',

            // deleted vehicles count
            'SUM(CASE WHEN v.is_active = 3 THEN 1 ELSE 0 END) AS total_deleted_vehicles',

            // sold vehicles count
            'SUM(CASE WHEN v.is_active = 4 THEN 1 ELSE 0 END) AS total_sold_vehicles',

            // Vehicles under admin approval count
            'SUM(CASE WHEN v.is_admin_approved = 0 THEN 1 ELSE 0 END) AS total_under_admin_approval',

            // Branch ID to group the results by branch
            'v.branch_id'
        ]);

        // Join with the other necessary tables
        $builder->join('branches b', 'b.id = v.branch_id AND b.dealer_id = ' . $dealerId . ' AND b.is_active = 1', 'inner')
            ->join('vehiclecompanies as vc', 'vc.id = v.cmp_id', 'left')
            ->join('vehiclecompaniesmodels as vcm', 'vcm.id = v.model_id', 'left')
            ->join('fueltypes as ft', 'v.fuel_type = ft.id', 'left')
            ->join('vehiclebodytypes as vbt', 'v.body_type = vbt.id', 'left')
            ->join('transmissions as vt', 'v.transmission_id = vt.id', 'left')
            ->join('vehiclecompaniesmodelvariants as vcmv', 'vcmv.id = v.variant_id', 'left')
            ->join('states as st', 'v.registered_state_id = st.id', 'left')
            ->join('indiarto as rto', 'v.rto = rto.id', 'left')
            ->join('dealer_promotion as dp', 'dp.itemId = v.id AND dp.promotionUnder = "vehicle" AND dp.is_active = 1', 'left');

        // Filter based on branch IDs
        $builder->whereIn('v.branch_id', $branchIds);

        // Group by branch_id to get insights per branch
        $builder->groupBy('v.branch_id');

        // Execute the query and return the results
        return $builder->get()->getResultArray();
    }

    public function getPromotedInsight($dealerId) {
        $builder = $this->db->table('dealer_promotion');

        // Select the promotionUnder and count the occurrences
        $builder->select('promotionUnder, COUNT(*) as count');

        // Filter based on the current date and dealerId
        $builder->where('start_dt <=', 'NOW()', false); // Use `false` for raw SQL functions
        $builder->where('end_dt >=', 'NOW()', false); // Use `false` for raw SQL functions
        $builder->where('dealerId', $dealerId);

        // Group by promotionUnder to count each promotion type separately
        $builder->groupBy('promotionUnder');

        // Execute the query and get the result
        $result = $builder->get()->getResultArray();

        // Initialize variables for counts
        $promotionUnderVehicle = 0;
        $promotionUnderShowroom = 0;

        // Loop through the result and assign counts to respective variables
        foreach ($result as $row) {
            if ($row['promotionUnder'] == 'vehicle') {
                $promotionUnderVehicle = $row['count'];
            } elseif ($row['promotionUnder'] == 'showroom') {
                $promotionUnderShowroom = $row['count'];
            }
        }

        // Return the counts for both promotionUnder types
        return [
            'promotionUnderVehicle' => $promotionUnderVehicle,
            'promotionUnderShowroom' => $promotionUnderShowroom
        ];
    }

    /* Test Drive Query - For Chart */

    public function fetchTestDriveDataForChart($dealerId = '', $search = '', $request_id = '') {
        $builder = $this->select('
                vcm.model_name AS vehicle_name,
                test_drive_request.status,
                COUNT(test_drive_request.id) AS status_count
            ')
            ->from('test_drive_request')
            ->join('vehicles as v', 'test_drive_request.vehicle_id = v.id', 'left')
            ->join('branches as b', 'test_drive_request.branch_id = b.id', 'left')
            ->join('vehiclecompaniesmodels as vcm', 'vcm.id = v.model_id', 'left');

        if (!empty($dealerId)) {
            $builder->where('b.dealer_id', $dealerId);
        }

        if (!empty($search)) {
            $builder->groupStart()
                ->like('test_drive_request.customer_name', $search)
                ->orLike('test_drive_request.customer_phone', $search)
                ->orLike('b.name', $search)
                ->orLike('vcm.model_name', $search)
                ->groupEnd();
        }

        if (!empty($request_id)) {
            $builder->where('test_drive_request.id', $request_id);
        }

        // Group by vehicle name and status
        $builder->groupBy(['vcm.model_name', 'test_drive_request.status']);

        // Execute the query and return the results
        return $builder->get()->getResultArray();
    }

    public function getPromotiondetails($vehicleOrBranchID) {
        $query = $this->db->table('dealer_promotion dp')
            ->select('dp.*, dp.id as promotionId, dp.itemId, dp.promotionUnder,
                MAX(dp.start_dt) as start_dt, 
                MAX(dp.end_dt) as end_dt,
                pp.promotionName, pp.promotionAmount, pp.promotionDetails, 
                pp.promotionType, pp.promotionDaysValidity, 
                tr.orderId, tr.dealerUserId, tr.CustomerUserId, 
                tr.amount, tr.currency, tr.payment_status, 
                tr.transactionFor, tr.created_dt AS transaction_created_dt,
                CASE 
                    WHEN dp.is_active = 3 THEN "Deleted"
                    WHEN dp.is_active = 2 THEN "Inactive"
                    WHEN MAX(dp.end_dt) >= CURDATE() THEN "Active"
                    ELSE "Expired" 
                END AS promotion_status')
            ->join('promotionPlans pp', 'dp.promotionPlanId = pp.id', 'left')
            ->join('transactionsrazorpay tr', 'dp.transactionsrazorpay_id = tr.id', 'left')
            ->where('dp.itemId', $vehicleOrBranchID)
            ->groupBy('dp.itemId')  // Required when using MAX() functions
            ->get();

        return $query->getResultArray();
    }
}
