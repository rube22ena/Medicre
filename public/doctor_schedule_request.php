<?php
// Force all PHP date/time functions to use Nepal Time
date_default_timezone_set('Asia/Kathmandu');

require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole(['doctor']); // Only doctors can access
include '../includes/header.php';

$doctor_id = $_SESSION['user_id'];
$msg = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $schedule_date = $_POST['schedule_date'] ?? '';
    $start         = $_POST['start_time'] ?? '';
    $end           = $_POST['end_time'] ?? '';
    $available     = isset($_POST['is_available']) ? 1 : 0;

    $errors = [];

    // Basic checks
    if (!$schedule_date) $errors[] = "Date is required.";
    if (!$start || !$end) $errors[] = "Start and End times are required.";
    if ($start && $end && $start === $end) $errors[] = "Start and End times cannot be the same.";
    if ($start && $end && $start > $end) $errors[] = "End time must be later than start time.";

    // Prevent past dates
    $today = date('Y-m-d');
    if ($schedule_date < $today) {
        $errors[] = "Past dates are not allowed.";
    }

    // Prevent past times if selected date is today
  
if ($schedule_date === $today && $start && $end) {
    $currentMinutes = date('G') * 60 + date('i');

    list($startHour, $startMin) = array_map('intval', explode(':', substr($start,0,5)));
    list($endHour, $endMin) = array_map('intval', explode(':', substr($end,0,5)));

    $startMinutes = $startHour * 60 + $startMin;
    $endMinutes   = $endHour * 60 + $endMin;

    if ($startMinutes < $currentMinutes) {
        $errors[] = "Start time cannot be in the past for today.";
    }
    if ($endMinutes < $currentMinutes) {
        $errors[] = "End time cannot be in the past for today.";
    }
}

    // Auto-calculate day_of_week from date
    $day = $schedule_date ? date('l', strtotime($schedule_date)) : null;
      // Prevent schedule if approved leave exists
// Normalize date to YYYY-MM-DD
$schedule_date = date('Y-m-d', strtotime($schedule_date));

// Prevent schedule if approved leave exists
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM doctor_leave 
    WHERE doctor_id=? AND leave_date=? AND status='approved'
");
$stmt->execute([$doctor_id, $schedule_date]);
$leaveCount = $stmt->fetchColumn();

if ($leaveCount > 0) {
    $errors[] = "You have approved leave for this date. Schedule cannot be created.";
}
    if (!empty($errors)) {
        $error = implode(" ", $errors);
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO doctor_schedule_request 
                (doctor_id, schedule_date, day_of_week, start_time, end_time, is_available, status) 
                VALUES (?,?,?,?,?,?, 'pending')");
            $stmt->execute([$doctor_id, $schedule_date, $day, $start, $end, $available]);
            $msg = "Schedule request submitted. Waiting for receptionist approval.";
        } catch (PDOException $e) {
            $error = "Failed to submit schedule request.";
        }
    }
}

// Load doctor’s own requests
$requests = $pdo->prepare("
    SELECT request_id, schedule_date, day_of_week, start_time, end_time, is_available, status
    FROM doctor_schedule_request
    WHERE doctor_id = ?
    ORDER BY schedule_date ASC
");
$requests->execute([$doctor_id]);
$requests = $requests->fetchAll();
?>

<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/doctor_schedule_request.css">
<script src="../js/Doctor_schedule.js"></script>

<div class="doctor-schedule-request">
  <h2>Request Schedule Change</h2>

  <?php if($msg): ?><div class="alert success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
  <?php if($error): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

  <form method="post" class="doctor-schedule-form">
    <label><strong>Date</strong></label>
    <input type="date" name="schedule_date" required min="<?= date('Y-m-d') ?>">

    <label><strong>Start Time</strong></label>
    <input type="time" name="start_time" required>

    <label><strong>End Time</strong></label>
    <input type="time" name="end_time" required>

    <label><input type="checkbox" name="is_available" checked> Available</label>

    <button type="submit" class="btn">Submit Request</button>
    
  </form>

  <h3>My Schedule Requests</h3>
  <table class="schedule-table">
    <thead>
      <tr>
        <th>Date</th><th>Day</th><th>Start</th><th>End</th><th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($requests as $r): ?>
        <tr>
          <td><?= htmlspecialchars($r['schedule_date']) ?></td>
          <td><?= htmlspecialchars($r['day_of_week']) ?></td>
          <td><?= htmlspecialchars($r['start_time']) ?></td>
          <td><?= htmlspecialchars($r['end_time']) ?></td>
          <td><?= ucfirst($r['status']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include '../includes/footer.php'; ?>