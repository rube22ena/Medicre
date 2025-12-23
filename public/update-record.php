<?php
require_once '../includes/db-connect.php'; 
require_once '../includes/auth.php'; 
requireRole('doctor');
include '../includes/header.php';

// Load appointments for this doctor that are checked-in
$appts = $pdo->prepare("
    SELECT a.appointments_id,
           p.name AS patient_name, p.gender, p.age, p.mobile, p.email, p.address
    FROM appointments a
    JOIN patient_details p ON p.appointments_id = a.appointments_id
    WHERE a.doctor_id=? AND a.status='checked-in'
    ORDER BY a.appointments_date DESC, a.appointments_time DESC
");
$appts->execute([$_SESSION['user_id']]); 
$appts = $appts->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aid  = (int)($_POST['appointments_id'] ?? 0);
    $diag = trim($_POST['diagnosis'] ?? '');
    $pres = trim($_POST['prescription'] ?? '');
    $notes= trim($_POST['notes'] ?? '');

    // Verify appointments belongs to this doctor
    $check = $pdo->prepare("SELECT patient_id FROM appointments WHERE appointments_id=? AND doctor_id=?");
    $check->execute([$aid, $_SESSION['user_id']]);
    if ($row = $check->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO records 
            (appointments_id, patient_id, doctor_id, visit_date, diagnosis, prescription, notes, created_at)
            VALUES (?,?,?,?,?,?,?, NOW())");
        $stmt->execute([$aid, $row['patient_id'], $_SESSION['user_id'], date('Y-m-d'), $diag, $pres, $notes]);

        $pdo->prepare("UPDATE appointments SET status='completed' WHERE appointments_id=?")->execute([$aid]);
        $msg = "Record saved successfully!";
    } else {
        $error = "Invalid appointments";
    }
}
?>
<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/updaterecord.css">

<div class="record-form">
  <h2>Add Health Record</h2>
  <form method="post">
    <label>appointments</label><br>
    <select name="appointments_id" required>
      <option value="">Select appointments</option>
      <?php if(!empty($appts)): ?>
        <?php foreach($appts as $a): ?>
          <option value="<?= $a['appointments_id'] ?>">
            <?= htmlspecialchars($a['patient_name']) ?> 
            (<?= htmlspecialchars($a['gender']) ?>, <?= htmlspecialchars($a['age']) ?> yrs, 
             <?= htmlspecialchars($a['mobile']) ?>)
          </option>
        <?php endforeach; ?>
      <?php endif; ?>
    </select><br>

    <label>Diagnosis</label><br>
    <textarea name="diagnosis" required></textarea><br>

    <label>Prescription</label><br>
    <textarea name="prescription" required></textarea><br>

    <label>Notes</label><br>
    <textarea name="notes"></textarea><br><br>

    <button type="submit">Save Record</button>
  </form>

  <?php if(isset($msg)) echo "<p style='color:green;'>$msg</p>"; ?>
  <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</div>