<?php
include "config.php";
 
$query=mysqli_query($conn,"
SELECT 
participants.name,
participants.department,
SUM(CASE
WHEN participants.participant_id = results.first_place THEN 5
WHEN participants.participant_id = results.second_place THEN 3
WHEN participants.participant_id = results.third_place THEN 1
ELSE 0 END) AS points
FROM participants
LEFT JOIN results ON participants.participant_id IN (results.first_place,results.second_place,results.third_place)
GROUP BY participants.participant_id
ORDER BY points DESC
");
 
$data=[];
$departments=[];
while($row=mysqli_fetch_assoc($query)){
  $data[]=$row;
  $departments[$row['department']]=$row['department'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Top Athletes - Anti-Gravity Engine</title>
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
      min-height: 100vh; background: var(--bg-color); font-family: var(--font-tech); color: var(--text-main);
      display: flex; flex-direction: column; align-items: center; padding: 40px 20px; overflow-x: hidden; position: relative;
    }
 
    .bg-elements { position: fixed; inset: 0; pointer-events: none; z-index: 0; }
    .orb { position: absolute; border-radius: 50%; filter: blur(80px); animation: floatOrb 25s infinite alternate ease-in-out; }
    .orb-1 { width: 45vw; height: 45vw; background: rgba(0, 119, 255, 0.15); top: -10%; left: -10%; }
    .orb-2 { width: 55vw; height: 55vw; background: rgba(136, 0, 255, 0.1); bottom: -15%; right: -10%; animation-delay: -5s; }
    .orb-3 { width: 35vw; height: 35vw; background: rgba(0, 170, 136, 0.12); top: 30%; left: 40%; animation-duration: 30s; }
    @keyframes floatOrb { 0% { transform: translate(0, 0) scale(1); } 50% { transform: translate(3%, 8%) scale(1.05); } 100% { transform: translate(-3%, -5%) scale(0.95); } }
 
    body::after { content: ''; position: fixed; inset: 0; background-image: radial-gradient(rgba(0,0,0,0.03) 1px, transparent 1px), radial-gradient(rgba(0,0,0,0.02) 1px, transparent 1px); background-size: 50px 50px, 20px 20px; background-position: 0 0, 10px 10px; pointer-events: none; z-index: 0; animation: drift 60s linear infinite; }
    @keyframes drift { from { background-position: 0 0, 10px 10px; } to { background-position: 1000px 500px, 1010px 510px; } }
 
    .container-box { z-index: 10; width: 100%; max-width: 1200px; margin: 0 auto; animation: popIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) both; }
    @keyframes popIn { from { opacity: 0; transform: translateY(30px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
 
    .controls-row { display: flex; gap: 15px; margin-bottom: 30px; flex-wrap: wrap; justify-content: center; }
    .controls-row .form-control { flex: 1; min-width: 250px; background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(0, 0, 0, 0.1); border-radius: 12px; padding: 12px 16px; font-weight: 600; color: var(--text-main); font-family: var(--font-tech); transition: all 0.3s; }
    .controls-row .form-control:focus { outline: none; border-color: rgba(0, 119, 255, 0.5); background: rgba(255, 255, 255, 0.9); box-shadow: 0 0 15px rgba(0, 119, 255, 0.1); }
    .btn-download { background: linear-gradient(135deg, rgba(0, 170, 136, 0.9), rgba(0, 204, 153, 0.9)); color: white; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 700; cursor: pointer; transition: all 0.3s; box-shadow: 0 0 10px rgba(0, 170, 136, 0.2); }
    .btn-download:hover { transform: translateY(-2px); box-shadow: 0 0 15px rgba(0, 170, 136, 0.4); }
 
    .podium-row { display: flex; gap: 20px; margin-bottom: 40px; align-items: flex-end; justify-content: center; flex-wrap: wrap; }
    .podium-card { background: var(--glass-bg); backdrop-filter: blur(24px); border: 1px solid var(--glass-border); border-radius: 20px; padding: 30px; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.05); min-width: 250px; flex: 1; transition: all 0.3s; }
    .podium-card:hover { transform: translateY(-10px); box-shadow: 0 30px 60px rgba(0,0,0,0.1); }
    
    .podium-card h4 { font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; opacity: 0.8; }
    .podium-card h5 { font-size: 22px; font-weight: 800; margin-bottom: 5px; }
    .podium-card p { font-size: 14px; font-weight: 600; opacity: 0.8; margin-bottom: 10px; }
    .podium-card h3 { font-size: 30px; font-weight: 800; }
 
    .rank1 { background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); color: #333; transform: scale(1.05); }
    .rank1:hover { transform: translateY(-10px) scale(1.05); }
    .rank1 h4, .rank1 p { color: #555; }
    
    .rank2 { background: linear-gradient(135deg, #E8E8E8 0%, #B0B0B0 100%); color: #333; }
    .rank3 { background: linear-gradient(135deg, #CD7F32 0%, #A0522D 100%); color: white; }
    .rank3 p, .rank3 h4 { color: rgba(255,255,255,0.8); }
 
    .card { background: var(--glass-bg); backdrop-filter: blur(24px); border: 1px solid var(--glass-border); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.04); }
    .table { width: 100%; border-collapse: collapse; text-align: center; }
    .table thead th { background: rgba(0, 119, 255, 0.05); color: var(--neon-blue); font-weight: 700; padding: 18px 20px; text-transform: uppercase; font-size: 13px; border-bottom: 2px solid rgba(0, 119, 255, 0.1); }
    .table tbody tr { border-bottom: 1px solid rgba(0, 0, 0, 0.04); transition: all 0.3s; }
    .table tbody td { padding: 16px 20px; font-size: 15px; font-weight: 600; }
    .table tbody tr:hover { background: rgba(255, 255, 255, 0.8); box-shadow: 0 4px 15px rgba(0, 119, 255, 0.05); transform: scale(1.005); }
 
    .title-group { text-align: center; margin-bottom: 30px; }
    .title-group h2 { font-size: 36px; font-weight: 800; color: transparent; background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple)); -webkit-background-clip: text; background-clip: text; display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 5px; }
    .btn-return { position: absolute; top: 20px; left: 20px; background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(0, 0, 0, 0.1); padding: 10px 18px; border-radius: 12px; color: var(--text-main); font-family: var(--font-tech); font-weight: 700; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); transition: all 0.3s; z-index: 1000; }
    .btn-return:hover { background: rgba(255, 255, 255, 0.9); color: var(--neon-blue); transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0, 119, 255, 0.15); text-decoration: none; }
  </style>
</head>
<body>
  <a href="dashboard.php" class="btn-return"><i class="fa fa-arrow-left"></i> Return</a>
 
  <div class="bg-elements"><div class="orb orb-1"></div><div class="orb orb-2"></div><div class="orb orb-3"></div></div>
 
  <div class="container-box">
    <div class="title-group">
      <h2><i class="fa fa-fire-flame-curved"></i> Top Athletes Matrix</h2>
      <p style="color:var(--text-muted); font-weight:600; text-transform:uppercase; letter-spacing:1px;">Global Performance Ratings</p>
    </div>
 
    <div class="controls-row">
      <input type="text" id="search" class="form-control" placeholder="Query athlete...">
      <select id="deptFilter" class="form-control">
        <option value="">All Sectors</option>
        <?php foreach($departments as $dept) { echo "<option value='$dept'>$dept</option>"; } ?>
      </select>
      <button onclick="exportCSV()" class="btn-download"><i class="fa fa-download"></i> Extract Data</button>
    </div>
 
    <!-- TOP 3 PODIUM -->
    <div class="podium-row">
      <?php for($i=0; $i<3; $i++){
        if(isset($data[$i])){
          $class = ($i==0) ? "rank1" : (($i==1) ? "rank2" : "rank3");
          $medal = ($i==0) ? "🥇" : (($i==1) ? "🥈" : "🥉");
      ?>
      <div class="podium-card <?php echo $class; ?>">
        <h4>Tier <?php echo $i+1; ?> <?php echo $medal; ?></h4>
        <h5><?php echo htmlspecialchars($data[$i]['name']); ?></h5>
        <p><?php echo htmlspecialchars($data[$i]['department']); ?></p>
        <h3><?php echo $data[$i]['points']; ?> pts</h3>
      </div>
      <?php }} ?>
    </div>
 
    <div class="card">
      <table class="table" id="rankingTable">
        <thead>
          <tr><th>Rank</th><th>Designation</th><th>Sector</th><th>Score Aggregate</th></tr>
        </thead>
        <tbody id="athleteTable">
          <?php $rank=1; foreach($data as $row){ ?>
          <tr>
            <td style="color:var(--text-muted);">#<?php echo $rank++; ?></td>
            <td style="color:var(--text-main); font-weight:700;"><?php echo htmlspecialchars($row['name']); ?></td>
            <td class="dept"><?php echo htmlspecialchars($row['department']); ?></td>
            <td style="color:var(--neon-blue); font-weight:800; font-size:16px;"><?php echo $row['points']; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
 
  <script>
    document.getElementById("search").addEventListener("keyup", function(){
      let value=this.value.toLowerCase();
      document.querySelectorAll("#athleteTable tr").forEach(row => {
        row.style.display=row.innerText.toLowerCase().includes(value) ? "" : "none";
      });
    });
 
    document.getElementById("deptFilter").addEventListener("change", function(){
      let dept=this.value;
      document.querySelectorAll("#athleteTable tr").forEach(row => {
        let d=row.querySelector(".dept").innerText;
        row.style.display=(dept=="" || d==dept) ? "" : "none";
      });
    });
 
    function exportCSV(){
      let csv=[];
      document.querySelectorAll("#rankingTable tr").forEach(row => {
        let cols=row.querySelectorAll("td,th");
        let data=[];
        cols.forEach(col => data.push(col.innerText));
        csv.push(data.join(","));
      });
      let csvFile=new Blob([csv.join("\n")],{type:"text/csv"});
      let link=document.createElement("a");
      link.download="athlete_matrix.csv";
      link.href=window.URL.createObjectURL(csvFile);
      link.click();
    }
  </script>
</body>
</html>