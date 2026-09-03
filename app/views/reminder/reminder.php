<!DOCTYPE html>
<html>
<head>
<title>Set Reminder</title>
<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial;
}
body{
background:#eaf3ff;
display:flex;
justify-content:center;
align-items:center;
height:100vh;
background:url('Images/new.jpg');
background-size:cover;
background-position:center;
background-repeat:no-repeat;
}
.container{
width:400px;
background:white;
padding:30px;
border-radius:25px;
box-shadow:0 10px 25px rgba(19, 100, 140, 1);
}
h2{
text-align:center;
margin-bottom:25px;
color:#333;
}
.input-box{
margin-bottom:20px;
}
.input-box label{
display:block;
margin-bottom:8px;
font-weight:bold;
}
.input-box input,
.input-box select{
width:100%;
padding:12px;
border:1px solid #cccccc;
border-radius:10px;
font-size:15px;
}
button{
width:100%;
padding:14px;
border:none;
background:#4facfe;
color:white;
font-size:17px;
border-radius:12px;
cursor:pointer;
}
button:hover{
background:#2f8dfd;
}
.success{
text-align:center;
color:green;
margin-bottom:15px;
}
.back{
display:block;
text-align:center;
margin-top:15px;
text-decoration:none;
color:#4facfe;
}
</style>
</head>
<body>
<a href="dashboard.php" style="position:fixed; top:20px; left:20px; color:#2c7ea6; text-decoration:none; font-weight:bold; font-size:15px;">← Back to Dashboard</a>
<div class="container">
<h2>Set Reminder</h2>
<?php
if(!empty($success))
{
    echo "<p class='success'>" . htmlspecialchars($success) . "</p>";
}
?>
<form method="POST">
<div class="input-box">
<label>Reminder Type</label>
<select name="type" required>
<option value="Take Medicine">Take Medicine</option>
<option value="Drink Water">Drink Water</option>
<option value="Exercise">Exercise</option>
<option value="Sleep">Sleep</option>
</select>
</div>
<div class="input-box">
<label>Select Date</label>
<input type="date" name="day" required>
</div>
<div class="input-box">
<label>Select Time</label>
<input type="time" name="time" required>
</div>
<button type="submit" name="save">
Save Reminder
</button>
</form>
<a class="back" href="dashboard.php">
Back to Dashboard
</a>
</div>
</body>
</html>
