<?php
/**
 * Medical Report Controller
 */

class MedicalReportController extends Controller {
    private $reportModel;

    public function __construct() {
        $this->reportModel = new MedicalReportModel();
    }

    public function index() {
        $this->requireAuth();
        $userId = $this->getUserId();
        $usertype = $this->getUserType();

        $message = "";
        $message_type = "";

        // Patient: Upload report
        if ($usertype == 'patient' && $this->isPost() && isset($_POST['upload_report'])) {
            $receiver_type = $this->post('receiver_type', '');
            $receiver_id   = intval($this->post('receiver_id', 0));
            $report_name   = trim($this->post('report_name', ''));
            $file = $this->files('report_file');

            if ($receiver_id <= 0 || $report_name == '') {
                $message = "Please select a doctor/trainer and enter report name.";
                $message_type = "error";
            } elseif (!$file || $file['error'] != 0) {
                $message = "Please select a medical report file.";
                $message_type = "error";
            } else {
                $validReceiver = $this->reportModel->hasAppointmentWithReceiver($userId, $receiver_id, $receiver_type);

                if (!$validReceiver) {
                    $message = "You can only send reports to a doctor/trainer you have an appointment with.";
                    $message_type = "error";
                } else {
                    $allowed_extensions = ['pdf', 'jpg', 'jpeg', 'png'];
                    $fileName = $file['name'];
                    $tmpName  = $file['tmp_name'];
                    $fileSize = $file['size'];
                    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                    if (!in_array($extension, $allowed_extensions)) {
                        $message = "Only PDF, JPG, JPEG and PNG files are allowed.";
                        $message_type = "error";
                    } elseif ($fileSize > 10 * 1024 * 1024) {
                        $message = "File size must be less than 10 MB.";
                        $message_type = "error";
                    } else {
                        $upload_dir = "Uploads/medical_reports/";
                        if (!is_dir($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }

                        $new_file_name = "report_" . $userId . "_" . time() . "_" . rand(1000, 9999) . "." . $extension;
                        $destination = $upload_dir . $new_file_name;

                        if (move_uploaded_file($tmpName, $destination)) {
                            if ($this->reportModel->uploadReport($userId, $receiver_id, $receiver_type, $report_name, $destination)) {
                                $message = "Medical report sent successfully!";
                                $message_type = "success";
                            } else {
                                $message = "Database error: " . $this->reportModel->error();
                                $message_type = "error";
                            }
                        } else {
                            $message = "Failed to upload file.";
                            $message_type = "error";
                        }
                    }
                }
            }
        }

        // Doctor / Trainer: Add suggestion
        if (($usertype == 'doctor' || $usertype == 'trainer') && $this->isPost() && isset($_POST['add_suggestion'])) {
            $report_id = intval($this->post('report_id', 0));
            $suggestion = trim($this->post('suggestion', ''));

            if ($report_id <= 0 || $suggestion == '') {
                $message = "Please write a suggestion.";
                $message_type = "error";
            } else {
                if (!$this->reportModel->canAddSuggestion($report_id, $userId, $usertype)) {
                    $message = "You are not authorized to update this report.";
                    $message_type = "error";
                } else {
                    if ($this->reportModel->addSuggestion($report_id, $userId, $suggestion)) {
                        $message = "Suggestion added successfully!";
                        $message_type = "success";
                    } else {
                        $message = "Failed to save suggestion.";
                        $message_type = "error";
                    }
                }
            }
        }

        $doctors = [];
        $trainers = [];
        $patient_reports = [];
        $received_reports = [];

        if ($usertype == 'patient') {
            $doctors = $this->reportModel->getPatientDoctors($userId);
            $trainers = $this->reportModel->getPatientTrainers($userId);
            $patient_reports = $this->reportModel->getPatientReports($userId);
        } elseif ($usertype == 'doctor') {
            $received_reports = $this->reportModel->getReceivedReportsForDoctor($userId);
        } elseif ($usertype == 'trainer') {
            $received_reports = $this->reportModel->getReceivedReportsForTrainer($userId);
        }

        $this->view('reports/medical_reports', [
            'usertype' => $usertype,
            'message' => $message,
            'message_type' => $message_type,
            'doctors' => $doctors,
            'trainers' => $trainers,
            'patient_reports' => $patient_reports,
            'received_reports' => $received_reports
        ]);
    }
}
