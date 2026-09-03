<!DOCTYPE html>
<html>
<head>
<title>Book a Trainer</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }

body {
    font-family: Arial;
    background: #eaf3ff;
    padding: 20px;
}

h1 {
    text-align: center;
    color: #2c7ea6;
    margin-bottom: 25px;
}

.back-btn {
    display: inline-block;
    margin-bottom: 20px;
    color: #2c7ea6;
    text-decoration: none;
    font-weight: bold;
}

/* ALERT */
.alert {
    text-align: center;
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 15px;
}
.success { background:#d4edda; color:#155724; }
.error-msg { background:#f8d7da; color:#721c24; }

/* GRID */
.trainer-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 25px;
    justify-content: center;
}

/* CARD */
.trainer-card {
    background: white;
    border-radius: 20px;
    width: 320px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: 0.3s;
}

.trainer-card:hover { transform: translateY(-5px); }

.card-top {
    background: linear-gradient(135deg,#4facfe,#2c7ea6);
    padding: 25px;
    text-align: center;
    color: white;
}

.card-top img {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    border: 3px solid white;
    object-fit: cover;
}

.card-top h3 { margin-top: 10px; }

.card-body { padding: 20px; }

.badge {
    display: inline-block;
    background: #e0f3ff;
    color: #2c7ea6;
    padding: 4px 10px;
    border-radius: 20px;
    margin: 3px;
    font-size: 12px;
}

.info-line {
    font-size: 14px;
    color: #555;
    margin: 5px 0;
}

/* DATE */
.date-input {
    width: 100%;
    padding: 9px;
    margin-top: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
}

/* SLOTS */
.slots-title {
    font-size: 14px;
    color: #2c7ea6;
    font-weight: bold;
    margin: 12px 0 8px;
}

.slot-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 12px;
}

.slot-btn {
    padding: 8px 14px;
    border: 2px solid #4facfe;
    border-radius: 20px;
    background: white;
    color: #2c7ea6;
    cursor: pointer;
    font-size: 13px;
    transition: 0.2s;
}

.slot-btn:hover { background: #4facfe; color: white; }

.slot-btn.booked {
    background: #f0f0f0;
    border-color: #ccc;
    color: #aaa;
    cursor: not-allowed;
    text-decoration: line-through;
}

.slot-btn.selected {
    background: #4facfe;
    color: white;
}

.no-slots {
    font-size: 13px;
    color: #aaa;
    text-align: center;
    padding: 10px;
}

/* BOOK BTN */
.book-btn {
    width: 100%;
    padding: 11px;
    background: linear-gradient(135deg,#4facfe,#2c7ea6);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    cursor: pointer;
    font-weight: bold;
}

.book-btn:hover { opacity: 0.9; }
</style>
</head>

<body>

<a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
<h1>Book a Trainer</h1>

<?php if(!empty($success)): ?>
    <div class="alert success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if(!empty($error)): ?>
    <div class="alert error-msg"><?php echo $error; ?></div>
<?php endif; ?>

<div class="trainer-grid">

<?php if(!empty($trainers)): ?>
<?php foreach($trainers as $t): 

    $photo = (!empty($t['ProfilePhoto']) && file_exists($t['ProfilePhoto']))
        ? $t['ProfilePhoto']
        : 'Images/default.png';

    $slots = $t['slots'] ?? [];
?>

<div class="trainer-card">

    <div class="card-top">
        <img src="<?php echo $photo; ?>">
        <h3><?php echo htmlspecialchars($t['Name']); ?></h3>
        <p><?php echo htmlspecialchars($t['Email']); ?></p>
    </div>

    <div class="card-body">

        <div>
            <?php if(!empty($t['Expertise'])): ?>
                <span class="badge"><?php echo htmlspecialchars($t['Expertise']); ?></span>
            <?php endif; ?>
            <?php if(!empty($t['Specialization'])): ?>
                <span class="badge"><?php echo htmlspecialchars($t['Specialization']); ?></span>
            <?php endif; ?>
        </div>

        <p class="info-line">🏅 <?php echo $t['YearsOfExperience'] ?? 0; ?> years experience</p>
        <p class="info-line">📞 <?php echo htmlspecialchars($t['PhoneNo'] ?? 'N/A'); ?></p>

        <form method="POST" action="trainer.php">
            <input type="hidden" name="trainer_id" value="<?php echo $t['Trainer_id']; ?>">
            <input type="hidden" name="slot_time" id="slot_<?php echo $t['Trainer_id']; ?>" value="">

            <!-- DATE PICK -->
            <input type="date"
                   name="date"
                   class="date-input"
                   id="date_<?php echo $t['Trainer_id']; ?>"
                   min="<?php echo date('Y-m-d'); ?>"
                   required
                   onchange="loadSlots(<?php echo $t['Trainer_id']; ?>)">

            <!-- SLOTS -->
            <div class="slots-title">Available Slots:</div>

            <div class="slot-grid" id="slots_<?php echo $t['Trainer_id']; ?>">

                <?php if(!empty($slots)): ?>
                    <?php foreach($slots as $slot): ?>
                        <button type="button"
                            class="slot-btn"
                            data-time="<?php echo $slot['SlotTime']; ?>"
                            data-trainer="<?php echo $t['Trainer_id']; ?>"
                            onclick="selectSlot(this, <?php echo $t['Trainer_id']; ?>)">
                            <?php echo $slot['SlotTime']; ?>
                        </button>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-slots">No slots available</p>
                <?php endif; ?>

            </div>

            <button type="submit" name="book" class="book-btn">
                📅 Book Appointment
            </button>

        </form>
    </div>

</div>

<?php endforeach; ?>
<?php endif; ?>

</div>

<script>
// Select slot
function selectSlot(btn, trainerId) {
    document.querySelectorAll(`[data-trainer="${trainerId}"]`)
        .forEach(b => b.classList.remove('selected'));

    btn.classList.add('selected');
    document.getElementById('slot_' + trainerId).value = btn.getAttribute('data-time');
}

// When date changes — check booked slots via AJAX
function loadSlots(trainerId) {
    const date = document.getElementById('date_' + trainerId).value;
    if(!date) return;

    document.querySelectorAll(`[data-trainer="${trainerId}"]`).forEach(btn => {
        btn.classList.remove('booked', 'selected');
        btn.disabled = false;
    });

    fetch(`get_booked_slots.php?trainer_id=${trainerId}&date=${date}`)
        .then(res => res.json())
        .then(bookedSlots => {
            document.querySelectorAll(`[data-trainer="${trainerId}"]`).forEach(btn => {
                if(bookedSlots.includes(btn.getAttribute('data-time'))) {
                    btn.classList.add('booked');
                    btn.disabled = true;
                }
            });
        });
}
</script>

</body>
</html>