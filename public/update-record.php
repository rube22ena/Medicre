<?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole('doctor');
include '../includes/header.php';

$aid = (int)($_GET['appointment_id'] ?? 0);
$msg = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aid  = (int)($_POST['appointment_id'] ?? 0);
    $diag = trim($_POST['diagnosis'] ?? '');
    $pres = trim($_POST['prescription'] ?? '');
    $notes= trim($_POST['notes'] ?? '');

    $errors = [];

    // ✅ Validation
    if ($aid <= 0) $errors[] = "Invalid appointment.";
    if ($diag === '') $errors[] = "Diagnosis is required.";
    if ($pres === '') $errors[] = "Prescription is required.";
    if (strlen($diag) > 1000) $errors[] = "Diagnosis must be less than 1000 characters.";
    if (strlen($pres) > 1000) $errors[] = "Prescription must be less than 1000 characters.";
    if (strlen($notes) > 2000) $errors[] = "Notes must be less than 2000 characters.";

    if (!empty($errors)) {
        $error = implode(" ", $errors);
    } else {
        // ✅ Fetch patient_id correctly
        $check = $pdo->prepare("
            SELECT patient_id 
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
}
?>

<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/updaterecord.css">
<script src="../js/update-record.js"></script>
<div class="record-form">
  <h2>Add Health Record</h2>
  <?php if($msg): ?><p style="color:green;"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
  <?php if($error): ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>

  <form method="post">
    <input type="hidden" name="appointment_id" value="<?= $aid ?>">

    <label>Diagnosis</label><br>
    <textarea name="diagnosis" required></textarea><br><br>

    <label>Prescription</label><br>
    <textarea name="prescription" required></textarea><br><br>

    <label>Notes (Optional)</label><br>
    <textarea name="notes"></textarea><br><br>

    <button type="submit">Save Record</button>
  </form>
</div>
<?php include '../includes/footer.php'; ?>