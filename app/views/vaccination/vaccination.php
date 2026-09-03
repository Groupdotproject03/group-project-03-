<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Vaccination Tracker</title>
<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial,Helvetica,sans-serif;
}

body{
background:#eaf2fb;
}

.container{
width:92%;
max-width:1100px;
margin:30px auto;
}

.back{
display:inline-block;
margin-bottom:18px;
text-decoration:none;
font-weight:bold;
color:#0b5cad;
}

.header{
background:linear-gradient(135deg,#3ea0e7,#102b68);
color:white;
padding:28px;
border-radius:22px;
margin-bottom:25px;
box-shadow:0 10px 25px rgba(0,0,0,.18);
}

.header h1{
font-size:30px;
margin-bottom:8px;
}

.summary{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(150px,1fr));
gap:15px;
margin-bottom:25px;
}

.box{
background:white;
padding:20px;
border-radius:18px;
text-align:center;
box-shadow:0 8px 18px rgba(0,0,0,.08);
}

.box h2{
font-size:28px;
color:#0b5cad;
}

.box p{
margin-top:6px;
color:#666;
}

.card{
background:white;
padding:25px;
border-radius:20px;
box-shadow:0 8px 20px rgba(0,0,0,.10);
margin-bottom:25px;
}

.card h2{
color:#0b5cad;
margin-bottom:18px;
}

.form-grid{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:15px;
}

label{
display:block;
font-size:14px;
font-weight:bold;
margin-bottom:6px;
color:#444;
}

input,select{
width:100%;
padding:12px;
border:1px solid #ccc;
border-radius:10px;
outline:none;
}

input:focus,
select:focus{
border-color:#1676d2;
}

button{
margin-top:18px;
width:100%;
padding:13px;
background:#1676d2;
color:white;
border:none;
border-radius:10px;
cursor:pointer;
font-size:15px;
font-weight:bold;
transition:.3s;
}

button:hover{
background:#0b5cad;
}

.table-wrap{
overflow-x:auto;
}

table{
width:100%;
border-collapse:collapse;
min-width:760px;
}

th{
background:#1676d2;
color:white;
padding:12px;
}

td{
padding:12px;
text-align:center;
border-bottom:1px solid #eee;
}

tr:nth-child(even){
background:#f8fbff;
}

tr:hover{
background:#eef6ff;
}

.badge{
padding:6px 12px;
border-radius:20px;
color:white;
font-size:13px;
font-weight:bold;
display:inline-block;
}

.completed{background:#28a745;}
.upcoming{background:#2196F3;}
.due{background:#f39c12;}
.over{background:#dc3545;}

.delete{
background:#dc3545;
color:white;
padding:7px 12px;
border-radius:8px;
text-decoration:none;
font-size:13px;
}

.delete:hover{
background:#b71c1c;
}

.empty{
text-align:center;
padding:30px;
color:#777;
}

small{
font-weight:bold;
}
</style>
</head>
<body>

<div class="container">

<a href="dashboard.php" class="back">← Back to Dashboard</a>

<div class="header">
<h1>💉 Vaccination Tracker</h1>
<p>Track your vaccination records and upcoming due dates easily.</p>
</div>

<div class="summary">

<div class="box">
<h2><?= $summary['total']?:0 ?></h2>
<p>Total</p>
</div>

<div class="box">
<h2><?= $summary['completed']?:0 ?></h2>
<p>Completed</p>
</div>

<div class="box">
<h2><?= $summary['upcoming']?:0 ?></h2>
<p>Upcoming</p>
</div>

<div class="box">
<h2><?= $summary['due']?:0 ?></h2>
<p>Due Soon</p>
</div>

<div class="box">
<h2><?= $summary['overdue']?:0 ?></h2>
<p>Overdue</p>
</div>

</div>

<div class="card">

<h2>Add New Vaccination</h2>

<form method="POST">

<div class="form-grid">

<div>
<label>Vaccine Name</label>
<input type="text" name="name" placeholder="COVID-19, TT, Hepatitis B" required>
</div>

<div>
<label>Dose</label>
<select name="dose">
<option>1st Dose</option>
<option>2nd Dose</option>
<option>Booster</option>
<option>Single Dose</option>
</select>
</div>

<div>
<label>Vaccination Date</label>
<input type="date" name="vdate" required>
</div>

<div>
<label>Next Due Date (Optional)</label>
<input type="date" name="next">
</div>

</div>

<button name="add">💉 Save Vaccination</button>

</form>

</div>

<div class="card">

<h2>My Vaccination Records</h2>

<?php if(!empty($records)){ ?>

<div class="table-wrap">

<table>

<tr>
<th>Vaccine</th>
<th>Dose</th>
<th>Vaccination Date</th>
<th>Next Due</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php foreach($records as $row){ ?>

<tr>

<td><?= htmlspecialchars($row['VaccineName']) ?></td>

<td><?= htmlspecialchars($row['Dose']) ?></td>

<td><?= date("d M Y",strtotime($row['VaccinationDate'])) ?></td>

<td>

<?php

if(!empty($row['NextDueDate'])){

    $days=floor((strtotime($row['NextDueDate'])-time())/86400);

    echo date("d M Y",strtotime($row['NextDueDate']));

    if($days>30){
        echo "<br><small style='color:#2196F3;'>$days days left</small>";
    }
    elseif($days>=0){
        echo "<br><small style='color:#f39c12;'>$days days left</small>";
    }
    else{
        echo "<br><small style='color:#dc3545;'>".abs($days)." days overdue</small>";
    }

}
else{
    echo "-";
}

?>

</td>

<td>

<?php

switch($row['Status']){

case 'Completed':
echo "<span class='badge completed'>Completed</span>";
break;

case 'Upcoming':
echo "<span class='badge upcoming'>Upcoming</span>";
break;

case 'Due Soon':
echo "<span class='badge due'>Due Soon</span>";
break;

case 'Overdue':
echo "<span class='badge over'>Overdue</span>";
break;

}

?>

</td>

<td>

<a class="delete"
href="vaccination.php?delete=<?= $row['VaccineID'] ?>"
onclick="return confirm('Delete this record?')">
Delete
</a>

</td>

</tr>

<?php } ?>

</table>

</div>

<?php } else { ?>

<div class="empty">
No vaccination records found.
</div>

<?php } ?>

</div>

</div>

</body>
</html>
