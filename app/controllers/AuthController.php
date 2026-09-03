<?php
/**
 * Auth Controller
 */

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function showLogin() {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard.php');
        }
        $this->view('auth/login');
    }

    public function login() {
        if ($this->isPost()) {
            $email = $this->post('email', '');
            $password = $this->post('password', '');

            $user = $this->userModel->authenticate($email, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['UserID'];
                $_SESSION['email'] = $user['Email'];
                $_SESSION['usertype'] = strtolower($user['UserType']);

                $this->redirect('dashboard.php');
            } else {
                $this->view('auth/login', ['error' => 'Wrong email or password']);
            }
        } else {
            $this->showLogin();
        }
    }

    public function showRegister() {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard.php');
        }
        $this->view('auth/register');
    }

    public function register() {
        if ($this->isPost()) {
            $email = $this->post('email', '');

            if ($this->userModel->emailExists($email)) {
                $this->view('auth/register', ['error' => 'This email is already registered!']);
                return;
            }

            $registeredId = $this->userModel->register($_POST);
            if ($registeredId) {
                $this->redirect('index.php');
            } else {
                $this->view('auth/register', ['error' => 'Registration error occurred. Please try again.']);
            }
        } else {
            $this->showRegister();
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('index.php');
    }
}
