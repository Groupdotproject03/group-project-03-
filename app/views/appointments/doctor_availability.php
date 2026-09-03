<!DOCTYPE html>
<html>
<head>
<title>Doctor Availability</title>

<style>

/* SAME AS DASHBOARD */
body{
    margin:0;
    font-family:Arial;
    background:#eaf3ff;
}

/* HEADER SAME */
.header{
    background:#4facfe;
    color:white;
    padding:15px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

/* MAIN */
.container{
    display:flex;
    gap:20px;
    padding:20px;
}

/* LEFT CARD (SAME STYLE AS DASHBOARD CARD) */
.profile{
    width:30%;
    background:linear-gradient(135deg,#4facfe,#0b1f4d);
    color:white;
    padding:20px;
    border-radius:20px;
}

/* RIGHT FORM CARD */
.form-box{
    width:70%;
    background:white;
    padding:20px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

/* INPUT */
input{
    width:100%;
    padding:10px;
    margin:10px 0;
    border:1px solid #ccc;
    border-radius:8px;
}

/* BUTTON SAME STYLE */
button{
    width:100%;
    padding:10px;
    background:#4facfe;
    border:none;
    color:white;
    border-radius:8px;
    cursor:pointer;
}

/* MESSAGE */
.msg{
    text-align:center;
    color:yellow;
}

/* PROFILE TEXT */
.profile p{
    margin:8px 0;
    padding:5px;
    background:rgba(255,255,255,0.2);
    border-radius:8px;
}

a{
    color:white;
    text-decoration:none;
}

</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <div>Doctor Availability</div>
    <a href="dashboard.php">Back</a>
</div>


<div class="container">

<!-- LEFT PROFILE -->
<div class="profile">

<h3>My Profile</h3>

<p><b>ID:</b> <?php echo $doctor_id; ?></p>
<p><b>License:</b> <?php echo htmlspecialchars($data['LicenceNo'] ?? 'Not set'); ?></p>
<p><b>Hospital:</b> <?php echo htmlspecialchars($data['HospitalName'] ?? 'Not set'); ?></p>
<p><b>Specialization:</b> <?php echo htmlspecialchars($data['Specialization'] ?? 'Not set'); ?></p>
<p><b>Time:</b> <?php echo htmlspecialchars($data['AvailableTime'] ?? 'Not set'); ?></p>

</div>


<!-- RIGHT FORM -->
<div class="form-box">

<h3>Update Availability</h3>

<?php if(!empty($msg)){ ?>
<p class="msg"><?php echo htmlspecialchars($msg); ?></p>
<?php } ?>

<form method="POST" action="doctor_availability.php">

<input type="text" name="license" placeholder="License No"
value="<?php echo htmlspecialchars($data['LicenceNo'] ?? ''); ?>">

<input type="text" name="time" placeholder="Available Time"
value="<?php echo htmlspecialchars($data['AvailableTime'] ?? ''); ?>">

<input type="text" name="hospital" placeholder="Hospital Name"
value="<?php echo htmlspecialchars($data['HospitalName'] ?? ''); ?>">

<input type="text" name="special" placeholder="Specialization"
value="<?php echo htmlspecialchars($data['Specialization'] ?? ''); ?>">

<button type="submit" name="save">Update</button>

</form>

</div>

</div>

</body>
</html>
