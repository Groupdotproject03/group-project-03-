<?php
/**
 * Database Connection (Singleton Pattern)
 */

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        require_once __DIR__ . '/config.php';
        
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    // Prevent cloning and unserializing
    private function __clone() {}
    public function __wakeup() {}
}
