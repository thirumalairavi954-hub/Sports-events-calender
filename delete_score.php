<?php
include "config.php";

$id=$_GET['id'];

mysqli_query($conn,"DELETE FROM live_scores WHERE score_id='$id'");

header("Location: admin_scores.php");
?>