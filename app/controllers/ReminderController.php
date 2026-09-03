<?php
/**
 * Reminder Controller
 */

class ReminderController extends Controller {
    private $reminderModel;

    public function __construct() {
        $this->reminderModel = new ReminderModel();
    }

    public function index() {
        $this->requireAuth(['patient']);
        $userId = $this->getUserId();
        $success = "";

        if ($this->isPost() && isset($_POST['save'])) {
            $type = $this->post('type', '');
            $day  = $this->post('day', '');
            $time = $this->post('time', '');

            if ($this->reminderModel->addReminder($userId, $type, $day, $time)) {
                $success = "Reminder Added Successfully!";
            }
        }

        $this->view('reminder/reminder', [
            'success' => $success,
        ]);
    }
}
