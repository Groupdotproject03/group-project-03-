<?php
/**
 * Base Controller
 */

abstract class Controller {
    protected function view($viewPath, $data = []) {
        View::render($viewPath, $data);
    }

    protected function model($modelName) {
        $modelFile = APP_PATH . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $modelName . '.php';
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $modelName();
        }
        die("Model not found: " . htmlspecialchars($modelName));
    }

    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit();
    }

    protected function redirect($url) {
        header("Location: " . $url);
        exit();
    }

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    protected function getUserId() {
        return isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;
    }

    protected function getUserType() {
        return isset($_SESSION['usertype']) ? strtolower($_SESSION['usertype']) : '';
    }

    protected function getUserEmail() {
        return $_SESSION['email'] ?? '';
    }

    protected function requireAuth($allowedRoles = []) {
        if (!$this->isLoggedIn()) {
            $this->redirect('index.php');
        }

        if (!empty($allowedRoles)) {
            $userType = $this->getUserType();
            $allowed = is_array($allowedRoles) ? $allowedRoles : [$allowedRoles];
            $allowedLower = array_map('strtolower', $allowed);

            if (!in_array($userType, $allowedLower)) {
                $this->redirect('dashboard.php');
            }
        }
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function post($key = null, $default = null) {
        if ($key === null) return $_POST;
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }

    protected function get($key = null, $default = null) {
        if ($key === null) return $_GET;
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }

    protected function files($key = null) {
        if ($key === null) return $_FILES;
        return isset($_FILES[$key]) ? $_FILES[$key] : null;
    }
}
