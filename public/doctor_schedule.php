<?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole(['receptionist']); // Receptionist only
include '../includes/header.php';

// Fetch all doctors for dropdown
$doctors = $pdo->query("SELECT user_id, name FROM user WHERE role='doctor' ORDER BY name")->fetchAll();

$msg = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add or update schedule
    if (isset($_POST['doctor_id'], $_POST['day_of_week'], $_POST['start_time'], $_POST['end_time'])) {
        $doctor_id = (int)$_POST['doctor_id'];
        $day       = $_POST['day_of_week'];
        $start     = $_POST['start_time'];
        $end       = $_POST['end_time'];
        $available = isset($_POST['is_available']) ? 1 : 0;

        $errors = [];

        // Validation
        if ($doctor_id <= 0) $errors[] = "Invalid doctor selected.";
        if (!$day) $errors[] = "Day is required.";
        if (!$start || !$end) $errors[] = "Start and End times are required.";
        if ($start && $end && $start >= $end) $errors[] = "End time must be later than start time.";

        if (!empty($errors)) {
            $error = implode(" ", $errors);
        } else {
            // Replace existing row for that doctor/day
            $pdo->prepare("DELETE FROM doctor_schedule WHERE doctor_id=? AND day_of_week=?")
                ->execute([$doctor_id, $day]);

            $pdo->prepare("INSERT INTO doctor_schedule (doctor_id, day_of_week, start_time, end_time, is_available)
                           VALUES (?,?,?,?,?)")
                ->execute([$doctor_id, $day, $start, $end, $available]);

            $msg = "Schedule updated successfully.";
        }
    }

    // Delete schedule
    if (isset($_POST['delete_schedule_id'])) {
        $schedule_id = (int)$_POST['delete_schedule_id'];
        $pdo->prepare("DELETE FROM doctor_schedule WHERE schedule_id=?")
            ->execute([$schedule_id]);
        $msg = "Schedule removed.";
    }
}

// Show all doctors' schedules
$schedules = $pdo->query("
    SELECT ds.*, u.name 
    FROM doctor_schedule ds 
    JOIN user u ON ds.doctor_id = u.user_id 
    ORDER BY FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), u.name
")->fetchAll();
?>
<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/doctor_schedule.css">
<script src="../js/doctor_schedule.js"></script>
<h2>Manage Doctor Weekly Schedules</h2>

<?php if($msg): ?><p style="color:green;"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
<?php if($error): ?><p style="color:red;"><?= htmlspecialchars($error) ?></p><?php endif; ?>

<form method="post" class="doctor-schedule-form">
  <label><strong>Doctor</strong></label><br>
  <select name="doctor_id" required>
    <?php foreach($doctors as $doc): ?>
      <option value="<?= $doc['user_id'] ?>"><?= htmlspecialchars($doc['name']) ?></option>
    <?php endforeach; ?>
  </select><br><br>

  <label><strong>Day</strong></label><br>
  <select name="day_of_week" required>
    <option>Monday</option><option>Tuesday</option><option>Wednesday</option>
    <option>Thursday</option><option>Friday</option><option>Saturday</option><option>Sunday</option>
  </select><br><br>

  <label><strong>Start Time</strong></label><br>
  <input type="time" name="start_time" required><br><br>

  <label><strong>End Time</strong></label><br>
  <input type="time" name="end_time" required><br><br>

  <label><input type="checkbox" name="is_available" checked> Available</label><br><br>

  <button type="submit">Save Schedule</button>
</form>

<table>
  <tr><th>Doctor</th><th>Day</th><th>Start</th><th>End</th><th>Status</th><th>Action</th></tr>
  <?php foreach ($schedules as $r): ?>
    <tr>
      <td><?= htmlspecialchars($r['name']) ?></td>
      <td><?= htmlspecialchars($r['day_of_week']) ?></td>
      <td><?= htmlspecialchars($r['start_time']) ?></td>
      <td><?= htmlspecialchars($r['end_time']) ?></td>
      <td><?= $r['is_available'] ? 'Available' : 'Off' ?></td>
      <td>
        <form method="post" style="display:inline;">
          <input type="hidden" name="delete_schedule_id" value="<?= (int)$r['schedule_id'] ?>">
          <button type="submit" onclick="return confirm('Delete this schedule?')">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<?php include '../includes/footer.php'; ?>