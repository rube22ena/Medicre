<?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole(['receptionist']);
include '../includes/header.php';

// Fetch all doctors for dropdown
$doctors = $pdo->query("SELECT user_id, name FROM user WHERE role='doctor' ORDER BY name")->fetchAll();

$msg = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Add leave
    if (isset($_POST['leave_date'], $_POST['doctor_id'])) {
        $errors = [];

        $doctor_id = (int)($_POST['doctor_id'] ?? 0);
        $leave_date_raw = $_POST['leave_date'] ?? '';
        $reason = trim($_POST['reason'] ?? '');

        // 1) Validate doctor
        if ($doctor_id <= 0) {
            $errors[] = "Invalid doctor selected.";
        } else {
            $stmt = $pdo->prepare("SELECT user_id FROM user WHERE user_id=? AND role='doctor'");
            $stmt->execute([$doctor_id]);
            if (!$stmt->fetchColumn()) {
                $errors[] = "Selected doctor does not exist.";
            }
        }

        // 2) Validate date format and not past
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

        // 3) Reason length (optional, but constrained)
        if (strlen($reason) > 200) {
            $errors[] = "Reason must be less than 200 characters.";
        }

        // 4) Prevent duplicate leave (same doctor + date)
        if (empty($errors)) {
            $stmt = $pdo->prepare("SELECT 1 FROM doctor_leave WHERE doctor_id=? AND leave_date=?");
            $stmt->execute([$doctor_id, $leave_date_raw]);
            if ($stmt->fetch()) {
                $errors[] = "Leave for this doctor on this date already exists.";
            }
        }

        // Final decision
        if (!empty($errors)) {
            $error = implode(' ', $errors);
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO doctor_leave (doctor_id, leave_date, reason) VALUES (?,?,?)");
                $stmt->execute([$doctor_id, $leave_date_raw, $reason !== '' ? $reason : null]);
                $msg = "Leave added.";
            } catch (PDOException $e) {
                $error = "Failed to add leave. Please try again.";
            }
        }
    }

    // Delete leave
    if (isset($_POST['delete_leave_id'])) {
        $leave_id = (int)$_POST['delete_leave_id'];
        try {
            $stmt = $pdo->prepare("DELETE FROM doctor_leave WHERE leave_id=?");
            $stmt->execute([$leave_id]);
            $msg = "Leave removed.";
        } catch (PDOException $e) {
            $error = "Failed to remove leave.";
        }
    }
}

// Load existing leaves
$leaves = $pdo->query("
    SELECT dl.*, u.name
    FROM doctor_leave dl
    JOIN user u ON dl.doctor_id = u.user_id
    ORDER BY leave_date DESC
")->fetchAll();
?>
<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/doctor_leave.css">
<h2>Manage Doctor Leave</h2>

<?php if($msg): ?><p style="color:green;"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
<?php if($error): ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>

<form method="post" class="doctor-leave-form">
  <label><strong>Doctor</strong></label><br>
  <select name="doctor_id" required>
    <?php foreach($doctors as $doc): ?>
      <option value="<?= $doc['user_id'] ?>"><?= htmlspecialchars($doc['name']) ?></option>
    <?php endforeach; ?>
  </select><br><br>

  <label><strong>Date</strong></label><br>
  <input type="date" name="leave_date" required min="<?= date('Y-m-d') ?>"><br><br>

  <label><strong>Reason (Optional)</strong></label><br>
  <input type="text" name="reason" placeholder="Optional" maxlength="200"><br><br>

  <button type="submit">Add Leave</button>
</form>

<table border="1" cellpadding="6" style="margin-top:12px; border-collapse:collapse;">
  <tr><th>Doctor</th><th>Date</th><th>Reason</th><th>Action</th></tr>
  <?php foreach($leaves as $r): ?>
    <tr>
      <td><?= htmlspecialchars($r['name']) ?></td>
      <td><?= htmlspecialchars($r['leave_date']) ?></td>
      <td><?= htmlspecialchars($r['reason'] ?? '') ?></td>
      <td>
        <form method="post" style="display:inline;">
          <input type="hidden" name="delete_leave_id" value="<?= (int)$r['leave_id'] ?>">
          <button type="submit" onclick="return confirm('Delete this leave?')">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<?php include '../includes/footer.php'; ?>