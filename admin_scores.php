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
(event_name,team1,team2,score1,score2,match_time,status)
VALUES
('$event','$team1','$team2','$score1','$score2','$time','$status')
");

header("Location: admin_scores.php");

}
?>

<!DOCTYPE html>
<html>

<head>

<title>Score Update</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h2>🏆 Score Update</h2>

<form method="post">

<input type="text" name="event" class="form-control mb-2" placeholder="Event Name">

<input type="text" name="team1" class="form-control mb-2" placeholder="Team 1">

<input type="text" name="team2" class="form-control mb-2" placeholder="Team 2">

<input type="number" name="score1" class="form-control mb-2" placeholder="Score 1">

<input type="number" name="score2" class="form-control mb-2" placeholder="Score 2">

<input type="text" name="time" class="form-control mb-2" placeholder="Match Time">

<select name="status" class="form-control mb-3">

<option>Live</option>
<option>Finished</option>

</select>

<button class="btn btn-success" name="submit">Add Score</button>

</form>

<hr>

<h3>Score List</h3>

<table class="table table-bordered">

<tr>

<th>ID</th>
<th>Event</th>
<th>Score</th>
<th>Status</th>
<th>Action</th>

</tr>

<?php

$q=mysqli_query($conn,"SELECT * FROM live_scores");

while($row=mysqli_fetch_assoc($q))
{
?>

<tr>

<td><?php echo $row['score_id']; ?></td>

<td><?php echo $row['event_name']; ?></td>

<td>
<?php echo $row['team1']." ".$row['score1']." - ".$row['score2']." ".$row['team2']; ?>
</td>

<td><?php echo $row['status']; ?></td>

<td>

<a href="edit_score.php?id=<?php echo $row['score_id']; ?>" class="btn btn-warning btn-sm">Edit</a>

<a href="delete_score.php?id=<?php echo $row['score_id']; ?>" class="btn btn-danger btn-sm">Delete</a>

</td>

</tr>

<?php
}
?>

</table>

</div>

</body>

</html>