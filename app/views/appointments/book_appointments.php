<!DOCTYPE html>
<html>
<head>
<title>Doctors</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{
    font-family:Arial;
    background:#e8f4f8;
    padding:30px;
}
.back-btn{
    display:inline-block;
    margin-bottom:20px;
    color:#2c7ea6;
    text-decoration:none;
}
h2{
    color:#2c7ea6;
    margin-bottom:20px;
}
.section{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}
table{
    width:100%;
    border-collapse:collapse;
}
th{
    background:#f0f8ff;
    color:#2c7ea6;
    padding:12px;
    font-size:14px;
}
td{
    padding:12px;
    border-bottom:1px solid #eee;
    font-size:14px;
    vertical-align:middle;
}
input,select{
    padding:6px;
    border-radius:6px;
    border:1px solid #ccc;
}
button{
    padding:6px 12px;
    background:#2c7ea6;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
}
button:hover{
    background:#1a5f80;
}
.msg{
    color:green;
    margin-bottom:10px;
    font-weight:bold;
}
.msg.error{
    color:red;
}
.form-row{
    display:flex;
    flex-wrap:wrap;
    gap:6px;
    align-items:center;
}
.med-label{
    font-size:13px;
    color:#555;
}
.file-note{
    font-size:11px;
    color:#999;
    margin-top:2px;
}
</style>
</head>
<body>
<a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
<h2>Doctors & Booking</h2>
<div class="section">

<?php if(!empty($message)): ?>
    <p class="msg <?php echo (strpos($message,'Error')!==false||strpos($message,'Only')!==false) ? 'error':''; ?>">
        <?php echo $message; ?>
    </p>
<?php endif; ?>

<table>
<tr>
    <th>Name</th>
    <th>Specialization</th>
    <th>Hospital</th>
    <th>Available Time</th>
    <th>Book</th>
</tr>
<?php if(!empty($doctors)): ?>
<?php foreach($doctors as $doc): ?>
<tr>
    <td><?php echo htmlspecialchars($doc['Name']); ?></td>
    <td><?php echo htmlspecialchars($doc['Specialization']); ?></td>
    <td><?php echo htmlspecialchars($doc['HospitalName']); ?></td>
    <td><?php echo htmlspecialchars($doc['AvailableTime']); ?></td>
    <td>
        <form method="POST" action="book_appointments.php" enctype="multipart/form-data">
            <input type="hidden" name="doctor_id" value="<?php echo $doc['UserID']; ?>">

            <div class="form-row">
                <input type="date" name="date" required>
                <input type="time" name="time" required>
            </div>

            <div class="form-row" style="margin-top:6px;">
                <span class="med-label">Medication:</span>
                <select name="medication">
                    <option value="Yes">Yes</option>
                    <option value="No" selected>No</option>
                </select>
            </div>

            <div style="margin-top:6px;">
                <input type="file" name="report" accept=".pdf">
                <div class="file-note">Optional — PDF only</div>
            </div>

            <div style="margin-top:8px;">
                <button type="submit" name="book">Book</button>
            </div>
        </form>
    </td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</table>
</div>
</body>
</html>
