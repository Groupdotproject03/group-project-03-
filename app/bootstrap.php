<?php
/**
 * Application Bootstrapper
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load configurations
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/Database.php';

// Load Core classes
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/View.php';
require_once __DIR__ . '/core/Controller.php';

// Load Helper functions (Crypto encryption/decryption)
if (file_exists(ROOT_PATH . '/includes/crypto_helper.php')) {
    require_once ROOT_PATH . '/includes/crypto_helper.php';
}

// Global $conn variable for backward compatibility with any direct queries
global $conn;
$conn = Database::getInstance()->getConnection();

// Autoload Models and Controllers
spl_autoload_register(function ($className) {
    $modelFile = APP_PATH . '/models/' . $className . '.php';
    if (file_exists($modelFile)) {
        require_once $modelFile;
        return;
    }

    $controllerFile = APP_PATH . '/controllers/' . $className . '.php';
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        return;
    }
});
