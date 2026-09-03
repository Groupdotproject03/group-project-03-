<?php
$docList = !empty($doctorAppointments) ? $doctorAppointments : (!empty($doc_result) ? $doc_result : []);
$trainerList = !empty($trainerAppointments) ? $trainerAppointments : (!empty($trainer_result) ? $trainer_result : []);
?>
<!DOCTYPE html>
<html>
<head>
<title>My Appointments</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body {
    font-family: Arial;
    padding: 45px 5px;
    background:url('Images/new.jpg');
    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
}
.back-btn {
    display: inline-block;
    margin-bottom:30px;
    color: #2c7ea6;
    text-decoration: none;
    font-weight: bold;
    font-size: 15px;
}
.back-btn:hover { text-decoration: underline; }
h2 {
    text-align: center;
    color: #2c7ea6;
    margin-bottom: 25px;
    font-size: 30px;
}
.section-title {
    color: #2c7ea6;
    font-size: 18px;
    margin: 30px 0 12px;
    padding-bottom: 6px;
    border-bottom: 2px solid #4facfe;
}
.box {
    max-width: 800px;
    margin: auto;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(30, 89, 135, 1);
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
}
th, td {
    padding: 12px 10px;
    border: 1px solid #dde6f0;
    text-align: center;
    font-size: 14px;
}
th {
    background: linear-gradient(135deg, #4facfe, #2c7ea6);
    color: white;
}
tr:hover td { background: #f0f8ff; }
.no-data {
    text-align: center;
    color: #aaa;
    padding: 20px;
    font-size: 14px;
}
.status-accepted {
    color: #28a745;
    font-weight: bold;
}
.status-pending {
    color: #e67e00;
    font-weight: bold;
}
</style>
</head>
<body>

<div class="box">

    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>

    <h2>My Appointments</h2>

    <!-- DOCTOR APPOINTMENTS -->
    <div class="section-title">👨‍⚕️ Doctor Appointments</div>

    <?php if(!empty($docList)): ?>
    <table>
        <tr>
            <th>#</th>
            <th>Doctor</th>
            <th>Date</th>
            <th>Time</th>
            <th>Medication</th>
            <th>Status</th>
        </tr>
        <?php $i = 1; foreach($docList as $row): ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars($row['DoctorName']); ?></td>
            <td><?php echo date("d M Y", strtotime($row['Date'])); ?></td>
            <td><?php echo date("h:i A", strtotime($row['Time'])); ?></td>
            <td><?php echo htmlspecialchars($row['Medication'] ?? 'N/A'); ?></td>
            <td>
                <?php if(isset($row['Status']) && $row['Status'] == 'Accepted'): ?>
                    <span class="status-accepted">✔ Accepted</span>
                <?php else: ?>
                    <span class="status-pending">⏳ Pending</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p class="no-data">No upcoming doctor appointments.</p>
    <?php endif; ?>

    <!-- TRAINER APPOINTMENTS -->
    <div class="section-title">🏋️ Trainer Appointments</div>

    <?php if(!empty($trainerList)): ?>
    <table>
        <tr>
            <th>#</th>
            <th>Trainer</th>
            <th>Date</th>
            <th>Time</th>
            <th>Chat</th>
        </tr>
        <?php $i = 1; foreach($trainerList as $row): ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars($row['TrainerName']); ?></td>
            <td><?php echo date("d M Y", strtotime($row['Date'])); ?></td>
            <td><?php echo date("h:i A", strtotime($row['Time'])); ?></td>
            <td>
                <?php
                $apptTs = strtotime($row['Date'].' '.$row['Time']);
                $now = time();
                if($now >= ($apptTs - 900) && $now <= ($apptTs + 3600)):
                ?>
                    <a href="trainer_chat.php?id=<?php echo $row['AppointmentID']; ?>"
                       style="background:#4facfe;color:white;padding:5px 12px;border-radius:8px;text-decoration:none;font-size:13px;">
                        💬 Chat
                    </a>
                <?php else: ?>
                    <span style="color:#aaa;font-size:12px;">Opens 15 min before</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: ?>
        <p class="no-data">No upcoming trainer appointments.</p>
    <?php endif; ?>

</div>

</body>
</html>
