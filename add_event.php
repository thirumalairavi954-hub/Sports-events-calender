<?php
session_start();
include "config.php";
if(!isset($_SESSION['admin'])) {
  header("Location: sports.admin.portal.login.php");
}
 
if(isset($_POST['add'])) {
  $name = $_POST['event_name'];
  $date = $_POST['event_date'];
  $type = $_POST['event_type'];
 
  $sql = "INSERT INTO sports_events(event_name, event_date, event_type)
          VALUES('$name','$date','$type')";
  mysqli_query($conn, $sql);
  $msg = "Event Added Successfully";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Sports Event - Anti-Gravity Engine</title>
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
      overflow: hidden; /* Prevent scrolling if orbs go out of bounds */
      position: relative;
    }
 
    /* Anti-gravity animated background environment */
    .bg-elements {
      position: absolute;
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
 
    /* Dotted starfield texture - adjusted for light theme */
    body::after {
      content: '';
      position: absolute;
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
    }
 
    .card {
      background: var(--glass-bg);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border: 1px solid var(--glass-border);
      border-radius: 24px;
      padding: 40px;
      box-shadow: 
        0 25px 50px rgba(0,0,0,0.05), 
        inset 0 1px 1px rgba(255,255,255,1),
        0 0 20px rgba(0, 119, 255, 0.05);
      animation: zeroGravity 8s infinite ease-in-out;
      transform-style: preserve-3d;
    }
 
    @keyframes zeroGravity {
      0%, 100% { transform: translateY(0) rotateX(0deg) rotateY(0deg); }
      33% { transform: translateY(-12px) rotateX(2deg) rotateY(-1deg); }
      66% { transform: translateY(8px) rotateX(-1deg) rotateY(2deg); }
    }
 
    /* ── Header ── */
    .header {
      text-align: center;
      margin-bottom: 35px;
    }
 
    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(0, 119, 255, 0.1);
      border: 1px solid rgba(0, 119, 255, 0.2);
      color: var(--neon-blue);
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 2px;
      text-transform: uppercase;
      padding: 6px 14px;
      border-radius: 50px;
      margin-bottom: 15px;
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
      font-size: 32px;
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
      margin-top: 8px;
      color: var(--text-muted);
      font-size: 14px;
    }
 
    /* ── Form Floating Elements ── */
    .field {
      margin-bottom: 25px;
      position: relative;
    }
 
    .field label {
      display: block;
      font-size: 12px;
      font-weight: 700;
      color: var(--text-muted);
      margin-bottom: 8px;
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
      padding: 14px 16px 14px 44px;
      color: var(--text-main);
      font-family: var(--font-tech);
      font-size: 15px;
      font-weight: 600;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
 
    .input-icon {
      position: absolute;
      left: 15px;
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
      transform: translateZ(10px); /* 3D float effect on focus */
    }
 
    .input-field:focus + .input-icon, 
    .field:focus-within .input-icon {
      color: var(--neon-blue);
    }
 
    .input-field::placeholder { color: rgba(0,0,0,0.3); font-weight: 400; }
 
    /* Select & Date specific tweaks */
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
 
    input[type="date"]::-webkit-calendar-picker-indicator {
      cursor: pointer;
      opacity: 0.5;
      transition: opacity 0.3s;
    }
 
    input[type="date"]:focus::-webkit-calendar-picker-indicator {
      opacity: 1;
    }
 
    /* ── Buttons ── */
    .btn-container {
      display: flex;
      flex-direction: column;
      gap: 15px;
      margin-top: 30px;
    }
 
    .btn {
      width: 100%;
      padding: 16px;
      border-radius: 12px;
      font-family: var(--font-tech);
      font-size: 15px;
      font-weight: 700;
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
      transform: translateY(-3px) scale(1.02);
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
      padding: 15px;
      border-radius: 12px;
      margin-bottom: 25px;
      font-size: 14px;
      font-weight: 600;
      box-shadow: 0 0 15px rgba(0, 170, 136, 0.1);
    }
 
  </style>
</head>
<body>
 
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
          System Ready
        </div>
        <h1>Event <span>Engine</span></h1>
        <p class="subtitle">Initialize a new spatial event protocol</p>
      </div>
 
      <?php if(isset($msg)): ?>
      <div class="alert">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <?= htmlspecialchars($msg) ?>
      </div>
      <?php endif; ?>
 
      <form method="post">
        
        <!-- Event Name -->
        <div class="field">
          <label>Designation</label>
          <div class="input-wrapper">
            <input type="text" name="event_name" class="input-field" placeholder="Enter event name..." required autocomplete="off" />
            <div class="input-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
            </div>
          </div>
        </div>
 
        <!-- Event Date -->
        <div class="field">
          <label>Temporal Coordinates</label>
          <div class="input-wrapper">
            <input type="date" name="event_date" class="input-field" required />
            <div class="input-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
          </div>
        </div>
 
        <!-- Event Type -->
        <div class="field">
          <label>Classification</label>
          <div class="input-wrapper">
            <select name="event_type" class="input-field" required>
              <option value="" disabled selected>Select parameter...</option>
              <option value="Track">Track Module</option>
              <option value="Field">Field Zone</option>
              <option value="Indoor">Indoor Chamber</option>
            </select>
            <div class="input-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 12 12 14 14"></polyline></svg>
            </div>
            <div class="select-arrow"></div>
          </div>
        </div>
 
        <div class="btn-container">
          <button type="submit" name="add" class="btn btn-submit">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Deploy Event
          </button>
          
          <a href="javascript:history.back()" class="btn btn-back">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Abort / Return
          </a>
        </div>
 
      </form>
    </div>
  </div>
 
</body>
</html>