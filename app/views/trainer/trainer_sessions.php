<!DOCTYPE html>
<html>
<head>
<title>Sessions</title>
<style>
body{
    font-family:Arial;
    background:#eaf3ff;
}
.box{
    width:500px;
    margin:50px auto;
    background:white;
    padding:20px;
    border-radius:20px;
}
.item{
    padding:10px;
    border-bottom:1px solid #eee;
}
</style>
</head>
<body>

<div class="box">
<h2>Training Sessions</h2>
<?php
if(!empty($sessions)) {
    foreach($sessions as $row){
        echo "<div class='item'>";
        echo "<b>" . htmlspecialchars($row['Name']) . "</b><br>";
        echo "Date: " . htmlspecialchars($row['Date']) . " | Time: " . htmlspecialchars($row['Time']);
        echo "</div>";
    }
} else {
    echo "<p>No training sessions scheduled.</p>";
}
?>
</div>

</body>
</html>
