<?php
/**
 * User Model
 */

class UserModel extends Model {

    public function authenticate($email, $password) {
        $email = $this->escape($email);
        $password = $this->escape($password);
        $sql = "SELECT * FROM user WHERE Email='$email' AND Password='$password' LIMIT 1";
        return $this->fetchOne($sql);
    }

    public function emailExists($email) {
        $email = $this->escape($email);
        $sql = "SELECT UserID FROM user WHERE Email='$email' LIMIT 1";
        return $this->fetchOne($sql) !== null;
    }

    public function register($data) {
        $name = $this->escape($data['name']);
        $gender = $this->escape($data['gender']);
        $profile_photo = 'default.png';
        $joining_date = date('Y-m-d');
        $email = $this->escape($data['email']);
        $phone = $this->escape($data['phone']);
        $dob = $this->escape($data['dob']);
        $password = $this->escape($data['password']);
        $usertype = $this->escape($data['usertype']);

        $sql = "INSERT INTO user (Name, Gender, ProfilePhoto, JoiningDate, Email, PhoneNo, DateOfBirth, Password, UserType)
                VALUES ('$name', '$gender', '$profile_photo', '$joining_date', '$email', '$phone', '$dob', '$password', '$usertype')";

        if ($this->query($sql)) {
            $newId = $this->insertId();

            if ($usertype == 'Trainer') {
                $this->query("INSERT INTO trainer (Trainer_id) VALUES ('$newId')");
            } elseif ($usertype == 'Doctor') {
                $this->query("INSERT INTO doctor (Doctor_id) VALUES ('$newId')");
            } elseif ($usertype == 'Patient') {
                $this->query("INSERT INTO patient (Patient_id) VALUES ('$newId')");
            } elseif ($usertype == 'Staff') {
                $this->query("INSERT INTO staff (Staff_id) VALUES ('$newId')");
            }

            return $newId;
        }

        return false;
    }

    public function getUserById($userId) {
        $userId = intval($userId);
        $sql = "SELECT * FROM user WHERE UserID='$userId'";
        return $this->fetchOne($sql);
    }

    public function updateUser($userId, $data) {
        $userId = intval($userId);
        $name = $this->escape($data['name']);
        $email = $this->escape($data['email']);
        $phone = $this->escape($data['phone']);
        $gender = $this->escape($data['gender']);
        $dob = $this->escape($data['dob']);

        $sql = "UPDATE user SET
                Name='$name',
                Email='$email',
                PhoneNo='$phone',
                Gender='$gender',
                DateOfBirth='$dob'
                WHERE UserID='$userId'";
        return $this->query($sql);
    }

    public function updateProfilePhoto($userId, $path) {
        $userId = intval($userId);
        $path = $this->escape($path);
        $sql = "UPDATE user SET ProfilePhoto='$path' WHERE UserID='$userId'";
        return $this->query($sql);
    }

    public function getDoctorData($userId) {
        $userId = intval($userId);
        $sql = "SELECT * FROM doctor WHERE Doctor_id='$userId'";
        return $this->fetchOne($sql);
    }

    public function saveDoctorData($userId, $data) {
        $userId = intval($userId);
        $license = $this->escape($data['license']);
        $special = $this->escape($data['special']);
        $hospital = $this->escape($data['hospital']);
        $available = $this->escape($data['available']);

        $check = $this->getDoctorData($userId);
        if ($check) {
            $sql = "UPDATE doctor SET
                    LicenceNo='$license',
                    Specialization='$special',
                    HospitalName='$hospital',
                    AvailableTime='$available'
                    WHERE Doctor_id='$userId'";
        } else {
            $sql = "INSERT INTO doctor (Doctor_id, LicenceNo, AvailableTime, HospitalName, Specialization)
                    VALUES ('$userId', '$license', '$available', '$hospital', '$special')";
        }
        return $this->query($sql);
    }

    public function getPatientData($userId) {
        $userId = intval($userId);
        $sql = "SELECT * FROM patient WHERE Patient_id='$userId'";
        return $this->fetchOne($sql);
    }

    public function savePatientData($userId, $data) {
        $userId = intval($userId);
        $weight = $this->escape($data['weight']);
        $height = $this->escape($data['height']);

        $check = $this->getPatientData($userId);
        if ($check) {
            $sql = "UPDATE patient SET
                    Weight='$weight',
                    Height='$height'
                    WHERE Patient_id='$userId'";
        } else {
            $sql = "INSERT INTO patient (Patient_id, Weight, Height)
                    VALUES ('$userId', '$weight', '$height')";
        }
        return $this->query($sql);
    }
}
