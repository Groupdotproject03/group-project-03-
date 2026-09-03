<?php
/**
 * Symptom Checker Model
 */

class SymptomCheckerModel extends Model {

    public function getAllSymptoms() {
        $sql = "SELECT SymptomName FROM symptom_checker ORDER BY SymptomName ASC";
        return $this->fetchAll($sql);
    }

    public function checkSymptom($symptomName) {
        $symptomName = $this->escape($symptomName);
        $sql = "SELECT * FROM symptom_checker WHERE SymptomName='$symptomName' LIMIT 1";
        return $this->fetchOne($sql);
    }
}
