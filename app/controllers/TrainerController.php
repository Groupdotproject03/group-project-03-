<?php
/**
 * Trainer Controller
 */

class TrainerController extends Controller {
    private $trainerModel;
    private $userModel;

    public function __construct() {
        $this->trainerModel = new TrainerModel();
        $this->userModel = new UserModel();
    }

    public function bookTrainer() {
        $this->requireAuth(['patient']);
        $userId = $this->getUserId();
        $success = "";
        $error = "";

        if ($this->isPost()) {
            $trainerId = intval($this->post('trainer_id', 0));
            $date = $this->post('date', '');
            $slotTime = $this->post('slot_time', '');

            if ($this->trainerModel->isSlotBooked($trainerId, $date, $slotTime)) {
                $error = "This slot is already booked! Please choose another.";
            } else {
                if ($this->trainerModel->bookTrainerAppointment($userId, $trainerId, $date, $slotTime)) {
                    $success = "Appointment booked successfully!";
                } else {
                    $error = "Error booking appointment.";
                }
            }
        }

        $trainers = $this->trainerModel->getAllTrainers();

        // Attach slots to each trainer
        foreach ($trainers as &$t) {
            $t['slots'] = $this->trainerModel->getTrainerSlots($t['Trainer_id']);
        }

        $this->view('trainer/trainer', [
            'trainers' => $trainers,
            'success'  => $success,
            'error'    => $error,
        ]);
    }

    public function trainerSlots() {
        $this->requireAuth(['trainer']);
        $trainerId = $this->getUserId();
        $message = "";

        if (isset($_GET['delete'])) {
            $slotId = intval($_GET['delete']);
            $this->trainerModel->deleteTrainerSlot($trainerId, $slotId);
            $this->redirect('trainer_slots.php');
        }

        if ($this->isPost() && isset($_POST['add_slot'])) {
            $slotTime = $this->post('slot_time', '');
            if ($this->trainerModel->slotExists($trainerId, $slotTime)) {
                $message = "error|This slot already exists!";
            } else {
                if ($this->trainerModel->addTrainerSlot($trainerId, $slotTime)) {
                    $message = "success|Slot added successfully!";
                } else {
                    $message = "error|Failed to add slot.";
                }
            }
        }

        $slots = $this->trainerModel->getTrainerSlots($trainerId);

        $msgType = "";
        $msgText = "";
        if ($message != "") {
            [$msgType, $msgText] = explode("|", $message);
        }

        $allSlots = [
            '08:00 AM','09:00 AM','10:00 AM','11:00 AM',
            '12:00 PM','01:00 PM','02:00 PM','03:00 PM',
            '04:00 PM','05:00 PM','06:00 PM','07:00 PM'
        ];

        $existing = array_column($slots, 'SlotTime');

        $this->view('trainer/trainer_slots', [
            'slots'    => $slots,
            'allSlots' => $allSlots,
            'existing' => $existing,
            'msgType'  => $msgType,
            'msgText'  => $msgText,
        ]);
    }

    public function trainerAppointment() {
        $this->requireAuth(['trainer']);
        $trainerId = $this->getUserId();

        if (isset($_GET['delete'])) {
            $apptId = intval($_GET['delete']);
            $this->trainerModel->deleteTrainerAppointment($trainerId, $apptId);
            $this->redirect('trainer_appointment.php');
        }

        $appointments = $this->trainerModel->getTrainerAppointments($trainerId);

        $this->view('trainer/trainer_appointment', [
            'appointments' => $appointments,
        ]);
    }

    public function trainerClients() {
        $this->requireAuth(['trainer']);
        $trainerId = $this->getUserId();
        $clients = $this->trainerModel->getTrainerClients($trainerId);

        $this->view('trainer/trainer_clients', [
            'clients' => $clients,
        ]);
    }

    public function clientDetails() {
        $this->requireAuth(['trainer']);
        $clientId = intval($this->get('id', 0));
        if ($clientId <= 0) {
            $this->redirect('trainer_clients.php');
        }
        $data = $this->trainerModel->getClientDetailsData($clientId);
        $this->view('trainer/client_details', [
            'client'  => $data['client'],
            'reports' => $data['reports'],
            'logs'    => $data['logs'],
        ]);
    }

    public function trainerWorkout() {
        $this->requireAuth(['trainer']);
        $trainerId = $this->getUserId();
        $msg = "";

        if ($this->isPost()) {
            $clientId  = intval($this->post('client_id', 0));
            $suggestion = trim($this->post('suggestion', ''));
            if ($clientId > 0 && $suggestion !== '') {
                $this->trainerModel->addWorkoutSuggestion($trainerId, $clientId, $suggestion);
                $msg = "Suggestion sent successfully!";
            }
        }

        $clients = $this->trainerModel->getTrainerClients($trainerId);
        $history = $this->trainerModel->getTrainerSuggestionsHistory($trainerId);

        $this->view('trainer/trainer_workout', [
            'clients' => $clients,
            'history' => $history,
            'msg'     => $msg,
        ]);
    }

    public function trainerNotes() {
        $this->requireAuth(['trainer']);
        $trainerId = $this->getUserId();
        $msg = "";

        if ($this->isPost()) {
            $clientId   = intval($this->post('client_id', 0));
            $suggestion = trim($this->post('suggestion', ''));
            if ($clientId > 0 && $suggestion !== '') {
                $this->trainerModel->addWorkoutSuggestion($trainerId, $clientId, $suggestion);
                $msg = "Note sent successfully!";
            }
        }

        $clients = $this->trainerModel->getTrainerClients($trainerId);
        $history = $this->trainerModel->getTrainerSuggestionsHistory($trainerId);

        $this->view('trainer/trainer_notes', [
            'clients' => $clients,
            'history' => $history,
            'msg'     => $msg,
        ]);
    }

    public function trainerProfile() {
        $this->requireAuth(['trainer']);
        $trainerId = $this->getUserId();
        $success = "";

        if ($this->isPost()) {
            $data = [
                'phone'          => $this->post('phone', ''),
                'expertise'      => $this->post('expertise', ''),
                'certification'  => $this->post('certification', ''),
                'experience'     => $this->post('experience', ''),
                'specialization' => $this->post('specialization', ''),
                'available'      => $this->post('available', ''),
            ];
            $this->trainerModel->updateTrainerProfile($trainerId, $data);

            $file = $this->files('photo');
            if ($file && $file['size'] > 0) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newName = "profile_" . $trainerId . "." . $ext;
                $uploadPath = "Images/" . $newName;
                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    $this->userModel->updateProfilePhoto($trainerId, $uploadPath);
                }
            }

            $success = "Profile updated successfully!";
        }

        $trainer = $this->trainerModel->getTrainerProfile($trainerId);

        $this->view('trainer/trainer_profile', [
            'trainer' => $trainer,
            'success' => $success,
        ]);
    }

    public function trainerSessions() {
        $this->requireAuth(['trainer']);
        $trainerId = $this->getUserId();
        $sessions = $this->trainerModel->getTrainerSessions($trainerId);

        $this->view('trainer/trainer_sessions', [
            'sessions' => $sessions,
        ]);
    }

    public function viewSuggestions() {
        $this->requireAuth(['patient']);
        $userId = $this->getUserId();
        $suggestions = $this->trainerModel->getPatientSuggestions($userId);

        $this->view('trainer/view_suggestions', [
            'suggestions' => $suggestions,
        ]);
    }

    public function chat() {
        $this->requireAuth(['patient', 'trainer']);
        $userId = $this->getUserId();
        $usertype = $this->getUserType();
        $appointmentId = intval($this->get('id', 0));

        $appt = $this->trainerModel->getTrainerAppointmentWithUsers($appointmentId);

        if (!$appt) {
            $this->redirect('dashboard.php');
        }

        if ($usertype == 'patient' && $appt['PatientID'] != $userId) {
            $this->redirect('dashboard.php');
        }
        if ($usertype == 'trainer' && $appt['TrainerID'] != $userId) {
            $this->redirect('dashboard.php');
        }

        $apptDateTime = strtotime($appt['Date'] . ' ' . $appt['Time']);
        $windowStart  = $apptDateTime - (15 * 60);
        $windowEnd    = $apptDateTime + (60 * 60);
        $isActive     = (time() >= $windowStart && time() <= $windowEnd);

        $otherName = ($usertype == 'patient') ? $appt['TrainerName'] : $appt['PatientName'];

        $this->view('trainer/trainer_chat', [
            'appointment_id' => $appointmentId,
            'usertype'       => $usertype,
            'appt'           => $appt,
            'isActive'       => $isActive,
            'otherName'      => $otherName,
        ]);
    }

    public function getMessages() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false]);
        }

        $userId = $this->getUserId();
        $usertype = $this->getUserType();
        $appointmentId = intval($this->get('id', 0));

        $appt = $this->trainerModel->getTrainerAppointmentWithUsers($appointmentId);
        if (!$appt) {
            $this->json(['success' => false]);
        }

        $authorized = ($usertype == 'patient' && $appt['PatientID'] == $userId)
                   || ($usertype == 'trainer' && $appt['TrainerID'] == $userId);
        if (!$authorized) {
            $this->json(['success' => false]);
        }

        $rows = $this->trainerModel->getTrainerMessages($appointmentId);
        $messages = [];
        foreach ($rows as $row) {
            $messages[] = [
                'sender_type' => $row['SenderType'],
                'text'        => $row['Message'],
                'time'        => date('h:i A', strtotime($row['CreatedAt'])),
            ];
        }

        $this->json(['success' => true, 'messages' => $messages]);
    }

    public function sendMessage() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false]);
        }

        $userId = $this->getUserId();
        $usertype = $this->getUserType();
        $appointmentId = intval($this->post('appointment_id', 0));
        $message = trim($this->post('message', ''));

        if ($message === '') {
            $this->json(['success' => false, 'error' => 'Empty message']);
        }

        $appt = $this->trainerModel->getTrainerAppointmentWithUsers($appointmentId);
        if (!$appt) {
            $this->json(['success' => false]);
        }

        $authorized = ($usertype == 'patient' && $appt['PatientID'] == $userId)
                   || ($usertype == 'trainer' && $appt['TrainerID'] == $userId);
        if (!$authorized) {
            $this->json(['success' => false]);
        }

        $this->trainerModel->sendTrainerMessage($appointmentId, $userId, $usertype, $message);
        $this->json(['success' => true]);
    }

    public function getBookedSlots() {
        $trainerId = intval($this->get('trainer_id', 0));
        $date = $this->get('date', '');

        $slots = $this->trainerModel->getTrainerSlots($trainerId);
        $booked = [];
        foreach ($slots as $slot) {
            if ($this->trainerModel->isSlotBooked($trainerId, $date, $slot['SlotTime'])) {
                $booked[] = $slot['SlotTime'];
            }
        }
        $this->json($booked);
    }
}
