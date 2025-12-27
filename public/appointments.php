<?php
session_start();
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole(['patient']); // only patients can use this page
require_once '../includes/availability_helper.php'; // slot helper

include '../includes/header.php';

// --- Doctor info (optional) ---
$doctor_id = isset($_GET['doctor_id']) ? (int) $_GET['doctor_id'] : 0;
$doctor = null;

if ($doctor_id > 0) {
  $docStmt = $pdo->prepare("SELECT user_id, name, specialization, photo 
                              FROM user WHERE user_id=? AND role='doctor'");
  $docStmt->execute([$doctor_id]);
  $doctor = $docStmt->fetch();
}

// ✅ Messages (success/error banners)
if (isset($_GET['success']) && $_GET['success'] === 'updated') {
    echo "<p style='color:green;'>Appointment updated successfully!</p>";
}
if (isset($_GET['error'])) {
  switch ($_GET['error']) {
    case 'duplicate': echo "<p style='color:red;'>This doctor already has an appointment at that time.</p>"; break;
    case 'leave': echo "<p style='color:red;'>Doctor is on leave for that date.</p>"; break;
    case 'noschedule': echo "<p style='color:red;'>Doctor has no schedule for that day.</p>"; break;
    case 'outofhours': echo "<p style='color:red;'>Selected time is outside doctor’s working hours.</p>"; break;
    case 'missing': echo "<p style='color:red;'>Please fill all required fields.</p>"; break;
    case 'editfail': echo "<p style='color:red;'>Could not edit appointment.</p>"; break;
    case 'pasttime': echo "<p style='color:red;'>You cannot book an appointment in the past.</p>"; break;
  }
}

// ✅ Show doctor summary + booking form only if doctor info exists
if ($doctor) {
  echo '<h2>Book an Appointment</h2>';
  echo '<div class="doctor-summary">';
  echo '<img src="../uploads/' . htmlspecialchars($doctor['photo'] ?? 'default.png') . '" alt="Doctor" style="width:120px; height:auto; border-radius:8px;">';
  echo '<div>';
  echo '<h3>' . htmlspecialchars($doctor['name']) . '</h3>';
  echo '<p><strong>Department:</strong> ' . htmlspecialchars($doctor['specialization']) . '</p>';
  echo '</div></div>';

$selectedDate = $_GET['appointment_date'] ?? date('Y-m-d');
$slots = getAvailableSlots($pdo, $doctor_id, $selectedDate, 30);

// Only hide passed times if booking for today
if ($selectedDate === date('Y-m-d')) {
    date_default_timezone_set('Asia/Kathmandu'); // keep server time aligned
    $nowTs = time();

    // Keep only slots strictly after current time
    $slots = array_filter($slots, function ($slot) use ($selectedDate, $nowTs) {
        // Normalize "HH:MM" to "HH:MM:SS"
        $slotTime = strlen($slot) === 5 ? $slot . ':00' : $slot;
        $slotTs = strtotime($selectedDate . ' ' . $slotTime);
        return $slotTs > $nowTs;
    });
}

  ?>
  <form method="post" action="appointments_save.php" class="appointments-form">
    <input type="hidden" name="doctor_id" value="<?= (int) $doctor['user_id'] ?>">
    <input type="hidden" name="department" value="<?= htmlspecialchars($doctor['specialization']) ?>">

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
    <input type="date" name="appointment_date" required min="<?= date('Y-m-d') ?>"
      value="<?= htmlspecialchars($selectedDate) ?>"
      onchange="location.href='appointments.php?doctor_id=<?= (int) $doctor['user_id'] ?>&appointment_date=' + this.value;">

    <label>Appointment Time</label>
    <?php if (empty($slots)): ?>
      <p style="color:orange;">No available slots for this date. Please choose another date.</p>
    <?php else: ?>
      <select name="appointment_time" required>
        <option value="">Select a time</option>
        <?php foreach ($slots as $s): ?>
          <option value="<?= $s ?>"><?= substr($s, 0, 5) ?></option>
        <?php endforeach; ?>
      </select>
    <?php endif; ?>

    <button type="submit" <?= empty($slots) ? 'disabled' : '' ?>>Book Consultation</button>
  </form>
  <?php
}

// ✅ Patient's appointments list
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
                <td>" . htmlspecialchars($r['doctor_name']) . "</td>
                <td>" . htmlspecialchars($r['department']) . "</td>
                <td>{$r['appointment_date']}</td>
                <td>{$r['appointment_time']}</td>
                <td>{$r['status']}</td>
                <td>";
 if ($r['status'] === 'pending') {
  // Cancel button
  echo "<form method='post' style='display:inline;'>
          <input type='hidden' name='cancel_id' value='{$r['appointment_id']}'>
          <button type='submit' onclick=\"return confirm('Cancel this appointment?');\">Cancel</button>
        </form>";

  // Edit button
  echo "<form method='get' action='appointments_edit.php' style='display:inline; margin-left:5px;'>
          <input type='hidden' name='appointment_id' value='{$r['appointment_id']}'>
          <button type='submit'>Edit</button>
        </form>";

} elseif ($r['status'] === 'completed') {
  // ✅ New "My Record" button
  echo "<a href='view-record.php?appointment_id={$r['appointment_id']}'>
          <button type='button'>My Record</button>
        </a>";
}
    echo "</td></tr>";
  }
  echo "</table>";
} else {
  echo "<p>You have no appointments yet.</p>";
}

include '../includes/footer.php';
?>