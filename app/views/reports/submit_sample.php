<!DOCTYPE html>
<html>
<head>
<title>Request Sample Collection</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{
    font-family:Arial;
    background:#eaf3ff;
    padding:20px;
}

.header{
    background:linear-gradient(135deg,#4facfe,#2c7ea6);
    color:white;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-radius:20px;
    margin-bottom:25px;
}

.header a{
    color:white;
    text-decoration:none;
    font-weight:bold;
}

.page-wrap{
    display:flex;
    gap:25px;
    justify-content:center;
    align-items:flex-start;
    flex-wrap:wrap;
}

.form-box{
    width:420px;
    background:white;
    padding:30px;
    border-radius:20px;
    box-shadow:0 8px 20px rgba(23,84,127,0.2);
}

.side-note{
    width:260px;
    background:#2c7ea6;
    color:white;
    padding:25px 20px;
    border-radius:20px;
    box-shadow:0 8px 20px rgba(23,84,127,0.2);
    font-size:14px;
    line-height:1.6;
    text-align:center;
}

.form-box label{
    display:block;
    margin-bottom:6px;
    color:#555;
    font-size:14px;
}

.form-box input, .form-box textarea{
    width:100%;
    padding:10px 12px;
    border:1px solid #ddd;
    border-radius:10px;
    margin-bottom:18px;
    font-size:14px;
}

.submit-btn{
    width:100%;
    padding:12px;
    background:#4facfe;
    color:white;
    border:none;
    border-radius:12px;
    font-size:16px;
    cursor:pointer;
}

.submit-btn:hover{ background:#2c7ea6; }

.msg{
    text-align:center;
    padding:12px;
    border-radius:10px;
    margin-bottom:18px;
    font-size:14px;
}

.msg.success{ background:#d4f8e8; color:#1e824c; }
.msg.error{ background:#fde2e2; color:#c0392b; }
</style>
</head>
<body>

<div class="header">
    <a href="dashboard.php">← Back to Dashboard</a>
    <h2 style="font-weight:normal;">🧪 Request Sample Collection</h2>
    <div style="width:150px;"></div>
</div>

<div class="page-wrap">

    <div class="form-box">

        <?php if(!empty($success)): ?>
            <div class="msg success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if(!empty($error)): ?>
            <div class="msg error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="submit_sample.php" enctype="multipart/form-data">

            <label>Mobile No</label>
            <input type="text" name="mobile_no" placeholder="Enter your contact number" value="<?php echo htmlspecialchars($user['PhoneNo'] ?? ''); ?>" required>

            <label>Collection Address</label>
            <textarea name="address" rows="3" placeholder="Enter full address for sample pickup" required><?php echo htmlspecialchars($patient['Address'] ?? ''); ?></textarea>

            <label>Preferred Date & Time for Sample Collection</label>
            <input type="datetime-local" name="preferred_datetime" required>

            <label>Upload Test / Prescription Photo</label>
            <input type="file" name="photo" accept="image/*" required>

            <button type="submit" class="submit-btn">Submit Request</button>

        </form>

    </div>

    <div class="side-note">
        Our staff will call you within 10 mins about the pricing after you submit the details.
    </div>

</div>

</body>
</html>
