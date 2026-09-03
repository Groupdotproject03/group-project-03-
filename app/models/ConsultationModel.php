<?php
/**
 * Consultation Model
 */

class ConsultationModel extends Model {

    public function getConsultationsForPatient($patientId) {
        $patientId = intval($patientId);
        $sql = "SELECT a.AppointmentID, a.Date, a.Time, a.Status, a.MeetingLink, u.Name AS OtherName
                FROM appointment a
                JOIN user u ON u.UserID = a.DoctorID
                WHERE a.PatientID='$patientId' AND a.DoctorID IS NOT NULL
                ORDER BY a.Date DESC, a.Time DESC";
        return $this->fetchAll($sql);
    }

    public function getConsultationsForDoctor($doctorId) {
        $doctorId = intval($doctorId);
        $sql = "SELECT a.AppointmentID, a.Date, a.Time, a.Status, a.MeetingLink, u.Name AS OtherName
                FROM appointment a
                JOIN user u ON u.UserID = a.PatientID
                WHERE a.DoctorID='$doctorId'
                ORDER BY a.Date DESC, a.Time DESC";
        return $this->fetchAll($sql);
    }

    public function getAppointmentDetails($appointmentId) {
        $appointmentId = intval($appointmentId);
        $sql = "SELECT a.*, 
                       p.Name AS PatientName, 
                       d.Name AS DoctorName, 
                       doc.Specialization
                FROM appointment a
                JOIN user p ON p.UserID = a.PatientID
                JOIN user d ON d.UserID = a.DoctorID
                LEFT JOIN doctor doc ON doc.Doctor_id = a.DoctorID
                WHERE a.AppointmentID='$appointmentId'";
        return $this->fetchOne($sql);
    }

    public function getMessages($appointmentId) {
        $appointmentId = intval($appointmentId);
        $sql = "SELECT * FROM consultation_messages WHERE AppointmentID='$appointmentId' ORDER BY MessageID ASC";
        $rows = $this->fetchAll($sql);
        $messages = [];
        foreach ($rows as $row) {
            $messages[] = [
                'sender_type' => $row['SenderType'],
                'text'        => decrypt_message($row['MessageEnc']),
                'time'        => date('h:i A', strtotime($row['CreatedAt'])),
            ];
        }
        return $messages;
    }

    public function sendMessage($appointmentId, $senderId, $senderType, $messageText) {
        $appointmentId = intval($appointmentId);
        $senderId = intval($senderId);
        $senderType = $this->escape($senderType);
        $enc = encrypt_message($messageText);
        $encEsc = $this->escape($enc);

        $sql = "INSERT INTO consultation_messages (AppointmentID, SenderID, SenderType, MessageEnc)
                VALUES ('$appointmentId', '$senderId', '$senderType', '$encEsc')";
        return $this->query($sql);
    }

    public function updateMeetingLink($appointmentId, $meetingLink) {
        $appointmentId = intval($appointmentId);
        $linkEsc = $this->escape($meetingLink);
        $sql = "UPDATE appointment SET MeetingLink='$linkEsc' WHERE AppointmentID='$appointmentId'";
        return $this->query($sql);
    }
}
