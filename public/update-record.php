<?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole('doctor');
include '../includes/header.php';

$aid = (int)($_GET['appointment_id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aid  = (int)($_POST['appointment_id'] ?? 0);
    $diag = trim($_POST['diagnosis'] ?? '');
    $pres = trim($_POST['prescription'] ?? '');
    $notes= trim($_POST['notes'] ?? '');

    // ✅ Fetch patient_id correctly from patient_details
    $check = $pdo->prepare("
        SELECT p.patient_id 
        FROM appointments a
        JOIN patient_details p ON p.appointment_id = a.appointment_id
        WHERE a.appointment_id=? AND a.doctor_id=?");
    $check->execute([$aid, $_SESSION['user_id']]);

    if ($row = $check->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO records 
            (appointment_id, patient_id, doctor_id, visit_date, diagnosis, prescription, notes, created_at)
            VALUES (?,?,?,?,?,?,?, NOW())");
        $stmt->execute([$aid, $row['patient_id'], $_SESSION['user_id'], date('Y-m-d'), $diag, $pres, $notes]);

        // ✅ Mark appointment as completed
        $pdo->prepare("UPDATE appointments SET status='completed' WHERE appointment_id=?")->execute([$aid]);

        header("Location: doctor_dashboard.php?msg=record_saved");
        exit;
    } else {
        $error = "Invalid appointment or patient not found.";
    }
}
?>

<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/updaterecord.css">

<div class="record-form">
  <h2>Add Health Record</h2>
  <form method="post">
    <input type="hidden" name="appointment_id" value="<?= $aid ?>">

    <label>Diagnosis</label><br>
    <textarea name="diagnosis" required></textarea><br>

    <label>Prescription</label><br>
    <textarea name="prescription" required></textarea><br>

    <label>Notes</label><br>
    <textarea name="notes"></textarea><br><br>

    <button type="submit">Save Record</button>
  </form>

  <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</div>