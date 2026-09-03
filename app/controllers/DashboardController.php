<?php
/**
 * Dashboard Controller
 */

class DashboardController extends Controller {
    private $userModel;
    private $dailyLogModel;
    private $appointmentModel;
    private $trainerModel;
    private $sampleModel;
    private $reminderModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->dailyLogModel = new DailyLogModel();
        $this->appointmentModel = new AppointmentModel();
        $this->trainerModel = new TrainerModel();
        $this->sampleModel = new SampleModel();
        $this->reminderModel = new ReminderModel();
    }

    public function index() {
        $this->requireAuth();
        $userId = $this->getUserId();
        $usertype = $this->getUserType();
        $today = date("Y-m-d");

        $user = $this->userModel->getUserById($userId);

        $img = (!empty($user['ProfilePhoto']) && file_exists($user['ProfilePhoto']))
            ? $user['ProfilePhoto']
            : 'Images/default.png';

        // Patient metrics
        $avgScore = 0;
        $message = "";
        $reminders = [];
        $todayDoctorAppointments = [];
        $todayTrainerAppointments = [];

        if ($usertype == 'patient') {
            $avgScore = $this->dailyLogModel->getAverageScore($userId);

            if ($avgScore >= 80) {
                $message = "Very Good 👍 Keep maintaining your healthy lifestyle!";
            } elseif ($avgScore >= 60) {
                $message = "Going Good 🙂 but you need more care and consistency.";
            } elseif ($avgScore >= 30) {
                $message = "Not Good ⚠️ Focus on your health and improve daily habits.";
            } else {
                $message = "Very disappointing 💔 Start improving step by step.";
            }

            $reminders = $this->reminderModel->getTodayReminders($userId, $today);
            $todayDoctorAppointments = $this->appointmentModel->getTodayDoctorAppointmentsForPatient($userId, $today);
            $todayTrainerAppointments = $this->appointmentModel->getTodayTrainerAppointmentsForPatient($userId, $today);
        }

        // Trainer metrics
        $totalClients = 0;
        $totalAppointments = 0;
        $todayTrainerSchedule = [];

        if ($usertype == 'trainer') {
            $stats = $this->trainerModel->getTrainerStats($userId);
            $totalClients = $stats['totalClients'];
            $totalAppointments = $stats['totalAppointments'];
            $todayTrainerSchedule = $this->trainerModel->getTodayTrainerAppointments($userId, $today);
        }

        // Doctor metrics
        $doctorTodayAppointments = [];
        $doctorTodayTotal = 0;

        if ($usertype == 'doctor') {
            $doctorTodayAppointments = $this->appointmentModel->getTodayDoctorAppointments($userId, $today);
            $doctorTodayTotal = count($doctorTodayAppointments);
        }

        // Staff metrics
        $pendingSamples = 0;
        $totalReviewedByMe = 0;

        if ($usertype == 'staff') {
            $pendingSamples = $this->sampleModel->getPendingCount();
            $totalReviewedByMe = $this->sampleModel->getReviewedByStaffCount($userId);
        }

        $this->view('dashboard/dashboard', [
            'user' => $user,
            'usertype' => $usertype,
            'img' => $img,
            'avgScore' => $avgScore,
            'message' => $message,
            'reminders' => $reminders,
            'todayDoctorAppointments' => $todayDoctorAppointments,
            'todayTrainerAppointments' => $todayTrainerAppointments,
            'totalClients' => $totalClients,
            'totalAppointments' => $totalAppointments,
            'todayTrainerSchedule' => $todayTrainerSchedule,
            'doctorTodayAppointments' => $doctorTodayAppointments,
            'doctorTodayTotal' => $doctorTodayTotal,
            'pendingSamples' => $pendingSamples,
            'totalReviewedByMe' => $totalReviewedByMe
        ]);
    }
}
