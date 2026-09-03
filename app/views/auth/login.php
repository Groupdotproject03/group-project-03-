<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In - HealthFirst</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: Arial, sans-serif;
}

body{
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:6%;
    background-image:url("Images/new.jpg");
    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
    position:relative;
}

body::before{
    content:"";
    position:absolute;
    top:0; left:0; right:0;
    height:3px;
    background:#1a1a1a;
}

.brand{
    max-width:750px;
    z-index:1;
}

.brand h1{
    font-family:'Brush Script MT', cursive;
    font-size:140px;
    white-space:nowrap;
    background:linear-gradient(90deg,#5ed8d0,#3aa0e0,#1a56c4);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
    background-clip:text;
    margin-bottom:10px;
    padding-right:15px;
}

.brand p{
    font-family:'Comic Sans MS', 'Comic Sans', cursive;
    font-size:30px;
    color:#3aa0e0;
    line-height:1.4;
}

.container{
    width:500px;
    background:white;
    padding:40px 50px;
    border-radius:20px;
    box-shadow:0 0 25px rgba(79,172,254,0.45);
    z-index:1;
}

.container h2{
    text-align:center;
    margin-bottom:50px;
    font-size:30px;
    color:#1d6fa5;
}

.input-box{
    margin-bottom:20px;
}

.input-box label{
    display:block;
    margin-bottom:6px;
    font-size:15px;
    color:#2c7ea6;
    font-weight:bold;
}

.input-box input{
    width:100%;
    padding:12px;
    border:2px solid #4a84a8;
    border-radius:8px;
    background:#eef0fb;
    font-size:15px;
}

.input-box input:focus{
    outline:none;
    border-color:#2c7ea6;
}

.options{
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-size:14px;
    margin-bottom:20px;
}

.options label{
    display:flex;
    align-items:center;
    gap:6px;
    color:#333;
}

.options a{
    text-decoration:none;
    color:#27759f;
}

.btn{
    width:100%;
    padding:13px;
    background:#4facfe;
    color:white;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
}

.btn:hover{
    background:#2f8dfd;
}

.bottom-text{
    text-align:center;
    margin-top:18px;
    font-size:14px;
}

.bottom-text a{
    color:#1d6b84;
    text-decoration:none;
    font-weight:bold;
}

.error-msg {
    color: #e74c3c;
    text-align: center;
    margin-bottom: 15px;
    font-weight: bold;
}
</style>
</head>
<body>

<div class="brand">
    <h1>HealthFirst</h1>
    <p>Personalized Health &amp;<br>Wellness Tracker</p>
</div>

<div class="container">
    <h2>Sign In</h2>
    <?php if(!empty($error)): ?>
        <p class="error-msg"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form action="login.php" method="POST">
        <div class="input-box">
            <label>Email</label>
            <input type="email" name="email" required placeholder="Enter your email">
        </div>
        <div class="input-box">
            <label>Password</label>
            <input type="password" name="password" required placeholder="Enter your password">
        </div>
        <div class="options">
            <label><input type="checkbox"> Remember me</label>
            <a href="#">Forgot Password?</a>
        </div>
        <button type="submit" class="btn">Login</button>
        <div class="bottom-text">
            Don't have an account? <a href="register.php">Register</a>
        </div>
    </form>
</div>

</body>
</html>
