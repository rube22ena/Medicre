<?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
// requireRole('admin');
include '../includes/header.php';

// appointments stats
$stats = $pdo->query("
  SELECT status, COUNT(*) AS count
  FROM appointments
  GROUP BY status
")->fetchAll();

// User stats
$user = $pdo->query("
  SELECT role, COUNT(*) AS count
  FROM user
  GROUP BY role
")->fetchAll();
?>
<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/reports.css">
<h2>System Reports</h2>

<h3>appointments Statistics</h3>
<table border="1" cellpadding="6">
  <tr><th>Status</th><th>Count</th></tr>
  <?php foreach($stats as $s): ?>
    <tr>
      <td><?= htmlspecialchars($s['status']) ?></td>
      <td><?= $s['count'] ?></td>
    </tr>
  <?php endforeach; ?>
</table>

<h3>User Statistics</h3>
<table border="1" cellpadding="6">
  <tr><th>Role</th><th>Count</th></tr>
  <?php foreach($user as $u): ?>
    <tr>
      <td><?= htmlspecialchars($u['role']) ?></td>
      <td><?= $u['count'] ?></td>
    </tr>
  <?php endforeach; ?>
</table>

