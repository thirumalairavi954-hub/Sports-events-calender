<?php
session_start();
include "config.php";

$user=$_SESSION['user'];
$event_id=$_GET['event'];

$event=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM sports_events WHERE event_id='$event_id'
"));

$event_name=$event['event_name'];
$date=$event['event_date'];
?>

<!DOCTYPE html>

<html>

<head>

<title>Participation Certificate</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  font-family: 'Georgia', serif;
  min-height: 100vh;
  padding: 30px 20px;
}

.certificate {
  width: 100%;
  max-width: 900px;
  margin: auto;
  padding: 70px;
  border: 8px solid;
  border-image: linear-gradient(135deg, #FFD700 0%, #FFA500 50%, #FFD700 100%) 1;
  background: linear-gradient(135deg, #ffffff 0%, #f9f9f9 100%);
  text-align: center;
  box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4),
              inset 0 0 0 1px rgba(255, 215, 0, 0.3);
  position: relative;
  animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Ornamental corners */
.certificate::before,
.certificate::after {
  content: '';
  position: absolute;
  width: 30px;
  height: 30px;
  border: 3px solid #FFD700;
}

.certificate::before {
  top: 15px;
  left: 15px;
  border-right: none;
  border-bottom: none;
}

.certificate::after {
  bottom: 15px;
  right: 15px;
  border-left: none;
  border-top: none;
}

.title {
  font-size: 48px;
  font-weight: 900;
  margin-bottom: 20px;
  background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  letter-spacing: 2px;
  text-transform: uppercase;
}

.subtitle {
  font-size: 22px;
  margin-bottom: 30px;
  color: #555;
  font-weight: 500;
  letter-spacing: 0.5px;
}

.name {
  font-size: 52px;
  font-weight: 900;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin: 30px 0;
  letter-spacing: 1px;
  text-transform: uppercase;
}

.event {
  font-size: 22px;
  margin-top: 20px;
  color: #555;
  font-weight: 500;
  line-height: 1.8;
}

.event b {
  font-size: 28px;
  background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  font-weight: 900;
  display: block;
  margin-top: 10px;
}

.date {
  margin-top: 25px;
  font-size: 18px;
  color: #764ba2;
  font-weight: 600;
  letter-spacing: 1px;
}

.sign {
  margin-top: 90px;
  display: flex;
  justify-content: space-between;
  padding: 0 100px;
  position: relative;
  z-index: 1;
}

.sign div {
  text-align: center;
  font-size: 16px;
  color: #555;
  width: 40%;
}

.sign div::before {
  content: '';
  display: block;
  width: 100%;
  height: 2px;
  background: linear-gradient(90deg, transparent 0%, #FFD700 50%, transparent 100%);
  margin-bottom: 10px;
}

.print-btn {
  margin-top: 40px;
}

.download-options {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 10px;
}

.download-options button {
  flex: 1;
  min-width: 160px;
}

.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
  color: white !important;
  border: none !important;
  padding: 14px 40px !important;
  font-size: 16px !important;
  font-weight: 700 !important;
  letter-spacing: 1px !important;
  text-transform: uppercase !important;
  border-radius: 10px !important;
  box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3) !important;
  transition: all 0.3s ease !important;
  margin: 10px;
  cursor: pointer;
}

.btn-primary:hover {
  transform: translateY(-3px) !important;
  box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4) !important;
  background: linear-gradient(135deg, #764ba2 0%, #667eea 100%) !important;
  color: white !important;
}

.download-options button:nth-child(2) {
  background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
  box-shadow: 0 10px 30px rgba(17, 153, 142, 0.3) !important;
}

.download-options button:nth-child(2):hover {
  background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%) !important;
  box-shadow: 0 15px 40px rgba(17, 153, 142, 0.4) !important;
}

.download-options button:nth-child(3) {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
  box-shadow: 0 10px 30px rgba(245, 87, 108, 0.3) !important;
}

.download-options button:nth-child(3):hover {
  background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%) !important;
  box-shadow: 0 15px 40px rgba(245, 87, 108, 0.4) !important;
}

.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
  color: white !important;
  border: none !important;
  padding: 14px 40px !important;
  font-size: 16px !important;
  font-weight: 700 !important;
  letter-spacing: 1px !important;
  text-transform: uppercase !important;
  border-radius: 10px !important;
  box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3) !important;
  transition: all 0.3s ease !important;
}

.btn-primary:hover {
  transform: translateY(-3px) !important;
  box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4) !important;
  background: linear-gradient(135deg, #764ba2 0%, #667eea 100%) !important;
}

/* Print styles */
@media print {
  body {
    background: white !important;
    padding: 0 !important;
  }

  .certificate {
    margin: 0 !important;
    box-shadow: none !important;
    page-break-after: avoid;
    border-width: 5px !important;
  }

  .print-btn {
    display: none !important;
  }

  .download-options {
    display: none !important;
  }

  button {
    display: none !important;
  }

  .btn-primary {
    display: none !important;
  }
}

/* Responsive Design */
@media (max-width: 768px) {
  .certificate {
    padding: 50px 30px;
    border-width: 5px;
  }

  .title {
    font-size: 34px;
  }

  .subtitle {
    font-size: 18px;
  }

  .name {
    font-size: 36px;
  }

  .event {
    font-size: 18px;
  }

  .event b {
    font-size: 24px;
  }

  .sign {
    margin-top: 60px;
    padding: 0 40px;
  }

  .sign div {
    font-size: 14px;
  }

  .btn-primary {
    padding: 12px 30px !important;
    font-size: 14px !important;
  }
}

@media (max-width: 480px) {
  .certificate {
    padding: 30px 20px;
    border-width: 4px;
  }

  .title {
    font-size: 24px;
  }

  .subtitle {
    font-size: 16px;
  }

  .name {
    font-size: 28px;
    margin: 20px 0;
  }

  .event {
    font-size: 16px;
  }

  .event b {
    font-size: 20px;
  }

  .date {
    font-size: 16px;
    margin-top: 20px;
  }

  .sign {
    margin-top: 50px;
    padding: 0 20px;
  }

  .sign div {
    font-size: 12px;
  }

  .btn-primary {
    padding: 10px 20px !important;
    font-size: 12px !important;
  }
}

</style>

</head>

<body>

<div class="certificate">

<div class="title">Certificate of Participation</div>

<div class="subtitle">
This certificate is proudly presented to
</div>

<div class="name">
<?php echo $user; ?>
</div>

<div class="event">
for successfully participating in
<br><b><?php echo $event_name; ?></b>
</div>

<div class="date">
Event Date : <?php echo $date; ?>
</div>

<div class="sign">

<div>
____________________<br>
Sports Coordinator
</div>

<div>
____________________<br>
Principal
</div>

</div>

<div class="print-btn">

<div class="download-options">
  <button onclick="downloadCertificatePDF()" class="btn btn-primary">📥 Download as PDF</button>
  <button onclick="window.print()" class="btn btn-primary">🖨️ Print Certificate</button>
  <button onclick="downloadCertificateImage()" class="btn btn-primary">🖼️ Download as Image</button>
</div>

</div>

</div>

</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>

// Download as Image
function downloadCertificateImage() {
  const certificateElement = document.querySelector('.certificate');
  html2canvas(certificateElement, {
    scale: 2,
    useCORS: true,
    logging: false,
    backgroundColor: '#ffffff'
  }).then(canvas => {
    const link = document.createElement('a');
    link.href = canvas.toDataURL('image/png');
    link.download = 'Certificate_' + new Date().getTime() + '.png';
    link.click();
  });
}

// Download as PDF
function downloadCertificatePDF() {
  const certificateElement = document.querySelector('.certificate');
  html2canvas(certificateElement, {
    scale: 2,
    useCORS: true,
    logging: false,
    backgroundColor: '#ffffff'
  }).then(canvas => {
    const imgData = canvas.toDataURL('image/png');
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF({
      orientation: 'landscape',
      unit: 'mm',
      format: 'a4'
    });
    
    const imgWidth = 297;
    const imgHeight = (canvas.height * imgWidth) / canvas.width;
    pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
    pdf.save('Certificate_' + new Date().getTime() + '.pdf');
  });
}

</script>

</html>
