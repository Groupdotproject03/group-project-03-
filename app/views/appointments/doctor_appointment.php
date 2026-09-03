<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Doctor Appointments</title>

<style>

body{
    font-family:Arial;
    background:#eaf3ff;
    text-align:center;
    margin:0;
    padding:30px;
}

h2{
    color:#2c7ea6;
}

table{
    width:95%;
    margin:auto;
    margin-top:25px;
    border-collapse:collapse;
    background:white;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
}

th,td{
    border:1px solid #ddd;
    padding:12px;
    text-align:center;
}

th{
    background:#4facfe;
    color:white;
}

.status-accepted{
    color:#28a745;
    font-weight:bold;
}

.status-pending{
    color:#e67e00;
    font-weight:bold;
}

.btn-accept,
.btn-reject,
.btn-pdf{
    text-decoration:none;
    color:white;
    padding:7px 14px;
    border-radius:8px;
    display:inline-block;
    font-size:14px;
}

.btn-accept{
    background:#28a745;
}

.btn-accept:hover{
    background:#1e7e34;
}

.btn-reject{
    background:#dc3545;
    margin-left:5px;
}

.btn-reject:hover{
    background:#a71d2a;
}

.btn-pdf{
    background:#6c5ce7;
}

.btn-pdf:hover{
    background:#4b3dbf;
}

.no-pdf{
    color:#999;
}

.back{
    display:inline-block;
    margin-bottom:20px;
    text-decoration:none;
    color:#2c7ea6;
    font-weight:bold;
}

</style>

</head>
<body>

<a href="dashboard.php" class="back">← Back to Dashboard</a>

<h2>My Patient Appointments</h2>

<table>

<tr>
    <th>Appointment ID</th>
    <th>Patient Name</th>
    <th>Date</th>
    <th>Time</th>
    <th>Medication</th>
    <th>Report</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php if(!empty($appointments)): ?>
<?php foreach($appointments as $row): ?>

<tr>

<td><?php echo $row['AppointmentID']; ?></td>

<td><?php echo htmlspecialchars($row['PatientName']); ?></td>

<td><?php echo date("d M Y",strtotime($row['Date'])); ?></td>

<td><?php echo date("h:i A",strtotime($row['Time'])); ?></td>

<td><?php echo !empty($row['Medication']) ? htmlspecialchars($row['Medication']) : '—'; ?></td>

<td>

<?php if(!empty($row['ReportFile']) && file_exists($row['ReportFile'])){ ?>

<a class="btn-pdf"
   href="<?php echo $row['ReportFile']; ?>"
   target="_blank">📄 View PDF</a>

<?php }else{ ?>

<span class="no-pdf">No Report</span>

<?php } ?>

</td>

<td>

<?php if($row['Status']=="Accepted"){ ?>

<span class="status-accepted">✔ Accepted</span>

<?php }else{ ?>

<span class="status-pending">⏳ Pending</span>

<?php } ?>

</td>

<td>

<?php if($row['Status']!="Accepted"){ ?>

<a class="btn-accept"
href="doctor_appointment.php?accept=<?php echo $row['AppointmentID']; ?>"
onclick="return confirm('Accept this appointment?')">
✔ Accept
</a>

<?php }else{ ?>

<span class="status-accepted">✔ Done</span>

<?php } ?>

<a class="btn-reject"
href="doctor_appointment.php?reject=<?php echo $row['AppointmentID']; ?>"
onclick="return confirm('Reject this appointment?')">
✖ Reject
</a>

</td>

</tr>

<?php endforeach; ?>
<?php endif; ?>

</table>

</body>
</html>
