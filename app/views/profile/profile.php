<!DOCTYPE html>
<html>
<head>
<title>Profile</title>
<style>
body{
font-family:Arial;
padding:30px;
background:url('Images/new.jpg');
background-size:cover;
background-position:center;
background-repeat:no-repeat;
}
.top{
display:flex;
justify-content:flex-end;
margin-bottom:5px;
}
.edit-btn{
background:#4facfe;
color:white;
padding:5px 10px;
text-decoration:none;
border-radius:30px;
border:none;
cursor:pointer;
}
.profile-header{
text-align:center;
background:linear-gradient(135deg,#4facfe,#2c7ea6);
padding:2px;
border-radius:30px;
margin-bottom:10px;
box-shadow:0 5px 15px rgb(21, 69, 109);
width:400px;
margin:auto;
color:white;
}

.profile-header img{
width:110px;
height:110px;
border-radius:50%;
object-fit:cover;
border:4px solid white;
}
.grid{
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    gap:20px;
    max-width:1100px;
    margin:10px auto;
}

.card{
background:white;
padding:2px;
border-radius:25px;
box-shadow:0 5px 10px rgb(100, 136, 159);
text-align:center;
width:220px;
}
.card h4{
color:#4facfe;
margin-bottom:5px;
}
.card input{
width:70%;
padding:10px;
border:1px solid #ccc;
border-radius:30px;
margin-bottom:10px;
}

.save-btn{
display:block;
margin:20px auto;
padding:14px 40px;
background:#4facfe;
color:white;
border:none;
border-radius:15px;
font-size:16px;
cursor:pointer;
}

</style>
</head>
<body>
<div class="top" style="justify-content:space-between;">
<a href="dashboard.php" style="color:#2c7ea6; text-decoration:none; font-weight:bold; font-size:15px;">← Back to Dashboard</a>

<?php if(!$edit){ ?>

<a href="profile.php?edit=1" class="edit-btn">
Edit Profile
</a>

<?php } else { ?>

<div></div>

<?php } ?>

</div>

<form method="POST" action="profile.php" enctype="multipart/form-data">

<!-- PROFILE HEADER -->

<div class="profile-header">

<img src="<?php echo $img; ?>">

<h2><?php echo htmlspecialchars($user['Name']); ?></h2>

<p>User ID: <?php echo $user['UserID']; ?></p>

<?php if($edit){ ?>

<br>
<label style="color:white; font-size:14px; display:block; margin-bottom:6px;">Upload Photo</label>
<input type="file" name="photo" accept="image/*" style="color:white; font-size:13px;">
<br><br>

<?php } ?>

</div>

<!-- GRID -->

<div class="grid">

<!-- NAME -->

<div class="card">
<h4>Name</h4>

<?php if($edit){ ?>

<input type="text" name="name"
value="<?php echo htmlspecialchars($user['Name']); ?>">

<?php } else { ?>

<p><?php echo htmlspecialchars($user['Name']); ?></p>

<?php } ?>

</div>

<!-- EMAIL -->

<div class="card">
<h4>Email</h4>

<?php if($edit){ ?>

<input type="email" name="email"
value="<?php echo htmlspecialchars($user['Email']); ?>">

<?php } else { ?>

<p><?php echo htmlspecialchars($user['Email']); ?></p>

<?php } ?>

</div>

<!-- PHONE -->

<div class="card">
<h4>Phone</h4>

<?php if($edit){ ?>

<input type="text" name="phone"
value="<?php echo htmlspecialchars($user['PhoneNo']); ?>">

<?php } else { ?>

<p><?php echo htmlspecialchars($user['PhoneNo']); ?></p>

<?php } ?>

</div>

<!-- GENDER -->
<div class="card">
<h4>Gender</h4>

<?php if($edit){ ?>

<input type="text" name="gender"
value="<?php echo htmlspecialchars($user['Gender']); ?>">

<?php } else { ?>

<p><?php echo htmlspecialchars($user['Gender']); ?></p>

<?php } ?>

</div>

<!-- DOB -->

<div class="card">
<h4>Date Of Birth</h4>

<?php if($edit){ ?>

<input type="date" name="dob"
value="<?php echo htmlspecialchars($user['DateOfBirth']); ?>">

<?php } else { ?>

<p><?php echo htmlspecialchars($user['DateOfBirth']); ?></p>

<?php } ?>

</div>

<!-- USER TYPE -->

<div class="card">
<h4>User Type</h4>
<p><?php echo htmlspecialchars($user['UserType']); ?></p>
</div>


<?php if($usertype == 'patient'){ ?>

<div class="card">
<h4>Weight</h4>

<?php if($edit){ ?>

<input type="text" name="weight"
value="<?php echo htmlspecialchars($patient_data['Weight'] ?? ''); ?>">

<?php } else { ?>

<p><?php echo htmlspecialchars($patient_data['Weight'] ?? ''); ?></p>

<?php } ?>

</div>

<div class="card">
<h4>Height</h4>

<?php if($edit){ ?>

<input type="text" name="height"
value="<?php echo htmlspecialchars($patient_data['Height'] ?? ''); ?>">

<?php } else { ?>

<p><?php echo htmlspecialchars($patient_data['Height'] ?? ''); ?></p>

<?php } ?>

</div>

<?php } ?>

</div>

<?php if($edit){ ?>

<button type="submit" name="update" class="save-btn">
Save Updates
</button>

<?php } ?>

</form>

</body>
</html>
