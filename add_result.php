<?php
include "config.php";
 
if(isset($_POST['save'])) {
  $event=$_POST['event'];
  $first=$_POST['first'];
  $second=$_POST['second'];
  $third=$_POST['third'];
 
  mysqli_query($conn,"INSERT INTO results (event_id,first_place,second_place,third_place) VALUES ('$event','$first','$second','$third')");
  $msg="Result Data Committed";
}
 
$events=mysqli_query($conn,"SELECT * FROM sports_events");
$participants=mysqli_query($conn,"SELECT * FROM participants");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Enter Result - Anti-Gravity Engine</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
 
    :root {
      --bg-color: #f0f4f8;
      --glass-bg: rgba(255, 255, 255, 0.65);
      --glass-border: rgba(255, 255, 255, 0.8);
      --neon-blue: #0077ff;
      --neon-purple: #8800ff;
      --neon-cyan: #00aa88;
      --text-main: #1e293b;
      --text-muted: #64748b;
      --font-tech: 'Poppins', sans-serif;
    }
 
    body {
      min-height: 100vh;
      background: var(--bg-color);
      font-family: var(--font-tech);
      color: var(--text-main);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 24px;
      overflow-x: hidden;
      position: relative;
    }
 
    /* Anti-gravity animated background environment */
    .bg-elements {
      position: fixed;
      inset: 0;
      pointer-events: none;
      z-index: 0;
    }
 
    .orb {
      position: absolute;
      border-radius: 50%;
      filter: blur(70px);
      animation: floatOrb 20s infinite alternate ease-in-out;
    }
 
    .orb-1 {
      width: 40vw; height: 40vw;
      background: rgba(0, 119, 255, 0.2);
      top: -10%; left: -10%;
    }
 
    .orb-2 {
      width: 50vw; height: 50vw;
      background: rgba(136, 0, 255, 0.15);
      bottom: -20%; right: -15%;
      animation-delay: -7s;
    }
 
    .orb-3 {
      width: 30vw; height: 30vw;
      background: rgba(0, 170, 136, 0.15);
      top: 40%; left: 50%;
      animation-duration: 25s;
    }
 
    @keyframes floatOrb {
      0% { transform: translate(0, 0) scale(1); }
      50% { transform: translate(5%, 10%) scale(1.1); }
      100% { transform: translate(-5%, -5%) scale(0.95); }
    }
 
    /* Dotted starfield texture */
    body::after {
      content: '';
      position: fixed;
      inset: 0;
      background-image: 
        radial-gradient(rgba(0,0,0,0.04) 1px, transparent 1px),
        radial-gradient(rgba(0,0,0,0.02) 1px, transparent 1px);
      background-size: 50px 50px, 20px 20px;
      background-position: 0 0, 10px 10px;
      pointer-events: none;
      z-index: 0;
      animation: drift 60s linear infinite;
    }
 
    @keyframes drift {
      from { background-position: 0 0, 10px 10px; }
      to { background-position: 1000px 500px, 1010px 510px; }
    }
 
    /* ── Main Glassmorphism Card ── */
    .card-wrapper {
      perspective: 1000px;
      z-index: 10;
      width: 100%;
      max-width: 460px;
      margin: 20px 0;
    }
 
    .card {
      background: var(--glass-bg);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border: 1px solid var(--glass-border);
      border-radius: 24px;
      padding: 35px 40px;
      box-shadow: 
        0 25px 50px rgba(0,0,0,0.05), 
        inset 0 1px 1px rgba(255,255,255,1),
        0 0 20px rgba(0, 119, 255, 0.05);
      animation: zeroGravity 8s infinite ease-in-out;
      transform-style: preserve-3d;
    }
 
    @keyframes zeroGravity {
      0%, 100% { transform: translateY(0) rotateX(0deg) rotateY(0deg); }
      33% { transform: translateY(-12px) rotateX(1deg) rotateY(-1deg); }
      66% { transform: translateY(8px) rotateX(-1deg) rotateY(1deg); }
    }
 
    /* ── Header ── */
    .header {
      text-align: center;
      margin-bottom: 30px;
    }
 
    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(0, 119, 255, 0.1);
      border: 1px solid rgba(0, 119, 255, 0.2);
      color: var(--neon-blue);
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 2px;
      text-transform: uppercase;
      padding: 6px 14px;
      border-radius: 50px;
      margin-bottom: 12px;
      box-shadow: 0 0 10px rgba(0, 119, 255, 0.1);
    }
 
    .status-badge .dot {
      width: 6px; height: 6px;
      background: var(--neon-blue);
      border-radius: 50%;
      box-shadow: 0 0 6px var(--neon-blue);
      animation: blink 2s infinite;
    }
 
    @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
 
    h1 {
      font-size: 28px;
      font-weight: 700;
      letter-spacing: -0.5px;
      line-height: 1.2;
    }
 
    h1 span {
      color: transparent;
      background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple));
      -webkit-background-clip: text;
      background-clip: text;
      text-shadow: 0 0 15px rgba(0, 119, 255, 0.2);
    }
 
    .subtitle {
      margin-top: 5px;
      color: var(--text-muted);
      font-size: 13px;
    }
 
    /* ── Form Floating Elements ── */
    .field {
      margin-bottom: 20px;
      position: relative;
    }
 
    .field label {
      display: block;
      font-size: 12px;
      font-weight: 600;
      color: var(--text-muted);
      margin-bottom: 6px;
      letter-spacing: 1px;
      text-transform: uppercase;
      transition: color 0.3s;
    }
 
    .field:focus-within label {
      color: var(--neon-blue);
    }
 
    .input-wrapper {
      position: relative;
    }
 
    .input-field {
      width: 100%;
      background: rgba(255, 255, 255, 0.7);
      border: 1px solid rgba(0, 0, 0, 0.1);
      border-radius: 12px;
      padding: 12px 14px 12px 42px;
      color: var(--text-main);
      font-family: var(--font-tech);
      font-size: 14px;
      font-weight: 600;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
 
    .input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      transition: all 0.3s;
      display: flex;
      pointer-events: none;
    }
 
    .input-field:focus {
      outline: none;
      border-color: rgba(0, 119, 255, 0.5);
      background: rgba(255, 255, 255, 0.9);
      box-shadow: 
        0 0 15px rgba(0, 119, 255, 0.15), 
        inset 0 0 8px rgba(0, 119, 255, 0.05);
      transform: translateZ(10px);
    }
 
    .input-field:focus + .input-icon, 
    .field:focus-within .input-icon {
      color: var(--neon-blue);
    }
 
    /* Select specific tweaks */
    select.input-field {
      appearance: none;
      cursor: pointer;
    }
 
    .select-arrow {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      pointer-events: none;
      border-left: 5px solid transparent;
      border-right: 5px solid transparent;
      border-top: 6px solid var(--text-muted);
      transition: all 0.3s;
    }
 
    .field:focus-within .select-arrow {
      border-top-color: var(--neon-blue);
      transform: translateY(-50%) rotate(180deg);
    }
 
    select option {
      background: #ffffff;
      color: var(--text-main);
    }
 
    /* ── Buttons ── */
    .btn-container {
      display: flex;
      flex-direction: column;
      gap: 12px;
      margin-top: 25px;
    }
 
    .btn {
      width: 100%;
      padding: 14px;
      border-radius: 12px;
      font-family: var(--font-tech);
      font-size: 14px;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      text-decoration: none;
    }
 
    .btn-submit {
      background: linear-gradient(135deg, rgba(0,119,255,0.95), rgba(136,0,255,0.95));
      color: #fff;
      border: 1px solid rgba(255,255,255,0.2);
      box-shadow: 0 0 15px rgba(0, 119, 255, 0.2);
      animation: neonPulse 3s infinite alternate;
    }
 
    @keyframes neonPulse {
      0% { box-shadow: 0 0 12px rgba(0, 119, 255, 0.2); }
      100% { box-shadow: 0 0 20px rgba(136, 0, 255, 0.3), 0 0 15px rgba(0, 119, 255, 0.2); }
    }
 
    .btn-submit:hover {
      transform: translateY(-2px) scale(1.02);
      filter: brightness(1.1);
      box-shadow: 0 0 20px rgba(0, 119, 255, 0.4);
    }
 
    .btn-back {
      background: transparent;
      color: var(--text-muted);
      border: 1px solid rgba(0,0,0,0.15);
    }
 
    .btn-back:hover {
      background: rgba(0,0,0,0.05);
      color: var(--text-main);
      border-color: rgba(0,0,0,0.3);
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
 
    /* ── Alert ── */
    .alert {
      display: flex;
      align-items: center;
      gap: 10px;
      background: rgba(0, 170, 136, 0.1);
      border: 1px solid rgba(0, 170, 136, 0.3);
      color: #008866;
      padding: 12px 15px;
      border-radius: 12px;
      margin-bottom: 20px;
      font-size: 13px;
      font-weight: 600;
      box-shadow: 0 0 15px rgba(0, 170, 136, 0.1);
    }
 
    .btn-return { position: absolute; top: 20px; left: 20px; background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(0, 0, 0, 0.1); padding: 10px 18px; border-radius: 12px; color: var(--text-main); font-family: var(--font-tech); font-weight: 700; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); transition: all 0.3s; z-index: 1000; }
    .btn-return:hover { background: rgba(255, 255, 255, 0.9); color: var(--neon-blue); transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0, 119, 255, 0.15); text-decoration: none; }
  </style>
</head>
<body>
  <a href="dashboard.php" class="btn-return"><i class="fa fa-arrow-left"></i> Return</a>
 
  <div class="bg-elements">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
  </div>
 
  <div class="card-wrapper">
    <div class="card">
      <div class="header">
        <div class="status-badge">
          <div class="dot"></div>
          Signal Detected
        </div>
        <h1>Event <span>Results</span></h1>
        <p class="subtitle">Commit performance data metrics</p>
      </div>
 
      <?php if(isset($msg)): ?>
      <div class="alert">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <?= htmlspecialchars($msg) ?>
      </div>
      <?php endif; ?>
 
      <form method="post">
        
        <!-- Target Event -->
        <div class="field">
          <label>Target Event</label>
          <div class="input-wrapper">
            <select name="event" class="input-field" required>
              <option value="" disabled selected>Establish event link...</option>
              <?php while($row=mysqli_fetch_assoc($events)) { ?>
                <option value="<?php echo $row['event_id']; ?>">
                  <?php echo htmlspecialchars($row['event_name']); ?>
                </option>
              <?php } ?>
            </select>
            <div class="input-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
            </div>
            <div class="select-arrow"></div>
          </div>
        </div>
 
        <!-- First Place -->
        <div class="field">
          <label>🥇 Alpha Node (First Place)</label>
          <div class="input-wrapper">
            <select name="first" class="input-field" required>
              <option value="" disabled selected>Assign tier 1 rank...</option>
              <?php mysqli_data_seek($participants,0); while($row=mysqli_fetch_assoc($participants)) { ?>
                <option value="<?php echo $row['participant_id']; ?>">
                  <?php echo htmlspecialchars($row['name']); ?>
                </option>
              <?php } ?>
            </select>
            <div class="input-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </div>
            <div class="select-arrow"></div>
          </div>
        </div>
 
        <!-- Second Place -->
        <div class="field">
          <label>🥈 Beta Node (Second Place)</label>
          <div class="input-wrapper">
            <select name="second" class="input-field" required>
              <option value="" disabled selected>Assign tier 2 rank...</option>
              <?php mysqli_data_seek($participants,0); while($row=mysqli_fetch_assoc($participants)) { ?>
                <option value="<?php echo $row['participant_id']; ?>">
                  <?php echo htmlspecialchars($row['name']); ?>
                </option>
              <?php } ?>
            </select>
            <div class="input-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </div>
            <div class="select-arrow"></div>
          </div>
        </div>
 
        <!-- Third Place -->
        <div class="field">
          <label>🥉 Gamma Node (Third Place)</label>
          <div class="input-wrapper">
            <select name="third" class="input-field" required>
              <option value="" disabled selected>Assign tier 3 rank...</option>
              <?php mysqli_data_seek($participants,0); while($row=mysqli_fetch_assoc($participants)) { ?>
                <option value="<?php echo $row['participant_id']; ?>">
                  <?php echo htmlspecialchars($row['name']); ?>
                </option>
              <?php } ?>
            </select>
            <div class="input-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            </div>
            <div class="select-arrow"></div>
          </div>
        </div>
 
        <div class="btn-container">
          <button type="submit" name="save" class="btn btn-submit">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
            Commit Results
          </button>
          
          <a href="javascript:history.back()" class="btn btn-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Abort Protocol
          </a>
        </div>
 
      </form>
    </div>
  </div>
 
</body>
</html>