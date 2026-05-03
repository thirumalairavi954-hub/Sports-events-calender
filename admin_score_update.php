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

mysqli_query($conn,"
INSERT INTO live_scores
(event_id,team1,team2,score1,score2,match_time,status)
VALUES
('$event','$team1','$team2','$score1','$score2','$time','$status')
");

echo "<script>alert('Score Added')</script>";

}
?>

<!DOCTYPE html>
<html>

<head>

<title>Score Update</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f4f6f9;
font-family:Poppins;
}

</style>

</head>

<body>

<div class="container mt-5">

<h2>🏆 Admin Score Update</h2>

<form method="post">

<select name="event" class="form-control mb-2">

<option>Select Event</option>

<?php

$q=mysqli_query($conn,"SELECT * FROM sports_events");

while($row=mysqli_fetch_assoc($q))
{
echo "<option value='".$row['event_id']."'>".$row['event_name']."</option>";
}

?>

</select>

<input type="text" name="team1" class="form-control mb-2" placeholder="Team 1">

<input type="text" name="team2" class="form-control mb-2" placeholder="Team 2">

<input type="number" name="score1" class="form-control mb-2" placeholder="Score 1">

<input type="number" name="score2" class="form-control mb-2" placeholder="Score 2">

<input type="text" name="time" class="form-control mb-2" placeholder="Match Time (45:00)">

<select name="status" class="form-control mb-3">

<option>Live</option>
<option>Finished</option>

</select>

<button class="btn btn-success" name="submit">Add Score</button>

</form>

</div>

</body>

</html>