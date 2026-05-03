<?php
include "config.php";
$query = mysqli_query($conn,"
SELECT live_scores.*, sports_events.event_name
FROM live_scores
JOIN sports_events ON sports_events.event_id=live_scores.event_id
ORDER BY FIELD(live_scores.status, 'Live') DESC, updated_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Live Scoreboard - Anti-Gravity Engine</title>
  <meta http-equiv="refresh" content="10"> <!-- auto-refresh extended to 10s slightly better UX -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;900&display=swap" rel="stylesheet">
  <style>
    *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
 
    :root {
      --bg-color: #f0f4f8; --glass-bg: rgba(255, 255, 255, 0.65); --glass-border: rgba(255, 255, 255, 0.8);
      --neon-blue: #0077ff; --neon-purple: #8800ff; --neon-cyan: #00aa88; --text-main: #1e293b; --text-muted: #64748b; --font-tech: 'Poppins', sans-serif;
    }
 
    body { min-height: 100vh; background: var(--bg-color); font-family: var(--font-tech); color: var(--text-main); display: flex; flex-direction: column; align-items: center; padding: 40px 20px; overflow-x: hidden; position: relative; }
    .bg-elements { position: fixed; inset: 0; pointer-events: none; z-index: 0; }
    .orb { position: absolute; border-radius: 50%; filter: blur(80px); animation: floatOrb 25s infinite alternate ease-in-out; }
    .orb-1 { width: 45vw; height: 45vw; background: rgba(0, 119, 255, 0.15); top: -10%; left: -10%; }
    .orb-2 { width: 55vw; height: 55vw; background: rgba(136, 0, 255, 0.1); bottom: -15%; right: -10%; animation-delay: -5s; }
    .orb-3 { width: 35vw; height: 35vw; background: rgba(0, 170, 136, 0.12); top: 30%; left: 40%; animation-duration: 30s; }
    @keyframes floatOrb { 0% { transform: translate(0, 0) scale(1); } 50% { transform: translate(3%, 8%) scale(1.05); } 100% { transform: translate(-3%, -5%) scale(0.95); } }
 
    .container-box { z-index: 10; width: 100%; max-width: 1200px; margin: 0 auto; animation: popIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) both; }
    @keyframes popIn { from { opacity: 0; transform: translateY(30px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
 
    .title-group { text-align: center; margin-bottom: 40px; }
    .title-group h2 { font-size: 38px; font-weight: 900; color: transparent; background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple)); -webkit-background-clip: text; background-clip: text; display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 10px; filter: drop-shadow(0 0 10px rgba(0,119,255,0.2)); text-transform: uppercase; letter-spacing: 1px; }
    
    .search-box { background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(0, 0, 0, 0.1); border-radius: 12px; padding: 14px 20px; color: var(--text-main); font-family: var(--font-tech); font-size: 15px; font-weight: 600; transition: all 0.3s; width: 100%; max-width: 400px; margin: 0 auto 40px auto; display: block; box-shadow: 0 4px 15px rgba(0,0,0,0.02); text-align: center; }
    .search-box:focus { outline: none; border-color: rgba(0, 119, 255, 0.5); background: rgba(255, 255, 255, 0.9); box-shadow: 0 0 20px rgba(0, 119, 255, 0.15); transform: translateY(-2px); }
 
    .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px; }
 
    .scoreCard { background: var(--glass-bg); backdrop-filter: blur(24px); border: 1px solid var(--glass-border); border-radius: 20px; padding: 30px 20px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); text-align: center; position: relative; transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); overflow: hidden; }
    .scoreCard:hover { transform: translateY(-10px) scale(1.02); box-shadow: 0 25px 50px rgba(0, 119, 255, 0.15); border-color: rgba(0, 119, 255, 0.3); }
    
    .card-title { font-size: 13px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px; }
    
    .score-container { display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 20px; }
    .team { flex: 1; font-weight: 800; font-size: 18px; line-height: 1.2; word-break: break-word; }
    .score-val { font-size: 42px; font-weight: 900; background: linear-gradient(135deg, var(--text-main), var(--text-muted)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1; }
    .divider { font-size: 24px; color: rgba(0,0,0,0.1); font-weight: 300; }
    
    .lead .team { color: var(--neon-blue); }
    .lead .score-val { background: linear-gradient(135deg, var(--neon-blue), var(--neon-cyan)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; filter: drop-shadow(0 0 8px rgba(0,119,255,0.3)); }
 
    .time-badge { display: inline-block; background: rgba(0,0,0,0.04); padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: 700; color: var(--text-muted); margin-bottom: 15px; font-family: monospace; }
    
    .status-live { display: inline-flex; align-items: center; justify-content: center; width: 100%; padding: 10px; background: rgba(255, 0, 102, 0.05); color: #ff0066; font-weight: 800; font-size: 14px; letter-spacing: 2px; border-radius: 12px; position: relative; overflow: hidden; }
    .status-live::before { content: ''; position: absolute; top:0; left:0; right:0; bottom:0; border: 1px solid rgba(255, 0, 102, 0.3); border-radius: 12px; animation: pulseBorder 1.5s infinite alternate; }
    .status-live i { margin-right: 8px; font-size: 10px; animation: blink 1s infinite; }
    
    @keyframes pulseBorder { 0% { box-shadow: inset 0 0 0 rgba(255,0,102,0); } 100% { box-shadow: inset 0 0 10px rgba(255,0,102,0.2); } }
    @keyframes blink { 50% { opacity: 0; } }
    
    .status-finished { display: inline-flex; width: 100%; padding: 10px; justify-content: center; background: rgba(0,0,0,0.03); color: var(--text-muted); font-weight: 700; font-size: 13px; letter-spacing: 1px; border-radius: 12px; }
 
    .btn-return { position: absolute; top: 20px; left: 20px; background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(0, 0, 0, 0.1); padding: 10px 18px; border-radius: 12px; color: var(--text-main); font-family: var(--font-tech); font-weight: 700; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); transition: all 0.3s; z-index: 1000; }
    .btn-return:hover { background: rgba(255, 255, 255, 0.9); color: var(--neon-blue); transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0, 119, 255, 0.15); text-decoration: none; }
  </style>
</head>
<body>
  <a href="user_dashboard.php" class="btn-return"><i class="fa fa-arrow-left"></i> Return</a>
 
  <div class="bg-elements"><div class="orb orb-1"></div><div class="orb orb-2"></div><div class="orb orb-3"></div></div>
 
  <div class="container-box">
    <div class="title-group">
      <h2><i class="fa fa-broadcast-tower"></i> Live Score Broadcast</h2>
    </div>
 
    <input type="text" id="search" class="search-box" placeholder="Scan nodes (Event or Team)...">
 
    <div class="grid" id="scoreArea">
      <?php while($row=mysqli_fetch_assoc($query)) {
        if($row['score1'] > $row['score2']) { $lead1="lead"; $lead2=""; } 
        elseif($row['score2'] > $row['score1']) { $lead1=""; $lead2="lead"; } 
        else { $lead1=$lead2="lead"; }
      ?>
      <div class="scoreCard" data-search="<?php echo strtolower($row['event_name']." ".$row['team1']." ".$row['team2']); ?>">
        <div class="card-title"><?php echo htmlspecialchars($row['event_name']); ?></div>
        
        <div class="score-container">
          <div class="team-block <?php echo $lead1; ?>">
            <div class="score-val"><?php echo $row['score1']; ?></div>
            <div class="team"><?php echo htmlspecialchars($row['team1']); ?></div>
          </div>
          <div class="divider">VS</div>
          <div class="team-block <?php echo $lead2; ?>">
            <div class="score-val"><?php echo $row['score2']; ?></div>
            <div class="team"><?php echo htmlspecialchars($row['team2']); ?></div>
          </div>
        </div>
        
        <div class="time-badge"><i class="fa fa-clock"></i> <?php echo $row['match_time']; ?></div>
        
        <div>
          <?php if($row['status']=="Live") { ?>
            <div class="status-live"><i class="fa fa-circle"></i> LIVE STREAM</div>
          <?php } else { ?>
            <div class="status-finished">CONNECTION TERMINATED (FINISHED)</div>
          <?php } ?>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
 
  <script>
    document.getElementById("search").addEventListener("keyup", function(){
      let value = this.value.toLowerCase();
      document.querySelectorAll(".scoreCard").forEach(card => {
        card.style.display = card.dataset.search.includes(value) ? "" : "none";
      });
    });
  </script>
</body>
</html>