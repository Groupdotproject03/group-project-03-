<?php
/**
 * Healthcare Finder Controller
 */

class HealthcareController extends Controller {
    private $healthcareModel;

    public function __construct() {
        $this->healthcareModel = new HealthcareModel();
    }

    public function index() {
        $this->requireAuth();
        $services = $this->healthcareModel->getAllServices();

        $this->view('healthcare/healthcare_finder', [
            'services' => $services,
        ]);
    }
}
