<?php
date_default_timezone_set('Asia/Kathmandu'); // Always use Nepal time
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole(['doctor']); 
include '../includes/header.php';

$doctor_id = $_SESSION['user_id'];
$msg = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leave_date_raw = $_POST['leave_date'] ?? '';
    $reason = trim($_POST['reason'] ?? '');

    $errors = [];

    // Validate date format
    $dt = DateTime::createFromFormat('Y-m-d', $leave_date_raw);
    $date_ok = $dt && $dt->format('Y-m-d') === $leave_date_raw;
    if (!$date_ok) {
        $errors[] = "Invalid date format.";
    } else {
        $today = new DateTime('today');
        if ($dt < $today) {
            $errors[] = "Leave date cannot be in the past.";
        }
    }

    // Reason length
    if (strlen($reason) > 200) {
        $errors[] = "Reason must be less than 200 characters.";
    }

    // Prevent duplicate leave request
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT 1 FROM doctor_leave WHERE doctor_id=? AND leave_date=?");
        $stmt->execute([$doctor_id, $leave_date_raw]);
        if ($stmt->fetch()) {
            $errors[] = "You already requested leave for this date.";
        }
    }

    // Prevent leave if appointments already exist
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE doctor_id=? AND appointment_date=?");
        $stmt->execute([$doctor_id, $leave_date_raw]);
        $appointmentCount = $stmt->fetchColumn();
        if ($appointmentCount > 0) {
            $errors[] = "You already have appointments booked for this date. Leave cannot be requested.";
        }
    }

    if (!empty($errors)) {
        $error = implode(' ', $errors);
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO doctor_leave (doctor_id, leave_date, reason, status) VALUES (?,?,?, 'pending')");
            $stmt->execute([$doctor_id, $leave_date_raw, $reason !== '' ? $reason : null]);
            $msg = "Leave request submitted. Waiting for receptionist approval.";
        } catch (PDOException $e) {
            $error = "Failed to submit leave request.";
        }
    }
}

// Load doctor’s own leave requests
$leaves = $pdo->prepare("
    SELECT leave_id, leave_date, reason, status
    FROM doctor_leave
    WHERE doctor_id = ?
    ORDER BY leave_date DESC
");
$leaves->execute([$doctor_id]);
$leaves = $leaves->fetchAll();
?>

<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/doctorleave_request.css">
<script src="../js/doctor_leave.js"></script>
<div class="doctor-leave-request">
  <h2>Request Leave</h2>

  <?php if($msg): ?><div class="alert success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  <?php if($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <form method="post" class="doctor-leave-form">
    <label><strong>Date</strong></label>
    <input type="date" name="leave_date" required min="<?= date('Y-m-d') ?>">

    <label><strong>Reason </strong></label><br>
<textarea name="reason" placeholder="Optional" rows="4" cols="50" maxlength="200"
          style="resize: vertical; width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;"></textarea>

    <button type="submit" class="btn">Submit Request</button>
  </form>

  <h3>My Leave Requests</h3>
  <table class="leave-table">
    <thead>
      <tr>
        <th>Date</th>
        <th>Reason</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($leaves as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['leave_date']) ?></td>
          <td><?= htmlspecialchars($r['reason'] ?? '') ?></td>
          <td><?= ucfirst($r['status']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include '../includes/footer.php'; ?>
