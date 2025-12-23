<?php
session_start();
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole(['patient']); // only patients can use this page

include '../includes/header.php';

// Doctor info
$doctor_id = (int)($_GET['doctor_id'] ?? 0);
$docStmt = $pdo->prepare("SELECT user_id, name, specialization, image 
                          FROM user WHERE user_id=? AND role='doctor'");
$docStmt->execute([$doctor_id]);
$doctor = $docStmt->fetch();

if (!$doctor) {
  echo "<p>Doctor not found.</p>";
  include '../includes/footer.php';
  exit;
}

// ✅ Handle cancellation directly here
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_id'])) {
    $appointment_id = (int)$_POST['cancel_id'];
    $patient_id = (int)($_SESSION['user_id'] ?? 0);

    if ($appointment_id > 0 && $patient_id > 0) {
        $stmt = $pdo->prepare("UPDATE appointments 
                               SET status = 'cancelled' 
                               WHERE appointment_id = ? AND patient_id = ?");
        $stmt->execute([$appointment_id, $patient_id]);
        echo "<p style='color:orange;'>Appointment cancelled successfully.</p>";
    }
}
?>

<link rel="stylesheet" href="../css/appointments.css">

<h2>Book an Appointment</h2>
<div class="doctor-summary">
  <img src="../uploads/doctor_images/<?= htmlspecialchars($doctor['image'] ?? 'default.png') ?>" alt="Doctor">
  <div>
    <h3><?= htmlspecialchars($doctor['name']) ?></h3>
    <p><strong>Department:</strong> <?= htmlspecialchars($doctor['specialization']) ?></p>
  </div>
</div>

<form method="post" action="appointments_save.php" class="appointments-form">
  <input type="hidden" name="doctor_id" value="<?= (int)$doctor['user_id'] ?>">
  <input type="hidden" name="department" value="<?= htmlspecialchars($doctor['specialization']) ?>">

  <!-- patient details fields -->
  <label>Your Name</label>
  <input type="text" name="name" required>
  <label>Gender</label>
  <select name="gender" required>
    <option value="">Select</option>
    <option value="Male">Male</option>
    <option value="Female">Female</option>
    <option value="Other">Other</option>
  </select>
  <label>Age</label>
  <input type="number" name="age" required min="1" max="120">
  <label>Mobile Number</label>
  <input type="text" name="mobile" required>
  <label>Email</label>
  <input type="email" name="email" required>
  <label>Address</label>
  <textarea name="address" rows="2" required></textarea>
  <label>Appointment Date</label>
  <input type="date" name="appointment_date" required min="<?= date('Y-m-d') ?>">
  <label>Appointment Time</label>
  <input type="time" name="appointment_time" required>

  <button type="submit">Book Consultation</button>
</form>

<?php
// Messages
if (isset($_GET['success'])) {
    echo "<p style='color:green;'>Appointment booked successfully!</p>";
}
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'duplicate') {
        echo "<p style='color:red;'>This doctor already has an appointment at that time.</p>";
    } else {
        echo "<p style='color:red;'>Please fill all required fields.</p>";
    }
}

// Patient's appointments
$patient_id = $_SESSION['user_id'];
$myAppts = $pdo->prepare("
    SELECT a.appointment_id, a.department, a.appointment_date, a.appointment_time, a.status,
           d.name AS doctor_name, d.specialization
    FROM appointments a
    JOIN user d ON d.user_id = a.doctor_id
    WHERE a.patient_id = ?
    ORDER BY a.appointment_date, a.appointment_time
");
$myAppts->execute([$patient_id]);
$rows = $myAppts->fetchAll();

if ($rows) {
    echo "<h2>My Appointments</h2>";
    echo "<table border='1' cellpadding='6'>
            <tr>
              <th>ID</th>
              <th>Doctor</th>
              <th>Department</th>
              <th>Date</th>
              <th>Time</th>
              <th>Status</th>
              <th>Action</th>
            </tr>";
    foreach ($rows as $r) {
        echo "<tr>
                <td>{$r['appointment_id']}</td>
                <td>".htmlspecialchars($r['doctor_name'])."</td>
                <td>".htmlspecialchars($r['department'])."</td>
                <td>{$r['appointment_date']}</td>
                <td>{$r['appointment_time']}</td>
                <td>{$r['status']}</td>
                <td>";
        if ($r['status'] === 'pending') {
            echo "<form method='post' style='display:inline;'>
                    <input type='hidden' name='cancel_id' value='{$r['appointment_id']}'>
                    <button type='submit' onclick=\"return confirm('Cancel this appointment?');\">Cancel</button>
                  </form>";
        }
        echo "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>You have no appointments yet.</p>";
}

include '../includes/footer.php';
?>