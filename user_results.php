<?php
session_start();
include "config.php";

// Check if user logged in
if(!isset($_SESSION['user'])){
    header("Location: member.login.php");
    exit;
}

$user_name = mysqli_real_escape_string($conn, $_SESSION['user']);
// Find participant ID
$query_p = mysqli_query($conn, "SELECT participant_id FROM participants WHERE name = '$user_name'");
$p_row = mysqli_fetch_assoc($query_p);
$pid = $p_row ? $p_row['participant_id'] : 0;

// Fetch results for this specific user
$query = mysqli_query($conn, "
    SELECT r.result_id, se.event_name, se.event_date,
           CASE 
             WHEN r.first_place = $pid THEN '🥇 1st Place'
             WHEN r.second_place = $pid THEN '🥈 2nd Place'
             WHEN r.third_place = $pid THEN '🥉 3rd Place'
           END AS position
    FROM results r
    JOIN sports_events se ON r.event_id = se.event_id
    WHERE (r.first_place = $pid OR r.second_place = $pid OR r.third_place = $pid) AND $pid > 0
    ORDER BY se.event_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family:Poppins,sans-serif; background:#f4f6f9; }
        .container-box { width:90%; margin:auto; margin-top:40px; }
        .highlight { background:#d1ecf1 !important; } /* highlight logged-in user rows */
        .search-box { width:300px; margin-bottom:10px; }
        .table th { background:#0d6efd; color:white; }
    </style>
</head>
<body>

<div class="container-box">
    <h3>📊 Event Results</h3>

    <input type="text" id="search" class="form-control search-box" placeholder="Search results...">

    <table class="table table-hover">
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Date</th>
                <th>Participant / Team</th>
                <th>Position</th>
                <th>Certificate</th>
            </tr>
        </thead>
        <tbody id="resultTable">
        <?php while($row = mysqli_fetch_assoc($query)) { ?>
            <tr class="highlight">
                <td><?= htmlspecialchars($row['event_name']) ?></td>
                <td><?= $row['event_date'] ?></td>
                <td><?= htmlspecialchars($_SESSION['user']) ?></td>
                <td><?= $row['position'] ?></td>
                <td>
                    <a href="certificate.php?id=<?= $row['result_id'] ?>" target="_blank" class="btn btn-sm btn-success">View Certificate</a>
                </td>
            </tr>
        <?php } ?>
        <?php if(mysqli_num_rows($query) == 0) { ?>
            <tr><td colspan="5" style="text-align:center; padding: 20px;">No results found for your account yet.</td></tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById("search").addEventListener("keyup", function(){
    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll("#resultTable tr");
    rows.forEach(function(row){
        row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
    });
});
</script>

</body>
</html>