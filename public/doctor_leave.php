<?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole(['receptionist']); // Receptionist only
include '../includes/header.php';

// Fetch all doctors for dropdown
$doctors = $pdo->query("SELECT user_id, name FROM user WHERE role='doctor' ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['leave_date'], $_POST['doctor_id'])) {
        $pdo->prepare("INSERT INTO doctor_leave (doctor_id, leave_date, reason) VALUES (?,?,?)")
            ->execute([$_POST['doctor_id'], $_POST['leave_date'], $_POST['reason'] ?? null]);
        echo "<p style='color:green;'>Leave added.</p>";
    }
    if (isset($_POST['delete_leave_id'])) {
        $pdo->prepare("DELETE FROM doctor_leave WHERE leave_id=?")
            ->execute([(int)$_POST['delete_leave_id']]);
        echo "<p style='color:orange;'>Leave removed.</p>";
    }
}

// Show all doctors' leave recordsok 
$leaves = $pdo->query("SELECT dl.*, u.name 
                       FROM doctor_leave dl 
                       JOIN user u ON dl.doctor_id = u.user_id 
                       ORDER BY leave_date DESC");
$rows = $leaves->fetchAll();
?>

<h2>Manage Doctor Leave</h2>
<form method="post" style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">
  <label>Doctor
    <select name="doctor_id" required>
      <?php foreach($doctors as $doc): ?>
        <option value="<?= $doc['user_id'] ?>"><?= htmlspecialchars($doc['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Date <input type="date" name="leave_date" required min="<?= date('Y-m-d') ?>"></label>
  <label>Reason <input type="text" name="reason" placeholder="Optional"></label>
  <button type="submit">Add Leave</button>
</form>

<table border="1" cellpadding="6" style="margin-top:12px; border-collapse:collapse;">
  <tr><th>Doctor</th><th>Date</th><th>Reason</th><th>Action</th></tr>
  <?php foreach($rows as $r): ?>
    <tr>
      <td><?= htmlspecialchars($r['name']) ?></td>
      <td><?= $r['leave_date'] ?></td>
      <td><?= htmlspecialchars($r['reason'] ?? '') ?></td>
      <td>
        <form method="post" style="display:inline;">
          <input type="hidden" name="delete_leave_id" value="<?= $r['leave_id'] ?>">
          <button type="submit" onclick="return confirm('Delete this leave?')">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<?php include '../includes/footer.php'; ?>