<?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole(['receptionist']); // Only receptionists can access
include '../includes/header.php';

$msg = null;
$error = null;

// Handle receptionist actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Approve leave
    if (isset($_POST['approve_leave_id'])) {
        $leave_id = (int)$_POST['approve_leave_id'];
        $pdo->prepare("UPDATE doctor_leave SET status='approved' WHERE leave_id=?")->execute([$leave_id]);
        $msg = "Leave approved.";
    }

    // Deny leave
    if (isset($_POST['deny_leave_id'])) {
        $leave_id = (int)$_POST['deny_leave_id'];
        $pdo->prepare("UPDATE doctor_leave SET status='denied' WHERE leave_id=?")->execute([$leave_id]);
        $msg = "Leave denied.";
    }

    // Delete leave (optional cleanup)
    if (isset($_POST['delete_leave_id'])) {
        $leave_id = (int)$_POST['delete_leave_id'];
        $pdo->prepare("DELETE FROM doctor_leave WHERE leave_id=?")->execute([$leave_id]);
        $msg = "Leave removed.";
    }
}

// Load all leave requests
$leaves = $pdo->query("
    SELECT dl.*, u.name
    FROM doctor_leave dl
    JOIN user u ON dl.doctor_id = u.user_id
    ORDER BY leave_date DESC
")->fetchAll();
?>

<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/doctor_leave.css">

<div class="doctor-leave-container">
  <h2>Manage Doctor Leave Requests</h2>

  <?php if($msg): ?><div class="alert success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  <?php if($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <table class="leave-table">
    <thead>
      <tr>
        <th>Doctor</th>
        <th>Date</th>
        <th>Reason</th>
        <th>Status</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($leaves as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['name']) ?></td>
          <td><?= htmlspecialchars($r['leave_date']) ?></td>
          <td><?= htmlspecialchars($r['reason'] ?? '') ?></td>
          <td><?= ucfirst($r['status']) ?></td>
  <td>
    <?php if($r['status'] === 'pending'): ?>
      <form method="post" style="display:inline;">
        <input type="hidden" name="approve_leave_id" value="<?= (int)$r['leave_id'] ?>">
        <button type="submit" class="btn">Approve</button>
      </form>
      <form method="post" style="display:inline;">
        <input type="hidden" name="deny_leave_id" value="<?= (int)$r['leave_id'] ?>">
        <button type="submit" class="btn delete">Deny</button>
      </form>
    <?php elseif($r['status'] === 'approved'): ?>
      <form method="post" style="display:inline;">
        <input type="hidden" name="deny_leave_id" value="<?= (int)$r['leave_id'] ?>">
        <button type="submit" class="btn delete">Deny</button>
      </form>
    <?php elseif($r['status'] === 'denied'): ?>
      <form method="post" style="display:inline;">
        <input type="hidden" name="approve_leave_id" value="<?= (int)$r['leave_id'] ?>">
        <button type="submit" class="btn">Approve</button>
      </form>
    <?php else: ?>
      <form method="post" style="display:inline;">
        <input type="hidden" name="delete_leave_id" value="<?= (int)$r['leave_id'] ?>">
        <button type="submit" class="btn delete">Delete</button>
      </form>
    <?php endif; ?>
  </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include '../includes/footer.php'; ?>