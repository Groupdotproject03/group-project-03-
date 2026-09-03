<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Appointment History</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial, sans-serif;
    background:#eaf3ff;
    min-height:100vh;
    padding:30px;
}


/* BACK BUTTON */

.back-btn{
    display:inline-block;
    margin-bottom:20px;
    color:#2c7ea6;
    text-decoration:none;
    font-weight:bold;
}

.back-btn:hover{
    text-decoration:underline;
}


/* TITLE */

h1{
    text-align:center;
    color:#2c7ea6;
    margin-bottom:30px;
}


/* SECTION */

.section{
    max-width:1000px;
    margin:0 auto 30px;
    background:white;
    padding:25px;
    border-radius:18px;
    box-shadow:0 7px 22px rgba(0,0,0,0.1);
}

.section-title{
    color:#2c7ea6;
    font-size:20px;
    margin-bottom:18px;
    padding-bottom:10px;
    border-bottom:2px solid #4facfe;
}


/* TABLE */

.table-wrapper{
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:linear-gradient(
        135deg,
        #4facfe,
        #2c7ea6
    );

    color:white;
    padding:12px;
    text-align:center;
    font-size:14px;
}

td{
    padding:12px;
    border:1px solid #dde6f0;
    text-align:center;
    font-size:14px;
    color:#555;
}

tr:hover td{
    background:#f0f8ff;
}


/* STATUS */

.status{
    display:inline-block;
    padding:5px 11px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
}


/* Accepted */

.status-accepted{
    background:#d4edda;
    color:#155724;
}


/* Pending */

.status-pending{
    background:#fff3cd;
    color:#856404;
}


/* Rejected */

.status-rejected{
    background:#f8d7da;
    color:#721c24;
}


/* Other */

.status-other{
    background:#e2e3e5;
    color:#555;
}


/* NO DATA */

.no-data{
    text-align:center;
    color:#999;
    padding:30px;
    font-size:14px;
}


/* APPOINTMENT ID */

.appointment-id{
    font-weight:bold;
    color:#2c7ea6;
}


/* MOBILE */

@media(max-width:700px){

    body{
        padding:15px;
    }

    .section{
        padding:15px;
    }

    th,td{
        padding:9px;
        font-size:12px;
    }

}

</style>

</head>


<body>


<a href="dashboard.php" class="back-btn">
    ← Back to Dashboard
</a>


<h1>📜 Appointment History</h1>


<!-- ==================================================
     DOCTOR HISTORY
================================================== -->

<div class="section">

    <div class="section-title">
        👨‍⚕️ Doctor Appointment History
    </div>


    <?php if(!empty($doctorHistory)): ?>

    <div class="table-wrapper">

    <table>

        <tr>
            <th>Appointment ID</th>
            <th>Doctor</th>
            <th>Date</th>
            <th>Time</th>
            <th>Medication</th>
            <th>Status</th>
        </tr>


        <?php foreach($doctorHistory as $row): ?>

        <tr>

            <td class="appointment-id">
                #<?php echo $row['AppointmentID']; ?>
            </td>


            <td>
                <?php
                echo htmlspecialchars($row['DoctorName']);
                ?>
            </td>


            <td>
                <?php
                echo date(
                    "d M Y",
                    strtotime($row['Date'])
                );
                ?>
            </td>


            <td>
                <?php
                echo date(
                    "h:i A",
                    strtotime($row['Time'])
                );
                ?>
            </td>


            <td>
                <?php
                echo !empty($row['Medication'])
                    ? htmlspecialchars($row['Medication'])
                    : "—";
                ?>
            </td>


            <td>

                <?php

                $status = $row['Status'] ?? 'Pending';

                if($status == 'Accepted'):

                ?>

                    <span class="status status-accepted">
                        ✔ Accepted
                    </span>

                <?php elseif($status == 'Rejected'): ?>

                    <span class="status status-rejected">
                        ✖ Rejected
                    </span>

                <?php elseif($status == 'Pending'): ?>

                    <span class="status status-pending">
                        ⏳ Pending
                    </span>

                <?php else: ?>

                    <span class="status status-other">
                        <?php echo htmlspecialchars($status); ?>
                    </span>

                <?php endif; ?>

            </td>

        </tr>

        <?php endforeach; ?>

    </table>

    </div>

    <?php else: ?>

        <p class="no-data">
            No doctor appointment history found.
        </p>

    <?php endif; ?>

</div>



<!-- ==================================================
     TRAINER HISTORY
================================================== -->

<div class="section">

    <div class="section-title">
        🏋️ Trainer Appointment History
    </div>


    <?php if(!empty($trainerHistory)): ?>

    <div class="table-wrapper">

    <table>

        <tr>
            <th>Appointment ID</th>
            <th>Trainer</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
        </tr>


        <?php foreach($trainerHistory as $row): ?>

        <tr>

            <td class="appointment-id">
                #<?php echo $row['AppointmentID']; ?>
            </td>


            <td>
                <?php
                echo htmlspecialchars($row['TrainerName']);
                ?>
            </td>


            <td>
                <?php
                echo date(
                    "d M Y",
                    strtotime($row['Date'])
                );
                ?>
            </td>


            <td>
                <?php
                echo date(
                    "h:i A",
                    strtotime($row['Time'])
                );
                ?>
            </td>


            <td>

                <?php

                $status = $row['Status'] ?? 'Pending';

                if($status == 'Accepted'):

                ?>

                    <span class="status status-accepted">
                        ✔ Accepted
                    </span>

                <?php elseif($status == 'Rejected'): ?>

                    <span class="status status-rejected">
                        ✖ Rejected
                    </span>

                <?php elseif($status == 'Pending'): ?>

                    <span class="status status-pending">
                        ⏳ Pending
                    </span>

                <?php else: ?>

                    <span class="status status-other">
                        <?php echo htmlspecialchars($status); ?>
                    </span>

                <?php endif; ?>

            </td>

        </tr>

        <?php endforeach; ?>

    </table>

    </div>

    <?php else: ?>

        <p class="no-data">
            No trainer appointment history found.
        </p>

    <?php endif; ?>

</div>


</body>

</html>
