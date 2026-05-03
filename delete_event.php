<?php

include "config.php";

$id = $_GET['id'];

mysqli_query($conn,"DELETE FROM sports_events WHERE event_id='$id'");

header("Location: view_events.php");

?>