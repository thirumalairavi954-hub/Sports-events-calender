<?php
include "config.php";
 
if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $dept = trim($_POST['department']);
    $gender = $_POST['gender'];
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
 
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $check = $stmt->get_result();
 
    if (mysqli_num_rows($check) > 0) {
        $error = "This email is already registered.";
    } else {
        $ins = $conn->prepare("INSERT INTO users(name,email,department,gender,phone,password) VALUES(?,?,?,?,?,?)");
        $ins->bind_param("ssssss", $name, $email, $dept, $gender, $phone, $password);
        $ins->execute();
        
        echo "<script>alert('Registration Successful! You may now login.'); window.location='member.login.php';</script>";
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Athlete Registration | Sports Meet</title>
    
    <!-- Fonts for a professional sports aesthetic -->
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
 
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f1f5f9;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 24px 24px;
            font-family: 'Inter', sans-serif;
            color: #111827;
            padding: 30px 20px;
        }
 
        .auth-wrapper {
            width: 100%;
            max-width: 500px;
        }
 
        .auth-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
 
        .auth-header {
            background: #111827;
            color: #ffffff;
            padding: 35px 35px 30px;
            text-align: center;
            position: relative;
        }
 
        .auth-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: #ea580c; /* Sporty Orange */
        }
 
        .auth-title {
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 32px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1;
            margin-bottom: 8px;
        }
 
        .auth-subtitle {
            font-size: 14px;
            color: #9ca3af;
            font-weight: 500;
        }
 
        .auth-body {
            padding: 35px;
        }
 
        .alert-error {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            color: #991b1b;
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 24px;
            border-radius: 0 4px 4px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
 
        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 18px;
        }
 
        .form-col {
            flex: 1;
        }
 
        .form-group.single { margin-bottom: 18px; }
 
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
 
        .form-control, select.form-control {
            width: 100%;
            padding: 12px 14px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #111827;
            background-color: #f9fafb;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            transition: all 0.2s;
        }
 
        .form-control:focus {
            outline: none;
            background-color: #ffffff;
            border-color: #ea580c;
            box-shadow: 0 0 0 4px rgba(234, 88, 12, 0.1);
        }
 
        .form-control::placeholder {
            color: #9ca3af;
        }
 
        .btn-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 16px;
            background: #ea580c;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-family: 'Barlow Condensed', sans-serif;
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
            margin-top: 10px;
        }
 
        .btn-primary:hover {
            background: #c2410c;
        }
 
        .btn-primary:active {
            transform: scale(0.98);
        }
 
        .auth-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: #6b7280;
        }
 
        .auth-footer a {
            color: #ea580c;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
 
        .auth-footer a:hover {
            color: #9a3412;
        }
 
        @media (max-width: 600px) {
            .form-row { flex-direction: column; gap: 0; margin-bottom: 0; }
            .form-col { margin-bottom: 18px; }
        }
 
    </style>
</head>
<body>
 
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">Join the Roster</h1>
            <p class="auth-subtitle">Create your athlete profile today</p>
        </div>
        
        <div class="auth-body">
            <?php if (isset($error)): ?>
                <div class="alert-error">
                    <i class="fa fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
 
            <form method="post">
                <div class="form-group single">
                    <label class="form-label" for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" required autocomplete="off">
                </div>
 
                <div class="form-group single">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email address" required autocomplete="off">
                </div>
 
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label" for="department">Department</label>
                        <input type="text" id="department" name="department" class="form-control" placeholder="e.g. Computer Science" required>
                    </div>
                    <div class="form-col">
                        <label class="form-label" for="gender">Gender</label>
                        <select name="gender" id="gender" class="form-control" required>
                            <option value="" disabled selected>Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>
 
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label" for="phone">Phone Number</label>
                        <input type="text" id="phone" name="phone" class="form-control" placeholder="e.g. 1234567890" required>
                    </div>
                    <div class="form-col">
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Choose a password" required>
                    </div>
                </div>
 
                <button type="submit" name="register" class="btn-primary">
                    Create Profile <i class="fa fa-user-check"></i>
                </button>
            </form>
 
            <div class="auth-footer">
                <p>Already have an account? <a href="member.login.php">Sign In</a></p>
            </div>
        </div>
    </div>
</div>
 
</body>
</html>
