<?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole(['receptionist','admin','doctor']); // adjust roles as needed

// Load all appointments with doctor + patient details
$appts = $pdo->query("
    SELECT a.appointment_id, a.department, a.appointment_date, a.appointment_time, a.status,
           d.name AS doctor_name, d.specialization,
           p.name AS patient_name, p.gender, p.age, p.mobile, p.email, p.address
    FROM appointments a
    JOIN user d ON d.user_id = a.doctor_id
    JOIN patient_details p ON p.appointment_id = a.appointment_id
    ORDER BY a.appointment_date, a.appointment_time
")->fetchAll();
?>

<link rel="stylesheet" href="../css/appointment.css">

<h2>Appointments List</h2>
<table border="1" cellpadding="6">
  <tr>
    <th>ID</th>
    <th>Patient</th>
    <th>Gender</th>
    <th>Age</th>
    <th>Mobile</th>
    <th>Email</th>
    <th>Address</th>
    <th>Doctor</th>
    <th>Department</th>
    <th>Date</th>
    <th>Time</th>
    <th>Status</th>
    <th>Actions</th>
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
      <td><?= htmlspecialchars($a['doctor_name']) ?></td>
      <td><?= htmlspecialchars($a['department']) ?></td>
      <td><?= $a['appointment_date'] ?></td>
      <td><?= $a['appointment_time'] ?></td>
      <td><?= $a['status'] ?></td>
      <td>
        <form method="post" action="update_status.php" style="display:inline;">
          <input type="hidden" name="appointment_id" value="<?= $a['appointment_id'] ?>">
          <button type="submit" name="status" value="Confirmed">Confirm</button>
          <button type="submit" name="status" value="Cancelled">Cancel</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>