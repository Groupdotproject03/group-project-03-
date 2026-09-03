<?php
/**
 * Support Chat Controller
 */

class SupportChatController extends Controller {
    private $supportModel;

    public function __construct() {
        $this->supportModel = new SupportChatModel();
    }

    public function index() {
        $this->requireAuth(['patient', 'staff']);
        $userId = $this->getUserId();
        $usertype = $this->getUserType();

        $active_patient_id = 0;
        $active_patient = null;

        if ($usertype == 'staff') {
            $active_patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;
            if ($active_patient_id > 0) {
                $active_patient = $this->supportModel->getActivePatientInfo($active_patient_id);
            }
        }

        $this->view('support/chat_support', [
            'usertype' => $usertype,
            'active_patient_id' => $active_patient_id,
            'active_patient' => $active_patient
        ]);
    }

    public function chatStuff() {
        $this->requireAuth(['staff']);
        $this->view('support/chat_stuff');
    }

    public function getMessages() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false]);
        }

        $userId = $this->getUserId();
        $usertype = $this->getUserType();

        if ($usertype == 'patient') {
            $patientId = $userId;
        } elseif ($usertype == 'staff') {
            $patientId = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;
            if ($patientId <= 0) {
                $this->json(['success' => false]);
            }
            $this->supportModel->markAsReadForStaff($patientId);
        } else {
            $this->json(['success' => false]);
        }

        $messages = $this->supportModel->getMessages($patientId);
        $this->json(['success' => true, 'messages' => $messages]);
    }

    public function sendMessage() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false]);
        }

        $userId = $this->getUserId();
        $usertype = $this->getUserType();
        $message = trim($this->post('message', ''));

        if ($message === '') {
            $this->json(['success' => false, 'error' => 'Empty message']);
        }

        if ($usertype == 'patient') {
            $this->supportModel->sendPatientMessage($userId, $message);
            $this->json(['success' => true]);
        }

        if ($usertype == 'staff') {
            $patientId = intval($this->post('patient_id', 0));
            if ($patientId <= 0) {
                $this->json(['success' => false, 'error' => 'No patient selected']);
            }
            $this->supportModel->sendStaffMessage($patientId, $userId, $message);
            $this->json(['success' => true]);
        }

        $this->json(['success' => false]);
    }

    public function getConversations() {
        if (!$this->isLoggedIn() || $this->getUserType() != 'staff') {
            $this->json(['success' => false, 'conversations' => []]);
        }

        $conversations = $this->supportModel->getConversationsList();
        $this->json(['success' => true, 'conversations' => $conversations]);
    }
}
