<?php
include "config.php";
$query = mysqli_query($conn, "SELECT es.schedule_id, se.event_name, es.event_date, es.event_time, es.location FROM event_schedule es JOIN sports_events se ON se.event_id = es.event_id ORDER BY es.event_date, es.event_time");
$events = [];
while($row = mysqli_fetch_assoc($query)) {
    $events[] = [
        "title" => $row['event_name'],
        "start" => $row['event_date'] . "T" . $row['event_time'],
        "extendedProps" => ["location" => $row['location'] ?? ''],
        "color" => "#0077ff"
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Event Protocol Calendar - Anti-Gravity Engine</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css">
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
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
 
    .container-box { z-index: 10; width: 100%; max-width: 1200px; margin: 0 auto; animation: popIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) both; }
    @keyframes popIn { from { opacity: 0; transform: translateY(30px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
 
    .title-group { text-align: center; margin-bottom: 30px; }
    .title-group h2 { font-size: 36px; font-weight: 800; color: transparent; background: linear-gradient(90deg, var(--neon-blue), var(--neon-purple)); -webkit-background-clip: text; background-clip: text; display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 5px; }
 
    #calendar { background: var(--glass-bg); backdrop-filter: blur(24px); border: 1px solid var(--glass-border); border-radius: 20px; padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.04); }
    .fc { font-family: var(--font-tech); color: var(--text-main); }
    .fc .fc-button-primary { background: linear-gradient(135deg, rgba(0, 119, 255, 0.9), rgba(136, 0, 255, 0.9)); border: none; box-shadow: 0 4px 10px rgba(0, 119, 255, 0.2); transition: all 0.3s; font-weight: 700; border-radius: 8px; }
    .fc .fc-button-primary:hover, .fc .fc-button-primary:focus, .fc .fc-button-primary.fc-button-active { background: linear-gradient(135deg, rgba(136, 0, 255, 0.9), rgba(0, 119, 255, 0.9)); box-shadow: 0 6px 15px rgba(0, 119, 255, 0.3); transform: translateY(-1px); }
    .fc .fc-daygrid-day:hover { background-color: rgba(255, 255, 255, 0.4); }
    .fc .fc-col-header-cell { background: rgba(0, 119, 255, 0.05); color: var(--neon-blue); font-weight: 700; padding: 12px 0; border: none; border-bottom: 2px solid rgba(0, 119, 255, 0.1); }
    .fc .fc-event { border-radius: 6px; border: none; overflow: hidden; transition: all 0.2s; cursor: pointer; box-shadow: 0 2px 5px rgba(0, 119, 255, 0.3); padding: 2px 4px; font-weight: 600; font-size: 11px; }
    .fc .fc-event:hover { transform: scale(1.05); box-shadow: 0 4px 10px rgba(0, 119, 255, 0.4); }
    .fc-theme-standard td, .fc-theme-standard th { border-color: rgba(0,0,0,0.05); }
    
    .modal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.4); backdrop-filter: blur(5px); justify-content: center; align-items: center; }
    .modal-content { background: var(--glass-bg); padding: 40px; border-radius: 20px; width: 90%; max-width: 450px; border: 1px solid var(--glass-border); box-shadow: 0 30px 60px rgba(0,0,0,0.1); position: relative; animation: popIn 0.3s ease-out; }
    .modal-content h3 { font-size: 24px; font-weight: 800; color: var(--neon-blue); margin-bottom: 15px; }
    .modal-content p { font-size: 14px; font-weight: 600; color: var(--text-main); margin-bottom: 10px; }
    .close { position: absolute; right: 20px; top: 15px; font-size: 28px; color: var(--text-muted); cursor: pointer; transition: color 0.3s; line-height: 1; }
    .close:hover { color: var(--neon-purple); }
    .btn-map { display: inline-flex; align-items: center; gap: 8px; margin-top: 20px; padding: 10px 18px; border-radius: 12px; background: linear-gradient(135deg, rgba(0, 119, 255, 0.9), rgba(136, 0, 255, 0.9)); color: white; text-decoration: none; font-weight: 700; font-size: 13px; box-shadow: 0 4px 15px rgba(0, 119, 255, 0.2); transition: all 0.3s; }
    .btn-map:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0, 119, 255, 0.3); color: white; }
    
    #toastContainer { position: fixed; top: 20px; right: 20px; z-index: 10000; }
    .toast { background: linear-gradient(135deg, rgba(0, 170, 136, 0.95), rgba(0, 204, 153, 0.95)); color: white; padding: 16px 24px; margin-top: 10px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0, 170, 136, 0.2); font-weight: 600; font-size: 14px; animation: slideInRight 0.4s ease-out; }
    @keyframes slideInRight { from { opacity: 0; transform: translateX(50px); } to { opacity: 1; transform: translateX(0); } }
    .btn-return { position: absolute; top: 20px; left: 20px; background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(0, 0, 0, 0.1); padding: 10px 18px; border-radius: 12px; color: var(--text-main); font-family: var(--font-tech); font-weight: 700; font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05); transition: all 0.3s; z-index: 1000; }
    .btn-return:hover { background: rgba(255, 255, 255, 0.9); color: var(--neon-blue); transform: translateY(-2px); box-shadow: 0 8px 15px rgba(0, 119, 255, 0.15); text-decoration: none; }

    @media (max-width: 768px) {
      .fc .fc-toolbar { flex-direction: column !important; gap: 12px; }
      .fc .fc-toolbar-chunk { text-align: center; }
      .fc .fc-toolbar-title { font-size: 1.3rem !important; }
      #calendar { padding: 15px; overflow-x: auto; }
      .btn-return { top: 10px; left: 10px; padding: 8px 12px; font-size: 12px; }
      body { padding: 50px 10px 20px; }
      .title-group h2 { font-size: 24px; }
    }
  </style>
</head>
<body>
  <a href="user_dashboard.php" class="btn-return"><i class="fa fa-arrow-left"></i> Return</a>
 
  <div class="bg-elements"><div class="orb orb-1"></div><div class="orb orb-2"></div><div class="orb orb-3"></div></div>
 
  <div class="container-box">
    <div class="title-group">
      <h2><i class="fa fa-calendar-alt"></i> Temporal Event Calendar</h2>
    </div>
    <div id="calendar"></div>
  </div>
 
  <!-- Modal -->
  <div id="eventModal" class="modal" style="display:flex; visibility:hidden; opacity:0;">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h3 id="modalTitle"></h3>
      <p><strong>Date & Time:</strong> <span id="modalDate" style="color:var(--text-muted);"></span></p>
      <p><strong>Coordinates:</strong> <span id="modalLocation" style="color:var(--text-muted);"></span></p>
      <a id="mapLink" href="#" target="_blank" class="btn-map"><i class="fa fa-map-marker-alt"></i> Trace Location</a>
    </div>
  </div>
 
  <div id="toastContainer"></div>
 
  <script>
    function showToast(message){
        let toast = document.createElement('div');
        toast.className = "toast";
        toast.innerHTML = `<i class="fa fa-bell"></i> ` + message;
        document.getElementById('toastContainer').appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    }
 
    document.addEventListener('DOMContentLoaded', function(){
        let events = <?php echo json_encode($events); ?>;
        let today = new Date();
        events.forEach(ev => {
            let eventDate = new Date(ev.start);
            let daysDiff = Math.ceil((eventDate - today) / (1000*60*60*24));
            if(daysDiff >=0 && daysDiff <=1) showToast("Imminent Event: " + ev.title);
        });
 
        let calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
            initialView: 'dayGridMonth',
            headerToolbar: { left:'prev,next today', center:'title', right:'dayGridMonth,timeGridWeek,timeGridDay' },
            events: events,
            eventClick: function(info){
                document.getElementById('modalTitle').innerText = info.event.title;
                document.getElementById('modalDate').innerText = info.event.start.toLocaleString();
                let loc = info.event.extendedProps.location;
                document.getElementById('modalLocation').innerText = loc || 'Unassigned';
                let ml = document.getElementById('mapLink');
                if(loc){ ml.href = 'https://www.google.com/maps?q=' + encodeURIComponent(loc); ml.style.display = 'inline-flex'; }
                else { ml.style.display = 'none'; }
                
                let m = document.getElementById('eventModal');
                m.style.visibility = 'visible'; m.style.opacity = '1';
            }
        });
        calendar.render();
 
        document.querySelector(".close").onclick = function(){ document.getElementById('eventModal').style.visibility="hidden"; document.getElementById('eventModal').style.opacity="0"; }
        window.onclick = function(e){ if(e.target==document.getElementById('eventModal')){ document.getElementById('eventModal').style.visibility="hidden"; document.getElementById('eventModal').style.opacity="0"; } }
    });
  </script>
</body>
</html>