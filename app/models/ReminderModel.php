<?php
/**
 * Reminder Model
 */

class ReminderModel extends Model {

    public function getTodayReminders($patientId, $todayDate) {
        $patientId = intval($patientId);
        $todayDate = $this->escape($todayDate);
        $sql = "SELECT * FROM reminder
                WHERE PatientID='$patientId'
                AND Day='$todayDate'
                ORDER BY Time ASC";
        return $this->fetchAll($sql);
    }

    public function addReminder($patientId, $type, $day, $time) {
        $patientId = intval($patientId);
        $type = $this->escape($type);
        $day = $this->escape($day);
        $time = $this->escape($time);

        $sql = "INSERT INTO reminder (PatientID, Time, Type, Day)
                VALUES ('$patientId', '$time', '$type', '$day')";
        return $this->query($sql);
    }
}
