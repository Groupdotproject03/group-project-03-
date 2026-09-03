<?php
/**
 * Healthcare Finder Model
 */

class HealthcareModel extends Model {

    public function getAllServices() {
        $sql = "SELECT * FROM healthcare_services ORDER BY ServiceName ASC";
        return $this->fetchAll($sql);
    }
}
