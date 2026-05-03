<?php
session_start();
include "config.php";
 
if(!isset($_SESSION['admin'])){
    header("Location: sports.admin.portal.login.php");
    exit;
}
 
$events = mysqli_query($conn, "SELECT * FROM sports_events ORDER BY event_name ASC");
 
if(isset($_POST['submit'])){
    $event_id = $_POST['event_id'];
    $team1 = $_POST['team1'];
    $team2 = $_POST['team2'];
    $score1 = $_POST['score1'];
    $score2 = $_POST['score2'];
    $match_time = $_POST['match_time'];
    $status = $_POST['status'];
 
    $check = mysqli_query($conn, "SELECT * FROM live_scores WHERE event_id='$event_id' AND team1='$team1' AND team2='$team2'");
    if(mysqli_num_rows($check) > 0){
        mysqli_query($conn, "UPDATE live_scores SET score1='$score1', score2='$score2', match_time='$match_time', status='$status', updated_at=NOW() WHERE event_id='$event_id' AND team1='$team1' AND team2='$team2'");
        $msg = "Score Data Stream Updated!";
    } else {
        mysqli_query($conn, "INSERT INTO live_scores(event_id, team1, team2, score1, score2, match_time, status) VALUES('$event_id','$team1','$team2','$score1','$score2','$match_time','$status')");
        $msg = "Score Data Stream Initiated!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Score Terminal - Anti-Gravity Engine</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
    :root {
      --bg-color: #f0f4f8; --glass-bg: rgba(255, 255, 255, 0.65); --glass-border: rgba(255, 255, 255, 0.8);
      --neon-blue: #0077ff; --neon-purple: #8800ff; --neon-cyan: #00aa88; --text-main: #1e293b; --text-muted: #64748b; --font-tech: 'Poppins', sans-serif;
    }
    body { min-height: 100vh; background: var(--bg-color); font-family: var(--font-tech); color: var(--text-main); display: flex; align-items: center; justify-content: center; padding: 24px; position: relative; overflow-x:hidden; }
    .bg-elements { position: fixed; inset: 0; pointer-events: none; z-index: 0; }
    .orb { position: absolute; border-radius: 50%; filter: blur(70px); animation: floatOrb 20s infinite alternate ease-in-out; }
    .orb-1 { width: 40vw; height: 40vw; background: rgba(0, 119, 255, 0.2); top: -10%; left: -10%; }
    .orb-2 { width: 50vw; height: 50vw; background: rgba(136, 0, 255, 0.15); bottom: -20%; right: -15%; animation-delay: -7s; }
    .orb-3 { width: 30vw; height: 30vw; background: rgba(0, 170, 136, 0.15); top: 40%; left: 50%; animation-duration: 25s; }
    @keyframes floatOrb { 0% { transform: translate(0, 0) scale(1); } 50% { transform: translate(5%, 10%) scale(1.1); } 100% { transform: translate(-5%, -5%) scale(0.95); } }
    body::after { content: ''; position: fixed; inset: 0; background-image: radial-gradient(rgba(0,0,0,0.04) 1px, transparent 1px), radial-gradient(rgba(0,0,0,0.02) 1px, transparent 1px); background-size: 50px 50px, 20px 20px; background-position: 0 0, 10px 10px; pointer-events: none; z-index: 0; animation: drift 60s linear infinite; }
    @keyframes drift { from { background-position: 0 0, 10px 10px; } to { background-position: 1000px 500px, 1010px 510px; } }
 
    .card-wrapper { perspective: 1000px; z-index: 10; width: 100%; max-width: 500px; margin: 20px 0; }
    .card { background: var(--glass-bg); backdrop-filter: blur(24px); border: 1px solid var(--glass-border); border-radius: 24px; padding: 40px; box-shadow: 0 25px 50px rgba(0,0,0,0.05), inset 0 1px 1px rgba(255,255,255,1), 0 0 20px rgba(0, 119, 255, 0.05); animation: zeroGravity 8s infinite ease-in-out; transform-style: preserve-3d; }
    @keyframes zeroGravity { 0%, 100% { transform: translateY(0) rotateX(0deg) rotateY(0deg); } 33% { transform: translateY(-12px) rotateX(1deg) rotateY(-1deg); } 66% { transform: translateY(8px) rotateX(-1deg) rotateY(1deg); } }
 
    .header { text-align: center; margin-bottom: 30px; }
    .status-badge { display: inline-flex; align-items: center; gap: 8px; background: rgba(0, 119, 255, 0.1); border: 1px solid rgba(0, 119, 255, 0.2); color: var(--neon-blue); font-size: 11px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; padding: 6px 14px; border-radius: 50px; margin-bottom: 12px; box-shadow: 0 0 10px rgba(0, 119, 255, 0.1); }
    .status-badge .dot { width: 6px; height: 6px; background: var(--neon-blue); border-radius: 50%; box-shadow: 0 0 6px var(--neon-blue); animation: blink 2s infinite; }
    @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
    h1 { font-size: 26px; font-weight: 800; letter-spacing: -0.5px; line-height: 1.2; color: transparent; background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple)); -webkit-background-clip: text; background-clip: text; text-shadow: 0 0 15px rgba(0, 119, 255, 0.2); }
 
    .field { margin-bottom: 18px; }
    .field label { display: block; font-size: 12px; font-weight: 700; color: var(--text-muted); margin-bottom: 6px; letter-spacing: 1px; text-transform: uppercase; transition: color 0.3s; }
    .field:focus-within label { color: var(--neon-blue); }
    .input-field { width: 100%; background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(0, 0, 0, 0.1); border-radius: 12px; padding: 12px 14px; color: var(--text-main); font-family: var(--font-tech); font-size: 14px; font-weight: 600; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: inset 0 2px 4px rgba(0,0,0,0.02); }
    .input-field:focus { outline: none; border-color: rgba(0, 119, 255, 0.5); background: rgba(255, 255, 255, 0.9); box-shadow: 0 0 15px rgba(0, 119, 255, 0.15); transform: translateZ(10px); }
    select.input-field { appearance: auto; cursor: pointer; }
    
    .row-split { display: flex; gap: 15px; }
    .row-split .field { flex: 1; }
 
    .btn-submit { width: 100%; margin-top: 15px; padding: 14px; border-radius: 12px; font-family: var(--font-tech); font-size: 14px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; transition: all 0.4s; background: linear-gradient(135deg, rgba(0,119,255,0.95), rgba(136,0,255,0.95)); color: #fff; border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 0 15px rgba(0, 119, 255, 0.2); animation: neonPulse 3s infinite alternate; }
    @keyframes neonPulse { 0% { box-shadow: 0 0 12px rgba(0, 119, 255, 0.2); } 100% { box-shadow: 0 0 20px rgba(136, 0, 255, 0.3), 0 0 15px rgba(0, 119, 255, 0.2); } }
    .btn-submit:hover { transform: translateY(-2px) scale(1.02); filter: brightness(1.1); box-shadow: 0 0 20px rgba(0, 119, 255, 0.4); }
 
    .alert { background: rgba(0, 170, 136, 0.1); border: 1px solid rgba(0, 170, 136, 0.3); color: #008866; padding: 12px 15px; border-radius: 12px; margin-bottom: 20px; font-size: 13px; font-weight: 700; box-shadow: 0 0 15px rgba(0, 170, 136, 0.1); }
    .btn-return { position: absolute; top: 20px; left: 20px; background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(0, 0, 0, 0.1); padding: 10px 18px; border-radius: 12px; color: var(--text-main); font-family: var(--font-tech); font-weight: 700; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); transition: all 0.3s; z-index: 1000; }
    .btn-return:hover { background: rgba(255, 255, 255, 0.9); color: var(--neon-blue); transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0, 119, 255, 0.15); text-decoration: none; }
  </style>
</head>
<body>
  <a href="dashboard.php" class="btn-return"><i class="fa fa-arrow-left"></i> Return</a>
 
  <div class="bg-elements"><div class="orb orb-1"></div><div class="orb orb-2"></div><div class="orb orb-3"></div></div>
 
  <div class="card-wrapper">
    <div class="card">
      <div class="header">
        <div class="status-badge"><div class="dot"></div> Admin System</div>
        <h1>Live Data Terminal</h1>
      </div>
 
      <?php if(isset($msg)) echo "<div class='alert'>$msg</div>"; ?>
 
      <form method="POST">
        <div class="field">
          <label>Target Event</label>
          <select name="event_id" class="input-field" required>
            <option value="" disabled selected>Establish link...</option>
            <?php while($row = mysqli_fetch_assoc($events)){ echo "<option value='{$row['event_id']}'>{$row['event_name']}</option>"; } ?>
          </select>
        </div>
 
        <div class="row-split">
          <div class="field">
            <label>Team/Player Alpha</label>
            <input type="text" name="team1" class="input-field" required>
          </div>
          <div class="field">
            <label>Alpha Score</label>
            <input type="number" name="score1" class="input-field" value="0" required>
          </div>
        </div>
        
        <div class="row-split">
          <div class="field">
            <label>Team/Player Beta</label>
            <input type="text" name="team2" class="input-field" required>
          </div>
          <div class="field">
            <label>Beta Score</label>
            <input type="number" name="score2" class="input-field" value="0" required>
          </div>
        </div>
 
        <div class="row-split">
          <div class="field">
            <label>Temporal Check (Time)</label>
            <input type="time" name="match_time" class="input-field" required>
          </div>
          <div class="field">
            <label>Status Vector</label>
            <select name="status" class="input-field" required>
              <option value="Live">🟢 Active (Live)</option>
              <option value="Finished">🔴 Concluded</option>
            </select>
          </div>
        </div>
 
        <button type="submit" name="submit" class="btn-submit">Transmit Stream Data</button>
      </form>
    </div>
  </div>
 
</body>
</html>