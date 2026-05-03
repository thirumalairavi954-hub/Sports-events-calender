<?php
include "config.php";

if(isset($_GET['id']))
{

$id = $_GET['id'];

mysqli_query($conn,"
UPDATE event_registrations
SET status='Rejected'
WHERE reg_id='$id'
");

header("Location: admin_event_requests.php");
exit();

}
else
{
echo "Invalid Request";
}
?>