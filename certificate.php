<?php
include "config.php";

$id = $_GET['id'];

$query = mysqli_query($conn,"
SELECT 
sports_events.event_name,
p1.name AS first_name
FROM results
JOIN sports_events ON sports_events.event_id = results.event_id
LEFT JOIN participants p1 ON p1.participant_id = results.first_place
WHERE results.result_id='$id'
");

$row = mysqli_fetch_assoc($query);

$name = $row['first_name'];
$event = $row['event_name'];

$cert_no = "CERT-" . $id . "-" . date("Y");
?>

<!DOCTYPE html>
<html>

<head>

<title>Sports Certificate</title>

<style>

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  font-family: 'Georgia', 'Times New Roman', serif;
  min-height: 100vh;
  padding: 30px 20px;
}

.certificate {
  width: 100%;
  max-width: 1100px;
  margin: auto;
  padding: 80px;
  background: linear-gradient(135deg, #ffffff 0%, #f9f9f9 100%);
  border: 8px solid;
  border-image: linear-gradient(135deg, #FFD700 0%, #FFA500 50%, #FFD700 100%) 1;
  box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4),
              inset 0 0 0 1px rgba(255, 215, 0, 0.3);
  position: relative;
  text-align: center;
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

.watermark {
  position: absolute;
  top: 35%;
  left: 50%;
  transform: translateX(-50%);
  opacity: 0.06;
  width: 500px;
  z-index: 0;
}

.certno {
  position: absolute;
  top: 30px;
  right: 50px;
  font-size: 15px;
  font-weight: 600;
  color: #667eea;
  letter-spacing: 1px;
  text-transform: uppercase;
}

.title {
  margin-top: 30px;
  position: relative;
  z-index: 1;
}

.title h1 {
  font-size: 52px;
  letter-spacing: 4px;
  margin-bottom: 8px;
  background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  font-weight: 900;
  text-transform: uppercase;
}

.title h2 {
  font-size: 32px;
  color: #667eea;
  font-weight: 700;
  letter-spacing: 2px;
  margin-top: 10px;
}

.content {
  margin-top: 60px;
  line-height: 2;
  position: relative;
  z-index: 1;
}

.content p {
  font-size: 20px;
  color: #555;
  font-weight: 500;
  letter-spacing: 0.5px;
}

.name {
  font-size: 56px;
  font-weight: 900;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin: 30px 0 20px 0;
  letter-spacing: 1px;
  text-transform: uppercase;
}

.position {
  font-size: 38px;
  background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  font-weight: 900;
  margin: 20px 0;
  letter-spacing: 1px;
}

.event {
  font-size: 38px;
  background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  font-weight: 900;
  margin-top: 15px;
  margin-bottom: 30px;
  letter-spacing: 1px;
}

.date {
  margin-top: 25px;
  font-size: 18px;
  color: #764ba2;
  font-weight: 600;
  letter-spacing: 1px;
}

.sign {
  margin-top: 100px;
  display: flex;
  justify-content: space-between;
  padding: 0 120px;
  position: relative;
  z-index: 1;
}

.sign div {
  font-size: 16px;
  width: 40%;
  text-align: center;
  color: #555;
}

.sign div::before {
  content: '';
  display: block;
  width: 100%;
  height: 2px;
  background: linear-gradient(90deg, transparent 0%, #FFD700 50%, transparent 100%);
  margin-bottom: 10px;
}

.print {
  margin-top: 50px;
  position: relative;
  z-index: 1;
}

button {
  padding: 14px 40px;
  font-size: 18px;
  background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  color: white;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 700;
  letter-spacing: 1px;
  text-transform: uppercase;
  transition: all 0.3s ease;
  box-shadow: 0 10px 30px rgba(17, 153, 142, 0.3);
  margin: 10px;
}

button:hover {
  transform: translateY(-3px);
  box-shadow: 0 15px 40px rgba(17, 153, 142, 0.4);
  background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%);
}

button:active {
  transform: translateY(-1px);
}

.download-options {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 10px;
}

.download-options button {
  flex: 1;
  min-width: 180px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.download-options button:hover {
  background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
  box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
}

.download-options button:nth-child(2) {
  background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  box-shadow: 0 10px 30px rgba(17, 153, 142, 0.3);
}

.download-options button:nth-child(2):hover {
  background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%);
  box-shadow: 0 15px 40px rgba(17, 153, 142, 0.4);
}

.download-options button:nth-child(3) {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  box-shadow: 0 10px 30px rgba(245, 87, 108, 0.3);
}

.download-options button:nth-child(3):hover {
  background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
  box-shadow: 0 15px 40px rgba(245, 87, 108, 0.4);
}

/* Print styles */
@media print {
  body {
    background: white !important;
    padding: 0 !important;
  }

  .certificate {
    margin: 0 !important;
    padding: 60px !important;
    box-shadow: none !important;
    page-break-after: avoid;
    border-width: 5px !important;
  }

  .print {
    display: none !important;
  }

  .download-options {
    display: none !important;
  }

  button {
    display: none !important;
  }

  .certno {
    display: none !important;
  }
}

/* Responsive Design */
@media (max-width: 768px) {
  .certificate {
    padding: 50px 30px;
    border-width: 5px;
  }

  .title h1 {
    font-size: 36px;
    letter-spacing: 2px;
  }

  .title h2 {
    font-size: 22px;
    letter-spacing: 1px;
  }

  .content {
    margin-top: 40px;
  }

  .content p {
    font-size: 16px;
  }

  .name {
    font-size: 40px;
    margin: 20px 0 15px 0;
  }

  .position {
    font-size: 28px;
  }

  .event {
    font-size: 28px;
  }

  .sign {
    margin-top: 60px;
    padding: 0 40px;
  }

  .sign div {
    font-size: 14px;
  }

  button {
    padding: 12px 30px;
    font-size: 16px;
  }
}

@media (max-width: 480px) {
  .certificate {
    padding: 30px 20px;
    border-width: 4px;
  }

  .certno {
    top: 15px;
    right: 20px;
    font-size: 12px;
  }

  .title h1 {
    font-size: 28px;
    letter-spacing: 1px;
  }

  .title h2 {
    font-size: 18px;
  }

  .content p {
    font-size: 14px;
  }

  .name {
    font-size: 32px;
    margin: 15px 0 10px 0;
  }

  .position {
    font-size: 22px;
  }

  .event {
    font-size: 22px;
  }

  .sign {
    margin-top: 50px;
    padding: 0 20px;
  }

  .sign div {
    font-size: 12px;
  }

  button {
    padding: 10px 20px;
    font-size: 14px;
  }
}

</style>

</head>

<body>

<div class="certificate">

<img src="logo.png" class="watermark">

<div class="certno">
Certificate No: <?php echo $cert_no; ?>
</div>

<div class="title">

<h1>ST. XAVIER'S COLLEGE</h1>
<h2>SPORTS MEET CERTIFICATE</h2>

</div>

<div class="content">

<p>This certificate is proudly presented to</p>

<div class="name"><?php echo $name; ?></div>

<p>For securing</p>

<div class="position">🥇 FIRST PLACE</div>

<p>In the event</p>

<div class="event"><?php echo $event; ?></div>

<div class="date">
Date : <?php echo date("d-m-Y"); ?>
</div>

</div>

<div class="sign">

<div>
_______________________  
<br>
Sports Coordinator
</div>

<div>
_______________________  
<br>
Principal
</div>

</div>

<div class="print">

<div class="download-options">
  <button onclick="downloadCertificatePDF()"><i>📥</i> Download as PDF</button>
  <button onclick="window.print()"><i>🖨️</i> Print Certificate</button>
  <button onclick="downloadCertificateImage()"><i>🖼️</i> Download as Image</button>
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