<?php
include "config.php";

if(isset($_POST['submit']))
{

$event=$_POST['event'];
$team1=$_POST['team1'];
$team2=$_POST['team2'];
$score1=$_POST['score1'];
$score2=$_POST['score2'];
$time=$_POST['time'];
$status=$_POST['status'];

mysqli_query($conn,"INSERT INTO live_scores
(event_name,team1,team2,score1,score2,match_time,status)
VALUES('$event','$team1','$team2','$score1','$score2','$time','$status')");

echo "<script>alert('Score Added')</script>";

}
?>

<!DOCTYPE html>
<html>

<head>

<title>Admin Live Score</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h2>🏆 Live Score Update</h2>

<form method="post">

<input type="text" name="event" class="form-control mb-2" placeholder="Event Name" required>

<input type="text" name="team1" class="form-control mb-2" placeholder="Team 1">

<input type="text" name="team2" class="form-control mb-2" placeholder="Team 2">

<input type="number" name="score1" class="form-control mb-2" placeholder="Score 1">

<input type="number" name="score2" class="form-control mb-2" placeholder="Score 2">

<input type="text" name="time" class="form-control mb-2" placeholder="Match Time (ex: 45:10)">

<select name="status" class="form-control mb-3">

<option>Live</option>
<option>Finished</option>

</select>

<button class="btn btn-success" name="submit">Add Score</button>

</form>

</div>

</body>

</html>