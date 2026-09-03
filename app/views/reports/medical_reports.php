<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Medical Reports - HealthCore</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial, sans-serif;
    background:#eaf3ff;
    min-height:100vh;
    padding:30px;
}

.container{
    max-width:1100px;
    margin:auto;
}

.back-btn{
    display:inline-block;
    margin-bottom:20px;
    color:#2c7ea6;
    text-decoration:none;
    font-weight:bold;
}

h1{
    text-align:center;
    color:#2c7ea6;
    margin-bottom:25px;
}

.box{
    background:white;
    padding:25px;
    border-radius:18px;
    margin-bottom:25px;
    box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

.box h2{
    color:#2c7ea6;
    margin-bottom:20px;
}

.form-group{
    margin-bottom:16px;
}

label{
    display:block;
    font-weight:bold;
    color:#444;
    margin-bottom:7px;
}

input[type="text"],
select,
input[type="file"],
textarea{
    width:100%;
    padding:11px;
    border:1px solid #ccd6df;
    border-radius:8px;
    font-size:14px;
}

textarea{
    min-height:100px;
    resize:vertical;
}

.receiver-box{
    display:flex;
    gap:15px;
}

.receiver-box .form-group{
    flex:1;
}

.upload-btn{
    background:linear-gradient(135deg,#4facfe,#2c7ea6);
    color:white;
    border:none;
    padding:12px 22px;
    border-radius:10px;
    cursor:pointer;
    font-size:15px;
    font-weight:bold;
}

.upload-btn:hover{
    opacity:.9;
}

.alert{
    padding:13px;
    border-radius:10px;
    margin-bottom:20px;
    text-align:center;
    font-weight:bold;
}

.success{
    background:#d4edda;
    color:#155724;
}

.error{
    background:#f8d7da;
    color:#721c24;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:linear-gradient(135deg,#4facfe,#2c7ea6);
    color:white;
    padding:12px;
    text-align:left;
}

td{
    padding:12px;
    border:1px solid #e0e6eb;
    font-size:14px;
    vertical-align:top;
}

.view-btn{
    display:inline-block;
    background:#6c5ce7;
    color:white;
    padding:7px 12px;
    border-radius:7px;
    text-decoration:none;
    font-size:13px;
}

.view-btn:hover{
    background:#5144bd;
}

.receiver-tag{
    display:inline-block;
    background:#e0f3ff;
    color:#2c7ea6;
    padding:5px 10px;
    border-radius:15px;
    font-size:12px;
}

.suggestion{
    background:#f3f9ff;
    padding:10px;
    border-radius:8px;
    margin-top:8px;
    color:#444;
}

.suggestion-form{
    min-width:220px;
}

.small-btn{
    background:#2c7ea6;
    color:white;
    border:none;
    padding:7px 12px;
    border-radius:7px;
    cursor:pointer;
    margin-top:7px;
}

.no-data{
    text-align:center;
    color:#999;
    padding:25px;
}

.file-info{
    color:#888;
    font-size:12px;
    margin-top:5px;
}

</style>

</head>

<body>

<div class="container">

<a href="dashboard.php" class="back-btn">
← Back to Dashboard
</a>

<h1>📄 Medical Reports</h1>


<?php if(!empty($message)): ?>

<div class="alert <?php echo $message_type; ?>">
    <?php echo htmlspecialchars($message); ?>
</div>

<?php endif; ?>


<!-- =====================================================
     PATIENT SECTION
     ===================================================== -->

<?php if($usertype == 'patient'): ?>

<div class="box">

<h2>📤 Send Medical Report</h2>

<form method="POST" action="medical_reports.php" enctype="multipart/form-data">

<div class="receiver-box">

<div class="form-group">

<label>Doctor</label>

<select name="receiver_id"
        id="doctor_select">

<option value="">-- Select Doctor --</option>

<?php
if(!empty($doctors)):
    foreach($doctors as $doctor):
?>

<option value="<?php echo $doctor['UserID']; ?>"
        data-type="doctor">

👨‍⚕️ <?php echo htmlspecialchars($doctor['Name']); ?>

</option>

<?php
    endforeach;
else:
?>

<option disabled>No doctor appointments</option>

<?php endif; ?>

</select>

</div>


<div class="form-group">

<label>Trainer</label>

<select name="trainer_select"
        id="trainer_select">

<option value="">-- Select Trainer --</option>

<?php
if(!empty($trainers)):
    foreach($trainers as $trainer):
?>

<option value="<?php echo $trainer['UserID']; ?>">

🏋️ <?php echo htmlspecialchars($trainer['Name']); ?>

</option>

<?php
    endforeach;
else:
?>

<option disabled>No trainer appointments</option>

<?php endif; ?>

</select>

</div>

</div>


<!-- Hidden receiver information -->

<input type="hidden"
       name="receiver_type"
       id="receiver_type">

<input type="hidden"
       name="receiver_id"
       id="receiver_id">


<div class="form-group">

<label>Report Name</label>

<input type="text"
       name="report_name"
       placeholder="Example: Blood Test Report"
       required>

</div>


<div class="form-group">

<label>Medical Report</label>

<input type="file"
       name="report_file"
       accept=".pdf,.jpg,.jpeg,.png"
       required>

<div class="file-info">
PDF, JPG, JPEG or PNG — Maximum 10 MB
</div>

</div>


<button type="submit"
        name="upload_report"
        class="upload-btn">

📤 Send Report

</button>

</form>

</div>


<!-- =====================================================
     PATIENT REPORT HISTORY
     ===================================================== -->

<div class="box">

<h2>📋 My Medical Reports</h2>

<?php if(!empty($patient_reports)): ?>

<table>

<tr>
<th>Report</th>
<th>Sent To</th>
<th>Date</th>
<th>File</th>
<th>Suggestion</th>
</tr>

<?php foreach($patient_reports as $report): ?>

<tr>

<td>
<strong>
<?php echo htmlspecialchars($report['ReportName']); ?>
</strong>
</td>

<td>

<?php if(!empty($report['DoctorName'])): ?>

<span class="receiver-tag">
👨‍⚕️ <?php echo htmlspecialchars($report['DoctorName']); ?>
</span>

<?php elseif(!empty($report['TrainerName'])): ?>

<span class="receiver-tag">
🏋️ <?php echo htmlspecialchars($report['TrainerName']); ?>
</span>

<?php endif; ?>

</td>

<td>
<?php echo date("d M Y h:i A", strtotime($report['UploadDate'])); ?>
</td>

<td>

<a href="<?php echo htmlspecialchars($report['FilePath']); ?>"
   target="_blank"
   class="view-btn">

📄 View

</a>

</td>

<td>

<?php if(!empty($report['Suggestion'])): ?>

<div class="suggestion">

<?php echo nl2br(htmlspecialchars($report['Suggestion'])); ?>

<br>

<small>
<?php echo $report['SuggestedAt']
    ? date("d M Y h:i A", strtotime($report['SuggestedAt']))
    : ''; ?>
</small>

</div>

<?php else: ?>

<span style="color:#aaa;">
No suggestion yet
</span>

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

</table>

<?php else: ?>

<p class="no-data">
No medical reports uploaded yet.
</p>

<?php endif; ?>

</div>


<?php endif; ?>



<!-- =====================================================
     DOCTOR / TRAINER SECTION
     ===================================================== -->

<?php if($usertype == 'doctor' || $usertype == 'trainer'): ?>

<div class="box">

<h2>
<?php echo $usertype == 'doctor'
    ? '📥 Patient Medical Reports'
    : '📥 Client Medical Reports'; ?>
</h2>


<?php if(!empty($received_reports)): ?>

<table>

<tr>
<th>Patient</th>
<th>Report</th>
<th>Uploaded</th>
<th>File</th>
<th>Suggestion</th>
</tr>


<?php foreach($received_reports as $report): ?>

<tr>

<td>

<strong>
<?php echo htmlspecialchars($report['PatientName']); ?>
</strong>

<br>

<small>
<?php echo htmlspecialchars($report['PatientEmail']); ?>
</small>

</td>


<td>

<?php echo htmlspecialchars($report['ReportName']); ?>

</td>


<td>

<?php echo date(
    "d M Y h:i A",
    strtotime($report['UploadDate'])
); ?>

</td>


<td>

<a href="<?php echo htmlspecialchars($report['FilePath']); ?>"
   target="_blank"
   class="view-btn">

📄 View Report

</a>

</td>


<td class="suggestion-form">

<form method="POST" action="medical_reports.php">

<input type="hidden"
       name="report_id"
       value="<?php echo $report['ReportID']; ?>">


<textarea name="suggestion"
          placeholder="Write your suggestion..."
          required><?php echo htmlspecialchars($report['Suggestion'] ?? ''); ?></textarea>


<button type="submit"
        name="add_suggestion"
        class="small-btn">

💬 Save Suggestion

</button>

</form>

</td>

</tr>

<?php endforeach; ?>

</table>

<?php else: ?>

<p class="no-data">

📭 No medical reports have been sent to you yet.

</p>

<?php endif; ?>

</div>

<?php endif; ?>

</div>


<script>

/*
 * Patient can select either Doctor OR Trainer.
 * Only one receiver can be selected at a time.
 */

const doctorSelect = document.getElementById('doctor_select');
const trainerSelect = document.getElementById('trainer_select');

const receiverType = document.getElementById('receiver_type');
const receiverId = document.getElementById('receiver_id');


if(doctorSelect && trainerSelect){

    doctorSelect.addEventListener('change', function(){

        if(this.value !== ''){

            trainerSelect.value = '';

            receiverType.value = 'doctor';
            receiverId.value = this.value;

        }else{

            receiverType.value = '';
            receiverId.value = '';
        }

    });


    trainerSelect.addEventListener('change', function(){

        if(this.value !== ''){

            doctorSelect.value = '';

            receiverType.value = 'trainer';
            receiverId.value = this.value;

        }else{

            receiverType.value = '';
            receiverId.value = '';
        }

    });

}

</script>

</body>
</html>
