<?php
/**
 * Emergency Controller
 */

class EmergencyController extends Controller {
    private $emergencyModel;

    public function __construct() {
        $this->emergencyModel = new EmergencyModel();
    }

    public function index() {
        $this->requireAuth(['patient']);
        $userId = $this->getUserId();
        $message = "";

        // Add contact
        if ($this->isPost() && isset($_POST['add_contact'])) {
            $name = $this->post('contact_name', '');
            $rel  = $this->post('contact_relation', '');
            $phone = $this->post('contact_phone', '');

            if ($name != '' && $phone != '') {
                $this->emergencyModel->addEmergencyContact($userId, $name, $rel, $phone);
                $message = "Emergency contact added.";
            }
        }

        // Delete contact
        if (isset($_GET['delete_contact'])) {
            $cid = intval($_GET['delete_contact']);
            $this->emergencyModel->deleteEmergencyContact($userId, $cid);
            $this->redirect('emergency.php');
        }

        $contacts = $this->emergencyModel->getEmergencyContacts($userId);
        $ambulanceRequests = $this->emergencyModel->getRecentAmbulanceRequests($userId, 5);

        $this->view('emergency/emergency', [
            'message' => $message,
            'contacts' => $contacts,
            'ambulanceRequests' => $ambulanceRequests
        ]);
    }

    public function requestAmbulance() {
        $this->requireAuth(['patient']);
        $userId = $this->getUserId();

        if ($this->isPost()) {
            $location = trim($this->post('location', ''));
            $contact  = trim($this->post('contact_number', ''));
            $notes    = trim($this->post('notes', ''));
            $lat      = isset($_POST['latitude']) && $_POST['latitude'] !== '' ? floatval($_POST['latitude']) : null;
            $lng      = isset($_POST['longitude']) && $_POST['longitude'] !== '' ? floatval($_POST['longitude']) : null;

            if ($location != '' && $contact != '') {
                $this->emergencyModel->requestAmbulance($userId, $location, $contact, $notes, $lat, $lng);
            }
        }

        $this->redirect('emergency.php');
    }

    public function ambulanceStatus() {
        if (!$this->isLoggedIn()) {
            $this->json(['success' => false]);
        }

        $userId = $this->getUserId();
        $usertype = $this->getUserType();
        $requestId = intval($this->get('id', 0));

        $req = $this->emergencyModel->getAmbulanceRequestById($requestId);

        if (!$req) {
            $this->json(['success' => false]);
        }

        if ($usertype == 'patient' && $req['PatientID'] != $userId) {
            $this->json(['success' => false]);
        }

        if ($usertype != 'patient' && $usertype != 'staff') {
            $this->json(['success' => false]);
        }

        $this->json([
            'success'      => true,
            'status'       => $req['Status'],
            'driver_name'  => $req['DriverName'],
            'driver_phone' => $req['DriverPhone'],
            'vehicle_no'   => $req['VehicleNo'],
            'eta'          => $req['ETA'],
            'latitude'     => $req['Latitude'],
            'longitude'    => $req['Longitude'],
        ]);
    }

    public function ambulanceManage() {
        $this->requireAuth(['staff']);

        if ($this->isPost() && isset($_POST['update'])) {
            $rid = intval($this->post('request_id', 0));
            $data = [
                'status' => $this->post('status', ''),
                'driver_name' => $this->post('driver_name', ''),
                'driver_phone' => $this->post('driver_phone', ''),
                'vehicle_no' => $this->post('vehicle_no', ''),
                'eta' => $this->post('eta', '')
            ];

            $this->emergencyModel->updateAmbulanceStatus($rid, $data);
            $this->redirect('ambulance_manage.php');
        }

        $requests = $this->emergencyModel->getAllAmbulanceRequests();

        $this->view('emergency/ambulance_manage', [
            'requests' => $requests
        ]);
    }
}
