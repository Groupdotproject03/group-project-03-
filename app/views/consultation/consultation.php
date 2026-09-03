<!DOCTYPE html>
<html>
<head>
<title>Online Consultation</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:Arial;background:#eaf3ff;}
.header{
    background:linear-gradient(135deg,#4facfe,#2c7ea6);
    color:white;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.header a{color:white;text-decoration:none;font-weight:bold;}
.box{
    max-width:900px;
    margin:40px auto;
    background:white;
    border-radius:20px;
    padding:30px;
    box-shadow:0 8px 20px rgba(23,84,127,0.2);
}
h2{color:#2c7ea6;margin-bottom:20px;text-align:center;}
table{width:100%;border-collapse:collapse;}
th,td{padding:12px 10px;border-bottom:1px solid #eee;text-align:center;font-size:14px;}
th{background:#f0f8ff;color:#2c7ea6;}
.badge{padding:4px 10px;border-radius:12px;font-size:12px;font-weight:bold;color:white;}
.badge.active{background:#28a745;}
.badge.upcoming{background:#e67e00;}
.badge.closed{background:#999;}
.btn{
    display:inline-block;
    padding:8px 16px;
    border-radius:8px;
    background:#4facfe;
    color:white;
    text-decoration:none;
    font-size:13px;
}
.btn.disabled{background:#ccc;pointer-events:none;}
.no-data{text-align:center;color:#aaa;padding:30px;}
</style>
</head>
<body>

<div class="header">
    <a href="dashboard.php">&larr; Back to Dashboard</a>
    <h2 style="font-weight:normal;">🩺 Online Consultation</h2>
    <div style="width:150px;"></div>
</div>

<div class="box">
<h2><?php echo $usertype == 'patient' ? 'Your Doctor Consultations' : 'Your Patient Consultations'; ?></h2>

<?php if (!empty($consultations)): ?>
<table>
<tr>
    <th>#</th>
    <th><?php echo $usertype == 'patient' ? 'Doctor' : 'Patient'; ?></th>
    <th>Date</th>
    <th>Time</th>
    <th>Status</th>
    <th>Consultation</th>
</tr>
<?php $i = 1; foreach ($consultations as $row):
    $apptDateTime = strtotime($row['Date'] . ' ' . $row['Time']);
    $windowStart  = $apptDateTime - (15 * 60);
    $windowEnd    = $apptDateTime + (60 * 60);
    $nowTs        = time();
    $accepted     = (isset($row['Status']) && $row['Status'] == 'Accepted');

    if (!$accepted) {
        $state = 'closed';
        $label = 'Not Confirmed Yet';
    } elseif ($nowTs < $windowStart) {
        $state = 'upcoming';
        $label = 'Opens ' . date("d M, h:i A", $windowStart);
    } elseif ($nowTs <= $windowEnd) {
        $state = 'active';
        $label = 'Live Now';
    } else {
        $state = 'closed';
        $label = 'Session Ended';
    }
?>
<tr>
    <td><?php echo $i++; ?></td>
    <td><?php echo htmlspecialchars($row['OtherName']); ?></td>
    <td><?php echo date("d M Y", strtotime($row['Date'])); ?></td>
    <td><?php echo date("h:i A", strtotime($row['Time'])); ?></td>
    <td><span class="badge <?php echo $state; ?>"><?php echo $label; ?></span></td>
    <td>
        <?php if ($accepted): ?>
            <a class="btn" href="consultation_chat.php?id=<?php echo $row['AppointmentID']; ?>">Open</a>
        <?php else: ?>
            <span class="btn disabled">Open</span>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>
<?php else: ?>
    <p class="no-data">No consultations found yet.</p>
<?php endif; ?>

<p style="margin-top:20px;font-size:12px;color:#888;text-align:center;">
    The consultation chat box and meeting link unlock automatically from 15 minutes before your
    scheduled time until 60 minutes after it. All messages are end-to-end encrypted.
</p>

</div>
</body>
</html>
