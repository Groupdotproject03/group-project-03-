<?php
/**
 * Support Chat Model
 */

class SupportChatModel extends Model {

    public function getActivePatientInfo($patientId) {
        $patientId = intval($patientId);
        $sql = "SELECT u.*, hs.avg_score FROM user u
                LEFT JOIN (
                    SELECT UserID, AVG(Score) AS avg_score FROM healthreport GROUP BY UserID
                ) hs ON hs.UserID = u.UserID
                WHERE u.UserID='$patientId'";
        return $this->fetchOne($sql);
    }

    public function markAsReadForStaff($patientId) {
        $patientId = intval($patientId);
        $sql = "UPDATE support_messages SET IsRead=1 WHERE PatientID='$patientId' AND SenderType='patient'";
        return $this->query($sql);
    }

    public function getMessages($patientId) {
        $patientId = intval($patientId);
        $sql = "SELECT * FROM support_messages WHERE PatientID='$patientId' ORDER BY MessageID ASC";
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

    public function sendPatientMessage($patientId, $messageText) {
        $patientId = intval($patientId);
        $enc = $this->escape(encrypt_message($messageText));
        $sql = "INSERT INTO support_messages (PatientID, StaffID, SenderType, MessageEnc, IsRead)
                VALUES ('$patientId', NULL, 'patient', '$enc', 0)";
        return $this->query($sql);
    }

    public function sendStaffMessage($patientId, $staffId, $messageText) {
        $patientId = intval($patientId);
        $staffId = intval($staffId);
        $enc = $this->escape(encrypt_message($messageText));
        $sql = "INSERT INTO support_messages (PatientID, StaffID, SenderType, MessageEnc, IsRead)
                VALUES ('$patientId', '$staffId', 'staff', '$enc', 1)";
        return $this->query($sql);
    }

    public function getConversationsList() {
        $sql = "SELECT sm.PatientID, u.Name,
                       MAX(sm.CreatedAt) AS LastAt,
                       SUM(CASE WHEN sm.SenderType='patient' AND sm.IsRead=0 THEN 1 ELSE 0 END) AS Unread
                FROM support_messages sm
                JOIN user u ON u.UserID = sm.PatientID
                GROUP BY sm.PatientID
                ORDER BY LastAt DESC";
        $rows = $this->fetchAll($sql);

        $conversations = [];
        foreach ($rows as $row) {
            $pid = intval($row['PatientID']);
            $lastSql = "SELECT MessageEnc FROM support_messages WHERE PatientID='$pid' ORDER BY MessageID DESC LIMIT 1";
            $lastRow = $this->fetchOne($lastSql);
            $preview = $lastRow ? decrypt_message($lastRow['MessageEnc']) : '';

            $conversations[] = [
                'patient_id' => $pid,
                'name'       => $row['Name'],
                'preview'    => mb_strimwidth($preview, 0, 40, '...'),
                'unread'     => intval($row['Unread']),
            ];
        }
        return $conversations;
    }
}
