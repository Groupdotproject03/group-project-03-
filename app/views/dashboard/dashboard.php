<!DOCTYPE html>
<html>

<head>

<title>Dashboard</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:Arial;
    background:#eaf3ff;
}


/* ================= HEADER ================= */

.header{
    background:linear-gradient(135deg,#4facfe,#2c7ea6);
    color:white;
    padding:10px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.header-left{
    display:flex;
    align-items:center;
    gap:15px;
}

.header img{
    width:60px;
    height:60px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid white;
}

.badge{
    padding:2px 0px;
    border-radius:30px;
    font-size:15px;
}


/* ================= TOP SECTION ================= */

.top-section{
    display:flex;
    justify-content:center;
    align-items:flex-start;
    gap:50px;
    margin-top:20px;
    flex-wrap:wrap;
}


/* ================= SCORE BOX ================= */

.score-box{
    width:380px;
    height:180px;
    border-radius:30px;
    background:linear-gradient(135deg,#4facfe,#0b1f4d);
    color:white;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
    box-shadow:0 10px 25px rgba(23,84,127,0.3);
    padding:20px;
}

.score-box h1{
    font-size:50px;
    margin:10px 0;
}

.score-box p{
    text-align:center;
    line-height:1.5;
}


/* ================= REMINDER BOX ================= */

.reminder-box{
    width:380px;
    min-height:180px;
    background:linear-gradient(135deg,#4facfe,#0b1f4d);
    border-radius:30px;
    padding:20px;
    box-shadow:0 10px 25px rgba(23,84,127,0.3);
}

.reminder-box h2{
    margin-bottom:15px;
    color:white;
    font-size:18px;
    text-align:center;
    font-weight:normal;
}

.reminder-item{
    background:#eaf3ff;
    padding:12px 15px;
    border-radius:15px;
    margin-bottom:10px;
    color:#333;
    font-size:15px;
    text-align:center;
}


/* ================= TRAINER CARDS ================= */

.trainer-box{
    display:flex;
    gap:20px;
    justify-content:center;
    margin-top:20px;
    flex-wrap:wrap;
}

.trainer-card{
    width:250px;
    padding:20px;
    border-radius:20px;
    background:linear-gradient(135deg,#4facfe,#0b1f4d);
    color:white;
    text-align:center;
    box-shadow:0 10px 25px rgba(23,84,127,0.3);
}

.trainer-card h2{
    font-size:40px;
    margin-bottom:8px;
}


/* ================= TODAY APPOINTMENTS ================= */

.today-box{
    width:500px;
    margin:25px auto;
    background:linear-gradient(135deg,#4facfe,#0b1f4d);
    padding:20px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(23,84,127,0.3);
}

.today-box h3{
    color:white;
    text-align:center;
    margin-bottom:15px;
    font-size:18px;
    font-weight:normal;
}

.app-item{
    background:#eaf3ff;
    padding:15px;
    border-radius:15px;
    margin-bottom:10px;
    color:#333;
    font-size:16px;
    text-align:center;
}





/* ================= MENU ================= */

.content{
    padding:30px 20px;
    display:flex;
    justify-content:center;
}

.menu-grid{
    display:flex;
    flex-wrap:wrap;
    gap:25px;
    justify-content:center;
    max-width:1000px;
}


/* ALL MENU CARDS */

.menu-card{
    width:180px;
    background:white;
    padding:10px;
    border-radius:30px;
    text-align:center;
    text-decoration:none;
    color:#333;
    box-shadow:0 5px 15px rgba(26, 86, 132, 1);
    position:relative;
    transition:0.3s;
}

.menu-card:hover{
    transform:translateY(-5px);
    background:#4facfe;
    color:white;
}

.menu-card span{
    font-size:30px;
    display:block;
    margin-bottom:5px;
}

.menu-card p{
    font-size:15px;
}


/* COUNT BADGE */

.menu-card .count-badge{
    position:absolute;
    top:-8px;
    right:-8px;
    background:#e74c3c;
    color:white;
    font-size:12px;
    padding:3px 8px;
    border-radius:20px;
}


/* LOGOUT */

.logout{
    color:white;
    text-decoration:none;
    font-size:18px;
}

</style>

</head>


<body>


<!-- ================= HEADER ================= -->

<div class="header">

    <div class="header-left">

        <img src="<?php echo $img; ?>">

        <div>

            <h2>
                <?php echo htmlspecialchars($user['Name']); ?>
            </h2>

            <span class="badge">
                <?php echo ucfirst($usertype); ?>
            </span>

        </div>

    </div>


    <div style="display:flex; align-items:center; gap:15px;">

        <?php if($usertype == 'patient'): ?>

        <a href="history_pdf.php"
           style="background:white;
                  color:#2c7ea6;
                  padding:8px 16px;
                  border-radius:20px;
                  text-decoration:none;
                  font-weight:bold;
                  font-size:14px;">

            📥 Download Health Report

        </a>

        <?php endif; ?>


        <a href="logout.php" class="logout">
            Logout
        </a>

    </div>

</div>



<!-- ================= PATIENT TOP SECTION ================= -->

<?php if($usertype == 'patient'): ?>

<div class="top-section">


    <!-- HEALTH SCORE -->

    <div class="score-box">

        <p style="font-size:18px;">
            Your average health score is
        </p>

        <h1>
            <?php echo $avgScore; ?>
        </h1>

        <p>
            <?php echo $message; ?>
        </p>

    </div>


    <!-- TODAY REMINDERS + APPOINTMENTS -->

    <div class="reminder-box">

        <h2>
            Today's Reminders
        </h2>

        <?php

        $hasAnything = false;


        /* REMINDERS */

        if(!empty($reminders)) {

            $hasAnything = true;

            foreach($reminders as $row) {

                echo "<div class='reminder-item'>";

                echo htmlspecialchars($row['Type']);

                echo " at ";

                echo date("h:i A", strtotime($row['Time']));

                echo "</div>";
            }
        }


        /* DOCTOR APPOINTMENTS */

        if(!empty($todayDoctorAppointments)) {

            $hasAnything = true;

            foreach($todayDoctorAppointments as $row) {

                echo "<div class='reminder-item'>";

                echo "Doctor Appointment at ";

                echo date("h:i A", strtotime($row['Time']));

                echo "</div>";
            }
        }


        /* TRAINER APPOINTMENTS */

        if(!empty($todayTrainerAppointments)) {

            $hasAnything = true;

            foreach($todayTrainerAppointments as $row) {

                echo "<div class='reminder-item'>";

                echo "Trainer Appointment at ";

                echo date("h:i A", strtotime($row['Time']));

                echo "</div>";
            }
        }


        if(!$hasAnything) {

            echo "<div class='reminder-item'>";
            echo "No reminders for today";
            echo "</div>";
        }

        ?>

    </div>

</div>

<?php endif; ?>



<!-- ================= TRAINER TOP SECTION ================= -->

<?php if($usertype == 'trainer'): ?>

<div class="trainer-box">

    <div class="trainer-card">

        <h2>
            <?php echo $totalClients; ?>
        </h2>

        <p>
            Total Clients
        </p>

    </div>


    <div class="trainer-card">

        <h2>
            <?php echo $totalAppointments; ?>
        </h2>

        <p>
            Total Appointments
        </p>

    </div>

</div>


<div class="today-box">

    <h3>
        Today's Appointments
    </h3>

    <?php

    if(!empty($todayTrainerSchedule)){

        foreach($todayTrainerSchedule as $row){

            echo "<div class='app-item'>";

            echo htmlspecialchars($row['Name']);

            echo " — ";

            echo date("h:i A", strtotime($row['Time']));

            echo "</div>";
        }

    }else{

        echo "<div class='app-item'>";
        echo "No appointments today";
        echo "</div>";
    }

    ?>

</div>

<?php endif; ?>



<!-- ================= DOCTOR TOP SECTION ================= -->

<?php if($usertype == 'doctor'): ?>

<div class="trainer-box">

    <div class="trainer-card">

        <h2>
            <?php echo $doctorTodayTotal; ?>
        </h2>

        <p>
            Today's Appointments
        </p>

    </div>

</div>


<div class="today-box">

    <h3>
        Today's Patient List
    </h3>

    <?php

    if(!empty($doctorTodayAppointments)){

        foreach($doctorTodayAppointments as $row){

            echo "<div class='app-item'>";

            echo htmlspecialchars($row['PatientName']);

            echo " — ";

            echo date("h:i A", strtotime($row['Time']));

            echo "</div>";
        }

    }else{

        echo "<div class='app-item'>";
        echo "No appointments today";
        echo "</div>";
    }

    ?>

</div>

<?php endif; ?>



<!-- ================= STAFF TOP SECTION ================= -->

<?php if($usertype == 'staff'): ?>

<div class="trainer-box">

    <div class="trainer-card">

        <h2>
            <?php echo $pendingSamples; ?>
        </h2>

        <p>
            Pending Sample Requests
        </p>

    </div>


    <div class="trainer-card">

        <h2>
            <?php echo $totalReviewedByMe; ?>
        </h2>

        <p>
            Reviewed By Me
        </p>

    </div>

</div>

<?php endif; ?>



<!-- ================= MENU ================= -->

<div class="content">

<div class="menu-grid">


    <!-- COMMON -->

    <a href="profile.php" class="menu-card">

        <span>👤</span>

        <p>
            My Profile
        </p>

    </a>



    <!-- ================= PATIENT ================= -->

    <?php if($usertype == 'patient'): ?>


        <a href="book_appointments.php" class="menu-card">

            <span>📅</span>

            <p>
                Book Doctor
            </p>

        </a>


        <a href="view_appointments.php" class="menu-card">

            <span>📋</span>

            <p>
                My Appointments
            </p>

        </a>


        <a href="dailylog.php" class="menu-card">

            <span>📝</span>

            <p>
                Daily Log
            </p>

        </a>


        <a href="history.php" class="menu-card">

            <span>📊</span>

            <p>
                Your History
            </p>

        </a>


        <a href="trainer.php" class="menu-card">

            <span>🏋️</span>

            <p>
                Book Trainer
            </p>

        </a>


        <a href="reminder.php" class="menu-card">

            <span>⏰</span>

            <p>
                Set Reminder
            </p>

        </a>


        <a href="submit_sample.php" class="menu-card">

            <span>🧪</span>

            <p>
                Request Sample Collection
            </p>

        </a>


        <a href="consultation.php" class="menu-card">

            <span>🩺</span>

            <p>
                Online Consultation
            </p>

        </a>


        <a href="chat_support.php" class="menu-card">

            <span>💬</span>

            <p>
                Chat Support
            </p>

        </a>


        <a href="emergency.php" class="menu-card">

            <span>🚑</span>

            <p>
                Emergency
            </p>

        </a>


        <a href="view_suggestions.php" class="menu-card">

            <span>📋</span>

            <p>
                Trainer's Notes
            </p>

        </a>



<a href="medical_reports.php" class="menu-card">

    <span>📄</span>

    <p>
        Medical Reports
    </p>

</a>


<a href="blood_donation.php" class="menu-card">

    <span>🩸</span>

    <p>
        Blood Donation
    </p>

</a>

<a href="appointment_history.php" class="menu-card">

    <span>📜</span>

    <p>
        Appointment History
    </p>

</a>

<a href="healthcare_finder.php" class="menu-card">

    <span>📍</span>

    <p>
        Nearby Healthcare Finder
    </p>

</a>

<a href="symptom_checker.php" class="menu-card">

    <span>🩺</span>

    <p>
        Symptom Checker
    </p>

</a>

<a href="vaccination.php" class="menu-card">
    <span>💉</span>
    <p>Vaccination Tracker</p>
</a>

    <!-- ================= DOCTOR ================= -->

    <?php elseif($usertype == 'doctor'): ?>


        <a href="doctor_appointment.php" class="menu-card">

            <span>👨‍⚕️</span>

            <p>
                Patient List
            </p>

        </a>


        <a href="doctor_availability.php" class="menu-card">

            <span>🕒</span>

            <p>
                My Availability
            </p>

        </a>


        <a href="consultation.php" class="menu-card">

            <span>🩺</span>

            <p>
                Online Consultation
            </p>

        </a>


        <a href="medical_reports.php" class="menu-card">

            <span>📄</span>

            <p>
                Medical Reports
            </p>

        </a>



    <!-- ================= TRAINER ================= -->

    <?php elseif($usertype == 'trainer'): ?>


        <a href="trainer_clients.php" class="menu-card">

            <span>👥</span>

            <p>
                My Clients
            </p>

        </a>


        <a href="trainer_appointment.php" class="menu-card">

            <span>📅</span>

            <p>
                Appointments
            </p>

        </a>


        <a href="trainer_slots.php" class="menu-card">

            <span>🗓️</span>

            <p>
                My Schedule
            </p>

        </a>

        <a href="trainer_workout.php" class="menu-card">

            <span>💪</span>

            <p>
                Workout Plans
            </p>

        </a>




        <a href="medical_reports.php" class="menu-card">

            <span>📤</span>

            <p>
                Medical Reports
            </p>

        </a>



    <!-- ================= STAFF ================= -->

    <?php elseif($usertype == 'staff'): ?>


        <a href="chat_support.php" class="menu-card">

            <span>💬</span>

            <p>
                Chat Support
            </p>

        </a>


        <a href="sample_review.php" class="menu-card">

            <span>🧪</span>

            <p>
                Sample Collection Review
            </p>

            <?php if($pendingSamples > 0): ?>

                <span class="count-badge">
                    <?php echo $pendingSamples; ?>
                </span>

            <?php endif; ?>

        </a>


        <a href="ambulance_manage.php" class="menu-card">

            <span>🚑</span>

            <p>
                Ambulance Requests
            </p>

        </a>


    <?php endif; ?>


</div>

</div>


<?php View::partial('chatbot_widget'); ?>


</body>
</html>
