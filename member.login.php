<?php
include "config.php";
session_start();
 
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
 
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
 
    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['user'] = $row['name'];
        header("Location: user_dashboard.php");
        exit;
    } else {
        $error = "Invalid Email or Password. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Athlete Login | Sports Meet</title>
    
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
            padding: 20px;
        }
 
        .auth-wrapper {
            width: 100%;
            max-width: 440px;
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
            padding: 35px 40px;
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
            font-size: 34px;
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
            padding: 40px;
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
 
        .form-group {
            margin-bottom: 20px;
        }
 
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
 
        .form-control {
            width: 100%;
            padding: 14px 16px;
            font-size: 15px;
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
            display: flex;
            flex-direction: column;
            gap: 8px;
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
 
        .home-link {
            color: #4b5563 !important;
            font-size: 13px;
        }
        
        .home-link:hover {
            color: #111827 !important;
        }
 
    </style>
</head>
<body>
 
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">Athlete Login</h1>
            <p class="auth-subtitle">Sign in to access your dashboard</p>
        </div>
        
        <div class="auth-body">
            <?php if (isset($error)): ?>
                <div class="alert-error">
                    <i class="fa fa-exclamation-circle"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
 
            <form method="post">
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required autocomplete="off">
                </div>
 
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
 
                <button type="submit" name="login" class="btn-primary">
                    Sign In <i class="fa fa-arrow-right"></i>
                </button>
            </form>
 
            <div class="auth-footer">
                <p>New to the team? <a href="member.register.php">Create an Account</a></p>
                <a href="index.php" class="home-link"><i class="fa fa-home"></i> Back to Homepage</a>
            </div>
        </div>
    </div>
</div>
 
</body>
</html>