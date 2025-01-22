<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\ConnectionInterface;

class CommonModel extends Model {

    public function getUpcomingTestDrives() {
        $query = $this->db->query("
            SELECT 
                tdr.id,
                tdr.customer_name,
                tdr.customer_phone,
                tdr.dateOfVisit,
                tdr.timeOfVisit,
                tdr.status,
                tdr.comments,
                c.email AS customer_email 
            FROM 
                test_drive_request tdr
            JOIN 
                customers c ON c.id = tdr.customer_id
            WHERE 
                tdr.status IN ('accepted')
                AND tdr.dateOfVisit = CURDATE() + INTERVAL 1 DAY
                AND tdr.is_active = '1'
        ");
        return $query->getResultArray();
    }
}
