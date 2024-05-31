<?php

namespace App\Models;

use CodeIgniter\Model;

class VehicleImagesModel extends Model {
    protected $DBGroup              = 'default';
    protected $table                = 'vehicleimages';
    protected $primaryKey           = 'id';
    protected $returnType           = 'array';
    protected $allowedFields        = ['vehicle_id', 'exterior_main_front_img', 'exterior_main_right_img', 'exterior_main_back_img', 'exterior_main_left_img', 'exterior_main_roof_img', 'exterior_main_bonetopen_img', 'exterior_main_engine_img', 'exterior_diagnoal_right_front_img', 'exterior_diagnoal_right_back_img', 'exterior_diagnoal_left_back_img', 'exterior_diagnoal_left_front_img', 'exterior_wheel_right_front_img', 'exterior_wheel_right_back_img', 'exterior_wheel_left_back_img', 'exterior_wheel_left_front_img', 'exterior_wheel_spare_img', 'exterior_tyrethread_right_front_img', 'exterior_tyrethread_right_back_img', 'exterior_tyrethread_left_back_img', 'exterior_tyrethread_left_front_img', 'exterior_underbody_front_img', 'exterior_underbody_rear_img', 'exterior_underbody_right_img', 'exterior_underbody_left_img', 'interior_dashboard_img', 'interior_infotainment_system_img', 'interior_steering_wheel_img', 'interior_odometer_img', 'interior_gear_lever_img', 'interior_pedals_img', 'interior_front_cabin_img', 'interior_mid_cabin_img', 'interior_rear_cabin_img', 'interior_driver_side_door_panel_img', 'interior_driver_side_adjustment_img', 'interior_boot_inside_img', 'interior_boot_door_open_img', 'others_keys_img'];

    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    public function update_data($id, $data = array()) {
        $this->db->table($this->table)->update($data, array(
            "id" => $id,
        ));
        return $this->db->affectedRows();
    }

    public function update_vehicle_image($vehicleId, $data = array()) {
        $this->db->table("vehicleimages")->update($data, array(
            "vehicle_id" => $vehicleId,
        ));
        return $this->db->affectedRows();
    }

    public function getVehicleImagesDetails($vehicleId) {
        $query = $this->db->table('vehicleimages')->select('*')->where('vehicle_id', $vehicleId);
        return $query->get()->getRowArray();
    }
}
