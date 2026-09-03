<?php
/**
 * Sample Controller
 */

class SampleController extends Controller {
    private $sampleModel;
    private $userModel;

    public function __construct() {
        $this->sampleModel = new SampleModel();
        $this->userModel = new UserModel();
    }

    public function submitSample() {
        $this->requireAuth(['patient']);
        $userId = $this->getUserId();

        $user = $this->userModel->getUserById($userId);
        $patient = $this->userModel->getPatientData($userId);

        $success = "";
        $error = "";

        if ($this->isPost()) {
            $mobileNo = $this->post('mobile_no', '');
            $address = $this->post('address', '');
            $preferredDatetime = $this->post('preferred_datetime', '');

            $file = $this->files('photo');
            if ($file && $file['error'] == 0 && !empty($file['name'])) {
                $filename = time() . "_sample_" . basename($file['name']);
                $folder = "Uploads/samples/";
                if (!is_dir($folder)) {
                    mkdir($folder, 0755, true);
                }

                $destination = $folder . $filename;
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    if ($this->sampleModel->submitSampleRequest($userId, $mobileNo, $address, $preferredDatetime, $destination)) {
                        $success = "Thank you! Your request has been submitted, our staff will call you within 10 mins.";
                    } else {
                        $error = "Something went wrong: " . $this->sampleModel->error();
                    }
                } else {
                    $error = "Photo upload failed. Please try again.";
                }
            } else {
                $error = "Please upload a photo of the test/prescription.";
            }
        }

        $this->view('reports/submit_sample', [
            'user' => $user,
            'patient' => $patient,
            'success' => $success,
            'error' => $error
        ]);
    }

    public function sampleReview() {
        $this->requireAuth(['staff']);
        $userId = $this->getUserId();

        if ($this->isPost() && isset($_POST['action']) && isset($_POST['sample_id'])) {
            $sampleId = intval($this->post('sample_id'));
            $action = $this->post('action');
            $remarks = $this->post('remarks', '');

            if ($action == 'Accepted' || $action == 'Rejected') {
                $this->sampleModel->updateSampleReview($sampleId, $userId, $action, $remarks);
            }

            $this->redirect('sample_review.php');
        }

        $filter = $this->get('status', 'Pending');
        $allowedFilters = ['Pending', 'Accepted', 'Rejected', 'All'];
        if (!in_array($filter, $allowedFilters)) {
            $filter = 'Pending';
        }

        $samplesList = $this->sampleModel->getSamplesList($filter);

        $this->view('reports/sample_review', [
            'filter' => $filter,
            'samplesList' => $samplesList
        ]);
    }
}
