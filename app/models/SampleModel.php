<?php
/**
 * Sample Model
 */

class SampleModel extends Model {

    public function getPendingCount() {
        $sql = "SELECT COUNT(*) as total FROM samplecollection WHERE Status='Pending'";
        $row = $this->fetchOne($sql);
        return $row['total'] ?? 0;
    }

    public function getReviewedByStaffCount($staffId) {
        $staffId = intval($staffId);
        $sql = "SELECT COUNT(*) as total FROM samplecollection WHERE ReviewedBy='$staffId'";
        $row = $this->fetchOne($sql);
        return $row['total'] ?? 0;
    }

    public function submitSampleRequest($patientId, $mobileNo, $address, $preferredDatetime, $photoPath = null) {
        $patientId = intval($patientId);
        $mobileNo = $this->escape($mobileNo);
        $address = $this->escape($address);
        $preferredDatetime = $this->escape($preferredDatetime);
        $photoPathVal = $photoPath ? "'" . $this->escape($photoPath) . "'" : "NULL";

        $sql = "INSERT INTO samplecollection
                (PatientID, MobileNo, Address, PreferredDateTime, PhotoPath, Status)
                VALUES
                ('$patientId', '$mobileNo', '$address', '$preferredDatetime', $photoPathVal, 'Pending')";
        return $this->query($sql);
    }

    public function getSamplesList($filter = 'Pending') {
        $filter = $this->escape($filter);
        if ($filter == 'All') {
            $sql = "SELECT s.*, u.Name AS PatientName
                    FROM samplecollection s
                    JOIN user u ON u.UserID = s.PatientID
                    ORDER BY s.RequestDate DESC";
        } else {
            $sql = "SELECT s.*, u.Name AS PatientName
                    FROM samplecollection s
                    JOIN user u ON u.UserID = s.PatientID
                    WHERE s.Status='$filter'
                    ORDER BY s.RequestDate DESC";
        }
        return $this->fetchAll($sql);
    }

    public function updateSampleReview($sampleId, $staffId, $action, $remarks = '') {
        $sampleId = intval($sampleId);
        $staffId = intval($staffId);
        $action = $this->escape($action);
        $remarks = $this->escape($remarks);

        $sql = "UPDATE samplecollection SET
                Status='$action',
                ReviewedBy='$staffId',
                ReviewDate=NOW(),
                Remarks='$remarks'
                WHERE SampleID='$sampleId'";
        return $this->query($sql);
    }
}
