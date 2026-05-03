<?php
include "config.php";
 
$query = mysqli_query($conn,"SELECT participants.*, sports_events.event_name 
FROM participants 
JOIN sports_events 
ON participants.event_id = sports_events.event_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Participants - Anti-Gravity Engine</title>
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
 
    .orb-1 {
      width: 45vw; height: 45vw;
      background: rgba(0, 119, 255, 0.15);
      top: -10%; left: -10%;
    }
 
    .orb-2 {
      width: 55vw; height: 55vw;
      background: rgba(136, 0, 255, 0.1);
      bottom: -15%; right: -10%;
      animation-delay: -5s;
    }
 
    .orb-3 {
      width: 35vw; height: 35vw;
      background: rgba(0, 170, 136, 0.12);
      top: 30%; left: 40%;
      animation-duration: 30s;
    }
 
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
      max-width: 1200px;
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
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      gap: 15px;
    }
 
    .title-group h3 {
      font-size: 28px;
      font-weight: 700;
      color: transparent;
      background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple));
      -webkit-background-clip: text;
      background-clip: text;
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 5px;
      text-shadow: 0 0 15px rgba(0, 119, 255, 0.1);
    }
 
    .title-group p {
      color: var(--text-muted);
      font-size: 14px;
      font-weight: 600;
    }
 
    /* ── Controls Area ── */
    .controls-wrapper {
      display: flex;
      gap: 15px;
      align-items: center;
      flex-wrap: wrap;
    }
 
    .search-wrapper {
      position: relative;
    }
 
    .search-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-muted);
      transition: color 0.3s;
    }
 
    .search-box {
      background: rgba(255, 255, 255, 0.7);
      border: 1px solid rgba(0, 0, 0, 0.1);
      border-radius: 12px;
      padding: 12px 16px 12px 42px;
      color: var(--text-main);
      font-family: var(--font-tech);
      font-size: 14px;
      font-weight: 600;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      width: 300px;
      box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
    }
 
    .search-box:focus {
      outline: none;
      border-color: rgba(0, 119, 255, 0.5);
      background: rgba(255, 255, 255, 0.9);
      box-shadow: 0 0 15px rgba(0, 119, 255, 0.1), inset 0 0 4px rgba(0, 119, 255, 0.05);
    }
 
    .search-box:focus + .search-icon {
      color: var(--neon-blue);
    }
    
    .search-box::placeholder {
      color: rgba(0,0,0,0.3);
      font-weight: 400;
    }
 
    /* ── Buttons ── */
    .btn {
      border-radius: 10px;
      padding: 10px 16px;
      border: none;
      font-family: var(--font-tech);
      font-size: 13px;
      font-weight: 700;
      letter-spacing: 1px;
      text-transform: uppercase;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      text-decoration: none;
      position: relative;
      overflow: hidden;
    }
 
    .btn-sm {
      padding: 7px 12px;
      font-size: 11px;
      border-radius: 8px;
    }
 
    .btn-success {
      background: linear-gradient(135deg, rgba(0, 170, 136, 0.9), rgba(0, 204, 153, 0.9));
      color: white;
      box-shadow: 0 0 10px rgba(0, 170, 136, 0.2);
      border: 1px solid rgba(255,255,255,0.2);
    }
    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 0 15px rgba(0, 170, 136, 0.4);
      color: white;
    }
 
    .btn-warning {
      background: linear-gradient(135deg, rgba(255, 153, 0, 0.9), rgba(255, 102, 0, 0.9));
      color: white;
      box-shadow: 0 0 10px rgba(255, 153, 0, 0.2);
      border: 1px solid rgba(255,255,255,0.2);
    }
    .btn-warning:hover {
      transform: translateY(-2px);
      box-shadow: 0 0 15px rgba(255, 153, 0, 0.4);
      color: white;
    }
 
    .btn-danger {
      background: linear-gradient(135deg, rgba(255, 0, 102, 0.9), rgba(204, 0, 82, 0.9));
      color: white;
      box-shadow: 0 0 10px rgba(255, 0, 102, 0.2);
      border: 1px solid rgba(255,255,255,0.2);
    }
    .btn-danger:hover {
      transform: translateY(-2px);
      box-shadow: 0 0 15px rgba(255, 0, 102, 0.4);
      color: white;
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
      text-align: left;
    }
 
    .table thead th {
      background: rgba(0, 119, 255, 0.05);
      color: var(--neon-blue);
      font-weight: 700;
      padding: 18px 20px;
      text-transform: uppercase;
      letter-spacing: 1px;
      font-size: 12px;
      border-bottom: 2px solid rgba(0, 119, 255, 0.1);
      white-space: nowrap;
    }
 
    .table tbody tr {
      border-bottom: 1px solid rgba(0, 0, 0, 0.04);
      transition: all 0.3s ease;
    }
 
    .table tbody td {
      padding: 16px 20px;
      color: var(--text-main);
      font-size: 14px;
      font-weight: 600;
      vertical-align: middle;
      white-space: nowrap;
    }
 
    .table tbody tr:last-child {
      border-bottom: none;
    }
 
    .table tbody tr:hover {
      background: rgba(255, 255, 255, 0.8);
      box-shadow: 0 4px 15px rgba(0, 119, 255, 0.05);
    }
 
    .actions-cell {
      display: flex;
      gap: 8px;
    }
 
    /* ── Responsive ── */
    @media (max-width: 900px) {
      .header-area {
        flex-direction: column;
        align-items: flex-start;
      }
      .search-box { width: 100%; }
      .controls-wrapper { width: 100%; }
      .card { padding: 25px; }
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
 
  <div class="container-box">
    <div class="card">
      
      <div class="header-area">
        <div class="title-group">
          <h3><i class="fa fa-users" style="color:var(--neon-blue);"></i> Participants List</h3>
          <p>Access registered participant metrics</p>
        </div>
        
        <div class="controls-wrapper">
          <a href="add_participant.php" class="btn btn-success">
            <i class="fa fa-user-plus"></i> Initialize Participant
          </a>
          
          <div class="search-wrapper">
            <input type="text" id="search" class="search-box" placeholder="Search parameters...">
            <i class="fa fa-search search-icon"></i>
          </div>
        </div>
      </div>
 
      <div class="table-container">
        <table class="table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name Identity</th>
              <th>Biological</th>
              <th>Comm Link</th>
              <th>Department</th>
              <th>Target Event</th>
              <th>Operations</th>
            </tr>
          </thead>
          <tbody id="participantTable">
            <?php while($row = mysqli_fetch_assoc($query)) { ?>
            <tr>
              <td><span style="color:var(--neon-blue);">#<?php echo $row['participant_id']; ?></span></td>
              <td><?php echo htmlspecialchars($row['name']); ?></td>
              <td><?php echo htmlspecialchars($row['gender']); ?></td>
              <td><?php echo htmlspecialchars($row['phone']); ?></td>
              <td><?php echo htmlspecialchars($row['department']); ?></td>
              <td><?php echo htmlspecialchars($row['event_name']); ?></td>
              
              <td class="actions-cell">
                  <a href="edit_participant.php?id=<?php echo $row['participant_id']; ?>" class="btn btn-warning btn-sm" title="Edit Profile">
                    <i class="fa fa-edit"></i> Modify
                  </a>
                  <a href="delete_participant.php?id=<?php echo $row['participant_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Erase participant matrix?')" title="Delete Profile">
                    <i class="fa fa-trash"></i> Erase
                  </a>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
 
    </div>
  </div>
 
  <script>
    document.getElementById("search").addEventListener("keyup", function(){
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll("#participantTable tr");
        rows.forEach(function(row){
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
    });
  </script>
 
</body>
</html>