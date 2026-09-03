<?php
/**
 * Vaccination Controller
 */

class VaccinationController extends Controller {
    private $vaccineModel;

    public function __construct() {
        $this->vaccineModel = new VaccineModel();
    }

    public function index() {
        $this->requireAuth(['patient']);
        $userId = $this->getUserId();

        $this->vaccineModel->autoUpdateStatus($userId);

        if ($this->isPost() && isset($_POST['add'])) {
            $name  = $this->post('name', '');
            $dose  = $this->post('dose', '');
            $vdate = $this->post('vdate', '');
            $next  = $this->post('next', '');
            $this->vaccineModel->addVaccine($userId, $name, $dose, $vdate, $next ?: null);
            $this->redirect('vaccination.php');
        }

        if (isset($_GET['delete'])) {
            $id = intval($_GET['delete']);
            $this->vaccineModel->deleteVaccine($id, $userId);
            $this->redirect('vaccination.php');
        }

        $summary = $this->vaccineModel->getVaccineSummary($userId);
        $records = $this->vaccineModel->getVaccineRecords($userId);

        $this->view('vaccination/vaccination', [
            'summary' => $summary,
            'records' => $records,
        ]);
    }
}
