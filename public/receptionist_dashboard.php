<?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole('receptionist');
?>
<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/receptionist.css">

<div class="receptionist-dashboard">
  <h2>Receptionist Dashboard</h2>
  <p>Welcome, <?= htmlspecialchars($_SESSION['name']) ?>! Here you can manage doctors, schedules, leaves, and patient appointments.</p>

  <nav style="margin:12px 0; display:flex; gap:12px; flex-wrap:wrap;">
    <a href="doctor_schedule.php" class="btn">Manage Doctor Schedules</a>
    <a href="doctor_leave.php" class="btn">Manage Doctor Leaves</a>
    <a href="appointments_list.php" class="btn">View Appointments</a>
  </nav>
</div>

<?php
// Fetch all doctors
$doctors = $pdo->query("SELECT user_id, name, specialization FROM user WHERE role = 'doctor' ORDER BY name")->fetchAll();
?>

<h3>Doctor List</h3>
<table border="1" cellpadding="6" style="border-collapse:collapse; margin-top:12px;">
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Specialization</th>
    <th>Actions</th>
  </tr>
  <?php foreach($doctors as $d): ?>
    <tr>
      <td><?= $d['user_id'] ?></td>
      <td><?= htmlspecialchars($d['name']) ?></td>
      <td><?= htmlspecialchars($d['specialization']) ?></td>
      <td>
        <a href="doctor_schedule.php?doctor_id=<?= $d['user_id'] ?>">Schedule</a> |
        <a href="doctor_leave.php?doctor_id=<?= $d['user_id'] ?>">Leave</a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

 