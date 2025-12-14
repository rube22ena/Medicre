<?php
require_once '../includes/db-connect.php'; 
require_once '../includes/auth.php'; 
requireRole('patient');
include '../includes/header.php';

$stmt = $pdo->prepare("SELECT r.*, d.name AS doctor_name
  FROM records r 
  JOIN user d ON d.user_id=r.doctor_id
  WHERE r.patient_id=? ORDER BY r.created_at DESC");
$stmt->execute([$_SESSION['user_id']]); 
$records = $stmt->fetchAll();
?>
<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/view-record.css">
<h2>My Medical Records</h2>
<?php foreach($records as $r): ?>
 <div class="record-card">
<p><strong>Doctor:</strong> <?= htmlspecialchars($r['doctor_name']) ?></p>
    <p><strong>Date:</strong> <?= $r['visit_date'] ?></p>
    <p><strong>Diagnosis:</strong> <?= nl2br(htmlspecialchars($r['diagnosis'])) ?></p>
    <p><strong>Prescription:</strong> <?= nl2br(htmlspecialchars($r['prescription'])) ?></p>
    <?php if(!empty($r['notes'])): ?>
      <div class="notes">
        <strong>Notes:</strong> <?= nl2br(htmlspecialchars($r['notes'])) ?>
      </div>
    <?php endif; ?>

  </div>
<?php endforeach; ?> 

