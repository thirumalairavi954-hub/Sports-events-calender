<?php
session_start();
include "config.php";

if(!isset($_GET['id'])) {
    die("Schedule ID missing!");
}

$schedule_id = $_GET['id'];

// Fetch schedule details
$schedule = mysqli_fetch_assoc(mysqli_query($conn, "SELECT es.*, se.event_name FROM event_schedule es JOIN sports_events se ON se.event_id = es.event_id WHERE schedule_id = $schedule_id"));

if(isset($_POST['update'])) {
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $location = $_POST['location'];

    $update = mysqli_query($conn, "
        UPDATE event_schedule 
        SET event_date = '$event_date', event_time = '$event_time', location = '$location' 
        WHERE schedule_id = $schedule_id
    ");

    if($update) {
        header("Location: view_events.php");
        exit;
    } else {
        $error = "Failed to update schedule: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Schedule</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
<h3>Edit Schedule for Event: <?= $schedule['event_name'] ?></h3>

<?php if(isset($error)) echo "<p class='text-danger'>$error</p>"; ?>

<form method="post">
    <div class="mb-3">
        <label>Date:</label>
        <input type="date" name="event_date" class="form-control" value="<?= $schedule['event_date'] ?>" required>
    </div>
    <div class="mb-3">
        <label>Time:</label>
        <input type="time" name="event_time" class="form-control" value="<?= $schedule['event_time'] ?>" required>
    </div>
    <div class="mb-3">
        <label>Location:</label>
        <input type="text" name="location" class="form-control" value="<?= $schedule['location'] ?>" required>
    </div>
    <button type="submit" name="update" class="btn btn-success">Update Schedule</button>
    <a href="admin_events.php" class="btn btn-secondary">Cancel</a>
</form>
</div>
</body>
</html>