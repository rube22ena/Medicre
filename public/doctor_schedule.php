<?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole(['receptionist']); // Only receptionists can access
include '../includes/header.php';

$msg = null;
$error = null;

// Handle receptionist actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Approve request
    if (isset($_POST['approve_request_id'])) {
        $req_id = (int)$_POST['approve_request_id'];

        // Fetch request details
        $stmt = $pdo->prepare("SELECT * FROM doctor_schedule_request WHERE request_id=? AND status='pending'");
        $stmt->execute([$req_id]);
        $req = $stmt->fetch();

        if ($req) {
            // Replace existing schedule for that doctor/day
            $pdo->prepare("DELETE FROM doctor_schedule WHERE doctor_id=? AND day_of_week=?")
                ->execute([$req['doctor_id'], $req['day_of_week']]);

            $pdo->prepare("INSERT INTO doctor_schedule (doctor_id, day_of_week, start_time, end_time, is_available)
                           VALUES (?,?,?,?,?)")
                ->execute([$req['doctor_id'], $req['day_of_week'], $req['start_time'], $req['end_time'], $req['is_available']]);

            // Mark request as approved
            $pdo->prepare("UPDATE doctor_schedule_request SET status='approved' WHERE request_id=?")
                ->execute([$req_id]);

            $msg = "Schedule request approved and published.";
        }
    }

    // Deny request
    if (isset($_POST['deny_request_id'])) {
        $req_id = (int)$_POST['deny_request_id'];
        $pdo->prepare("UPDATE doctor_schedule_request SET status='denied' WHERE request_id=?")->execute([$req_id]);
        $msg = "Schedule request denied.";
    }

    // Delete schedule (optional cleanup)
    if (isset($_POST['delete_schedule_id'])) {
        $schedule_id = (int)$_POST['delete_schedule_id'];
        $pdo->prepare("DELETE FROM doctor_schedule WHERE schedule_id=?")->execute([$schedule_id]);
        $msg = "Schedule removed.";
    }
}

// Load pending requests
$requests = $pdo->query("
    SELECT r.*, u.name 
    FROM doctor_schedule_request r
    JOIN user u ON r.doctor_id = u.user_id
    ORDER BY FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), u.name
")->fetchAll();

// Load published schedules
$schedules = $pdo->query("
    SELECT ds.*, u.name 
    FROM doctor_schedule ds
    JOIN user u ON ds.doctor_id = u.user_id
    ORDER BY FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), u.name
")->fetchAll();
?>

<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/doctor_schedule.css">

<div class="doctor-schedule-container">
  <h2>Manage Doctor Weekly Schedules</h2>

  <?php if($msg): ?><div class="alert success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  <?php if($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <!-- Pending Requests -->
  
  <table class="schedule-table">
    <thead>
      <tr>
        <th>Doctor</th><th>Day</th><th>Start</th><th>End</th><th>Status</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($requests as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['name']) ?></td>
          <td><?= htmlspecialchars($r['day_of_week']) ?></td>
          <td><?= htmlspecialchars($r['start_time']) ?></td>
          <td><?= htmlspecialchars($r['end_time']) ?></td>
          <td><?= ucfirst($r['status']) ?></td>
          <td>
            <?php if($r['status'] === 'pending'): ?>
              <form method="post" style="display:inline;">
                <input type="hidden" name="approve_request_id" value="<?= (int)$r['request_id'] ?>">
                <button type="submit" class="btn">Approve</button>
              </form>
              <form method="post" style="display:inline;">
                <input type="hidden" name="deny_request_id" value="<?= (int)$r['request_id'] ?>">
                <button type="submit" class="btn delete">Deny</button>
              </form>
              <?php elseif($r['status'] === 'approved'): ?>
    <form method="post" style="display:inline;">
      <input type="hidden" name="deny_request_id" value="<?= (int)$r['request_id'] ?>">
      <button type="submit" class="btn delete">Deny</button>
    </form>
  <?php elseif($r['status'] === 'denied'): ?>
    <form method="post" style="display:inline;">
      <input type="hidden" name="approve_request_id" value="<?= (int)$r['request_id'] ?>">
      <button type="submit" class="btn">Approve</button>
    </form>

            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- Published Master Schedule -->
  <!-- <h3>Published Master Schedule</h3>
  <table class="schedule-table">
    <thead>
      <tr>
        <th>Doctor</th><th>Day</th><th>Start</th><th>End</th><th>Status</th><th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($schedules as $s): ?>
        <tr>
          <td><?= htmlspecialchars($s['name']) ?></td>
          <td><?= htmlspecialchars($s['day_of_week']) ?></td>
          <td><?= htmlspecialchars($s['start_time']) ?></td>
          <td><?= htmlspecialchars($s['end_time']) ?></td>
          <td><?= $s['is_available'] ? 'Available' : 'Off' ?></td>
          <td>
            <form method="post" style="display:inline;">
              <input type="hidden" name="delete_schedule_id" value="<?= (int)$s['schedule_id'] ?>">
              <button type="submit" class="btn delete" onclick="return confirm('Delete this schedule?')">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table> -->
</div>

<?php include '../includes/footer.php'; ?>