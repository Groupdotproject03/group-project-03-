<?php
/**
 * Daily Log Model
 */

class DailyLogModel extends Model {

    public function getAverageScore($userId) {
        $userId = intval($userId);
        $sql = "SELECT AVG(Score) as avg_score FROM healthreport WHERE UserID='$userId'";
        $row = $this->fetchOne($sql);
        return round($row['avg_score'] ?? 0);
    }

    public function isLoggedToday($userId, $todayDate) {
        $userId = intval($userId);
        $todayDate = $this->escape($todayDate);
        $sql = "SELECT * FROM healthreport WHERE UserID='$userId' AND LogDate='$todayDate' LIMIT 1";
        return $this->fetchOne($sql) !== null;
    }

    public function getTodayHealthReport($userId, $todayDate) {
        $userId = intval($userId);
        $todayDate = $this->escape($todayDate);
        $sql = "SELECT * FROM healthreport WHERE UserID='$userId' AND LogDate='$todayDate' ORDER BY ReportID DESC LIMIT 1";
        return $this->fetchOne($sql);
    }

    public function saveDailyLog($userId, $data, $todayDate) {
        $userId = intval($userId);
        $todayDate = $this->escape($todayDate);

        $weight = floatval($data['weight']);
        $height = floatval($data['height']);
        $height_m = $height / 100;
        $bmi = ($height_m > 0) ? ($weight / ($height_m * $height_m)) : 0;

        $sleep   = floatval($data['sleep_hours']);
        $steps   = intval($data['step_count']);
        $water   = floatval($data['water_intake']);
        $workout = ($data['workout_done'] == "Yes") ? 1 : 0;
        $meds    = ($data['meds_taken']   == "Yes") ? 1 : 0;

        $sys   = intval($data['bp_systolic']);
        $dia   = intval($data['bp_diastolic']);
        $sugar = floatval($data['blood_sugar']);
        $sugar_type = $this->escape($data['sugar_type']); // fasting or postmeal

        /* Score Calculation */
        $score = 0;

        if ($sleep >= 7) $score += 20;
        elseif ($sleep >= 5) $score += 10;

        if ($steps >= 5000) $score += 10;

        if ($water >= 2) $score += 20;

        if ($workout == 1) $score += 10;

        if ($meds == 1) $score += 10;

        if ($bmi >= 18.5 && $bmi <= 24.9) $score += 10;

        // Blood Pressure (+5 if normal)
        $bp_status = "";
        if ($sys > 0 && $dia > 0) {
            if ($sys < 120 && $dia < 80) {
                $score += 5;
                $bp_status = "Normal";
            } elseif ($sys <= 129 && $dia < 80) {
                $bp_status = "Elevated";
            } elseif (($sys >= 130 && $sys <= 139) || ($dia >= 80 && $dia <= 89)) {
                $bp_status = "Hypertension Stage 1";
            } elseif ($sys >= 140 || $dia >= 90) {
                $bp_status = "Hypertension Stage 2";
            }
            if ($sys > 180 || $dia > 120) {
                $bp_status = "Hypertensive Crisis ⚠️";
            }
        }

        // Blood Sugar (+5 if normal range)
        $sugar_status = "";
        if ($sugar > 0) {
            if ($sugar_type == "fasting") {
                if ($sugar >= 3.9 && $sugar <= 5.5) {
                    $score += 5;
                    $sugar_status = "Normal (Fasting)";
                } elseif ($sugar <= 6.9) {
                    $sugar_status = "Prediabetes Range";
                } else {
                    $sugar_status = "High — Diabetes Range";
                }
            } else {
                if ($sugar <= 7.8) {
                    $score += 5;
                    $sugar_status = "Normal (Post-meal)";
                } elseif ($sugar <= 8.5) {
                    $sugar_status = "Slightly High";
                } else {
                    $sugar_status = "High";
                }
            }
        }

        /* Comment */
        if ($score < 40)      $comment = "Very Poor — please focus on your health immediately.";
        elseif ($score < 60)  $comment = "You need improvement in your health.";
        elseif ($score < 80)  $comment = "Good progress, keep improving.";
        else                  $comment = "Excellent health condition!";

        /* Insert daily log */
        $sql1 = "INSERT INTO dailylog
                (UserID, StepsCount, WaterIntake, WorkoutDone, MedsTaken, SleepHours,
                 BloodPressureSystolic, BloodPressureDiastolic, BloodSugar, BloodSugarType)
                VALUES
                ('$userId','$steps','$water','$workout','$meds','$sleep',
                 '$sys','$dia','$sugar','$sugar_type')";
        $this->query($sql1);

        /* Insert health report */
        $sql2 = "INSERT INTO healthreport (UserID, LogDate, BMI, Score)
                 VALUES ('$userId','$todayDate','$bmi','$score')";
        $this->query($sql2);

        return [
            'score' => $score,
            'bmi' => $bmi,
            'comment' => $comment,
            'bp_status' => $bp_status,
            'sugar_status' => $sugar_status
        ];
    }

    public function getRecentHistory($userId, $limit = 15) {
        $userId = intval($userId);
        $limit = intval($limit);
        $sql = "SELECT hr.LogDate, hr.BMI, hr.Score,
                       dl.SleepHours, dl.WaterIntake, dl.StepsCount,
                       dl.WorkoutDone, dl.MedsTaken,
                       dl.BloodPressureSystolic, dl.BloodPressureDiastolic,
                       dl.BloodSugar, dl.BloodSugarType
                FROM healthreport hr
                LEFT JOIN dailylog dl ON dl.UserID = hr.UserID
                    AND DATE(hr.LogDate) = DATE(hr.LogDate)
                WHERE hr.UserID='$userId'
                GROUP BY hr.ReportID
                ORDER BY hr.LogDate DESC
                LIMIT $limit";
        return $this->fetchAll($sql);
    }
}
