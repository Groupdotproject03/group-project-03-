<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - HealthCore</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: #e8edf2;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            width: 450px;
        }
        h2 { text-align: center; color: #333; margin-bottom: 25px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #555; font-size: 14px; }
        input, select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
        }
        input:focus, select:focus { border-color: #4aa8d8; }
        .btn {
            width: 100%;
            padding: 12px;
            background: #4aa8d8;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        .btn:hover { background: #2e86c1; }
        .login-link { text-align: center; margin-top: 15px; font-size: 14px; }
        .login-link a { color: #4aa8d8; text-decoration: none; }
        .error-msg { color: #e74c3c; text-align: center; margin-bottom: 15px; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <h2>Create Account</h2>
    <?php if(!empty($error)): ?>
        <p class="error-msg"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <form method="POST" action="register.php">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" placeholder="Enter your name" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>
        </div>
        <div class="form-group">
            <label>Gender</label>
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>
        </div>
        <div class="form-group">
            <label>Phone Number</label>
            <input type="text" name="phone" placeholder="Enter phone number" required>
        </div>
        <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" name="dob" required>
        </div>
		
		<div class="form-group">
            <label>User Type</label>
            <select name="usertype" required>
                <option value="">Select User Type</option>
                <option value="Patient">Patient</option>
                <option value="Doctor">Doctor</option>
                <option value="Trainer">Trainer</option>
                <option value="Staff">Staff (Support)</option>
            </select>
        </div>

        <button type="submit" class="btn">Register</button>
    </form>
    <div class="login-link">
        Already have an account? <a href="index.php">Sign In</a>
    </div>
</div>
</body>
</html>
