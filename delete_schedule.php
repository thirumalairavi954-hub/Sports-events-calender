<?php
include "config.php";

$id=$_GET['id'];

mysqli_query($conn,"DELETE FROM event_schedule WHERE schedule_id='$id'");

header("location:schedule.php");

?>