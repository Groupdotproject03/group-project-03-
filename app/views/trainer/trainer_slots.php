<!DOCTYPE html>
<html>
<head>
<title>My Schedule</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }

body {
    font-family: Arial;
    background: #eaf3ff;
    min-height: 100vh;
}

.header {
    background: linear-gradient(135deg,#4facfe,#2c7ea6);
    color: white;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header a {
    color: white;
    text-decoration: none;
    font-size: 15px;
}

.container {
    max-width: 700px;
    margin: 40px auto;
    padding: 0 20px;
}

/* ADD BOX */
.add-box {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(23,84,127,0.15);
    margin-bottom: 30px;
}

.add-box h2 {
    color: #2c7ea6;
    margin-bottom: 20px;
    font-size: 18px;
}

.time-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 15px;
}

.time-btn {
    padding: 10px 18px;
    border: 2px solid #4facfe;
    border-radius: 25px;
    background: white;
    color: #2c7ea6;
    cursor: pointer;
    font-size: 14px;
    transition: 0.2s;
}

.time-btn:hover,
.time-btn.selected {
    background: #4facfe;
    color: white;
}

.time-btn.already {
    background: #f0f0f0;
    border-color: #ccc;
    color: #aaa;
    cursor: not-allowed;
}

input[type="hidden"] { display: none; }

.add-btn {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg,#4facfe,#2c7ea6);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    cursor: pointer;
    margin-top: 10px;
}

.add-btn:hover { opacity: 0.9; }

/* SLOTS LIST */
.slots-box {
    background: white;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(23,84,127,0.15);
}

.slots-box h2 {
    color: #2c7ea6;
    margin-bottom: 20px;
    font-size: 18px;
}

.slot-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    background: #eaf3ff;
    border-radius: 12px;
    margin-bottom: 10px;
}

.slot-item span {
    font-size: 16px;
    color: #333;
}

.delete-btn {
    padding: 6px 14px;
    background: #ff4d4d;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
}

.delete-btn:hover { background: #cc0000; }

/* MESSAGE */
.msg {
    text-align: center;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 15px;
}

.msg.success { background: #d4edda; color: #155724; }
.msg.error { background: #f8d7da; color: #721c24; }

.empty {
    text-align: center;
    color: #aaa;
    padding: 20px;
}
</style>
</head>

<body>

<div class="header">
    <h2>🗓️ My Schedule</h2>
    <a href="dashboard.php">← Back to Dashboard</a>
</div>

<div class="container">

<?php if(!empty($msg_text)): ?>
    <div class="msg <?php echo $msg_type; ?>">
        <?php echo $msg_text; ?>
    </div>
<?php endif; ?>

<!-- ADD SLOT -->
<div class="add-box">
    <h2>Add New Time Slot</h2>

    <?php
    $all_slots = [
        '08:00 AM','09:00 AM','10:00 AM','11:00 AM',
        '12:00 PM','01:00 PM','02:00 PM','03:00 PM',
        '04:00 PM','05:00 PM','06:00 PM','07:00 PM'
    ];
    $existing = $existing ?? [];
    ?>

    <form method="POST" action="trainer_slots.php">
        <input type="hidden" name="slot_time" id="selected_slot" value="">

        <div class="time-grid">
            <?php foreach($all_slots as $slot): ?>
                <?php $already = in_array($slot, $existing); ?>
                <button type="button"
                    class="time-btn <?php echo $already ? 'already' : ''; ?>"
                    <?php echo $already ? 'disabled' : ''; ?>
                    onclick="selectSlot(this, '<?php echo $slot; ?>')">
                    <?php echo $slot; ?>
                </button>
            <?php endforeach; ?>
        </div>

        <button type="submit" name="add_slot" class="add-btn">
            ➕ Add Selected Slot
        </button>
    </form>
</div>

<!-- MY SLOTS -->
<div class="slots-box">
    <h2>My Current Slots</h2>

    <?php
    if(!empty($slotsList)):
        foreach($slotsList as $row):
    ?>
        <div class="slot-item">
            <span>🕐 <?php echo $row['SlotTime']; ?></span>
            <a href="trainer_slots.php?delete=<?php echo $row['SlotID']; ?>"
               class="delete-btn"
               onclick="return confirm('Remove this slot?')">
               Remove
            </a>
        </div>
    <?php
        endforeach;
    else:
        echo "<p class='empty'>No slots added yet</p>";
    endif;
    ?>
</div>

</div>

<script>
function selectSlot(btn, time) {
    document.querySelectorAll('.time-btn').forEach(b => b.classList.remove('selected'));
    btn.classList.add('selected');
    document.getElementById('selected_slot').value = time;
}
</script>

</body>
</html>
