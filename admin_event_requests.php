<?php
include "config.php";
 
$query=mysqli_query($conn,"
SELECT event_registrations.*,sports_events.event_name,users.name
FROM event_registrations
JOIN sports_events ON sports_events.event_id=event_registrations.event_id
JOIN users ON users.user_id=event_registrations.user_id
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Event Requests - Anti-Gravity Engine</title>
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
 
    .container-box { z-index: 10; width: 100%; max-width: 1000px; margin: 0 auto; animation: popIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) both; }
    @keyframes popIn { from { opacity: 0; transform: translateY(30px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
 
    .title-group { text-align: center; margin-bottom: 30px; }
    .title-group h2 { font-size: 36px; font-weight: 800; color: transparent; background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple)); -webkit-background-clip: text; background-clip: text; display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 5px; }
 
    .card { background: var(--glass-bg); backdrop-filter: blur(24px); border: 1px solid var(--glass-border); border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.04); }
    .table-container { width: 100%; overflow-x: auto; }
    .table { width: 100%; border-collapse: collapse; text-align: center; }
    .table thead th { background: rgba(0, 119, 255, 0.05); color: var(--neon-blue); font-weight: 700; padding: 18px 20px; text-transform: uppercase; font-size: 13px; border-bottom: 2px solid rgba(0, 119, 255, 0.1); white-space: nowrap; }
    .table tbody tr { border-bottom: 1px solid rgba(0, 0, 0, 0.04); transition: all 0.3s; }
    .table tbody td { padding: 16px 20px; font-size: 15px; font-weight: 600; vertical-align: middle; white-space: nowrap; }
    .table tbody tr:hover { background: rgba(255, 255, 255, 0.8); box-shadow: 0 4px 15px rgba(0, 119, 255, 0.05); }
    
    .status-badge { padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: 700; display: inline-block; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .status-pending { background: linear-gradient(135deg, rgba(255, 153, 0, 0.9), rgba(255, 102, 0, 0.9)); color: white; }
    .status-approved { background: linear-gradient(135deg, rgba(0, 170, 136, 0.9), rgba(0, 204, 153, 0.9)); color: white; }
    .status-rejected { background: linear-gradient(135deg, rgba(255, 0, 102, 0.9), rgba(204, 0, 82, 0.9)); color: white; }
 
    .btn-action { text-decoration: none; border-radius: 8px; font-size: 12px; font-weight: 700; padding: 8px 14px; display: inline-flex; align-items: center; gap: 5px; color: white; margin: 0 4px; transition: all 0.3s; box-shadow: 0 4px 10px rgba(0,0,0,0.1); text-transform: uppercase; }
    .btn-approve { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .btn-approve:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(17, 153, 142, 0.3); }
    .btn-reject { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
    .btn-reject:hover { transform: translateY(-2px); box-shadow: 0 8px 15px rgba(250, 112, 154, 0.3); }
    .btn-return { position: absolute; top: 20px; left: 20px; background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(0, 0, 0, 0.1); padding: 10px 18px; border-radius: 12px; color: var(--text-main); font-family: var(--font-tech); font-weight: 700; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); transition: all 0.3s; z-index: 1000; }
    .btn-return:hover { background: rgba(255, 255, 255, 0.9); color: var(--neon-blue); transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0, 119, 255, 0.15); text-decoration: none; }
  </style>
</head>
<body>
  <a href="dashboard.php" class="btn-return"><i class="fa fa-arrow-left"></i> Return</a>
 
  <div class="bg-elements"><div class="orb orb-1"></div><div class="orb orb-2"></div><div class="orb orb-3"></div></div>
 
  <div class="container-box">
    <div class="title-group">
      <h2><i class="fa fa-inbox"></i> Security Clearances</h2>
      <p style="color:var(--text-muted); font-weight:600; text-transform:uppercase; letter-spacing:1px;">Event Registration Requests</p>
    </div>
 
    <div class="card">
      <div class="table-container">
        <table class="table">
          <thead>
            <tr>
              <th style="text-align:left;">User Identity</th>
              <th>Target Event</th>
              <th>Clearance Status</th>
              <th>Protocol Override</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row=mysqli_fetch_assoc($query)) {
              $statusClass = '';
              if($row['status'] == 'pending') $statusClass = 'status-pending';
              else if($row['status'] == 'approved') $statusClass = 'status-approved';
              else if($row['status'] == 'rejected') $statusClass = 'status-rejected';
            ?>
            <tr>
              <td style="text-align:left; color:var(--text-main); font-weight:700;"><?php echo htmlspecialchars($row['name']); ?></td>
              <td style="color:var(--neon-blue);"><?php echo htmlspecialchars($row['event_name']); ?></td>
              <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo ucfirst($row['status']); ?></span></td>
              <td>
                <a href="approve_event.php?id=<?php echo $row['reg_id']; ?>" class="btn-action btn-approve"><i class="fa fa-check"></i> Grant</a>
                <a href="reject_event.php?id=<?php echo $row['reg_id']; ?>" onclick="return confirm('Deny this request?')" class="btn-action btn-reject"><i class="fa fa-times"></i> Deny</a>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
 
</body>
</html>