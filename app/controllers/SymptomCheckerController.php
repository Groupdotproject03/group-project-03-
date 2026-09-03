<?php
/**
 * Symptom Checker Controller
 */

class SymptomCheckerController extends Controller {
    private $symptomModel;

    public function __construct() {
        $this->symptomModel = new SymptomCheckerModel();
    }

    public function index() {
        $result   = null;
        $selected = "";

        if ($this->isPost() && isset($_POST['check'])) {
            $selected = $this->post('symptom', '');
            $result   = $this->symptomModel->checkSymptom($selected);
        }

        $symptoms = $this->symptomModel->getAllSymptoms();

        $this->view('symptomchecker/symptom_checker', [
            'result'   => $result,
            'selected' => $selected,
            'symptoms' => $symptoms,
        ]);
    }
}
