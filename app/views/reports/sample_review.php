<!DOCTYPE html>
<html>
<head>
<title>Sample Collection Review</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{
    font-family:Arial;
    background:#eaf3ff;
}

.header{
    background:linear-gradient(135deg,#4facfe,#2c7ea6);
    color:white;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.header a{
    color:white;
    text-decoration:none;
    font-weight:bold;
}

.header h2{
    font-weight:normal;
}

.filter-bar{
    display:flex;
    justify-content:center;
    gap:10px;
    margin:20px 0;
    flex-wrap:wrap;
}

.filter-bar a{
    padding:8px 18px;
    border-radius:20px;
    text-decoration:none;
    background:white;
    color:#2c7ea6;
    font-size:14px;
    box-shadow:0 3px 8px rgba(23,84,127,0.2);
}

.filter-bar a.active{
    background:#4facfe;
    color:white;
}

.grid{
    display:flex;
    flex-wrap:wrap;
    gap:25px;
    justify-content:center;
    padding:10px 20px 40px;
}

.sample-card{
    width:320px;
    background:white;
    border-radius:20px;
    box-shadow:0 8px 20px rgba(23,84,127,0.2);
    overflow:hidden;
}

.sample-card .photo{
    width:100%;
    height:200px;
    object-fit:cover;
    background:#ddd;
    display:block;
}

.sample-card .info{
    padding:15px 18px;
}

.sample-card .info h3{
    color:#2c7ea6;
    margin-bottom:8px;
}

.sample-card .info p{
    font-size:14px;
    color:#444;
    margin-bottom:5px;
}

.status-tag{
    display:inline-block;
    padding:3px 12px;
    border-radius:20px;
    font-size:12px;
    color:white;
    margin-bottom:8px;
}

.status-Pending{ background:#f39c12; }
.status-Accepted{ background:#2ecc71; }
.status-Rejected{ background:#e74c3c; }

.actions{
    display:flex;
    gap:10px;
    margin-top:12px;
}

.actions form{
    flex:1;
}

.btn-accept, .btn-reject{
    width:100%;
    padding:10px;
    border:none;
    border-radius:12px;
    color:white;
    cursor:pointer;
    font-size:14px;
}

.btn-accept{ background:#2ecc71; }
.btn-reject{ background:#e74c3c; }

.remarks-box{
    width:100%;
    padding:6px;
    border-radius:8px;
    border:1px solid #ccc;
    font-size:13px;
    margin-bottom:8px;
}

.empty{
    text-align:center;
    color:#777;
    margin-top:50px;
    font-size:16px;
}
</style>
</head>
<body>

<div class="header">
    <a href="dashboard.php">← Back to Dashboard</a>
    <h2>🧪 Sample Collection Review</h2>
    <div style="width:150px;"></div>
</div>

<div class="filter-bar">
    <a href="sample_review.php?status=Pending" class="<?php echo $filter=='Pending'?'active':''; ?>">Pending</a>
    <a href="sample_review.php?status=Accepted" class="<?php echo $filter=='Accepted'?'active':''; ?>">Accepted</a>
    <a href="sample_review.php?status=Rejected" class="<?php echo $filter=='Rejected'?'active':''; ?>">Rejected</a>
    <a href="sample_review.php?status=All" class="<?php echo $filter=='All'?'active':''; ?>">All</a>
</div>

<div class="grid">

<?php if(!empty($samplesList)): ?>

    <?php foreach($samplesList as $row): ?>

        <div class="sample-card">

            <img class="photo" src="<?php echo htmlspecialchars($row['PhotoPath']); ?>" alt="Sample Photo">

            <div class="info">

                <span class="status-tag status-<?php echo $row['Status']; ?>">
                    <?php echo $row['Status']; ?>
                </span>

                <h3><?php echo htmlspecialchars($row['PatientName']); ?></h3>
                <p>📞 <?php echo htmlspecialchars($row['MobileNo']); ?></p>
                <p>🏠 <?php echo htmlspecialchars($row['Address']); ?></p>
                <p>📅 Preferred: <?php echo date("d M Y, h:i A", strtotime($row['PreferredDateTime'])); ?></p>
                <p>🕒 Requested: <?php echo date("d M Y, h:i A", strtotime($row['RequestDate'])); ?></p>

                <?php if($row['Status'] == 'Pending'): ?>

                    <div class="actions">

                        <form method="POST" action="sample_review.php">
                            <input type="hidden" name="sample_id" value="<?php echo $row['SampleID']; ?>">
                            <input type="hidden" name="action" value="Accepted">
                            <button type="submit" class="btn-accept">Accept</button>
                        </form>

                        <form method="POST" action="sample_review.php">
                            <input type="hidden" name="sample_id" value="<?php echo $row['SampleID']; ?>">
                            <input type="hidden" name="action" value="Rejected">
                            <button type="submit" class="btn-reject">Reject</button>
                        </form>

                    </div>

                <?php else: ?>

                    <p style="margin-top:10px; font-size:13px; color:#777;">
                        Reviewed on <?php echo date("d M Y, h:i A", strtotime($row['ReviewDate'])); ?>
                        <?php if(!empty($row['Remarks'])): ?>
                            <br>Remarks: <?php echo htmlspecialchars($row['Remarks']); ?>
                        <?php endif; ?>
                    </p>

                <?php endif; ?>

            </div>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <div class="empty">No sample requests found for this filter.</div>

<?php endif; ?>

</div>

</body>
</html>
