<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Trainer Profile - HealthCore</title>
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
.card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    max-width: 550px;
    margin: auto;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}
.card h2 {
    color: #2c7ea6;
    margin-bottom: 25px;
    text-align: center;
}
.profile-img {
    text-align: center;
    margin-bottom: 20px;
}
.profile-img img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #4facfe;
}
.form-group { margin-bottom: 15px; }
label { display: block; margin-bottom: 5px; color: #555; font-size: 14px; }
input, select {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
}
input:focus { border-color: #4facfe; outline: none; }
.readonly-field {
    background: #f5f5f5;
    color: #888;
}
.btn {
    width: 100%;
    padding: 12px;
    background: #4facfe;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 10px;
}
.btn:hover { background: #2f8dfd; }
.alert-success {
    background: #d4edda;
    color: #155724;
    padding: 12px;
    border-radius: 8px;
    text-align: center;
    margin-bottom: 20px;
}
</style>
</head>
<body>

<a href="dashboard.php" class="back-btn">← Back to Dashboard</a>

<div class="card">
    <div class="profile-img">
        <img src="<?php echo (!empty($trainer['ProfilePhoto'])) ? htmlspecialchars($trainer['ProfilePhoto']) : 'Images/default.png'; ?>" alt="Profile">
    </div>
    <h2>My Profile</h2>

    <?php if(!empty($success)): ?>
        <div class="alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" value="<?php echo htmlspecialchars($trainer['Name'] ?? ''); ?>" class="readonly-field" readonly>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" value="<?php echo htmlspecialchars($trainer['Email'] ?? ''); ?>" class="readonly-field" readonly>
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($trainer['PhoneNo'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label>Expertise</label>
            <input type="text" name="expertise" value="<?php echo htmlspecialchars($trainer['Expertise'] ?? ''); ?>" placeholder="e.g. Weight Loss, Yoga">
        </div>
        <div class="form-group">
            <label>Certification ID</label>
            <input type="text" name="certification" value="<?php echo htmlspecialchars($trainer['CertificationID'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label>Years of Experience</label>
            <input type="number" name="experience" value="<?php echo htmlspecialchars($trainer['YearsOfExperience'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label>Specialization</label>
            <input type="text" name="specialization" value="<?php echo htmlspecialchars($trainer['Specialization'] ?? ''); ?>" placeholder="e.g. Nutrition, Cardio">
        </div>
        <div class="form-group">
            <label>Available Time</label>
            <input type="text" name="available" value="<?php echo htmlspecialchars($trainer['AvailableTime'] ?? ''); ?>" placeholder="e.g. 9am - 5pm">
        </div>
		
		<div class="form-group">
            <label>Profile Photo</label>
            <input type="file" name="photo" accept="image/*">
        </div>

        <button type="submit" class="btn">Update Profile</button>
    </form>
</div>

</body>
</html>
