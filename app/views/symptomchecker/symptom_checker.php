<!DOCTYPE html>
<html>
<head>
    <title>Symptom Checker</title>
    <style>
        body{
            font-family:Arial;
            background:#f4f7fb;
        }

        .box{
            width:520px;
            margin:40px auto;
            background:white;
            padding:25px;
            border-radius:12px;
            box-shadow:0 5px 15px rgba(0,0,0,.1);
        }

        h2{
            text-align:center;
            color:#1e88e5;
        }

        input,button{
            width:100%;
            padding:12px;
            margin-top:12px;
            border-radius:8px;
            border:1px solid #ccc;
            font-size:15px;
            box-sizing:border-box;
        }

        button{
            background:#1e88e5;
            color:white;
            border:none;
            cursor:pointer;
        }

        button:hover{
            background:#1565c0;
        }

        .result{
            margin-top:20px;
            background:#e8f5e9;
            padding:18px;
            border-radius:10px;
        }

        .note{
            color:#666;
            font-size:13px;
            margin-top:12px;
        }

        .emergency{
            background:#ffebee;
            color:#c62828;
            padding:12px;
            border-radius:8px;
            margin-top:12px;
            font-weight:bold;
        }
    </style>
</head>
<body>

<a href="dashboard.php" style="position:fixed; top:20px; left:20px; color:#2c7ea6; text-decoration:none; font-weight:bold; font-size:15px;">← Back to Dashboard</a>

<div class="box">

    <h2>🩺 Symptom Checker</h2>

    <form method="POST">

        <input
            type="text"
            name="symptom"
            list="symptomList"
            placeholder="Search or select a symptom..."
            value="<?= htmlspecialchars($selected ?? '') ?>"
            required>

        <datalist id="symptomList">
            <?php if(!empty($symptoms)): ?>
                <?php foreach($symptoms as $s): ?>
                    <option value="<?= htmlspecialchars($s['SymptomName']); ?>">
                <?php endforeach; ?>
            <?php endif; ?>
        </datalist>

        <button name="check">Check Symptom</button>

    </form>

    <?php if(!empty($result)){ ?>

        <div class="result">

            <h3>Possible Condition</h3>
            <p><?= htmlspecialchars($result['PossibleCondition']); ?></p>

            <h3>Recommended Department</h3>
            <p><?= htmlspecialchars($result['Department']); ?></p>

            <h3>Advice</h3>
            <p><?= htmlspecialchars($result['Advice']); ?></p>

            <?php
            $emergency = ['Chest Pain','Shortness of Breath','Swollen Face'];
            if(in_array($result['SymptomName'],$emergency)){
                echo '<div class="emergency">🚨 This symptom may require emergency medical attention.</div>';
            }
            ?>

            <p class="note">
                This is not a medical diagnosis. Please consult a qualified doctor.
            </p>

        </div>

    <?php } ?>

</div>

</body>
</html>
