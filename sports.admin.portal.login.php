<?php
session_start();
include "config.php";
 
if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
 
    $stmt = $conn->prepare("SELECT * FROM admin_login WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
 
    if (mysqli_num_rows($result) == 1) {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid Administrator Credentials";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login | Sports Meet</title>
    
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
            background: #0077ff; /* Sporty Blue for Admin */
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
            border-color: #0077ff;
            box-shadow: 0 0 0 4px rgba(0, 119, 255, 0.1);
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
            background: #0077ff;
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
            background: #005bb5;
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
 
        .home-link {
            color: #4b5563;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        
        .home-link:hover {
            color: #111827;
        }
 
    </style>
</head>
<body>
 
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h1 class="auth-title">Admin Portal</h1>
            <p class="auth-subtitle">Secure system access</p>
        </div>
        
        <div class="auth-body">
            <?php if (isset($error)): ?>
                <div class="alert-error">
                    <i class="fa fa-shield-alt"></i>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
 
            <form method="post">
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Enter admin username" required autocomplete="off">
                </div>
 
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>
 
                <button type="submit" name="login" class="btn-primary">
                    <i class="fa fa-lock"></i> Authorize Access
                </button>
            </form>
 
            <div class="auth-footer">
                <a href="index.php" class="home-link"><i class="fa fa-home"></i> Back to Homepage</a>
            </div>
        </div>
    </div>
</div>
 
</body>
</html>