<!DOCTYPE html>
<html>
<head>
<title>Client Details</title>
<style>
body{
    font-family:Arial;
    background:#eaf3ff;
}
.header{
    background:linear-gradient(135deg,#4facfe,#2c7ea6);
    color:white;
    padding:15px;
    text-align:center;
}
.container{
    display:flex;
    flex-wrap:wrap;
    gap:20px;
    justify-content:center;
    padding:20px;
}
.box{
    width:320px;
    background:white;
    padding:20px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
.box h3{
    color:#2c7ea6;
    margin-bottom:10px;
}
.item{
    padding:10px;
    border-bottom:1px solid #eee;
    font-size:14px;
}
.profile{
    text-align:center;
    margin-top:20px;
}
.profile img{
    width:90px;
    height:90px;
    border-radius:50%;
    border:3px solid #4facfe;
}
</style>
</head>
<body>

<div class="header">
    <h2>Client Details</h2>
</div>

<div class="profile">
    <img src="<?php echo (!empty($client['ProfilePhoto'])) ? htmlspecialchars($client['ProfilePhoto']) : 'Images/default.png'; ?>">
    <h3><?php echo htmlspecialchars($client['Name'] ?? ''); ?></h3>
    <p><?php echo htmlspecialchars($client['Email'] ?? ''); ?></p>
    <p><?php echo htmlspecialchars($client['PhoneNo'] ?? ''); ?></p>
</div>

<div class="container">

<!-- HEALTH REPORT -->
<div class="box">
    <h3>📊 Health Reports</h3>
    <?php
    if(!empty($reports)){
        foreach($reports as $row){
            echo "<div class='item'>";
            echo "BMI: " . htmlspecialchars($row['BMI']) . "<br>";
            echo "Score: " . htmlspecialchars($row['Score']) . "<br>";
            echo "Date: " . htmlspecialchars($row['LogDate']);
            echo "</div>";
        }
    } else {
        echo "<p>No report found</p>";
    }
    ?>
</div>

<!-- DAILY LOG -->
<div class="box">
    <h3>📝 Daily Logs</h3>
    <?php
    if(!empty($logs)){
        foreach($logs as $row){
            echo "<div class='item'>";
            echo "Steps: " . htmlspecialchars($row['StepsCount']) . "<br>";
            echo "Water: " . htmlspecialchars($row['WaterIntake']) . " L<br>";
            echo "Sleep: " . htmlspecialchars($row['SleepHours']) . " hrs<br>";
            echo "Workout: " . ($row['WorkoutDone'] ? "Yes" : "No");
            echo "</div>";
        }
    } else {
        echo "<p>No daily log found</p>";
    }
    ?>
</div>

</div>

</body>
</html>
