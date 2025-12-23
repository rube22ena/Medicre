<?php
require_once '../includes/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="../includes/headerstyle.css">
  <link rel="stylesheet" href="../css/Dashboard.css">
</head>
<body>
  <?php include '../includes/header.php'; ?>

  <main>
    <div class="dashboard-links">
  <?php if($_SESSION['role'] === 'admin'): ?>
    <a href="manage-user.php">👥 Manage user</a>
    <a href="appointments_list.php">📅 All appointments</a>
  <?php elseif($_SESSION['role'] === 'doctor'): ?>
    <a href="doctor_dashboard.php">📅 My appointments</a>
    <a href="update-record.php">➕ Add Record</a>
  <?php elseif($_SESSION['role'] === 'receptionist'): ?>
    <a href="receptionist_dashboard.php">📅 Manage appointments</a>
  <?php elseif($_SESSION['role'] === 'patient'): ?>
    <a href="appointments.php">📅 Book appointments</a>
    <a href="view-record.php">📄 My Records</a>
  <?php endif; ?>
</div>
    </div>
  </main>


</body>
</html>


 