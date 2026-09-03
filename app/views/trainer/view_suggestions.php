<!DOCTYPE html>
<html>
<head>
<title>Trainer Suggestions</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:Arial; background:#eaf3ff; padding:30px; }

.back-btn {
    display:inline-block;
    margin-bottom:20px;
    color:#2c7ea6;
    text-decoration:none;
    font-weight:bold;
    font-size:15px;
}

h2 { color:#2c7ea6; margin-bottom:20px; text-align:center; }

.suggestion-item {
    max-width:700px;
    margin:0 auto 20px;
    background:white;
    padding:20px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(26,86,132,0.15);
}

.trainer-name {
    font-weight:bold;
    color:#2c7ea6;
    font-size:16px;
    margin-bottom:8px;
}

.suggestion-text {
    color:#333;
    font-size:14px;
    line-height:1.6;
}

.date {
    color:#aaa;
    font-size:12px;
    margin-top:8px;
}

.no-data {
    text-align:center;
    color:#aaa;
    margin-top:50px;
    font-size:16px;
}
</style>
</head>
<body>

<a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
<h2>Trainer's Suggestions</h2>

<?php if(!empty($suggestions)): ?>
    <?php foreach($suggestions as $row): ?>
    <div class="suggestion-item">
        <div class="trainer-name">Trainer's Name: <?php echo htmlspecialchars($row['TrainerName']); ?></div>
        <div class="suggestion-text"><?php echo nl2br(htmlspecialchars($row['Suggestion'])); ?></div>
        <div class="date"><?php echo date("d M Y, h:i A", strtotime($row['CreatedDate'])); ?></div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="no-data">No suggestions from your trainer yet.</p>
<?php endif; ?>

</body>
</html>
