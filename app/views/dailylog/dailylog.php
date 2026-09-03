<!DOCTYPE html>
<html>
<head>
<title>Daily Log</title>
<style>
body{
    font-family:Arial;
    background-image:url("Images/new.jpg");
    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
    min-height:96vh;
    margin:0;
}

.back-btn{
    position:absolute;
    top:20px;
    left:20px;
    background:white;
    padding:10px 18px;
    text-decoration:none;
    color:#333;
    border-radius:30px;
    font-weight:bold;
    box-shadow:0 5px 12px rgba(20,100,127,1);
}

h2{
    text-align:center;
    padding-top:20px;
    color:#2c7ea6;
    
}

.form-grid{
    display:flex;
    flex-wrap:wrap;
    gap:30px;
    justify-content:center;
    padding-top:60px;
    padding-bottom:20px;
}

.input-card{
    width:200px;
    background:white;
    padding:20px;
    border-radius:30px;
    box-shadow:0 5px 12px rgba(20,100,127,1);
}

.input-card label{
    display:block;
    margin-bottom:10px;
    font-weight:bold;
    text-align:center;
    font-size:14px;
}

.input-card input,
.input-card select{
    width:100%;
    padding:10px;
    border:2px solid #ccc;
    border-radius:10px;
    box-sizing:border-box;
    font-size:14px;
}

/* BP card is wider — two inputs side by side */
.bp-card{
    width:220px;
}

.bp-row{
    display:flex;
    gap:8px;
}

.bp-row input{
    flex:1;
    padding:10px;
    border:2px solid #ccc;
    border-radius:10px;
    box-sizing:border-box;
    font-size:14px;
}

.bp-hint{
    font-size:11px;
    color:#888;
    text-align:center;
    margin-top:5px;
}

/* section divider */
.section-label{
    width:100%;
    text-align:center;
    font-size:16px;
    font-weight:bold;
    color:white;
    text-shadow:0 1px 6px rgba(0,0,0,0.4);
    padding:10px 0 0;
}

button{
    width:250px;
    padding:15px;
    background:#4facfe;
    color:white;
    border:none;
    border-radius:30px;
    cursor:pointer;
    display:block;
    margin:40px auto;
    font-size:16px;
}

button:hover{ background:#2f8dfd; }

/* RESULT */
.result{
    display:flex;
    flex-wrap:wrap;
    gap:25px;
    justify-content:center;
    align-items:flex-start;
    padding:20px;
}

.result-card{
    width:220px;
    background:#f5f9ff;
    padding:25px 20px;
    border-radius:30px;
    text-align:center;
    font-size:18px;
    box-shadow:0 5px 12px rgba(20,100,127,1);
}

.result-card .label{
    font-size:13px;
    color:#888;
    margin-bottom:8px;
}

.result-card .value{
    font-size:22px;
    font-weight:bold;
    color:#2c7ea6;
}

.result-card.comment-card{
    width:220px;
    font-size:14px;
    color:#333;
    text-align:left;
}

.tip{
    padding:5px 0;
    border-bottom:1px solid #e0eaf5;
    font-size:13px;
    color:#444;
    line-height:1.5;
}

.tip:last-child{ border-bottom:none; }

.status-normal  { color:#28a745; font-weight:bold; }
.status-warning { color:#e67e00; font-weight:bold; }
.status-danger  { color:#dc3545; font-weight:bold; }

.message{
    text-align:center;
    color:white;
    font-weight:bold;
    margin-top:180px;
    font-size:22px;
    text-shadow:0 2px 8px rgba(0,0,0,0.5);
}
</style>
</head>
<body>

<a href="dashboard.php" class="back-btn">← Back</a>
<h2>Daily Health Log</h2>

<?php if(!$already_logged || isset($_POST['done'])): ?>

<form method="POST" action="dailylog.php">
<div class="form-grid">

    <!-- Basic Info -->

    <div class="input-card">
        <label>Weight (kg)</label>
        <input type="number" step="0.1" name="weight" required>
    </div>

    <div class="input-card">
        <label>Height (cm)</label>
        <input type="number" name="height" required>
    </div>

    <!-- Activity -->

    <div class="input-card">
        <label>Sleep Hours</label>
        <input type="number" step="0.5" name="sleep_hours" required>
    </div>

    <div class="input-card">
        <label>Step Count</label>
        <input type="number" name="step_count" required>
    </div>

    <div class="input-card">
        <label>Water Intake (L)</label>
        <input type="number" step="0.1" name="water_intake" required>
    </div>

    <div class="input-card">
        <label>Workout Done?</label>
        <select name="workout_done" required>
            <option value="" disabled selected>Select</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>
    </div>

    <div class="input-card">
        <label>Medicine Taken?</label>
        <select name="meds_taken" required>
            <option value="" disabled selected>Select</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
        </select>
    </div>

    <!-- Health Vitals -->

    <div class="input-card bp-card">
        <label>Blood Pressure (mm Hg)</label>
        <div class="bp-row">
            <input type="number" name="bp_systolic"  placeholder="Systolic">
            <input type="number" name="bp_diastolic" placeholder="Diastolic">
        </div>
        <p class="bp-hint">Normal: &lt;120 / &lt;80</p>
    </div>

    <div class="input-card bp-card">
        <label>Blood Sugar (mmol/L)</label>
        <div class="bp-row">
            <input type="number" step="0.1" name="blood_sugar" placeholder="Value">
            <select name="sugar_type" style="flex:1; padding:10px; border:2px solid #ccc; border-radius:10px; font-size:14px;">
                <option value="fasting">Fasting</option>
                <option value="postmeal">After Meal</option>
            </select>
        </div>
        <p class="bp-hint">Normal fasting: 3.9–5.5 | After meal: &lt;7.8</p>
    </div>

</div>

<button type="submit" name="submit">Save Log</button>
</form>

<?php endif; ?>

<?php if($submitted && !empty($resultData)): ?>

<div class="result">

    <div class="result-card">
        <div class="label">BMI</div>
        <div class="value"><?php echo round($resultData['bmi'],1); ?></div>
        <div style="font-size:13px; margin-top:6px; color:#555;">
            <?php
            $bmiVal = $resultData['bmi'];
            if($bmiVal < 18.5)      echo "Underweight";
            elseif($bmiVal <= 24.9) echo "Normal ✓";
            elseif($bmiVal <= 29.9) echo "Overweight";
            else                 echo "Obese";
            ?>
        </div>
    </div>

    <div class="result-card">
        <div class="label">Health Score</div>
        <div class="value"><?php echo $resultData['score']; ?>/100</div>
    </div>

    <?php if($sys > 0): ?>
    <div class="result-card">
        <div class="label">Blood Pressure</div>
        <div class="value"><?php echo $sys."/".$dia; ?></div>
        <div style="font-size:13px; margin-top:6px;"
             class="<?php echo ($resultData['bp_status'] == 'Normal') ? 'status-normal' : (strpos($resultData['bp_status'],'Crisis') !== false ? 'status-danger' : 'status-warning'); ?>">
            <?php echo $resultData['bp_status']; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if($sugar > 0): ?>
    <div class="result-card">
        <div class="label">Blood Sugar</div>
        <div class="value"><?php echo $sugar; ?> mmol/L</div>
        <div style="font-size:13px; margin-top:6px;"
             class="<?php echo (strpos($resultData['sugar_status'],'Normal') !== false) ? 'status-normal' : (strpos($resultData['sugar_status'],'High') !== false ? 'status-danger' : 'status-warning'); ?>">
            <?php echo $resultData['sugar_status']; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="result-card comment-card">
        <div class="label" style="text-align:center; margin-bottom:10px;">💡 Suggestions</div>
        <?php
        $tips = [];

        // Health score comment
        $tips[] = "🏥 " . $resultData['comment'];

        // Blood pressure suggestion
        if($sys > 0 && $dia > 0 && $resultData['bp_status'] != "Normal") {
            $tips[] = "🩺 BP is " . $resultData['bp_status'] . " — reduce salt & stress";
        }

        // Blood sugar suggestion
        if($sugar > 0 && strpos($resultData['sugar_status'], "Normal") === false) {
            $tips[] = "🩸 Blood sugar is " . $resultData['sugar_status'] . " — reduce sugar & carbs";
        }

        foreach($tips as $tip) {
            echo "<div class='tip'>$tip</div>";
        }
        ?>
    </div>

</div>

<form method="POST" action="dailylog.php">
    <button type="submit" name="done">Done</button>
</form>

<?php endif; ?>

<?php if($already_logged && !$submitted): ?>
<div class="message">
    You have already submitted today's health log.
</div>
<?php endif; ?>

</body>
</html>
