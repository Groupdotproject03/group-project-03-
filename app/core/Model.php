<?php
/**
 * Base Model
 */

abstract class Model {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getDb() {
        return $this->db;
    }

    public function escape($string) {
        return mysqli_real_escape_string($this->db, trim($string ?? ''));
    }

    public function query($sql) {
        return mysqli_query($this->db, $sql);
    }

    public function fetchAll($sql) {
        $result = $this->query($sql);
        if (!$result) return [];
        $rows = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }
        return $rows;
    }

    public function fetchOne($sql) {
        $result = $this->query($sql);
        if (!$result || mysqli_num_rows($result) === 0) return null;
        return mysqli_fetch_assoc($result);
    }

    public function insertId() {
        return mysqli_insert_id($this->db);
    }

    public function affectedRows() {
        return mysqli_affected_rows($this->db);
    }

    public function error() {
        return mysqli_error($this->db);
    }
}
