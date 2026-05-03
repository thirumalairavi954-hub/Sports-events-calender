<?php
include "config.php";

$id=$_GET['id'];

$q=mysqli_query($conn,"SELECT * FROM live_scores WHERE score_id='$id'");
$row=mysqli_fetch_assoc($q);

if(isset($_POST['update']))
{

$score1=$_POST['score1'];
$score2=$_POST['score2'];
$status=$_POST['status'];

mysqli_query($conn,"
UPDATE live_scores
SET score1='$score1',
score2='$score2',
status='$status'
WHERE score_id='$id'
");

header("Location: admin_scores.php");

}
?>

<form method="post">

<input type="number" name="score1" value="<?php echo $row['score1']; ?>">

<input type="number" name="score2" value="<?php echo $row['score2']; ?>">

<select name="status">

<option>Live</option>
<option>Finished</option>

</select>

<button name="update">Update</button>

</form>