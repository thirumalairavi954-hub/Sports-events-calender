<?php
include "config.php";
 
$query=mysqli_query($conn,"
SELECT participants.name,
SUM(CASE WHEN results.first_place=participants.participant_id THEN 1 ELSE 0 END) AS gold,
SUM(CASE WHEN results.second_place=participants.participant_id THEN 1 ELSE 0 END) AS silver,
SUM(CASE WHEN results.third_place=participants.participant_id THEN 1 ELSE 0 END) AS bronze
FROM participants
LEFT JOIN results
ON participants.participant_id IN 
(results.first_place,results.second_place,results.third_place)
GROUP BY participants.participant_id
ORDER BY gold DESC,silver DESC,bronze DESC
");
 
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Medal Table - Anti-Gravity Engine</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
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
      flex-direction: column;
      align-items: center;
      padding: 40px 20px;
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
      filter: blur(80px);
      animation: floatOrb 25s infinite alternate ease-in-out;
    }
 
    .orb-1 { width: 45vw; height: 45vw; background: rgba(0, 119, 255, 0.15); top: -10%; left: -10%; }
    .orb-2 { width: 55vw; height: 55vw; background: rgba(136, 0, 255, 0.1); bottom: -15%; right: -10%; animation-delay: -5s; }
    .orb-3 { width: 35vw; height: 35vw; background: rgba(0, 170, 136, 0.12); top: 30%; left: 40%; animation-duration: 30s; }
 
    @keyframes floatOrb {
      0% { transform: translate(0, 0) scale(1); }
      50% { transform: translate(3%, 8%) scale(1.05); }
      100% { transform: translate(-3%, -5%) scale(0.95); }
    }
 
    /* Dotted starfield texture */
    body::after {
      content: '';
      position: fixed;
      inset: 0;
      background-image: 
        radial-gradient(rgba(0,0,0,0.03) 1px, transparent 1px),
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
    .container-box {
      z-index: 10;
      width: 100%;
      max-width: 900px;
      margin: 0 auto;
      animation: popIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) both;
    }
 
    @keyframes popIn {
      from { opacity: 0; transform: translateY(30px) scale(0.98); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }
 
    .card {
      background: var(--glass-bg);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border: 1px solid var(--glass-border);
      border-radius: 20px;
      padding: 35px 40px;
      box-shadow: 
        0 20px 40px rgba(0,0,0,0.04), 
        inset 0 1px 1px rgba(255,255,255,1),
        0 0 20px rgba(0, 119, 255, 0.05);
      position: relative;
    }
 
    /* ── Header ── */
    .header-area {
      display: flex;
      justify-content: center;
      text-align: center;
      margin-bottom: 30px;
    }
 
    .title-group h3 {
      font-size: 32px;
      font-weight: 700;
      color: transparent;
      background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple));
      -webkit-background-clip: text;
      background-clip: text;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      margin-bottom: 5px;
      text-shadow: 0 0 15px rgba(0, 119, 255, 0.1);
    }
 
    .title-group p {
      color: var(--text-muted);
      font-size: 15px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
 
    /* ── Table Styling ── */
    .table-container {
      width: 100%;
      overflow-x: auto;
      border-radius: 12px;
      box-shadow: inset 0 0 0 1px rgba(0,0,0,0.05);
      background: rgba(255, 255, 255, 0.4);
    }
 
    .table {
      width: 100%;
      border-collapse: collapse;
      text-align: center;
    }
 
    .table thead th {
      background: rgba(0, 119, 255, 0.05);
      color: var(--neon-blue);
      font-weight: 700;
      padding: 18px 20px;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-size: 13px;
      border-bottom: 2px solid rgba(0, 119, 255, 0.1);
      white-space: nowrap;
    }
 
    .table tbody tr {
      border-bottom: 1px solid rgba(0, 0, 0, 0.04);
      transition: all 0.3s ease;
    }
 
    .table tbody td {
      padding: 18px 20px;
      color: var(--text-main);
      font-size: 16px;
      font-weight: 600;
      vertical-align: middle;
      white-space: nowrap;
    }
 
    .table tbody td:nth-child(2) {
      font-weight: 700;
      text-align: left;
    }
 
    .table tbody tr:last-child {
      border-bottom: none;
    }
 
    .table tbody tr:hover {
      background: rgba(255, 255, 255, 0.8);
      box-shadow: 0 4px 15px rgba(0, 119, 255, 0.05);
      transform: scale(1.01);
    }
 
    /* Medal blocks */
    .medal-block {
      display: inline-block;
      padding: 6px 14px;
      border-radius: 8px;
      font-weight: 800;
      min-width: 44px;
      color: white;
    }
    
    .gold-bg {
      background: linear-gradient(135deg, #F59E0B, #FBBF24);
      box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3);
    }
    
    .silver-bg {
      background: linear-gradient(135deg, #94A3B8, #CBD5E1);
      box-shadow: 0 4px 10px rgba(148, 163, 184, 0.3);
      color: #333;
    }
    
    .bronze-bg {
      background: linear-gradient(135deg, #B45309, #D97706);
      box-shadow: 0 4px 10px rgba(180, 83, 9, 0.3);
    }
    
    .total-block {
      background: linear-gradient(135deg, rgba(0, 119, 255, 0.2), rgba(136, 0, 255, 0.15));
      color: var(--neon-blue);
      padding: 6px 14px;
      border-radius: 8px;
      font-weight: 800;
      box-shadow: inset 0 0 0 1px rgba(0, 119, 255, 0.3);
    }
 
    /* ── Responsive ── */
    @media (max-width: 900px) {
      .card { padding: 25px; }
      .title-group h3 { font-size: 24px; }
      .medal-block { padding: 4px 10px; }
    }
    .btn-return { position: absolute; top: 20px; left: 20px; background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(0, 0, 0, 0.1); padding: 10px 18px; border-radius: 12px; color: var(--text-main); font-family: var(--font-tech); font-weight: 700; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); transition: all 0.3s; z-index: 1000; }
    .btn-return:hover { background: rgba(255, 255, 255, 0.9); color: var(--neon-blue); transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0, 119, 255, 0.15); text-decoration: none; }
  </style>
</head>
<body>
  <a href="user_dashboard.php" class="btn-return"><i class="fa fa-arrow-left"></i> Return</a>
 
  <div class="bg-elements">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
  </div>
 
  <div class="container-box">
    <div class="card">
      
      <div class="header-area">
        <div class="title-group">
          <h3><i class="fa fa-medal" style="color:var(--neon-blue);"></i> Medal Data Table</h3>
          <p>Global Participant Rankings</p>
        </div>
      </div>
 
      <div class="table-container">
        <table class="table">
          <thead>
            <tr>
              <th>Rank Rank</th>
              <th style="text-align: left;">Identity</th>
              <th>🥇 Alpha</th>
              <th>🥈 Beta</th>
              <th>🥉 Gamma</th>
              <th>Aggregate</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $rank=1;
            while($row=mysqli_fetch_assoc($query)) {
              $total = $row['gold'] + $row['silver'] + $row['bronze'];
            ?>
            <tr>
              <td style="color:var(--text-muted);">#<?php echo $rank++; ?></td>
              <td><?php echo htmlspecialchars($row['name']); ?></td>
              <td><span class="medal-block gold-bg"><?php echo $row['gold']; ?></span></td>
              <td><span class="medal-block silver-bg"><?php echo $row['silver']; ?></span></td>
              <td><span class="medal-block bronze-bg"><?php echo $row['bronze']; ?></span></td>
              <td><span class="total-block"><?php echo $total; ?></span></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
 
    </div>
  </div>
 
</body>
</html>