<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Appointments - HealthCore</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body {
    font-family: Arial, sans-serif;
    background: #e8f4f8;
    min-height: 100vh;
    padding: 30px;
}
.back-btn {
    display: inline-block;
    margin-bottom: 20px;
    color: #2c7ea6;
    text-decoration: none;
    font-size: 14px;
}
h2 { color: #2c7ea6; margin-bottom: 25px; }
.section {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}
table { width: 100%; border-collapse: collapse; }
th {
    background: #f0f8ff;
    color: #2c7ea6;
    padding: 12px;
    text-align: left;
    font-size: 14px;
}
td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
    color: #555;
}
tr:last-child td { border-bottom: none; }
.no-data {
    text-align: center;
    color: #aaa;
    padding: 40px;
    font-size: 16px;
}
.btn-delete {
    padding: 5px 12px;
    background: #ff4d4d;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    text-decoration: none;
}
.btn-delete:hover { background: #cc0000; }
.upcoming {
    background: #e0f3ff;
    color: #2c7ea6;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
}
.past {
    background: #f0f0f0;
    color: #888;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
}
</style>
</head>
<body>

<a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
<h2>My Appointments</h2>

<div class="section">
<?php if(!empty($appointments)): ?>
    <table>
        <tr>
            <th>Patient Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Chat</th>
            <th>Action</th>
        </tr>
        <?php foreach($appointments as $appt): ?>
        <tr>
            <td><?php echo htmlspecialchars($appt['Name']); ?></td>
            <td><?php echo htmlspecialchars($appt['Email']); ?></td>
            <td><?php echo htmlspecialchars($appt['PhoneNo']); ?></td>
            <td><?php echo htmlspecialchars($appt['Date']); ?></td>
            <td><?php echo htmlspecialchars($appt['Time']); ?></td>
            <td>
                <?php if($appt['Date'] >= date('Y-m-d')): ?>
                    <span class="upcoming">Upcoming</span>
                <?php else: ?>
                    <span class="past">Past</span>
                <?php endif; ?>
            </td>
            <td>
                <?php
                $apptTs = strtotime($appt['Date'].' '.$appt['Time']);
                $now = time();
                if($now >= ($apptTs - 900) && $now <= ($apptTs + 3600)):
                ?>
                    <a href="trainer_chat.php?id=<?php echo $appt['AppointmentID']; ?>"
                       style="background:#4facfe;color:white;padding:5px 12px;border-radius:8px;text-decoration:none;font-size:13px;">
                        💬 Chat
                    </a>
                <?php else: ?>
                    <span style="color:#aaa;font-size:12px;">—</span>
                <?php endif; ?>
            </td>
            <td>
                <a href="trainer_appointment.php?delete=<?php echo $appt['AppointmentID']; ?>" 
                   class="btn-delete" 
                   onclick="return confirm('Delete this appointment?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p class="no-data">No appointments yet.</p>
<?php endif; ?>
</div>

</body>
</html>
