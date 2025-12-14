<?php
require_once '../includes/db-connect.php'; 
require_once '../includes/auth.php'; 
requireRole('doctor');
include '../includes/header.php';

// Load appointments for this doctor that are checked-in
$appts = $pdo->prepare("SELECT a.appointment_id, u.name AS patient_name
  FROM appointments a 
  JOIN user u ON u.user_id=a.patient_id
  WHERE a.doctor_id=? AND a.status='checked-in'
  ORDER BY a.appointment_date DESC, a.appointment_time DESC");
$appts->execute([$_SESSION['user_id']]); 
$appts = $appts->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $aid = (int)($_POST['appointment_id'] ?? 0);
  $diag = trim($_POST['diagnosis'] ?? '');
  $pres = trim($_POST['prescription'] ?? '');
  $notes = trim($_POST['notes'] ?? '');

  $check = $pdo->prepare("SELECT patient_id FROM appointments WHERE appointment_id=? AND doctor_id=?");
  $check->execute([$aid, $_SESSION['user_id']]);
  if ($row = $check->fetch()) {
    $stmt = $pdo->prepare("INSERT INTO records (appointment_id, patient_id, doctor_id, visit_date, diagnosis, prescription, notes, created_at)
                           VALUES (?,?,?,?,?,?,?, NOW())");
    $stmt->execute([$aid, $row['patient_id'], $_SESSION['user_id'], date('Y-m-d'), $diag, $pres, $notes]);
    $pdo->prepare("UPDATE appointments SET status='completed' WHERE appointment_id=?")->execute([$aid]);
    $msg = "Record saved successfully!";
  } else { $error = "Invalid appointment"; }
}
?>
<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/updaterecord.css">
<div class="record-form">

<h2>Add Health Record</h2>
<form method="post">
  <label>Appointment</label><br>
  <select name="appointment_id" required>
    <option value="">Select Appointment</option>
     <?php if(!empty($appts)): ?>

    <?php foreach($appts as $a): ?>
      <option value="<?= $a['appointment_id'] ?>"><?= htmlspecialchars($a['patient_name']) ?> (<?= $a['appointment_id'] ?>)
    </option>
    <?php endforeach; ?>
     <?php endif; ?>

  </select><br>
  <label>Diagnosis</label><br><textarea name="diagnosis" required></textarea><br>
  <label>Prescription</label><br><textarea name="prescription" required></textarea><br>
  <label>Notes</label><br><textarea name="notes"></textarea><br><br>
  <button type="submit">Save Record</button>
</form>
<?php if(isset($msg)) echo "<p style='color:green;'>$msg</p>"; ?>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</div>
 

