<?php
/**
 * Appointment Controller
 */

class AppointmentController extends Controller {
    private $appointmentModel;

    public function __construct() {
        $this->appointmentModel = new AppointmentModel();
    }

    public function bookDoctor() {
        $this->requireAuth();
        $userId = $this->getUserId();
        $message = "";

        if ($this->isPost() && isset($_POST['book'])) {
            $doctorId = $this->post('doctor_id');
            $date = $this->post('date');
            $time = $this->post('time');
            $medication = $this->post('medication', 'No');
            $reportPath = null;

            $file = $this->files('report');
            if ($file && $file['error'] == 0 && !empty($file['tmp_name'])) {
                $allowed = ['application/pdf'];
                $ftype = mime_content_type($file['tmp_name']);

                if (in_array($ftype, $allowed)) {
                    $uploadDir = 'uploads/reports/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $filename = time() . '_' . basename($file['name']);
                    $destination = $uploadDir . $filename;

                    if (move_uploaded_file($file['tmp_name'], $destination)) {
                        $reportPath = $destination;
                    }
                } else {
                    $message = "Only PDF files are allowed!";
                }
            }

            if ($message == "") {
                if ($this->appointmentModel->bookDoctor($userId, $doctorId, $date, $time, $medication, $reportPath)) {
                    $message = "Appointment Booked!";
                } else {
                    $message = "Error! " . $this->appointmentModel->error();
                }
            }
        }

        $doctors = $this->appointmentModel->getDoctorList();

        $this->view('appointments/book_appointments', [
            'message' => $message,
            'doctors' => $doctors
        ]);
    }

    public function getBookSlot() {
        $trainerId = intval($this->get('trainer_id', 0));
        $date = $this->get('date', '');

        $booked = $this->appointmentModel->getBookedTrainerSlots($trainerId, $date);
        $this->json($booked);
    }

    public function viewAppointments() {
        $this->requireAuth();
        $userId = $this->getUserId();

        $doctorAppointments = $this->appointmentModel->getPatientDoctorAppointments($userId);
        $trainerAppointments = $this->appointmentModel->getPatientTrainerAppointments($userId);

        $this->view('appointments/view_appointments', [
            'doctorAppointments' => $doctorAppointments,
            'trainerAppointments' => $trainerAppointments
        ]);
    }

    public function history() {
        $this->requireAuth();
        $userId = $this->getUserId();

        $doctorHistory = $this->appointmentModel->getPatientDoctorAppointments($userId);
        $trainerHistory = $this->appointmentModel->getPatientTrainerAppointments($userId);

        $this->view('appointments/appointment_history', [
            'doctorHistory' => $doctorHistory,
            'trainerHistory' => $trainerHistory
        ]);
    }

    public function doctorAppointments() {
        $this->requireAuth(['doctor']);
        $doctorId = $this->getUserId();

        if (isset($_GET['accept'])) {
            $aid = intval($_GET['accept']);
            $this->appointmentModel->acceptDoctorAppointment($aid, $doctorId);
            $this->redirect('doctor_appointment.php');
        }

        if (isset($_GET['reject'])) {
            $aid = intval($_GET['reject']);
            $this->appointmentModel->rejectDoctorAppointment($aid, $doctorId);
            $this->redirect('doctor_appointment.php');
        }

        $appointments = $this->appointmentModel->getDoctorAppointmentsList($doctorId);

        $this->view('appointments/doctor_appointment', [
            'appointments' => $appointments
        ]);
    }

    public function doctorAvailability() {
        $this->requireAuth();
        $doctorId = $this->getUserId();
        $msg = "";

        if ($this->isPost() && isset($_POST['save'])) {
            $data = [
                'license' => $this->post('license', ''),
                'time' => $this->post('time', ''),
                'hospital' => $this->post('hospital', ''),
                'special' => $this->post('special', '')
            ];

            if ($this->appointmentModel->saveDoctorAvailability($doctorId, $data)) {
                $msg = "Updated Successfully!";
            } else {
                $msg = "Error: " . $this->appointmentModel->error();
            }
        }

        $data = $this->appointmentModel->getDoctorAvailability($doctorId) ?? [];

        $this->view('appointments/doctor_availability', [
            'doctor_id' => $doctorId,
            'data' => $data,
            'msg' => $msg
        ]);
    }
}
