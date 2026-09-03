<?php
/**
 * Trainer Model
 */

class TrainerModel extends Model {

    public function getAllTrainers() {
        $sql = "SELECT t.*, u.Name, u.Email, u.PhoneNo, u.ProfilePhoto 
                FROM trainer t 
                JOIN user u ON u.UserID = t.Trainer_id";
        return $this->fetchAll($sql);
    }

    public function getTrainerSlots($trainerId) {
        $trainerId = intval($trainerId);
        $sql = "SELECT * FROM trainer_slots 
                WHERE TrainerID='$trainerId' 
                ORDER BY SlotTime ASC";
        return $this->fetchAll($sql);
    }

    public function slotExists($trainerId, $slotTime) {
        $trainerId = intval($trainerId);
        $slotTime = $this->escape($slotTime);
        $sql = "SELECT * FROM trainer_slots 
                WHERE TrainerID='$trainerId' 
                AND SlotTime='$slotTime' LIMIT 1";
        return $this->fetchOne($sql) !== null;
    }

    public function addTrainerSlot($trainerId, $slotTime) {
        $trainerId = intval($trainerId);
        $slotTime = $this->escape($slotTime);
        $sql = "INSERT INTO trainer_slots (TrainerID, SlotTime) 
                VALUES ('$trainerId', '$slotTime')";
        return $this->query($sql);
    }

    public function deleteTrainerSlot($trainerId, $slotId) {
        $trainerId = intval($trainerId);
        $slotId = intval($slotId);
        $sql = "DELETE FROM trainer_slots 
                WHERE SlotID='$slotId' 
                AND TrainerID='$trainerId'";
        return $this->query($sql);
    }

    public function isSlotBooked($trainerId, $date, $slotTime) {
        $trainerId = intval($trainerId);
        $date = $this->escape($date);
        $slotTime = $this->escape($slotTime);
        $sql = "SELECT * FROM appointment 
                WHERE TrainerID='$trainerId' 
                AND Date='$date' 
                AND Time='$slotTime' LIMIT 1";
        return $this->fetchOne($sql) !== null;
    }

    public function bookTrainerAppointment($patientId, $trainerId, $date, $slotTime) {
        $patientId = intval($patientId);
        $trainerId = intval($trainerId);
        $date = $this->escape($date);
        $slotTime = $this->escape($slotTime);
        $sql = "INSERT INTO appointment (PatientID, TrainerID, Date, Time) 
                VALUES ('$patientId', '$trainerId', '$date', '$slotTime')";
        return $this->query($sql);
    }

    public function getTrainerAppointments($trainerId) {
        $trainerId = intval($trainerId);
        $sql = "SELECT a.*, u.Name, u.Email, u.PhoneNo 
                FROM appointment a 
                JOIN user u ON u.UserID = a.PatientID 
                WHERE a.TrainerID = '$trainerId' 
                ORDER BY a.Date DESC";
        return $this->fetchAll($sql);
    }

    public function deleteTrainerAppointment($trainerId, $appointmentId) {
        $trainerId = intval($trainerId);
        $appointmentId = intval($appointmentId);
        $sql = "DELETE FROM appointment WHERE AppointmentID='$appointmentId' AND TrainerID='$trainerId'";
        return $this->query($sql);
    }

    public function getTrainerClients($trainerId) {
        $trainerId = intval($trainerId);
        $sql = "SELECT DISTINCT u.UserID, u.Name, u.Email, u.PhoneNo, u.ProfilePhoto
                FROM appointment a
                JOIN user u ON u.UserID = a.PatientID
                WHERE a.TrainerID='$trainerId'";
        return $this->fetchAll($sql);
    }

    public function getTrainerStats($trainerId) {
        $trainerId = intval($trainerId);
        $clientSql = "SELECT COUNT(DISTINCT PatientID) as total
                      FROM appointment
                      WHERE TrainerID='$trainerId'";
        $clientRow = $this->fetchOne($clientSql);

        $appSql = "SELECT COUNT(*) as total
                   FROM appointment
                   WHERE TrainerID='$trainerId'";
        $appRow = $this->fetchOne($appSql);

        return [
            'totalClients' => $clientRow['total'] ?? 0,
            'totalAppointments' => $appRow['total'] ?? 0
        ];
    }

    public function getTodayTrainerAppointments($trainerId, $todayDate) {
        $trainerId = intval($trainerId);
        $todayDate = $this->escape($todayDate);
        $sql = "SELECT a.*, u.Name
                FROM appointment a
                JOIN user u ON u.UserID = a.PatientID
                WHERE a.TrainerID='$trainerId'
                AND a.Date='$todayDate'
                ORDER BY a.Time ASC";
        return $this->fetchAll($sql);
    }

    public function addWorkoutSuggestion($trainerId, $patientId, $suggestion) {
        $trainerId = intval($trainerId);
        $patientId = intval($patientId);
        $suggestion = $this->escape($suggestion);
        $sql = "INSERT INTO workout_plan (TrainerID, PatientID, Suggestion, CreatedDate)
                VALUES ('$trainerId','$patientId','$suggestion',NOW())";
        return $this->query($sql);
    }

    public function getTrainerSuggestionsHistory($trainerId) {
        $trainerId = intval($trainerId);
        $sql = "SELECT wp.*, u.Name AS ClientName
                FROM workout_plan wp
                JOIN user u ON u.UserID = wp.PatientID
                WHERE wp.TrainerID='$trainerId'
                ORDER BY wp.CreatedDate DESC";
        return $this->fetchAll($sql);
    }

    public function getPatientSuggestions($patientId) {
        $patientId = intval($patientId);
        $sql = "SELECT wp.*, u.Name AS TrainerName
                FROM workout_plan wp
                JOIN user u ON u.UserID = wp.TrainerID
                WHERE wp.PatientID='$patientId'
                AND wp.Suggestion IS NOT NULL
                ORDER BY wp.CreatedDate DESC";
        return $this->fetchAll($sql);
    }

    public function getTrainerProfile($trainerId) {
        $trainerId = intval($trainerId);
        $sql = "SELECT u.*, t.CertificationID, t.Expertise, t.YearsOfExperience, t.Specialization, t.AvailableTime 
                FROM user u 
                LEFT JOIN trainer t ON t.Trainer_id = u.UserID 
                WHERE u.UserID='$trainerId'";
        return $this->fetchOne($sql);
    }

    public function updateTrainerProfile($trainerId, $data) {
        $trainerId = intval($trainerId);
        $phone = $this->escape($data['phone']);
        $expertise = $this->escape($data['expertise']);
        $cert = $this->escape($data['certification']);
        $exp = $this->escape($data['experience']);
        $specialization = $this->escape($data['specialization']);
        $available = $this->escape($data['available']);

        $sql1 = "UPDATE user SET PhoneNo='$phone' WHERE UserID='$trainerId'";
        $this->query($sql1);

        $sql2 = "UPDATE trainer SET CertificationID='$cert', Expertise='$expertise', 
                 YearsOfExperience='$exp', Specialization='$specialization', 
                 AvailableTime='$available' WHERE Trainer_id='$trainerId'";
        return $this->query($sql2);
    }

    public function getTrainerSessions($trainerId) {
        $trainerId = intval($trainerId);
        $sql = "SELECT a.*, u.Name 
                FROM appointment a
                JOIN user u ON u.UserID = a.PatientID
                WHERE a.TrainerID='$trainerId'
                ORDER BY a.Date DESC, a.Time DESC";
        return $this->fetchAll($sql);
    }

    public function getTrainerAppointmentWithUsers($appointmentId) {
        $appointmentId = intval($appointmentId);
        $sql = "SELECT a.*, 
                       p.Name AS PatientName, 
                       t.Name AS TrainerName
                FROM appointment a
                JOIN user p ON p.UserID = a.PatientID
                JOIN user t ON t.UserID = a.TrainerID
                WHERE a.AppointmentID='$appointmentId'
                AND a.TrainerID IS NOT NULL";
        return $this->fetchOne($sql);
    }

    public function getTrainerMessages($appointmentId) {
        $appointmentId = intval($appointmentId);
        $sql = "SELECT * FROM trainer_consultation_messages 
                WHERE AppointmentID='$appointmentId' 
                ORDER BY MessageID ASC";
        return $this->fetchAll($sql);
    }

    public function sendTrainerMessage($appointmentId, $senderId, $senderType, $message) {
        $appointmentId = intval($appointmentId);
        $senderId = intval($senderId);
        $senderType = $this->escape($senderType);
        $msgEsc = $this->escape($message);

        $sql = "INSERT INTO trainer_consultation_messages 
                (AppointmentID, SenderID, SenderType, Message)
                VALUES ('$appointmentId', '$senderId', '$senderType', '$msgEsc')";
        return $this->query($sql);
    }

    public function getClientDetailsData($clientId) {
        $clientId = intval($clientId);
        $userSql = "SELECT * FROM user WHERE UserID='$clientId'";
        $client = $this->fetchOne($userSql);

        $reportSql = "SELECT * FROM healthreport 
                      WHERE UserID='$clientId' 
                      ORDER BY ReportID DESC LIMIT 5";
        $reports = $this->fetchAll($reportSql);

        $logSql = "SELECT * FROM dailylog 
                   WHERE UserID='$clientId' 
                   ORDER BY LogID DESC LIMIT 7";
        $logs = $this->fetchAll($logSql);

        return [
            'client' => $client,
            'reports' => $reports,
            'logs' => $logs
        ];
    }
}
