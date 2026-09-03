<?php
/**
 * Daily Log Controller
 */

class DailyLogController extends Controller {
    private $dailyLogModel;

    public function __construct() {
        $this->dailyLogModel = new DailyLogModel();
    }

    public function index() {
        $this->requireAuth();
        $userId = $this->getUserId();
        $todayDate = date("Y-m-d");

        $alreadyLogged = $this->dailyLogModel->isLoggedToday($userId, $todayDate);
        $submitted = false;
        $resultData = [];
        $sys = 0;
        $dia = 0;
        $sugar = 0;

        if ($this->isPost() && isset($_POST['done'])) {
            $this->redirect('dailylog.php');
        }

        if ($this->isPost() && isset($_POST['submit']) && !$alreadyLogged) {
            $sys = intval($this->post('bp_systolic', 0));
            $dia = intval($this->post('bp_diastolic', 0));
            $sugar = floatval($this->post('blood_sugar', 0));

            $resultData = $this->dailyLogModel->saveDailyLog($userId, $_POST, $todayDate);
            $alreadyLogged = true;
            $submitted = true;
        }

        $this->view('dailylog/dailylog', [
            'already_logged' => $alreadyLogged,
            'submitted' => $submitted,
            'resultData' => $resultData,
            'sys' => $sys,
            'dia' => $dia,
            'sugar' => $sugar
        ]);
    }
}
