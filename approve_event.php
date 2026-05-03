<?php
include "config.php";

$id=$_GET['id'];

$data=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT event_registrations.*,users.name,users.department,users.gender,users.phone
FROM event_registrations
JOIN users ON users.user_id=event_registrations.user_id
WHERE reg_id='$id'
"));

$name=$data['name'];
$dept=$data['department'];
$gender=$data['gender'];
$phone=$data['phone'];
$event=$data['event_id'];

mysqli_query($conn,"
INSERT INTO participants(name,gender,phone,department,event_id)
VALUES('$name','$gender','$phone','$dept','$event')
");

mysqli_query($conn,"
UPDATE event_registrations
SET status='Approved'
WHERE reg_id='$id'
");

header("Location: admin_event_requests.php");

?>