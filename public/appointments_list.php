<?php
require_once '../includes/db-connect.php'; 
require_once '../includes/auth.php';
include '../includes/header.php';

$role = $_SESSION['role'];

if ($role === 'receptionist') {
  // confirm or cancel
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['appointment_id'])) {
    $aid = (int)$_POST['appointment_id']; 
    $action = $_POST['action'];
    if (in_array($action, ['confirm','cancel'])) {
      $status = $action === 'confirm' ? 'confirmed' : 'cancel';
      $stmt = $pdo->prepare("UPDATE appointments SET status=? WHERE appointment_id=?");
      $stmt->execute([$status, $aid]);
    }
  }
  $appts = $pdo->query("SELECT a.*, p.name AS patient_name, d.name AS doctor_name
    FROM appointments a
    JOIN user p ON p.user_id=a.patient_id
    JOIN user d ON d.user_id=a.doctor_id
    ORDER BY a.appointment_date, a.appointment_time")->fetchAll();

} elseif ($role === 'doctor') {
  // mark checked-in
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_id'])) {
    $aid = (int)$_POST['complete_id'];
    $stmt = $pdo->prepare("UPDATE appointments SET status='checked-in' WHERE appointment_id=? AND doctor_id=?");
    $stmt->execute([$aid, $_SESSION['user_id']]);
  }
  $stmt = $pdo->prepare("SELECT a.*, p.name AS patient_name
    FROM appointments a JOIN user p ON p.user_id=a.patient_id
    WHERE a.doctor_id=? ORDER BY a.appointment_date, a.appointment_time");
  $stmt->execute([$_SESSION['user_id']]); 
  $appts = $stmt->fetchAll();

} else {
  requireRole(['receptionist','doctor']);
}
?>
<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/appointment.css">
<h2>Appointments</h2>
<table border="1" cellpadding="6">
  <tr>
    <th>ID</th><th>Patient</th><th>Doctor</th><th>Dept</th><th>Date</th><th>Time</th><th>Status</th><th>Action</th>
  </tr>
  <?php foreach($appts as $a): ?>
    <tr>
      <td><?= $a['appointment_id'] ?></td>
      <td><?= htmlspecialchars($a['patient_name']) ?></td>
      <td><?= isset($a['doctor_name']) ? htmlspecialchars($a['doctor_name']) : 'You' ?></td>
      <td><?= htmlspecialchars($a['department']) ?></td>
      <td><?= $a['appointment_date'] ?></td>
      <td><?= $a['appointment_time'] ?></td>
      <td><?= $a['status'] ?></td>
      <td>
        <?php if($role === 'receptionist' && in_array($a['status'], ['pending','confirmed'])): ?>
          <form method="post" style="display:inline;">
            <input type="hidden" name="appointment_id" value="<?= $a['appointment_id'] ?>">
            <button name="action" value="confirm">Confirm</button>
            <button name="action" value="cancel">Cancel</button>
          </form>
        <?php elseif($role === 'doctor' && $a['status'] === 'confirmed'): ?>
          <form method="post" style="display:inline;">
            <input type="hidden" name="complete_id" value="<?= $a['appointment_id'] ?>">
            <button>Mark Checked-in</button>
          </form>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
<!-- <?php include '../includes/footer.php'; ?> -->