<!DOCTYPE html>
<html>
<head>
<title>My Clients</title>
<style>
body{
    font-family:Arial;
    background:#eaf3ff;
}
.header{
    background:linear-gradient(135deg,#4facfe,#2c7ea6);
    color:white;
    padding:15px;
    text-align:center;
}
.container{
    display:flex;
    flex-wrap:wrap;
    gap:20px;
    justify-content:center;
    padding:30px;
}
.card{
    width:250px;
    background:white;
    border-radius:20px;
    padding:20px;
    text-align:center;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
.card img{
    width:80px;
    height:80px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid #4facfe;
}
.card h3{
    margin-top:10px;
    color:#2c7ea6;
}
.btn{
    display:inline-block;
    margin-top:10px;
    padding:8px 12px;
    background:#4facfe;
    color:white;
    border-radius:10px;
    text-decoration:none;
}
.btn:hover{
    background:#2c7ea6;
}
</style>
</head>
<body>

<div class="header">
    <h2>My Clients</h2>
</div>

<div class="container">
<?php
if(!empty($clients)) {
    foreach($clients as $row) {
        $img = (!empty($row['ProfilePhoto']) && file_exists($row['ProfilePhoto']))
            ? $row['ProfilePhoto']
            : 'Images/default.png';
?>
<div class="card">
    <img src="<?php echo htmlspecialchars($img); ?>">
    <h3><?php echo htmlspecialchars($row['Name']); ?></h3>
    <p><?php echo htmlspecialchars($row['Email']); ?></p>
    <p><?php echo htmlspecialchars($row['PhoneNo']); ?></p>

    <a class="btn" href="client_details.php?id=<?php echo $row['UserID']; ?>">
        View Details
    </a>
</div>
<?php
    }
} else {
    echo "<p>No clients yet</p>";
}
?>
</div>

</body>
</html>
