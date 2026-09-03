<?php
/**
 * Blood Donation Model
 */

class BloodDonationModel extends Model {

    public function isRegisteredDonor($userId) {
        $userId = intval($userId);
        $sql = "SELECT DonorID FROM blood_donors WHERE UserID='$userId' LIMIT 1";
        return $this->fetchOne($sql) !== null;
    }

    public function registerDonor($userId, $bloodGroup, $location, $phone, $lastDonation = null) {
        $userId = intval($userId);
        $bloodGroup = $this->escape($bloodGroup);
        $location = $this->escape($location);
        $phone = $this->escape($phone);

        if (!empty($lastDonation)) {
            $lastDonation = $this->escape($lastDonation);
            $sql = "INSERT INTO blood_donors
                    (UserID, BloodGroup, Location, PhoneNo, LastDonationDate)
                    VALUES
                    ('$userId', '$bloodGroup', '$location', '$phone', '$lastDonation')";
        } else {
            $sql = "INSERT INTO blood_donors
                    (UserID, BloodGroup, Location, PhoneNo)
                    VALUES
                    ('$userId', '$bloodGroup', '$location', '$phone')";
        }

        return $this->query($sql);
    }

    public function toggleAvailability($donorId, $userId) {
        $donorId = intval($donorId);
        $userId = intval($userId);

        $sql = "SELECT Availability FROM blood_donors WHERE DonorID='$donorId' AND UserID='$userId' LIMIT 1";
        $row = $this->fetchOne($sql);

        if ($row) {
            $newStatus = ($row['Availability'] == 'Available') ? 'Not Available' : 'Available';
            $updateSql = "UPDATE blood_donors SET Availability='$newStatus' WHERE DonorID='$donorId' AND UserID='$userId'";
            return $this->query($updateSql);
        }

        return false;
    }

    public function deleteDonor($donorId, $userId) {
        $donorId = intval($donorId);
        $userId = intval($userId);
        $sql = "DELETE FROM blood_donors WHERE DonorID='$donorId' AND UserID='$userId'";
        return $this->query($sql);
    }

    public function getMyDonorProfile($userId) {
        $userId = intval($userId);
        $sql = "SELECT * FROM blood_donors WHERE UserID='$userId' LIMIT 1";
        return $this->fetchOne($sql);
    }

    public function getAvailableDonors($bloodGroup = '') {
        if (!empty($bloodGroup)) {
            $bloodGroup = $this->escape($bloodGroup);
            $sql = "SELECT bd.*, u.Name
                    FROM blood_donors bd
                    JOIN user u ON u.UserID = bd.UserID
                    WHERE bd.Availability='Available'
                    AND bd.BloodGroup='$bloodGroup'
                    ORDER BY bd.RegisteredAt DESC";
        } else {
            $sql = "SELECT bd.*, u.Name
                    FROM blood_donors bd
                    JOIN user u ON u.UserID = bd.UserID
                    WHERE bd.Availability='Available'
                    ORDER BY bd.RegisteredAt DESC";
        }
        return $this->fetchAll($sql);
    }
}
