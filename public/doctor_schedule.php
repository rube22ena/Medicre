<?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole(['receptionist']); // Receptionist only
include '../includes/header.php';

// Fetch all doctors for dropdown
$doctors = $pdo->query("SELECT user_id, name FROM user WHERE role='doctor' ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['doctor_id'], $_POST['day_of_week'], $_POST['start_time'], $_POST['end_time'])) {
        $doctor_id = $_POST['doctor_id'];
        $day = $_POST['day_of_week'];
        $start = $_POST['start_time'];
        $end = $_POST['end_time'];
        $available = isset($_POST['is_available']) ? 1 : 0;

        // Replace existing row for that doctor/day
        $pdo->prepare("DELETE FROM doctor_schedule WHERE doctor_id=? AND day_of_week=?")
            ->execute([$doctor_id, $day]);

        $pdo->prepare("INSERT INTO doctor_schedule (doctor_id, day_of_week, start_time, end_time, is_available)
                       VALUES (?,?,?,?,?)")
            ->execute([$doctor_id, $day, $start, $end, $available]);

        echo "<p style='color:green;'>Schedule updated.</p>";
    }
    if (isset($_POST['delete_schedule_id'])) {
        $pdo->prepare("DELETE FROM doctor_schedule WHERE schedule_id=?")
            ->execute([(int)$_POST['delete_schedule_id']]);
        echo "<p style='color:orange;'>Schedule removed.</p>";
    }
}

// Show all doctors' schedules
$schedules = $pdo->query("SELECT ds.*, u.name 
                          FROM doctor_schedule ds 
                          JOIN user u ON ds.doctor_id = u.user_id 
                          ORDER BY FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), u.name");
$rows = $schedules->fetchAll();
?>

<h2>Manage Doctor Weekly Schedules</h2>
<form method="post" style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">
  <label>Doctor
    <select name="doctor_id" required>
      <?php foreach($doctors as $doc): ?>
        <option value="<?= $doc['user_id'] ?>"><?= htmlspecialchars($doc['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Day
    <select name="day_of_week" required>
      <option>Monday</option><option>Tuesday</option><option>Wednesday</option>
      <option>Thursday</option><option>Friday</option><option>Saturday</option><option>Sunday</option>
    </select>
  </label>
  <label>Start <input type="time" name="start_time" required></label>
  <label>End <input type="time" name="end_time" required></label>
  <label><input type="checkbox" name="is_available" checked> Available</label>
  <button type="submit">Save</button>
</form>

<table border="1" cellpadding="6" style="margin-top:12px; border-collapse:collapse;">
  <tr><th>Doctor</th><th>Day</th><th>Start</th><th>End</th><th>Status</th><th>Action</th></tr>
  <?php foreach ($rows as $r): ?>
    <tr>
      <td><?= htmlspecialchars($r['name']) ?></td>
      <td><?= htmlspecialchars($r['day_of_week']) ?></td>
      <td><?= $r['start_time'] ?></td>
      <td><?= $r['end_time'] ?></td>
      <td><?= $r['is_available'] ? 'Available' : 'Off' ?></td>
      <td>
        <form method="post" style="display:inline;">
          <input type="hidden" name="delete_schedule_id" value="<?= $r['schedule_id'] ?>">
          <button type="submit" onclick="return confirm('Delete this schedule?')">Delete</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<?php include '../includes/footer.php'; ?>