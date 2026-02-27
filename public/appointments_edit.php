<?php
session_start();
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole(['patient']);

include '../includes/header.php';

$appointment_id = (int)($_GET['appointment_id'] ?? $_POST['appointment_id'] ?? 0);
$patient_id = $_SESSION['user_id'];

// If form submitted → update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $update_id = (int)$_POST['update_id'];

    // Collect form values
    $name    = trim($_POST['name']);
    $gender  = $_POST['gender'];
    $age     = (int)$_POST['age'];
    $mobile  = trim($_POST['mobile']);
    $email   = trim($_POST['email']);
    $address = trim($_POST['address']);
    $date    = $_POST['appointment_date'];
    $time    = $_POST['appointment_time'];

    
  // Update appointment date/time
$stmt = $pdo->prepare("UPDATE appointments 
                       SET appointment_date=?, appointment_time=? 
                       WHERE appointment_id=? AND patient_id=? AND status='pending'");
$stmt->execute([$date, $time, $update_id, $patient_id]);

// Update patient details
$detailStmt = $pdo->prepare("UPDATE patient_details 
                             SET name=?, gender=?, age=?, mobile=?, email=?, address=? 
                             WHERE appointment_id=?");
$detailStmt->execute([$name, $gender, $age, $mobile, $email, $address, $update_id]);

// ✅ Fetch doctor_id of the updated appointment
$docStmt = $pdo->prepare("SELECT doctor_id 
                          FROM appointments 
                          WHERE appointment_id=? AND patient_id=?");
$docStmt->execute([$update_id, $patient_id]);
$row = $docStmt->fetch();

if ($row) {
    $doctor_id = (int)$row['doctor_id'];
    // Redirect back to appointments.php with success + doctor_id
    header("Location: appointments.php?success=updated&doctor_id=" . $doctor_id);
    exit;
} else {
    // Fallback if doctor_id not found
    header("Location: appointments.php?success=updated");
    exit;
}}

// --- Load existing appointment data for edit form ---
$stmt = $pdo->prepare("
    SELECT a.appointment_id, a.appointment_date, a.appointment_time, a.department,
           p.name, p.gender, p.age, p.mobile, p.email, p.address
    FROM appointments a
    JOIN patient_details p ON p.appointment_id = a.appointment_id
    WHERE a.appointment_id=? AND a.patient_id=? AND a.status='pending'
");
$stmt->execute([$appointment_id, $patient_id]);
$data = $stmt->fetch();

if (!$data) {
    echo "<p>Appointment not found or cannot be edited.</p>";
    include '../includes/footer.php';
    exit;
}
?>
<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/appointment-edit.css">
<script src="../js/appointment-edit.js"></script>
<h2>Edit Appointment</h2>
<form method="post">
  <input type="hidden" name="update_id" value="<?= $data['appointment_id'] ?>">

  <label>Your Name</label><br>
  <input type="text" name="name" value="<?= htmlspecialchars($data['name']) ?>" required><br>

  <label>Gender</label><br><br>
  <select name="gender" required>
    <option value="Male" <?= $data['gender']=='Male'?'selected':'' ?>>Male</option>
    <option value="Female" <?= $data['gender']=='Female'?'selected':'' ?>>Female</option>
    <option value="Other" <?= $data['gender']=='Other'?'selected':'' ?>>Other</option>
  </select><br>

  <label>Age</label><br>
  <input type="number" name="age" value="<?= $data['age'] ?>" required min="1" max="120"><br>

  <label>Mobile Number</label><br>
  <input type="text" name="mobile" value="<?= htmlspecialchars($data['mobile']) ?>" required><br>
  <label>Email</label><br>
  <input type="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" required><br>

  <label>Address</label><br>
  <textarea name="address" rows="2" required><?= htmlspecialchars($data['address']) ?></textarea><br>
     <label>Blood Group</label><br>
<select name="blood_group" required>
  <option value="">Select Blood Group</option>
  <option value="A+" <?= (($_POST['blood_group'] ?? '') === 'A+') ? 'selected' : '' ?>>A+</option>
  <option value="A-" <?= (($_POST['blood_group'] ?? '') === 'A-') ? 'selected' : '' ?>>A-</option>
  <option value="B+" <?= (($_POST['blood_group'] ?? '') === 'B+') ? 'selected' : '' ?>>B+</option>
  <option value="B-" <?= (($_POST['blood_group'] ?? '') === 'B-') ? 'selected' : '' ?>>B-</option>
  <option value="AB+" <?= (($_POST['blood_group'] ?? '') === 'AB+') ? 'selected' : '' ?>>AB+</option>
  <option value="AB-" <?= (($_POST['blood_group'] ?? '') === 'AB-') ? 'selected' : '' ?>>AB-</option>
  <option value="O+" <?= (($_POST['blood_group'] ?? '') === 'O+') ? 'selected' : '' ?>>O+</option>
  <option value="O-" <?= (($_POST['blood_group'] ?? '') === 'O-') ? 'selected' : '' ?>>O-</option>
  <label>Appointment Date</label><br>
  <input type="date" name="appointment_date" value="<?= $data['appointment_date'] ?>" required><br>

  <label>Appointment Time</label><br>
  <input type="time" name="appointment_time" value="<?= $data['appointment_time'] ?>" required><br>

  <button type="submit">Update Appointment</button>
</form>


<?php include '../includes/footer.php'; ?>