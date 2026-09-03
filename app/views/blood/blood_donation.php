<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Blood Donation Management</title>

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


/* HEADER */

.header{

    background:linear-gradient(
        135deg,
        #4facfe,
        #2c7ea6
    );

    color:white;

    padding:25px;

    text-align:center;

    border-radius:18px;

    max-width:1100px;

    margin:auto;

    box-shadow:0 8px 20px rgba(0,0,0,0.15);
}

.header h1{
    margin-bottom:8px;
}

.header p{
    font-size:14px;
}


/* BACK */

.back{

    max-width:1100px;

    margin:20px auto;

}

.back a{

    color:#2c7ea6;

    text-decoration:none;

    font-weight:bold;
}


/* ALERT */

.alert{

    max-width:1100px;

    margin:15px auto;

    padding:12px;

    border-radius:10px;

    text-align:center;
}

.success{

    background:#d4edda;

    color:#155724;
}

.error{

    background:#f8d7da;

    color:#721c24;
}


/* MAIN */

.container{

    max-width:1100px;

    margin:auto;

    display:grid;

    grid-template-columns:350px 1fr;

    gap:25px;
}


/* BOX */

.box{

    background:white;

    padding:25px;

    border-radius:18px;

    box-shadow:0 7px 20px rgba(0,0,0,0.1);
}

.box h2{

    color:#2c7ea6;

    margin-bottom:20px;

    font-size:20px;
}


/* FORM */

label{

    display:block;

    margin-top:12px;

    margin-bottom:6px;

    color:#555;

    font-size:14px;

    font-weight:bold;
}

input,
select{

    width:100%;

    padding:10px;

    border:1px solid #ddd;

    border-radius:8px;

    font-size:14px;
}

input:focus,
select:focus{

    outline:none;

    border-color:#4facfe;
}


/* BUTTON */

.btn{

    display:inline-block;

    border:none;

    background:linear-gradient(
        135deg,
        #4facfe,
        #2c7ea6
    );

    color:white;

    padding:11px 18px;

    border-radius:10px;

    cursor:pointer;

    text-decoration:none;

    margin-top:15px;

    font-weight:bold;
}

.btn:hover{

    opacity:0.9;
}


/* SEARCH */

.search{

    display:flex;

    gap:10px;

    margin-bottom:20px;
}

.search select{

    flex:1;
}

.search button{

    padding:10px 18px;

    border:none;

    border-radius:8px;

    background:#2c7ea6;

    color:white;

    cursor:pointer;
}

.reset{

    display:inline-block;

    padding:10px 15px;

    background:#eee;

    color:#555;

    border-radius:8px;

    text-decoration:none;
}


/* DONOR CARD */

.donor-card{

    border:1px solid #e1e8ef;

    border-radius:15px;

    padding:18px;

    margin-bottom:15px;

    transition:0.2s;
}

.donor-card:hover{

    box-shadow:0 5px 15px rgba(0,0,0,0.08);

    transform:translateY(-2px);
}

.donor-name{

    font-size:18px;

    font-weight:bold;

    color:#2c7ea6;

    margin-bottom:10px;
}

.blood{

    display:inline-block;

    background:#ffe5e5;

    color:#d63031;

    font-weight:bold;

    padding:5px 12px;

    border-radius:20px;

    margin-bottom:8px;
}

.info{

    color:#555;

    font-size:14px;

    margin:5px 0;
}

.available{

    color:#28a745;

    font-weight:bold;
}


/* MY DONOR */

.my-donor{

    background:#f0f8ff;

    border:2px solid #4facfe;

    padding:15px;

    border-radius:12px;

    margin-bottom:20px;
}

.my-donor h3{

    color:#2c7ea6;

    margin-bottom:8px;
}

.status-btn{

    display:inline-block;

    padding:7px 12px;

    border-radius:8px;

    text-decoration:none;

    color:white;

    background:#28a745;

    font-size:13px;

    margin-top:8px;
}

.delete-btn{

    background:#dc3545;

    margin-left:5px;
}


/* EMPTY */

.empty{

    text-align:center;

    color:#999;

    padding:30px;
}


/* RESPONSIVE */

@media(max-width:800px){

    .container{

        grid-template-columns:1fr;
    }

    .search{

        flex-direction:column;
    }

}

</style>

</head>


<body>


<div class="header">

    <h1>🩸 Blood Donation Management</h1>

    <p>
        Donate blood, find available donors and help save lives.
    </p>

</div>


<div class="back">

    <a href="dashboard.php">
        ← Back to Dashboard
    </a>

</div>


<?php if(!empty($success)): ?>

<div class="alert success">

    <?php echo $success; ?>

</div>

<?php endif; ?>


<?php if(!empty($error)): ?>

<div class="alert error">

    <?php echo $error; ?>

</div>

<?php endif; ?>


<div class="container">


<!-- =========================
     LEFT SIDE
========================= -->

<div>


<div class="box">

<h2>🩸 Become a Donor</h2>


<?php if(empty($my_donor)): ?>


<form method="POST" action="blood_donation.php">


<label>Blood Group</label>

<select name="blood_group" required>

<option value="">Select Blood Group</option>

<option value="A+">A+</option>
<option value="A-">A-</option>

<option value="B+">B+</option>
<option value="B-">B-</option>

<option value="AB+">AB+</option>
<option value="AB-">AB-</option>

<option value="O+">O+</option>
<option value="O-">O-</option>

</select>


<label>Location</label>

<input
    type="text"
    name="location"
    placeholder="e.g. Cumilla"
    required
>


<label>Phone Number</label>

<input
    type="text"
    name="phone"
    placeholder="Your contact number"
    required
>


<label>Last Donation Date</label>

<input
    type="date"
    name="last_donation"
>


<button
    type="submit"
    name="register_donor"
    class="btn"
>

🩸 Register as Donor

</button>


</form>


<?php else: ?>


<?php $my = $my_donor; ?>


<div class="my-donor">

<h3>My Donor Profile</h3>

<p>
<strong>Blood Group:</strong>
<?php echo htmlspecialchars($my['BloodGroup']); ?>
</p>

<p>
<strong>Location:</strong>
<?php echo htmlspecialchars($my['Location']); ?>
</p>

<p>
<strong>Phone:</strong>
<?php echo htmlspecialchars($my['PhoneNo']); ?>
</p>

<p>

<strong>Status:</strong>

<?php if($my['Availability'] == 'Available'): ?>

<span class="available">
Available
</span>

<?php else: ?>

<span style="color:#dc3545;font-weight:bold;">
Not Available
</span>

<?php endif; ?>

</p>


<a
    class="status-btn"
    href="blood_donation.php?toggle=<?php echo $my['DonorID']; ?>"
>

<?php
echo ($my['Availability'] == 'Available')
    ? 'Set Not Available'
    : 'Set Available';
?>

</a>


<a
    class="status-btn delete-btn"
    href="blood_donation.php?delete=<?php echo $my['DonorID']; ?>"
    onclick="return confirm('Remove your donor registration?')"
>

Remove

</a>

</div>


<?php endif; ?>


</div>


</div>


<!-- =========================
     RIGHT SIDE
========================= -->

<div class="box">

<h2>🔎 Find Blood Donors</h2>


<form method="GET" action="blood_donation.php" class="search">

<select name="blood_group">

<option value="">
All Blood Groups
</option>

<option value="A+" <?php if($search_group=="A+") echo "selected"; ?>>A+</option>
<option value="A-" <?php if($search_group=="A-") echo "selected"; ?>>A-</option>

<option value="B+" <?php if($search_group=="B+") echo "selected"; ?>>B+</option>
<option value="B-" <?php if($search_group=="B-") echo "selected"; ?>>B-</option>

<option value="AB+" <?php if($search_group=="AB+") echo "selected"; ?>>AB+</option>
<option value="AB-" <?php if($search_group=="AB-") echo "selected"; ?>>AB-</option>

<option value="O+" <?php if($search_group=="O+") echo "selected"; ?>>O+</option>
<option value="O-" <?php if($search_group=="O-") echo "selected"; ?>>O-</option>

</select>


<button type="submit">
Search
</button>


<a href="blood_donation.php" class="reset">
Reset
</a>

</form>


<?php if(!empty($donors)): ?>


<?php foreach($donors as $donor): ?>


<div class="donor-card">


<div class="donor-name">

👤 <?php echo htmlspecialchars($donor['Name']); ?>

</div>


<span class="blood">

🩸 <?php echo htmlspecialchars($donor['BloodGroup']); ?>

</span>


<div class="info">

📍
<strong>Location:</strong>
<?php echo htmlspecialchars($donor['Location']); ?>

</div>


<div class="info">

📞
<strong>Phone:</strong>
<?php echo htmlspecialchars($donor['PhoneNo']); ?>

</div>


<div class="info available">

● Available for Donation

</div>


</div>


<?php endforeach; ?>


<?php else: ?>


<div class="empty">

🩸 No available donors found.

</div>


<?php endif; ?>


</div>


</div>


</body>

</html>
