<?php
require_once '../includes/db-connect.php'; 
require_once '../includes/auth.php'; 
requireRole('patient');
include '../includes/header.php';

// Load doctors for dropdown
$doctors = $pdo->query("SELECT user_id, name FROM user WHERE role='doctor' ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $doctor_id = (int)($_POST['doctor_id'] ?? 0);
  $dept = trim($_POST['department'] ?? '');
  $date = $_POST['appointment_date'] ?? '';
  $time = $_POST['appointment_time'] ?? '';

  if ($doctor_id && $dept && $date && $time) {
    $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, department, appointment_date, appointment_time, status)
                           VALUES (?,?,?,?,?, 'pending')");
    $stmt->execute([$_SESSION['user_id'], $doctor_id, $dept, $date, $time]);
    $msg = "Appointment requested successfully!";
  } else { 
    $error = "All fields are required"; 
  }
}

// Load patient's own appointments
$stmt = $pdo->prepare("SELECT a.*, d.name AS doctor_name 
                       FROM appointments a 
                       JOIN user d ON d.user_id=a.doctor_id 
                       WHERE a.patient_id=? 
                       ORDER BY a.appointment_date, a.appointment_time");
$stmt->execute([$_SESSION['user_id']]);
$myAppointments = $stmt->fetchAll();
?>
<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/appointments.css">
<h2>Book Appointment</h2>
<form method="post">
  <label>Doctor</label><br>
  <select name="doctor_id" required>
    <option value="">Select Doctor</option>
    <?php foreach($doctors as $d): ?>
      <option value="<?= $d['user_id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
    <?php endforeach; ?>
  </select><br>

  <label>Department</label><br>
<select name="department" required>
  <option value="">Select Department</option>
  <option value="Cardiology">Cardiology</option>
  <option value="Orthopedics">Orthopedics</option>
  <option value="Dermatology">Dermatology</option>
  <option value="General Medicine">General Medicine</option>
</select><br>

  <label>Date</label><br><input type="date" name="appointment_date" required><br>
  <label>Time</label><br><input type="time" name="appointment_time" required><br><br>
  <button type="submit">Book</button>
</form>
<script src="../js/time.js"></script>
<?php if(isset($msg)) echo "<p style='color:green;'>$msg</p>"; ?>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<h3>My Appointments</h3>
<table border="1" cellpadding="6">
  <tr>
    <th>Doctor</th><th>Department</th><th>Date</th><th>Time</th><th>Status</th>
  </tr>
  <?php foreach($myAppointments as $a): ?>
    <tr>
      <td><?= htmlspecialchars($a['doctor_name']) ?></td>
      <td><?= htmlspecialchars($a['department']) ?></td>
      <td><?= $a['appointment_date'] ?></td>
      <td><?= $a['appointment_time'] ?></td>
      <td><?= $a['status'] ?></td>
    </tr>
  <?php endforeach; ?>
</table>


