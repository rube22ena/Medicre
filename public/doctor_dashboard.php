<?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole('doctor');

include '../includes/header.php';

// Handle doctor action: mark checked-in
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_id'])) {
    $aid = (int)$_POST['complete_id'];
    $stmt = $pdo->prepare("UPDATE appointments SET status='checked-in' WHERE appointment_id=? AND doctor_id=?");
    $stmt->execute([$aid, $_SESSION['user_id']]);
}

// --- Filtering logic ---
$filter = $_GET['filter'] ?? 'all';
$today = date('Y-m-d');

$sql = "
    SELECT a.appointment_id, a.department, a.appointment_date, a.appointment_time, a.status,
           p.name AS patient_name, p.gender, p.age, p.mobile, p.email, p.address
    FROM appointments a
    JOIN patient_details p ON p.appointment_id = a.appointment_id
    WHERE a.doctor_id = ?
";

if ($filter === 'today') {
    $sql .= " AND a.appointment_date = ?";
    $params = [$_SESSION['user_id'], $today];
} elseif ($filter === 'confirmed') {
    $sql .= " AND a.status = 'confirmed'";
    $params = [$_SESSION['user_id']];
} else {
    $params = [$_SESSION['user_id']];
}

$sql .= " ORDER BY a.appointment_date, a.appointment_time";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$appts = $stmt->fetchAll();
?>

<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/doctor.css">

<h2>Doctor Dashboard</h2>
<p>Welcome, Dr. <?= htmlspecialchars($_SESSION['name']) ?>! Here are your appointments.</p>

<!-- Filter buttons -->
<div class="filters">
  <a href="?filter=all">All</a> |
  <a href="?filter=today">Today</a> |
  <a href="?filter=confirmed">Confirmed</a>
</div>

<table border="1" cellpadding="6">
  <tr>
    <th>ID</th>
    <th>Patient</th>
    <th>Gender</th>
    <th>Age</th>
    <th>Mobile</th>
    <th>Email</th>
    <th>Address</th>
    <th>Dept</th>
    <th>Date</th>
    <th>Time</th>
    <th>Status</th>
    <th>Action</th>
  </tr>
  <?php foreach($appts as $a): ?>
    <tr>
      <td><?= $a['appointment_id'] ?></td>
      <td><?= htmlspecialchars($a['patient_name']) ?></td>
      <td><?= htmlspecialchars($a['gender']) ?></td>
      <td><?= htmlspecialchars($a['age']) ?></td>
      <td><?= htmlspecialchars($a['mobile']) ?></td>
      <td><?= htmlspecialchars($a['email']) ?></td>
      <td><?= htmlspecialchars($a['address']) ?></td>
      <td><?= htmlspecialchars($a['department']) ?></td>
      <td><?= $a['appointment_date'] ?></td>
      <td><?= $a['appointment_time'] ?></td>
      <td><?= $a['status'] ?></td>
      <td>
        <?php if($a['status'] === 'confirmed'): ?>
          <form method="post" style="display:inline;">
            <input type="hidden" name="complete_id" value="<?= $a['appointment_id'] ?>">
            <button>Mark Checked-in</button>
          </form>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<?php include '../includes/footer.php'; ?>