<?php
/**
 * Consultation Controller
 */

class ConsultationController extends Controller {
    private $consultationModel;

    public function __construct() {
        $this->consultationModel = new ConsultationModel();
    }

    public function index() {
        $this->requireAuth(['patient', 'doctor']);
        $userId = $this->getUserId();
        $usertype = $this->getUserType();

        if ($usertype == 'patient') {
            $consultations = $this->consultationModel->getConsultationsForPatient($userId);
        } else {
            $consultations = $this->consultationModel->getConsultationsForDoctor($userId);
        }

        $this->view('consultation/consultation', [
            'usertype' => $usertype,
            'consultations' => $consultations
        ]);
    }

    public function chat() {
        $this->requireAuth(['patient', 'doctor']);
        $userId = $this->getUserId();
        $usertype = $this->getUserType();
        $appointmentId = intval($this->get('id', 0));

        $appt = $this->consultationModel->getAppointmentDetails($appointmentId);

        if (!$appt || empty($appt['DoctorID'])) {
            $this->redirect('dashboard.php');
        }

        if ($usertype == 'patient' && $appt['PatientID'] != $userId) {
            $this->redirect('dashboard.php');
        }
        if ($usertype == 'doctor' && $appt['DoctorID'] != $userId) {
            $this->redirect('dashboard.php');
        }
        if (!isset($appt['Status']) || $appt['Status'] != 'Accepted') {
            $this->redirect('consultation.php');
        }

        $apptDateTime = strtotime($appt['Date'] . ' ' . $appt['Time']);
        $windowStart  = $apptDateTime - (15 * 60);
        $windowEnd    = $apptDateTime + (60 * 60);
        $nowTs        = time();
        $isActive     = ($nowTs >= $windowStart && $nowTs <= $windowEnd);

        $otherName = ($usertype == 'patient') ? $appt['DoctorName'] : $appt['PatientName'];

        $this->view('consultation/consultation_chat', [
            'appointment_id' => $appointmentId,
            'usertype' => $usertype,
            'appt' => $appt,
            'isActive' => $isActive,
            'otherName' => $otherName
        ]);
    }

    public function getMessages() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false, 'error' => 'Not logged in']);
        }

        $userId = $this->getUserId();
        $usertype = $this->getUserType();
        $appointmentId = intval($this->get('id', 0));

        $appt = $this->consultationModel->getAppointmentDetails($appointmentId);
        if (!$appt) {
            $this->json(['success' => false, 'error' => 'Not found']);
        }

        $authorized = ($usertype == 'patient' && $appt['PatientID'] == $userId)
                   || ($usertype == 'doctor' && $appt['DoctorID'] == $userId);

        if (!$authorized) {
            $this->json(['success' => false, 'error' => 'Not authorized']);
        }

        $messages = $this->consultationModel->getMessages($appointmentId);

        $this->json([
            'success'      => true,
            'messages'     => $messages,
            'meeting_link' => $appt['MeetingLink'],
        ]);
    }

    public function sendMessage() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false, 'error' => 'Not logged in']);
        }

        $userId = $this->getUserId();
        $usertype = $this->getUserType();
        $appointmentId = intval($this->post('appointment_id', 0));

        $appt = $this->consultationModel->getAppointmentDetails($appointmentId);
        if (!$appt) {
            $this->json(['success' => false, 'error' => 'Not found']);
        }

        $authorized = ($usertype == 'patient' && $appt['PatientID'] == $userId)
                   || ($usertype == 'doctor' && $appt['DoctorID'] == $userId);

        if (!$authorized) {
            $this->json(['success' => false, 'error' => 'Not authorized']);
        }

        if (isset($_POST['set_link']) && $usertype == 'doctor') {
            $link = trim($this->post('meeting_link', ''));
            $this->consultationModel->updateMeetingLink($appointmentId, $link);
            $this->json(['success' => true]);
        }

        $message = trim($this->post('message', ''));
        if ($message === '') {
            $this->json(['success' => false, 'error' => 'Empty message']);
        }

        $this->consultationModel->sendMessage($appointmentId, $userId, $usertype, $message);
        $this->json(['success' => true]);
    }
}
