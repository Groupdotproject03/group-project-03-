<!DOCTYPE html>
<html>
<head>
<title>Trainer Notes</title>
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

h2 { color:#2c7ea6; margin-bottom:20px; }

.layout {
    display:flex;
    gap:30px;
    flex-wrap:wrap;
    justify-content:center;
}

.form-box {
    width:380px;
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(26,86,132,0.2);
}

.form-box h3 { color:#2c7ea6; margin-bottom:15px; }

.form-box select,
.form-box textarea {
    width:100%;
    padding:10px;
    margin-bottom:15px;
    border:1px solid #ccc;
    border-radius:10px;
    font-size:14px;
    font-family:Arial;
}

.form-box textarea { height:150px; resize:vertical; }

.form-box button {
    width:100%;
    padding:12px;
    background:linear-gradient(135deg,#4facfe,#2c7ea6);
    color:white;
    border:none;
    border-radius:10px;
    font-size:15px;
    cursor:pointer;
}

.form-box button:hover { opacity:0.9; }
.success-msg { color:#28a745; margin-bottom:12px; font-size:14px; }

.history-box {
    flex:1;
    min-width:380px;
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(26,86,132,0.2);
}

.history-box h3 { color:#2c7ea6; margin-bottom:15px; }

.note-item {
    padding:15px;
    border-radius:12px;
    background:#eaf3ff;
    margin-bottom:12px;
}

.note-item .client-name {
    font-weight:bold;
    color:#2c7ea6;
    margin-bottom:5px;
    font-size:15px;
}

.note-item .note-text {
    color:#333;
    font-size:14px;
    line-height:1.5;
}

.note-item .date {
    color:#aaa;
    font-size:12px;
    margin-top:5px;
}

.no-data { color:#aaa; text-align:center; padding:20px; }
</style>
</head>
<body>

<a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
<h2>📋 Client Notes</h2>

<div class="layout">

    <div class="form-box">
        <h3>Send Note to Client</h3>

        <?php if(!empty($msg)): ?>
            <p class="success-msg">✅ <?php echo htmlspecialchars($msg); ?></p>
        <?php endif; ?>

        <form method="POST">
            <select name="client_id" required>
                <option value="">Select Client</option>
                <?php foreach($clients as $c): ?>
                    <option value="<?php echo $c['UserID']; ?>">
                        <?php echo htmlspecialchars($c['Name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <textarea name="suggestion"
                      placeholder="Write your note, advice or feedback here..."
                      required></textarea>

            <button type="submit">📤 Send Note</button>
        </form>
    </div>

    <div class="history-box">
        <h3>Sent Notes</h3>

        <?php if(!empty($history)): ?>
            <?php foreach($history as $row): ?>
            <div class="note-item">
                <div class="client-name">👤 <?php echo htmlspecialchars($row['ClientName']); ?></div>
                <div class="note-text"><?php echo nl2br(htmlspecialchars($row['Suggestion'])); ?></div>
                <div class="date"><?php echo date("d M Y, h:i A", strtotime($row['CreatedDate'])); ?></div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-data">No notes sent yet.</p>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
