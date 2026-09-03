<?php
session_start();
require_once('dbconnect.php');
if(!isset($_SESSION['user_id']) || $_SESSION['usertype'] != 'Trainer') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Appointment delete করলে
if(isset($_GET['delete'])) {
    $del_id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM appointment WHERE AppointmentID='$del_id' AND TrainerID='$user_id'");
    header("Location: trainer_appointments.php");
    exit();
}

$sql = "SELECT a.*, u.Name, u.Email, u.PhoneNo 
        FROM appointment a 
        JOIN user u ON u.UserID = a.PatientID 
        WHERE a.TrainerID = '$user_id' 
        ORDER BY a.Date DESC";
$appointments = mysqli_query($conn, $sql);
?>

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

<a href="trainer_dashboard.php" class="back-btn">← Back to Dashboard</a>
<h2>My Appointments</h2>

<div class="section">
<?php if(mysqli_num_rows($appointments) > 0): ?>
    <table>
        <tr>
            <th>Patient Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while($appt = mysqli_fetch_assoc($appointments)): ?>
        <tr>
            <td><?php echo $appt['Name']; ?></td>
            <td><?php echo $appt['Email']; ?></td>
            <td><?php echo $appt['PhoneNo']; ?></td>
            <td><?php echo $appt['Date']; ?></td>
            <td><?php echo $appt['Time']; ?></td>
            <td>
                <?php if($appt['Date'] >= date('Y-m-d')): ?>
                    <span class="upcoming">Upcoming</span>
                <?php else: ?>
                    <span class="past">Past</span>
                <?php endif; ?>
            </td>
            <td>
                <a href="trainer_appointments.php?delete=<?php echo $appt['AppointmentID']; ?>" 
                   class="btn-delete" 
                   onclick="return confirm('Delete this appointment?')">Delete</a>
            </td>
			
			<td>
    <a href="give_review.php?trainer_id=<?php echo $row['TrainerID']; ?>"
       style="background:#f5b400;
              color:white;
              padding:7px 12px;
              border-radius:20px;
              text-decoration:none;
              display:inline-block;
              font-size:13px;">
        ⭐ Give Review
    </a>
</td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <p class="no-data">No appointments yet.</p>
<?php endif; ?>
</div>

</body>
</html>