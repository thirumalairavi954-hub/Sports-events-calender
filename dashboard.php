<?php
include "config.php";

$events       = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM sports_events"));
$participants = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM participants"));
$results      = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM results"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sports Admin — Dashboard</title>
  <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,400;0,600;0,700;0,800;0,900;1,700;1,900&family=Barlow:wght@300;400;500;600;800&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
  <style>
    *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

    :root {
      --red:      #e8341c;
      --orange:   #ff6b00;
      --yellow:   #ffbe00;
      --green:    #00b86b;
      --blue:     #0077ff;
      --ink:      #0f1923;
      --ink2:     #1e2d3d;
      --paper:    #f5f3ee;
      --white:    #ffffff;
      --sidebar-w: 240px;
      --font-disp: 'Barlow Condensed', sans-serif;
      --font-body: 'Barlow', sans-serif;
    }

    html, body { height:100%; }

    body {
      font-family: var(--font-body);
      background: var(--paper);
      color: var(--ink);
      display: flex;
    }

    /* ════════════════════════════════
       SIDEBAR
    ════════════════════════════════ */
    .sidebar {
      width: var(--sidebar-w);
      min-height: 100vh;
      background: var(--ink);
      position: fixed;
      top: 0; left: 0; bottom: 0;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      z-index: 100;
    }

    /* Track lane stripe on left */
    .sidebar::before {
      content: '';
      position: absolute;
      top: 0; left: 0; bottom: 0;
      width: 4px;
      background: linear-gradient(180deg,
        var(--red) 0%,
        var(--orange) 25%,
        var(--yellow) 50%,
        var(--green) 75%,
        var(--blue) 100%);
    }

    .sidebar-brand {
      padding: 28px 22px 22px 28px;
      border-bottom: 1px solid rgba(255,255,255,0.07);
    }

    .sidebar-brand .logo-eyebrow {
      font-family: var(--font-disp);
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: var(--orange);
      margin-bottom: 4px;
    }

    .sidebar-brand h2 {
      font-family: var(--font-disp);
      font-size: 22px;
      font-weight: 900;
      font-style: italic;
      color: var(--white);
      line-height: 1;
      letter-spacing: .5px;
    }

    .sidebar-brand h2 span { color: var(--yellow); }

    .sidebar-section {
      padding: 18px 0 4px 22px;
      font-family: var(--font-disp);
      font-size: 11px;
      font-weight: 800;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: var(--yellow);
    }

    .sidebar-nav-header {
      margin: 12px 0 4px; 
      padding: 10px 12px 2px; 
      font-family: var(--font-disp); 
      font-size: 11px; 
      font-weight: 800;
      letter-spacing: 2px; 
      text-transform: uppercase; 
      color: var(--yellow);
    }

    .sidebar nav {
      flex: 1;
      padding: 8px 12px;
      overflow-y: auto;
    }

    .sidebar nav a {
      display: flex;
      align-items: center;
      gap: 11px;
      color: rgba(255,255,255,.58);
      text-decoration: none;
      font-size: 13.5px;
      font-weight: 500;
      padding: 9px 12px;
      border-radius: 8px;
      margin-bottom: 2px;
      transition: background .18s, color .18s;
      position: relative;
    }

    .sidebar nav a i {
      width: 18px;
      text-align: center;
      font-size: 13px;
      flex-shrink: 0;
    }

    .sidebar nav a:hover,
    .sidebar nav a.active {
      background: rgba(255,255,255,.07);
      color: var(--white);
    }

    .sidebar nav a.active::before {
      content: '';
      position: absolute;
      left: 0; top: 50%;
      transform: translateY(-50%);
      width: 3px; height: 60%;
      background: var(--orange);
      border-radius: 0 2px 2px 0;
    }

    /* ════════════════════════════════
       MAIN AREA
    ════════════════════════════════ */
    .main {
      margin-left: var(--sidebar-w);
      flex: 1;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ── HERO BANNER ── */
    .hero {
      position: relative;
      background: var(--ink2);
      padding: 52px 48px 48px;
      overflow: hidden;
      min-height: 260px;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
    }

    /* Diagonal striped number background */
    .hero-bg-number {
      position: absolute;
      right: -10px; top: -30px;
      font-family: var(--font-disp);
      font-size: 320px;
      font-weight: 900;
      font-style: italic;
      color: rgba(255,255,255,.03);
      line-height: 1;
      pointer-events: none;
      user-select: none;
      letter-spacing: -10px;
    }

    /* Track lanes decoration */
    .hero-lanes {
      position: absolute;
      bottom: 0; left: 0; right: 0;
      height: 6px;
      display: flex;
    }
    .hero-lanes span {
      flex: 1;
    }
    .hero-lanes span:nth-child(1) { background: var(--red); }
    .hero-lanes span:nth-child(2) { background: var(--orange); }
    .hero-lanes span:nth-child(3) { background: var(--yellow); }
    .hero-lanes span:nth-child(4) { background: var(--green); }
    .hero-lanes span:nth-child(5) { background: var(--blue); }

    /* SVG track oval decoration */
    .hero-oval {
      position: absolute;
      right: 220px; top: 50%;
      transform: translateY(-50%);
      opacity: 0.06;
      pointer-events: none;
    }

    .hero-eyebrow {
      font-family: var(--font-disp);
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 4px;
      text-transform: uppercase;
      color: var(--orange);
      margin-bottom: 10px;
      animation: fadeUp .5s ease both;
    }

    .hero h1 {
      font-family: var(--font-disp);
      font-size: 56px;
      font-weight: 900;
      font-style: italic;
      color: var(--white);
      line-height: .95;
      letter-spacing: -1px;
      text-transform: uppercase;
      animation: fadeUp .55s .07s ease both;
    }

    .hero h1 em {
      font-style: italic;
      color: var(--yellow);
    }

    .hero-sub {
      margin-top: 12px;
      font-size: 15px;
      color: var(--paper);
      opacity: 0.9;
      font-weight: 400;
      letter-spacing: 0.5px;
      animation: fadeUp .55s .14s ease both;
    }

    /* Athlete silhouette SVG — right side */
    .hero-athlete {
      position: absolute;
      right: 48px;
      bottom: 6px;
      opacity: .18;
      pointer-events: none;
    }

    @keyframes fadeUp {
      from { opacity:0; transform:translateY(18px); }
      to   { opacity:1; transform:translateY(0); }
    }

    /* ── CONTENT AREA ── */
    .content {
      padding: 42px 48px;
      flex: 1;
    }

    .section-label {
      font-family: var(--font-disp);
      font-size: 13px;
      font-weight: 800;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: var(--ink);
      opacity: 0.85;
      margin-bottom: 20px;
    }

    /* ── STAT CARDS ── */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      margin-bottom: 48px;
    }

    .stat-card {
      background: var(--white);
      border-radius: 18px;
      padding: 0;
      overflow: hidden;
      box-shadow: 0 4px 24px rgba(15,25,35,.08);
      transition: transform .22s cubic-bezier(.34,1.56,.64,1), box-shadow .22s;
      animation: cardIn .55s cubic-bezier(.16,1,.3,1) both;
      position: relative;
    }

    .stat-card:nth-child(1) { animation-delay:.05s; }
    .stat-card:nth-child(2) { animation-delay:.12s; }
    .stat-card:nth-child(3) { animation-delay:.19s; }

    .stat-card:hover {
      transform: translateY(-5px) scale(1.01);
      box-shadow: 0 16px 40px rgba(15,25,35,.14);
    }

    @keyframes cardIn {
      from { opacity:0; transform:translateY(22px); }
      to   { opacity:1; transform:translateY(0); }
    }

    .stat-card-top {
      padding: 26px 26px 20px;
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 12px;
    }

    .stat-card-label {
      font-family: var(--font-disp);
      font-size: 12px;
      font-weight: 800;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: var(--ink);
      opacity: 0.75;
      margin-bottom: 8px;
    }

    .stat-card-value {
      font-family: var(--font-disp);
      font-size: 72px;
      font-weight: 900;
      font-style: italic;
      line-height: .85;
      letter-spacing: -2px;
    }

    .stat-card-desc {
      font-size: 13px;
      color: var(--ink2);
      font-weight: 500;
      opacity: 0.8;
      margin-top: 6px;
    }

    /* Sport icon */
    .stat-icon {
      width: 52px; height: 52px;
      border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
      font-size: 22px;
    }

    /* Bottom color bar */
    .stat-card-bar {
      height: 5px;
      width: 100%;
    }

    /* Individual card themes */
    .stat-card--events .stat-card-value { color: var(--red); }
    .stat-card--events .stat-icon { background: rgba(232,52,28,.1); color: var(--red); }
    .stat-card--events .stat-card-bar { background: linear-gradient(90deg, var(--red), var(--orange)); }

    .stat-card--participants .stat-card-value { color: var(--blue); }
    .stat-card--participants .stat-icon { background: rgba(0,119,255,.1); color: var(--blue); }
    .stat-card--participants .stat-card-bar { background: linear-gradient(90deg, var(--blue), #00d4ff); }

    .stat-card--results .stat-card-value { color: var(--green); }
    .stat-card--results .stat-icon { background: rgba(0,184,107,.1); color: var(--green); }
    .stat-card--results .stat-card-bar { background: linear-gradient(90deg, var(--green), var(--yellow)); }

    /* ── QUICK ACTIONS ── */
    .actions-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 14px;
    }

    .action-btn {
      background: var(--white);
      border: none;
      border-radius: 14px;
      padding: 20px 16px 18px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      color: var(--ink);
      cursor: pointer;
      box-shadow: 0 2px 12px rgba(15,25,35,.07);
      transition: transform .2s cubic-bezier(.34,1.56,.64,1), box-shadow .2s, background .2s;
      animation: cardIn .55s cubic-bezier(.16,1,.3,1) both;
    }

    .action-btn:nth-child(1){animation-delay:.22s}
    .action-btn:nth-child(2){animation-delay:.27s}
    .action-btn:nth-child(3){animation-delay:.32s}
    .action-btn:nth-child(4){animation-delay:.37s}

    .action-btn:hover {
      transform: translateY(-3px) scale(1.03);
      box-shadow: 0 10px 28px rgba(15,25,35,.12);
      background: var(--ink);
      color: var(--white);
    }

    .action-btn:hover .action-icon { background: rgba(255,255,255,.1); color: var(--white); }

    .action-icon {
      width: 46px; height: 46px;
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      font-size: 18px;
      transition: background .2s, color .2s;
    }

    .action-btn:nth-child(1) .action-icon { background: rgba(232,52,28,.1); color: var(--red); }
    .action-btn:nth-child(2) .action-icon { background: rgba(0,119,255,.1);  color: var(--blue); }
    .action-btn:nth-child(3) .action-icon { background: rgba(0,184,107,.1);  color: var(--green); }
    .action-btn:nth-child(4) .action-icon { background: rgba(255,190,0,.1);  color: #c98b00; }

    .action-label {
      font-family: var(--font-disp);
      font-size: 13px;
      font-weight: 700;
      letter-spacing: .5px;
      text-align: center;
      text-transform: uppercase;
    }

    /* ── Responsive ── */
    @media (max-width: 900px) {
      .stats-grid  { grid-template-columns: 1fr 1fr; }
      .actions-grid { grid-template-columns: 1fr 1fr; }
      .hero { padding: 36px 28px 44px; }
      .hero h1 { font-size: 40px; }
      .content { padding: 28px 24px; }
      .hero-athlete { display: none; }
    }

    /* ── Mobile Layout Fixes ── */
    .mobile-menu-btn {
      display: none;
      position: fixed;
      top: 15px; left: 20px;
      z-index: 1001;
      background: var(--ink);
      color: var(--white);
      border: none;
      border-radius: 8px;
      width: 44px; height: 44px;
      font-size: 18px;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .sidebar-overlay {
      display: none; position: fixed; inset: 0;
      background: rgba(15,25,35,0.6);
      backdrop-filter: blur(3px); z-index: 99;
    }
    .close-sidebar-btn {
      display: none; position: absolute; right: 20px; top: 25px;
      background: transparent; border: none; color: white;
      font-size: 20px; cursor: pointer;
    }

    @media (max-width: 640px) {
      :root { --sidebar-w: 0px; }
      .hero { padding-top: 70px; }
      .mobile-menu-btn { display: block; }
      .sidebar { 
        display: flex; 
        position: fixed; 
        transform: translateX(-100%); 
        transition: transform 0.3s ease;
        width: 260px;
      }
      .sidebar.active { transform: translateX(0); }
      .sidebar-overlay.active { display: block; }
      .close-sidebar-btn { display: block; }
      .stats-grid { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

<button class="mobile-menu-btn" onclick="toggleSidebar()"><i class="fa fa-bars"></i></button>
<div class="sidebar-overlay" onclick="toggleSidebar()"></div>

<aside class="sidebar">
  <div class="sidebar-brand" style="position: relative;">
    <button class="close-sidebar-btn" onclick="toggleSidebar()"><i class="fa fa-times"></i></button>
    <div class="logo-eyebrow">Admin Panel</div>
    <h2>SPORTS<br/><span>MEET</span></h2>
  </div>

  <div class="sidebar-section">Main</div>
  <nav>
    <a href="dashboard.php" class="active"><i class="fa fa-bolt"></i> Dashboard</a>
    <a href="add_event.php"><i class="fa fa-plus-circle"></i> Add Event</a>
    <a href="add_participant.php"><i class="fa fa-user-plus"></i> Add Participant</a>
    <a href="view_events.php"><i class="fa fa-calendar-check"></i> View Events</a>
    <a href="view_participants.php"><i class="fa fa-users"></i> Participants</a>

    <div class="sidebar-nav-header">Competition</div>

    <a href="add_result.php"><i class="fa fa-medal"></i> Enter Result</a>
    <a href="leaderboard.php"><i class="fa fa-trophy"></i> Leaderboard</a>
    <a href="medal_table.php"><i class="fa fa-award"></i> Medal Table</a>
    <a href="top_atheletes.php"><i class="fa fa-star"></i> Top Athletes</a>
    <a href="view_results.php"><i class="fa fa-list-ol"></i> View Results</a>

    <div class="sidebar-nav-header">Live</div>

    <a href="schedule.php"><i class="fa fa-clock"></i> Schedule</a>
    <a href="event_calendar.php"><i class="fa fa-calendar"></i> Calendar</a>
    <a href="admin_live_scores.php"><i class="fa fa-signal"></i> Score Control</a>
    <a href="live_scoreboard.php"><i class="fa fa-tv"></i> Live Board</a>
    <a href="admin_event_requests.php"><i class="fa fa-user-check"></i> Requests</a>
  </nav>
</aside>

<div class="main">

  <div class="hero">
    <div class="hero-bg-number">01</div>

    <svg class="hero-athlete" width="220" height="220" viewBox="0 0 100 120" fill="white">
      <circle cx="54" cy="12" r="7"/>
      <path d="M54 19 L48 42 L36 58 M54 19 L60 42 L72 56 M48 42 L38 65 L28 80 M60 42 L62 65 L74 80" stroke="white" stroke-width="4" stroke-linecap="round" fill="none"/>
    </svg>

    <svg class="hero-oval" width="340" height="180" viewBox="0 0 340 180">
      <ellipse cx="170" cy="90" rx="160" ry="76" fill="none" stroke="white" stroke-width="6"/>
      <ellipse cx="170" cy="90" rx="130" ry="55" fill="none" stroke="white" stroke-width="4"/>
      <ellipse cx="170" cy="90" rx="100" ry="36" fill="none" stroke="white" stroke-width="3"/>
    </svg>

    <div class="hero-eyebrow">⚡ Command Center</div>
    <h1>GAME<br/><em>DAY</em></h1>
    <p class="hero-sub">Here's how your sports meet is shaping up today.</p>

    <div class="hero-lanes">
      <span></span><span></span><span></span><span></span><span></span>
    </div>
  </div>

  <div class="content">

    <div class="section-label">📊 At a Glance</div>
    <div class="stats-grid">

      <div class="stat-card stat-card--events">
        <div class="stat-card-top">
          <div>
            <div class="stat-card-label">Total Events</div>
            <div class="stat-card-value"><?= $events ?></div>
            <div class="stat-card-desc">Scheduled competitions</div>
          </div>
          <div class="stat-icon">
            <i class="fa fa-flag-checkered"></i>
          </div>
        </div>
        <div class="stat-card-bar"></div>
      </div>

      <div class="stat-card stat-card--participants">
        <div class="stat-card-top">
          <div>
            <div class="stat-card-label">Participants</div>
            <div class="stat-card-value"><?= $participants ?></div>
            <div class="stat-card-desc">Athletes registered</div>
          </div>
          <div class="stat-icon">
            <i class="fa fa-users"></i>
          </div>
        </div>
        <div class="stat-card-bar"></div>
      </div>

      <div class="stat-card stat-card--results">
        <div class="stat-card-top">
          <div>
            <div class="stat-card-label">Results</div>
            <div class="stat-card-value"><?= $results ?></div>
            <div class="stat-card-desc">Results recorded</div>
          </div>
          <div class="stat-icon">
            <i class="fa fa-medal"></i>
          </div>
        </div>
        <div class="stat-card-bar"></div>
      </div>

    </div>

    <div class="section-label">⚡ Quick Actions</div>
    <div class="actions-grid">
      <a href="add_event.php" class="action-btn">
        <div class="action-icon"><i class="fa fa-plus"></i></div>
        <span class="action-label">New Event</span>
      </a>
      <a href="add_participant.php" class="action-btn">
        <div class="action-icon"><i class="fa fa-user-plus"></i></div>
        <span class="action-label">Register Athlete</span>
      </a>
      <a href="add_result.php" class="action-btn">
        <div class="action-icon"><i class="fa fa-trophy"></i></div>
        <span class="action-label">Enter Result</span>
      </a>
      <a href="live_scoreboard.php" class="action-btn">
        <div class="action-icon"><i class="fa fa-bolt"></i></div>
        <span class="action-label">Live Board</span>
      </a>
    </div>

  </div>
</div>
 
<script>
  function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('active');
    document.querySelector('.sidebar-overlay').classList.toggle('active');
  }
</script>
</body>
</html>