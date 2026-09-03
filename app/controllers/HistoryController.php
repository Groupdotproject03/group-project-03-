<?php
/**
 * History Controller
 */

class HistoryController extends Controller {
    private $historyModel;

    public function __construct() {
        $this->historyModel = new HistoryModel();
    }

    public function index() {
        $this->requireAuth();
        $userId = $this->getUserId();
        $searchDate = $this->get('search_date', '');
        $records = $this->historyModel->getHistory($userId, $searchDate);

        $this->view('history/history', [
            'records'    => $records,
            'searchDate' => $searchDate,
        ]);
    }

    public function downloadPdf() {
        $this->requireAuth();
        $userId = $this->getUserId();

        require_once ROOT_PATH . '/fpdf/fpdf.php';

        $userInfo = $this->historyModel->getUserInfoForPdf($userId);
        $records  = $this->historyModel->getPdfHistory($userId);
        $medsStatus = $this->historyModel->getLatestMedsStatus($userId);
        $latestBmi  = $this->historyModel->getLatestBmi($userId);

        // Generate PDF inline
        include VIEW_PATH . '/history/history_pdf_generate.php';
    }
}
