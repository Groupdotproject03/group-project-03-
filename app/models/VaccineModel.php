<?php
/**
 * Vaccine Model
 */

class VaccineModel extends Model {

    public function autoUpdateStatus($userId) {
        $userId = intval($userId);
        $today = date("Y-m-d");
        $dueSoonLimit = date("Y-m-d", strtotime("+30 days"));

        $sql = "UPDATE vaccination
                SET Status =
                CASE
                    WHEN NextDueDate IS NULL THEN 'Completed'
                    WHEN NextDueDate < '$today' THEN 'Overdue'
                    WHEN NextDueDate <= '$dueSoonLimit' THEN 'Due Soon'
                    ELSE 'Upcoming'
                END
                WHERE UserID='$userId'";
        return $this->query($sql);
    }

    public function addVaccine($userId, $name, $dose, $vdate, $next = null) {
        $userId = intval($userId);
        $name = $this->escape($name);
        $dose = $this->escape($dose);
        $vdate = $this->escape($vdate);
        $nextValue = empty($next) ? "NULL" : "'" . $this->escape($next) . "'";

        $sql = "INSERT INTO vaccination
                (UserID, VaccineName, Dose, VaccinationDate, NextDueDate)
                VALUES
                ('$userId', '$name', '$dose', '$vdate', $nextValue)";
        return $this->query($sql);
    }

    public function deleteVaccine($vaccineId, $userId) {
        $vaccineId = intval($vaccineId);
        $userId = intval($userId);
        $sql = "DELETE FROM vaccination WHERE VaccineID='$vaccineId' AND UserID='$userId'";
        return $this->query($sql);
    }

    public function getVaccineSummary($userId) {
        $userId = intval($userId);
        $sql = "SELECT
                COUNT(*) as total,
                SUM(Status='Completed') as completed,
                SUM(Status='Upcoming') as upcoming,
                SUM(Status='Due Soon') as due,
                SUM(Status='Overdue') as overdue
                FROM vaccination
                WHERE UserID='$userId'";
        return $this->fetchOne($sql);
    }

    public function getVaccineRecords($userId) {
        $userId = intval($userId);
        $sql = "SELECT * FROM vaccination WHERE UserID='$userId' ORDER BY VaccinationDate DESC";
        return $this->fetchAll($sql);
    }
}
