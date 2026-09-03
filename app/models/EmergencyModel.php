<?php
/**
 * Emergency Model
 */

class EmergencyModel extends Model {

    public function addEmergencyContact($patientId, $name, $relation, $phone) {
        $patientId = intval($patientId);
        $name = $this->escape($name);
        $relation = $this->escape($relation);
        $phone = $this->escape($phone);

        $sql = "INSERT INTO emergency_contact (PatientID, Name, Relation, Phone)
                VALUES ('$patientId','$name','$relation','$phone')";
        return $this->query($sql);
    }

    public function deleteEmergencyContact($patientId, $contactId) {
        $patientId = intval($patientId);
        $contactId = intval($contactId);
        $sql = "DELETE FROM emergency_contact WHERE ContactID='$contactId' AND PatientID='$patientId'";
        return $this->query($sql);
    }

    public function getEmergencyContacts($patientId) {
        $patientId = intval($patientId);
        $sql = "SELECT * FROM emergency_contact WHERE PatientID='$patientId' ORDER BY ContactID DESC";
        return $this->fetchAll($sql);
    }

    public function getRecentAmbulanceRequests($patientId, $limit = 5) {
        $patientId = intval($patientId);
        $limit = intval($limit);
        $sql = "SELECT * FROM ambulance_request WHERE PatientID='$patientId' ORDER BY RequestID DESC LIMIT $limit";
        return $this->fetchAll($sql);
    }

    public function requestAmbulance($patientId, $location, $contactNumber, $notes = '', $lat = null, $lng = null) {
        $patientId = intval($patientId);
        $location = $this->escape($location);
        $contactNumber = $this->escape($contactNumber);
        $notes = $this->escape($notes);
        $latVal = $lat !== null ? "'" . floatval($lat) . "'" : "NULL";
        $lngVal = $lng !== null ? "'" . floatval($lng) . "'" : "NULL";

        $sql = "INSERT INTO ambulance_request
                (PatientID, Location, Latitude, Longitude, ContactNumber, Notes, Status)
                VALUES ('$patientId', '$location', $latVal, $lngVal, '$contactNumber', '$notes', 'Requested')";
        return $this->query($sql);
    }

    public function getAmbulanceRequestById($requestId) {
        $requestId = intval($requestId);
        $sql = "SELECT * FROM ambulance_request WHERE RequestID='$requestId'";
        return $this->fetchOne($sql);
    }

    public function getAllAmbulanceRequests() {
        $sql = "SELECT ar.*, u.Name AS PatientName, u.PhoneNo AS PatientPhone
                FROM ambulance_request ar
                JOIN user u ON u.UserID = ar.PatientID
                ORDER BY FIELD(ar.Status,'Requested','Dispatched','On The Way','Arriving','Arrived','Cancelled'), ar.RequestedAt DESC";
        return $this->fetchAll($sql);
    }

    public function updateAmbulanceStatus($requestId, $data) {
        $requestId = intval($requestId);
        $status = $this->escape($data['status']);
        $driver = $this->escape($data['driver_name']);
        $phone  = $this->escape($data['driver_phone']);
        $vehicle= $this->escape($data['vehicle_no']);
        $eta    = $this->escape($data['eta']);

        $sql = "UPDATE ambulance_request SET
                Status='$status', DriverName='$driver', DriverPhone='$phone',
                VehicleNo='$vehicle', ETA='$eta', UpdatedAt=NOW()
                WHERE RequestID='$requestId'";
        return $this->query($sql);
    }
}
