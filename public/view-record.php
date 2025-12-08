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
<h2>My Medical Records</h2>
<?php foreach($records as $r): ?>
  <div style="border:1px solid #ddd; padding:8px; margin-bottom:8px;">
    <strong>Doctor:</strong> <?= htmlspecialchars($r['doctor_name']) ?><br>
    <strong>Date:</strong> <?= $r['visit_date'] ?><br>
    <strong>Diagnosis:</strong> <?= nl2br(htmlspecialchars($r['diagnosis'])) ?><br>
    <strong>Prescription:</strong> <?= nl2br(htmlspecialchars($r['prescription'])) ?><br>
    <?php if(!empty($r['notes'])): ?>
      <strong>Notes:</strong> <?= nl2br(htmlspecialchars($r['notes'])) ?>
    <?php endif; ?>
  </div>
<?php endforeach; ?>
<?php include '../includes/footer.php'; ?>