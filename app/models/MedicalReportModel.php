<?php
/**
 * Medical Report Model
 */

class MedicalReportModel extends Model {

    public function hasAppointmentWithReceiver($patientId, $receiverId, $receiverType) {
        $patientId = intval($patientId);
        $receiverId = intval($receiverId);

        if ($receiverType == 'doctor') {
            $sql = "SELECT AppointmentID FROM appointment
                    WHERE PatientID = '$patientId'
                    AND DoctorID = '$receiverId'
                    LIMIT 1";
        } elseif ($receiverType == 'trainer') {
            $sql = "SELECT AppointmentID FROM appointment
                    WHERE PatientID = '$patientId'
                    AND TrainerID = '$receiverId'
                    LIMIT 1";
        } else {
            return false;
        }

        return $this->fetchOne($sql) !== null;
    }

    public function uploadReport($patientId, $receiverId, $receiverType, $reportName, $filePath) {
        $patientId = intval($patientId);
        $receiverId = intval($receiverId);
        $reportName = $this->escape($reportName);
        $filePath = $this->escape($filePath);

        if ($receiverType == 'doctor') {
            $sql = "INSERT INTO medical_reports
                    (UserID, DoctorID, TrainerID, ReportName, FilePath)
                    VALUES ('$patientId', '$receiverId', NULL, '$reportName', '$filePath')";
        } else {
            $sql = "INSERT INTO medical_reports
                    (UserID, DoctorID, TrainerID, ReportName, FilePath)
                    VALUES ('$patientId', NULL, '$receiverId', '$reportName', '$filePath')";
        }

        return $this->query($sql);
    }

    public function canAddSuggestion($reportId, $userId, $userType) {
        $reportId = intval($reportId);
        $userId = intval($userId);

        if ($userType == 'doctor') {
            $sql = "SELECT ReportID FROM medical_reports
                    WHERE ReportID = '$reportId' AND DoctorID = '$userId'";
        } else {
            $sql = "SELECT ReportID FROM medical_reports
                    WHERE ReportID = '$reportId' AND TrainerID = '$userId'";
        }

        return $this->fetchOne($sql) !== null;
    }

    public function addSuggestion($reportId, $userId, $suggestion) {
        $reportId = intval($reportId);
        $userId = intval($userId);
        $suggestion = $this->escape($suggestion);

        $sql = "UPDATE medical_reports
                SET Suggestion = '$suggestion',
                    SuggestedBy = '$userId',
                    SuggestedAt = NOW()
                WHERE ReportID = '$reportId'";
        return $this->query($sql);
    }

    public function getPatientDoctors($patientId) {
        $patientId = intval($patientId);
        $sql = "SELECT DISTINCT u.UserID, u.Name
                FROM appointment a
                JOIN user u ON u.UserID = a.DoctorID
                WHERE a.PatientID = '$patientId'
                AND a.DoctorID IS NOT NULL
                ORDER BY u.Name ASC";
        return $this->fetchAll($sql);
    }

    public function getPatientTrainers($patientId) {
        $patientId = intval($patientId);
        $sql = "SELECT DISTINCT u.UserID, u.Name
                FROM appointment a
                JOIN user u ON u.UserID = a.TrainerID
                WHERE a.PatientID = '$patientId'
                AND a.TrainerID IS NOT NULL
                ORDER BY u.Name ASC";
        return $this->fetchAll($sql);
    }

    public function getPatientReports($patientId) {
        $patientId = intval($patientId);
        $sql = "SELECT mr.*,
                       d.Name AS DoctorName,
                       t.Name AS TrainerName
                FROM medical_reports mr
                LEFT JOIN user d ON d.UserID = mr.DoctorID
                LEFT JOIN user t ON t.UserID = mr.TrainerID
                WHERE mr.UserID = '$patientId'
                ORDER BY mr.UploadDate DESC";
        return $this->fetchAll($sql);
    }

    public function getReceivedReportsForDoctor($doctorId) {
        $doctorId = intval($doctorId);
        $sql = "SELECT mr.*,
                       u.Name AS PatientName,
                       u.Email AS PatientEmail
                FROM medical_reports mr
                JOIN user u ON u.UserID = mr.UserID
                WHERE mr.DoctorID = '$doctorId'
                ORDER BY mr.UploadDate DESC";
        return $this->fetchAll($sql);
    }

    public function getReceivedReportsForTrainer($trainerId) {
        $trainerId = intval($trainerId);
        $sql = "SELECT mr.*,
                       u.Name AS PatientName,
                       u.Email AS PatientEmail
                FROM medical_reports mr
                JOIN user u ON u.UserID = mr.UserID
                WHERE mr.TrainerID = '$trainerId'
                ORDER BY mr.UploadDate DESC";
        return $this->fetchAll($sql);
    }
}
