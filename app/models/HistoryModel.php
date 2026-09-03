<?php
/**
 * History Model
 */

class HistoryModel extends Model {

    public function getHistory($userId, $searchDate = null) {
        $userId = intval($userId);
        $where = "WHERE hr.UserID='$userId'";

        if (!empty($searchDate)) {
            $searchDate = $this->escape($searchDate);
            $where .= " AND hr.LogDate='$searchDate'";
        }

        $sql = "SELECT hr.LogDate, hr.BMI, hr.Score,
                       dl.BloodPressureSystolic, dl.BloodPressureDiastolic, dl.BloodSugar, dl.BloodSugarType
                FROM healthreport hr
                LEFT JOIN dailylog dl ON dl.UserID = hr.UserID
                    AND DATE(hr.LogDate) = DATE(hr.LogDate)
                $where
                GROUP BY hr.ReportID
                ORDER BY hr.LogDate DESC";
        return $this->fetchAll($sql);
    }

    public function getUserInfoForPdf($userId) {
        $userId = intval($userId);
        $sql = "SELECT u.*, p.Weight, p.Height
                FROM user u
                LEFT JOIN patient p ON p.Patient_id = u.UserID
                WHERE u.UserID='$userId'";
        return $this->fetchOne($sql);
    }

    public function getPdfHistory($userId, $limit = 15) {
        $userId = intval($userId);
        $limit = intval($limit);
        $sql = "SELECT hr.LogDate, hr.BMI, hr.Score,
                       dl.SleepHours, dl.WaterIntake, dl.StepsCount,
                       dl.WorkoutDone, dl.MedsTaken,
                       dl.BloodPressureSystolic, dl.BloodPressureDiastolic,
                       dl.BloodSugar, dl.BloodSugarType
                FROM healthreport hr
                LEFT JOIN dailylog dl ON dl.UserID = hr.UserID
                    AND DATE(hr.LogDate) = DATE(hr.LogDate)
                WHERE hr.UserID='$userId'
                GROUP BY hr.ReportID
                ORDER BY hr.LogDate DESC
                LIMIT $limit";
        return $this->fetchAll($sql);
    }

    public function getLatestMedsStatus($userId) {
        $userId = intval($userId);
        $sql = "SELECT MedsTaken FROM dailylog WHERE UserID='$userId' ORDER BY LogID DESC LIMIT 1";
        $row = $this->fetchOne($sql);
        return ($row && $row['MedsTaken'] == 1) ? 'Taking' : 'Not Taking';
    }

    public function getLatestBmi($userId) {
        $userId = intval($userId);
        $sql = "SELECT BMI FROM healthreport WHERE UserID='$userId' ORDER BY LogDate DESC LIMIT 1";
        $row = $this->fetchOne($sql);
        return $row ? round($row['BMI'], 1) : 'N/A';
    }
}
