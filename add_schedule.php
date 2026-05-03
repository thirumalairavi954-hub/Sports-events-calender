<?php
session_start();
include "config.php";

// Make sure event_id is passed
if(!isset($_GET['event_id'])){
    die("Event ID missing!");
}
$event_id = (int)$_GET['event_id']; // typecast to int for safety

// Fetch event name (optional, for display)
$event = mysqli_fetch_assoc(mysqli_query($conn, "SELECT event_name FROM sports_events WHERE event_id = $event_id"));

$error = "";
$success = "";

// Handle form submission
if(isset($_POST['save'])){
    // Escape inputs to prevent SQL errors
    $event_date = mysqli_real_escape_string($conn, $_POST['event_date']);
    $event_time = mysqli_real_escape_string($conn, $_POST['event_time']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    $insert = mysqli_query($conn, "
        INSERT INTO event_schedule (event_id, event_date, event_time, location)
        VALUES ($event_id, '$event_date', '$event_time', '$location')
    ");

    if($insert){
        $success = "Schedule added successfully!";
        header("Location: view_events.php"); // redirect to admin page
        exit;
    } else {
        $error = "Error adding schedule: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  font-family: 'Poppins', sans-serif;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
}

.container {
  width: 100%;
  max-width: 550px !important;
  background: white;
  padding: 40px;
  border-radius: 15px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  animation: slideUp 0.5s ease-out;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

h3 {
  color: #333;
  font-size: 26px;
  font-weight: 700;
  margin-bottom: 30px;
  text-align: center;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.mb-3 {
  margin-bottom: 20px !important;
}

.mb-3 label {
  font-weight: 600;
  color: #555;
  margin-bottom: 8px;
  display: block;
  font-size: 14px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.form-control {
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  padding: 12px 15px;
  font-size: 14px;
  transition: all 0.3s ease;
  background-color: #f8f9fa;
}

.form-control:focus {
  border-color: #667eea;
  background-color: #fff;
  box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
  outline: none;
}

.form-control:hover {
  border-color: #667eea;
  background-color: #fff;
}

.btn {
  padding: 12px 20px;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-right: 10px;
  margin-top: 10px;
  display: inline-block;
  text-decoration: none;
}

.btn-success {
  background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  color: white;
  box-shadow: 0 10px 25px rgba(17, 153, 142, 0.4);
  width: calc(50% - 10px);
}

.btn-success:hover {
  transform: translateY(-2px);
  box-shadow: 0 15px 35px rgba(17, 153, 142, 0.5);
  background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%);
  color: white;
  text-decoration: none;
}

.btn-success:active {
  transform: translateY(0);
}

.btn-secondary {
  background: linear-gradient(135deg, #a8a8a8 0%, #7d7d7d 100%);
  color: white;
  box-shadow: 0 10px 25px rgba(128, 128, 128, 0.4);
  width: calc(50% - 10px);
}

.btn-secondary:hover {
  transform: translateY(-2px);
  box-shadow: 0 15px 35px rgba(128, 128, 128, 0.5);
  background: linear-gradient(135deg, #7d7d7d 0%, #a8a8a8 100%);
  color: white;
  text-decoration: none;
}

.btn-secondary:active {
  transform: translateY(0);
}

.alert {
  border-radius: 8px;
  border: none;
  margin-bottom: 25px;
  animation: slideDown 0.4s ease-out;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.alert-danger {
  background-color: #f8d7da;
  color: #721c24;
  padding: 15px 20px;
  font-weight: 500;
}

.alert-success {
  background-color: #d4edda;
  color: #155724;
  padding: 15px 20px;
  font-weight: 500;
}

/* Responsive Design */
@media (max-width: 600px) {
  .container {
    padding: 30px 20px;
  }
  
  h3 {
    font-size: 22px;
  }
  
  .form-control {
    font-size: 16px;
  }

  .btn {
    width: 100% !important;
    margin-right: 0;
    margin-bottom: 10px;
  }
}

    </style>
</head>
<body>
<div class="container">
    <h3>Add Schedule for Event: <?= htmlspecialchars($event['event_name']) ?></h3>

    <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>

    <form method="post">
        <div class="mb-3">
            <label>Date:</label>
            <input type="date" name="event_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Time:</label>
            <input type="time" name="event_time" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Location / Google Maps link:</label>
            <input type="text" name="location" class="form-control" placeholder="Enter location or map link" required>
        </div>
        <button type="submit" name="save" class="btn btn-success">Save Schedule</button>
        <a href="view_events.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>