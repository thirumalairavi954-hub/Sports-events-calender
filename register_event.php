<?php
session_start();
include "config.php";

$user=$_SESSION['user'];

$user_data=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM users WHERE name='$user'
"));

$user_id=$user_data['user_id'];

$event_id=$_GET['id'];

mysqli_query($conn,"
INSERT INTO event_registrations(user_id,event_id,status)
VALUES('$user_id','$event_id','Pending')
");

echo "<script>alert('Registration Sent to Admin')</script>";
echo "<script>window.location='my_events.php'</script>";

?>