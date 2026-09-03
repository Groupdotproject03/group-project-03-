<?php
/**
 * Appointment Model
 */

class AppointmentModel extends Model {

    public function getTodayDoctorAppointmentsForPatient($patientId, $todayDate) {
        $patientId = intval($patientId);
        $todayDate = $this->escape($todayDate);
        $sql = "SELECT a.Time, u.Name AS DoctorName
                FROM appointment a
                JOIN user u ON u.UserID = a.DoctorID
                WHERE a.PatientID='$patientId'
                AND a.DoctorID IS NOT NULL
                AND a.Date='$todayDate'
                ORDER BY a.Time ASC";
        return $this->fetchAll($sql);
    }

    public function getTodayTrainerAppointmentsForPatient($patientId, $todayDate) {
        $patientId = intval($patientId);
        $todayDate = $this->escape($todayDate);
        $sql = "SELECT a.Time, u.Name AS TrainerName
                FROM appointment a
                JOIN user u ON u.UserID = a.TrainerID
                WHERE a.PatientID='$patientId'
                AND a.TrainerID IS NOT NULL
                AND a.Date='$todayDate'
                ORDER BY a.Time ASC";
        return $this->fetchAll($sql);
    }

    public function getTodayDoctorAppointments($doctorId, $todayDate) {
        $doctorId = intval($doctorId);
        $todayDate = $this->escape($todayDate);
        $sql = "SELECT a.Time, u.Name AS PatientName
                FROM appointment a
                JOIN user u ON u.UserID = a.PatientID
                WHERE a.DoctorID='$doctorId'
                AND a.Date='$todayDate'
                ORDER BY a.Time ASC";
        return $this->fetchAll($sql);
    }

    public function getDoctorList() {
        $sql = "SELECT u.UserID, u.Name, u.Email, u.PhoneNo,
                       d.Specialization, d.HospitalName, d.AvailableTime
                FROM user u
                JOIN doctor d ON u.UserID = d.Doctor_id
                WHERE u.UserType='doctor'
                ORDER BY d.Specialization ASC";
        return $this->fetchAll($sql);
    }

    public function getBookedTrainerSlots($trainerId, $date) {
        $trainerId = intval($trainerId);
        $date = $this->escape($date);
        $sql = "SELECT Time FROM appointment 
                WHERE TrainerID='$trainerId' 
                AND Date='$date'";
        $rows = $this->fetchAll($sql);
        $booked = [];
        foreach ($rows as $r) {
            $booked[] = $r['Time'];
        }
        return $booked;
    }

    public function bookDoctor($patientId, $doctorId, $date, $time, $medication = 'No', $reportPath = null) {
        $patientId = intval($patientId);
        $doctorId = intval($doctorId);
        $date = $this->escape($date);
        $time = $this->escape($time);
        $medication = $this->escape($medication);
        $reportVal = $reportPath ? "'" . $this->escape($reportPath) . "'" : "NULL";

        $sql = "INSERT INTO appointment (PatientID, DoctorID, Date, Time, Medication, ReportFile)
                VALUES ('$patientId','$doctorId','$date','$time','$medication',$reportVal)";
        return $this->query($sql);
    }

    public function getPatientDoctorAppointments($patientId) {
        $patientId = intval($patientId);
        $sql = "SELECT a.AppointmentID,a.DoctorID,a.Date,a.Time,a.Medication,a.Status,
                       u.Name AS DoctorName
                FROM appointment a
                JOIN user u ON u.UserID=a.DoctorID
                WHERE a.PatientID='$patientId'
                AND a.DoctorID IS NOT NULL
                ORDER BY a.Date DESC,a.Time DESC";
        return $this->fetchAll($sql);
    }

    public function getPatientTrainerAppointments($patientId) {
        $patientId = intval($patientId);
        $sql = "SELECT a.AppointmentID,a.TrainerID,a.Date,a.Time,a.Status,
                       u.Name AS TrainerName
                FROM appointment a
                JOIN user u ON u.UserID=a.TrainerID
                WHERE a.PatientID='$patientId'
                AND a.TrainerID IS NOT NULL
                ORDER BY a.Date DESC,a.Time DESC";
        return $this->fetchAll($sql);
    }

    public function getDoctorAppointmentsList($doctorId) {
        $doctorId = intval($doctorId);
        $sql = "SELECT a.*, u.Name AS PatientName
                FROM appointment a
                JOIN user u ON u.UserID=a.PatientID
                WHERE a.DoctorID='$doctorId'
                ORDER BY a.Date ASC,a.Time ASC";
        return $this->fetchAll($sql);
    }

    public function acceptDoctorAppointment($appointmentId, $doctorId) {
        $appointmentId = intval($appointmentId);
        $doctorId = intval($doctorId);
        $sql = "UPDATE appointment SET Status='Accepted'
                WHERE AppointmentID='$appointmentId' AND DoctorID='$doctorId'";
        return $this->query($sql);
    }

    public function rejectDoctorAppointment($appointmentId, $doctorId) {
        $appointmentId = intval($appointmentId);
        $doctorId = intval($doctorId);
        $sql = "DELETE FROM appointment
                WHERE AppointmentID='$appointmentId' AND DoctorID='$doctorId'";
        return $this->query($sql);
    }

    public function getDoctorAvailability($doctorId) {
        $doctorId = intval($doctorId);
        $sql = "SELECT * FROM doctor WHERE Doctor_id='$doctorId'";
        return $this->fetchOne($sql);
    }

    public function saveDoctorAvailability($doctorId, $data) {
        $doctorId = intval($doctorId);
        $license = $this->escape($data['license']);
        $time = $this->escape($data['time']);
        $hospital = $this->escape($data['hospital']);
        $special = $this->escape($data['special']);

        $check = $this->getDoctorAvailability($doctorId);
        if ($check) {
            $sql = "UPDATE doctor SET
                    LicenceNo='$license',
                    AvailableTime='$time',
                    HospitalName='$hospital',
                    Specialization='$special'
                    WHERE Doctor_id='$doctorId'";
        } else {
            $sql = "INSERT INTO doctor (Doctor_id, LicenceNo, AvailableTime, HospitalName, Specialization)
                    VALUES ('$doctorId','$license','$time','$hospital','$special')";
        }
        return $this->query($sql);
    }
}
