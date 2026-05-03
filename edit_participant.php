<?php

include "config.php";

$id = $_GET['id'];

$result = mysqli_query($conn,"SELECT * FROM participants WHERE participant_id='$id'");
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update']))
{

$name=$_POST['name'];
$gender=$_POST['gender'];
$phone=$_POST['phone'];
$dept=$_POST['department'];
$event=$_POST['event'];

mysqli_query($conn,"UPDATE participants 
SET name='$name',gender='$gender',phone='$phone',department='$dept',event_id='$event'
WHERE participant_id='$id'");

header("Location: view_participants.php");

}

$events=mysqli_query($conn,"SELECT * FROM sports_events");

?>

<!DOCTYPE html>
<html>

<head>

<title>Edit Participant</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  font-family: 'Poppins', sans-serif;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 20px;
}

.container-box {
  width: 100%;
  max-width: 550px;
  background: white;
  padding: 40px;
  border-radius: 15px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  animation: slideUp 0.5s ease-out;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

h3 {
  color: #333;
  font-size: 28px;
  font-weight: 700;
  margin-bottom: 30px;
  text-align: center;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

.mb-3 {
  margin-bottom: 20px !important;
}

.mb-3 label {
  font-weight: 600;
  color: #555;
  margin-bottom: 8px;
  display: block;
  font-size: 14px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.form-control {
  border: 2px solid #e0e0e0;
  border-radius: 8px;
  padding: 12px 15px;
  font-size: 14px;
  transition: all 0.3s ease;
  background-color: #f8f9fa;
}

.form-control:focus {
  border-color: #667eea;
  background-color: #fff;
  box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
  outline: none;
}

.form-control:hover {
  border-color: #667eea;
  background-color: #fff;
}

.btn {
  padding: 12px 20px;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-right: 10px;
  margin-top: 10px;
  display: inline-block;
  text-decoration: none;
}

.btn-success {
  background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  color: white;
  box-shadow: 0 10px 25px rgba(17, 153, 142, 0.4);
  width: calc(50% - 10px);
}

.btn-success:hover {
  transform: translateY(-2px);
  box-shadow: 0 15px 35px rgba(17, 153, 142, 0.5);
  background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%);
  color: white;
  text-decoration: none;
}

.btn-success:active {
  transform: translateY(0);
}

.btn-secondary {
  background: linear-gradient(135deg, #a8a8a8 0%, #7d7d7d 100%);
  color: white;
  box-shadow: 0 10px 25px rgba(128, 128, 128, 0.4);
  width: calc(50% - 10px);
}

.btn-secondary:hover {
  transform: translateY(-2px);
  box-shadow: 0 15px 35px rgba(128, 128, 128, 0.5);
  background: linear-gradient(135deg, #7d7d7d 0%, #a8a8a8 100%);
  color: white;
  text-decoration: none;
}

.btn-secondary:active {
  transform: translateY(0);
}

/* Responsive Design */
@media (max-width: 600px) {
  .container-box {
    padding: 30px 20px;
  }
  
  h3 {
    font-size: 24px;
  }
  
  .form-control {
    font-size: 16px;
  }

  .btn {
    width: 100% !important;
    margin-right: 0;
    margin-bottom: 10px;
  }
}

</style>

</head>

<body>

<div class="container-box">

<h3>Edit Participant</h3>

<form method="post">

<div class="mb-3">

<label>Participant Name</label>

<input type="text"
name="name"
class="form-control"
value="<?php echo $row['name']; ?>"
required>

</div>

<div class="mb-3">

<label>Gender</label>

<select name="gender" class="form-control">

<option <?php if($row['gender']=="Male") echo "selected"; ?>>Male</option>
<option <?php if($row['gender']=="Female") echo "selected"; ?>>Female</option>

</select>

</div>

<div class="mb-3">

<label>Phone Number</label>

<input type="text"
name="phone"
class="form-control"
value="<?php echo $row['phone']; ?>"
required>

</div>

<div class="mb-3">

<label>Department</label>

<input type="text"
name="department"
class="form-control"
value="<?php echo $row['department']; ?>"
required>

</div>

<div class="mb-3">

<label>Select Event</label>

<select name="event" class="form-control">

<?php
while($event_row=mysqli_fetch_assoc($events))
{
?>

<option value="<?php echo $event_row['event_id']; ?>" <?php if($row['event_id']==$event_row['event_id']) echo "selected"; ?>>

<?php echo $event_row['event_name']; ?>

</option>

<?php
}
?>

</select>

</div>

<button class="btn btn-success" name="update">
Update Participant
</button>

<a href="view_participants.php" class="btn btn-secondary">
Cancel
</a>

</form>

</div>

</body>

</html>
