<?php
session_start();
include "config.php";
$user=$_SESSION['user'];
$user_data=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM users WHERE name='$user'"));
$user_id=$user_data['user_id'];
 
$query=mysqli_query($conn,"
SELECT event_registrations.*,sports_events.event_name,sports_events.event_date
FROM event_registrations
JOIN sports_events ON sports_events.event_id=event_registrations.event_id
WHERE event_registrations.user_id='$user_id'
");
$total=mysqli_num_rows($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Events - Anti-Gravity Engine</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
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
    body::after { content: ''; position: fixed; inset: 0; background-image: radial-gradient(rgba(0,0,0,0.03) 1px, transparent 1px), radial-gradient(rgba(0,0,0,0.02) 1px, transparent 1px); background-size: 50px 50px, 20px 20px; background-position: 0 0, 10px 10px; pointer-events: none; z-index: 0; animation: drift 60s linear infinite; }
    @keyframes drift { from { background-position: 0 0, 10px 10px; } to { background-position: 1000px 500px, 1010px 510px; } }
 
    .container-box { z-index: 10; width: 100%; max-width: 1000px; margin: 0 auto; animation: popIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) both; }
    @keyframes popIn { from { opacity: 0; transform: translateY(30px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
 
    .card { background: var(--glass-bg); backdrop-filter: blur(24px); border: 1px solid var(--glass-border); border-radius: 20px; padding: 35px 40px; box-shadow: 0 20px 40px rgba(0,0,0,0.04), inset 0 1px 1px rgba(255,255,255,1), 0 0 20px rgba(0, 119, 255, 0.05); }
    
    .header-area { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-bottom: 20px; gap: 15px; }
    .title-group h3 { font-size: 28px; font-weight: 700; color: transparent; background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple)); -webkit-background-clip: text; background-clip: text; display: flex; align-items: center; gap: 12px; margin-bottom: 5px; text-shadow: 0 0 15px rgba(0, 119, 255, 0.1); }
    .title-group p { color: var(--text-muted); font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
 
    .alert { background: rgba(0, 119, 255, 0.1); border: 1px solid rgba(0, 119, 255, 0.2); color: var(--neon-blue); padding: 12px 20px; border-radius: 12px; margin-bottom: 20px; font-size: 14px; font-weight: 700; display: inline-block; box-shadow: 0 0 15px rgba(0, 119, 255, 0.1); }
    
    .table-container { width: 100%; overflow-x: auto; border-radius: 12px; box-shadow: inset 0 0 0 1px rgba(0,0,0,0.05); background: rgba(255, 255, 255, 0.4); margin-bottom: 25px; }
    .table { width: 100%; border-collapse: collapse; text-align: center; }
    .table thead th { background: rgba(0, 119, 255, 0.05); color: var(--neon-blue); font-weight: 700; padding: 18px 20px; text-transform: uppercase; font-size: 12px; border-bottom: 2px solid rgba(0, 119, 255, 0.1); white-space: nowrap; }
    .table tbody tr { border-bottom: 1px solid rgba(0, 0, 0, 0.04); transition: all 0.3s ease; }
    .table tbody td { padding: 16px 20px; color: var(--text-main); font-size: 14px; font-weight: 600; vertical-align: middle; white-space: nowrap; }
    .table tbody td:nth-child(2) { text-align: left; font-weight: 700; color: var(--neon-blue); }
    .table tbody tr:hover { background: rgba(255, 255, 255, 0.8); box-shadow: 0 4px 15px rgba(0, 119, 255, 0.05); transform: scale(1.005); }
    .table tbody tr:last-child { border-bottom: none; }
 
    .status-badge { padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; display: inline-block; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .status-pending { background: linear-gradient(135deg, rgba(255, 153, 0, 0.9), rgba(255, 102, 0, 0.9)); color: white; }
    .status-approved { background: linear-gradient(135deg, rgba(0, 170, 136, 0.9), rgba(0, 204, 153, 0.9)); color: white; }
    .status-rejected { background: linear-gradient(135deg, rgba(255, 0, 102, 0.9), rgba(204, 0, 82, 0.9)); color: white; }
 
    .btn { background: linear-gradient(135deg, rgba(0, 119, 255, 0.9), rgba(136, 0, 255, 0.9)); color: white; border: none; padding: 10px 18px; border-radius: 10px; font-family: var(--font-tech); font-weight: 700; font-size: 13px; text-transform: uppercase; cursor: pointer; transition: all 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(0, 119, 255, 0.2); }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0, 119, 255, 0.4); }
    .btn-success { background: linear-gradient(135deg, rgba(0, 170, 136, 0.9), rgba(0, 204, 153, 0.9)); }
    .btn-success:hover { box-shadow: 0 8px 15px rgba(0, 170, 136, 0.4); }
 
    .btn-return { position: absolute; top: 20px; left: 20px; background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(0, 0, 0, 0.1); padding: 10px 18px; border-radius: 12px; color: var(--text-main); font-family: var(--font-tech); font-weight: 700; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); transition: all 0.3s; z-index: 1000; }
    .btn-return:hover { background: rgba(255, 255, 255, 0.9); color: var(--neon-blue); transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0, 119, 255, 0.15); }
    
    .modal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(5px); justify-content: center; align-items: center; }
    .modal-content { background: var(--glass-bg); padding: 40px; border-radius: 20px; width: 90%; max-width: 500px; border: 1px solid var(--glass-border); box-shadow: 0 30px 60px rgba(0,0,0,0.1); position: relative; animation: popIn 0.3s ease-out; }
    .modal-content h3 { font-size: 24px; font-weight: 800; color: transparent; background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple)); -webkit-background-clip: text; background-clip: text; margin-bottom: 20px; }
    .modal-content ul { list-style: none; margin-bottom: 20px; }
    .modal-content ul li { font-size: 14px; font-weight: 600; color: var(--text-main); margin-bottom: 12px; padding-left: 25px; position: relative; line-height: 1.5; }
    .modal-content ul li::before { content: '\f058'; font-family: 'Font Awesome 5 Free'; font-weight: 900; position: absolute; left: 0; color: var(--neon-cyan); }
    .close { position: absolute; right: 20px; top: 15px; font-size: 28px; color: var(--text-muted); cursor: pointer; transition: color 0.3s; line-height: 1; }
    .close:hover { color: var(--neon-purple); }
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
          <h3><i class="fa fa-clipboard-check"></i> Registration Matrix</h3>
          <p>Active User Participations</p>
        </div>
        <div class="alert"><i class="fa fa-chart-line"></i> Total Deployments : <?php echo $total; ?></div>
      </div>
 
      <div class="table-container">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Target Event</th>
              <th>Temporal Date</th>
              <th>Clearance</th>
              <th>Artifact</th>
            </tr>
          </thead>
          <tbody>
            <?php $i=1; while($row=mysqli_fetch_assoc($query)) { 
              $status = $row['status'];
              $statusClass = '';
              if($status == 'Pending') $statusClass = 'status-pending';
              else if($status == 'Approved') $statusClass = 'status-approved';
              else $statusClass = 'status-rejected';
            ?>
            <tr>
              <td style="color:var(--text-muted);">#<?php echo $i++; ?></td>
              <td><?php echo htmlspecialchars($row['event_name']); ?></td>
              <td><?php echo $row['event_date']; ?></td>
              <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $status; ?></span></td>
              <td>
                <?php if($status=="Approved") { ?>
                  <a href='certificate1.php?event=<?php echo $row['event_id']; ?>' class='btn btn-success'><i class="fa fa-download"></i> Extract File</a>
                <?php } else { echo "-"; } ?>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      
      <button class="btn" onclick="openModal()"><i class="fa fa-shield-alt"></i> Access Protocol Rules</button>
    </div>
  </div>
 
  <!-- Modal -->
  <div id="rulesModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h3><i class="fa fa-info-circle"></i> Protocol Guidelines</h3>
      <ul>
        <li>Maximum instantiation allowed per unit is 3 events.</li>
        <li>Units must deploy to coordinates 30 minutes prior to sync.</li>
        <li>Physical Identification Cards are strictly mandatory.</li>
        <li>Comply with all assigned Referee node overriding instructions.</li>
        <li>Misconduct results in immediate network ejection.</li>
      </ul>
      <button class="btn" style="width:100%; justify-content:center; margin-top:10px;" onclick="closeModal()">Acknowledge</button>
    </div>
  </div>
 
  <script>
    function openModal() { document.getElementById('rulesModal').style.display = 'flex'; }
    function closeModal() { document.getElementById('rulesModal').style.display = 'none'; }
    window.onclick = function(e){ if(e.target==document.getElementById('rulesModal')){ closeModal(); } }
  </script>
</body>
</html>
