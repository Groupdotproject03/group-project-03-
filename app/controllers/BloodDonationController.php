<?php
/**
 * Blood Donation Controller
 */

class BloodDonationController extends Controller {
    private $bloodModel;

    public function __construct() {
        $this->bloodModel = new BloodDonationModel();
    }

    public function index() {
        $this->requireAuth();
        $userId = $this->getUserId();

        $success = "";
        $error = "";

        // Register as donor
        if ($this->isPost() && isset($_POST['register_donor'])) {
            $blood_group = $this->post('blood_group', '');
            $location = $this->post('location', '');
            $phone = $this->post('phone', '');
            $last_donation = $this->post('last_donation', null);

            if ($this->bloodModel->isRegisteredDonor($userId)) {
                $error = "You are already registered as a blood donor.";
            } else {
                if ($this->bloodModel->registerDonor($userId, $blood_group, $location, $phone, $last_donation)) {
                    $success = "You have successfully registered as a blood donor!";
                } else {
                    $error = "Error: " . $this->bloodModel->error();
                }
            }
        }

        // Toggle availability
        if (isset($_GET['toggle'])) {
            $donorId = intval($_GET['toggle']);
            $this->bloodModel->toggleAvailability($donorId, $userId);
            $this->redirect('blood_donation.php');
        }

        // Delete donor
        if (isset($_GET['delete'])) {
            $donorId = intval($_GET['delete']);
            $this->bloodModel->deleteDonor($donorId, $userId);
            $this->redirect('blood_donation.php');
        }

        $search_group = $this->get('blood_group', '');
        $my_donor = $this->bloodModel->getMyDonorProfile($userId);
        $donors = $this->bloodModel->getAvailableDonors($search_group);

        $this->view('blood/blood_donation', [
            'success' => $success,
            'error' => $error,
            'my_donor' => $my_donor,
            'donors' => $donors,
            'search_group' => $search_group
        ]);
    }
}
