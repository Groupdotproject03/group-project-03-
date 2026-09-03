<?php
/**
 * Profile Controller
 */

class ProfileController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function index() {
        $this->requireAuth();
        $userId = $this->getUserId();
        $user = $this->userModel->getUserById($userId);
        $usertype = strtolower($user['UserType']);

        $edit = isset($_GET['edit']) ? true : false;

        if ($this->isPost() && isset($_POST['update'])) {
            $this->userModel->updateUser($userId, [
                'name' => $this->post('name'),
                'email' => $this->post('email'),
                'phone' => $this->post('phone'),
                'gender' => $this->post('gender'),
                'dob' => $this->post('dob')
            ]);

            $files = $this->files('photo');
            if ($files && $files['error'] == 0 && $files['name'] != '') {
                $filename = time() . "_profile_" . basename($files['name']);
                $folder = "Uploads/";
                if (!is_dir($folder)) {
                    mkdir($folder, 0755, true);
                }
                $destination = $folder . $filename;
                if (move_uploaded_file($files['tmp_name'], $destination)) {
                    $this->userModel->updateProfilePhoto($userId, $destination);
                }
            }

            if ($usertype == 'doctor') {
                $this->userModel->saveDoctorData($userId, [
                    'license' => $this->post('license', ''),
                    'special' => $this->post('special', ''),
                    'hospital' => $this->post('hospital', ''),
                    'available' => $this->post('available', '')
                ]);
            } elseif ($usertype == 'patient') {
                $this->userModel->savePatientData($userId, [
                    'weight' => $this->post('weight', ''),
                    'height' => $this->post('height', '')
                ]);
            }

            $this->redirect('profile.php');
        }

        $doctor_data = [];
        $patient_data = [];

        if ($usertype == 'doctor') {
            $doctor_data = $this->userModel->getDoctorData($userId) ?? [];
        } elseif ($usertype == 'patient') {
            $patient_data = $this->userModel->getPatientData($userId) ?? [];
        }

        $img = (!empty($user['ProfilePhoto']) && file_exists($user['ProfilePhoto']))
            ? $user['ProfilePhoto']
            : 'Images/default.png';

        $this->view('profile/profile', [
            'user' => $user,
            'usertype' => $usertype,
            'edit' => $edit,
            'doctor_data' => $doctor_data,
            'patient_data' => $patient_data,
            'img' => $img
        ]);
    }

    public function uploadPhoto() {
        $this->requireAuth();
        $userId = $this->getUserId();

        $file = $this->files('photo');
        if ($file && isset($file['name']) && $file['name'] != '') {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newName = "profile_" . $userId . "." . $ext;
            $uploadPath = "Images/" . $newName;

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $this->userModel->updateProfilePhoto($userId, $uploadPath);
                $this->redirect('profile.php');
            } else {
                echo "Upload failed!";
            }
        } else {
            $this->redirect('profile.php');
        }
    }
}
