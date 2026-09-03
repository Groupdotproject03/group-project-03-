<!DOCTYPE html>
<html>
<head>
<title>Health History</title>
<style>
body{
    font-family:Arial;
    background-image:url("Images/new.jpg");
    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
    min-height:94vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.box{
    width:900px;
    background:white;
    padding:20px;
    border-radius:20px;
    box-shadow:0 10px 25px rgb(44, 106, 151);
    text-align:center;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    padding:12px;
    border:1px solid #ccc;
    font-size:14px;
}

th{
    background:#4facfe;
    color:white;
}

tr:hover td { background:#f0f8ff; }

.search-bar {
    display:flex;
    justify-content:center;
    align-items:center;
    gap:10px;
    margin-bottom:18px;
}

.search-bar input[type="date"] {
    padding:8px 12px;
    border:1px solid #ccc;
    border-radius:10px;
    font-size:14px;
}

.search-bar button {
    padding:8px 18px;
    background:#4facfe;
    color:white;
    border:none;
    border-radius:10px;
    font-size:14px;
    cursor:pointer;
}

.search-bar button:hover { background:#2f8dfd; }

.clear-btn {
    padding:8px 14px;
    background:#e0e0e0;
    color:#555;
    border:none;
    border-radius:10px;
    font-size:14px;
    text-decoration:none;
}

.clear-btn:hover { background:#ccc; }

.no-data {
    color:#aaa;
    padding:20px;
    font-size:14px;
}

.normal  { color:#28a745; font-weight:bold; }
.warning { color:#e67e00; font-weight:bold; }
.danger  { color:#dc3545; font-weight:bold; }
</style>
</head>
<body>
<a href="dashboard.php" style="position:fixed; top:20px; left:20px; color:#2c7ea6; text-decoration:none; font-weight:bold; font-size:15px;">← Back to Dashboard</a>
<div class="box">
<h2>Your Health History</h2>

<form method="GET" class="search-bar">
    <input type="date" name="search_date"
        value="<?php echo htmlspecialchars($searchDate ?? ''); ?>">
    <button type="submit">Search</button>
    <?php if(!empty($searchDate)): ?>
        <a href="history.php" class="clear-btn">Clear</a>
    <?php endif; ?>
</form>

<table>
<tr>
    <th>Date</th>
    <th>BMI</th>
    <th>Score</th>
    <th>Blood Pressure</th>
    <th>Blood Sugar</th>
</tr>
<?php if(!empty($records)): ?>
    <?php foreach($records as $row): ?>
    <?php
        $sys = $row['BloodPressureSystolic'];
        $dia = $row['BloodPressureDiastolic'];
        $bp_class = "";
        $bp_text = "—";
        if($sys > 0 && $dia > 0) {
            $bp_text = "$sys/$dia";
            if($sys < 120 && $dia < 80)          $bp_class = "normal";
            elseif($sys <= 129 && $dia < 80)      $bp_class = "warning";
            elseif($sys >= 130 || $dia >= 80)     $bp_class = "warning";
            if($sys >= 140 || $dia >= 90)         $bp_class = "danger";
            if($sys > 180 || $dia > 120)          $bp_class = "danger";
        }

        $sugar = $row['BloodSugar'];
        $sugar_class = "";
        $sugar_text = "—";
        if($sugar > 0) {
            $sugar_type_label = ($row['BloodSugarType'] == 'postmeal') ? 'After Meal' : 'Fasting';
            $sugar_text = $sugar . " mmol/L (" . $sugar_type_label . ")";
            if($sugar >= 3.9 && $sugar <= 7.8)   $sugar_class = "normal";
            elseif($sugar <= 8.5)                 $sugar_class = "warning";
            else                                  $sugar_class = "danger";
        }
    ?>
    <tr>
        <td><?php echo date("d M Y", strtotime($row['LogDate'])); ?></td>
        <td><?php echo round($row['BMI'],2); ?></td>
        <td><?php echo $row['Score']; ?>/100</td>
        <td class="<?php echo $bp_class; ?>"><?php echo $bp_text; ?></td>
        <td class="<?php echo $sugar_class; ?>"><?php echo $sugar_text; ?></td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr><td colspan="5" class="no-data">No records found.</td></tr>
<?php endif; ?>
</table>
</div>
</body>
</html>
