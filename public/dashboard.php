<?php
require_once '../includes/auth.php';
requireLogin();
include '../includes/header.php';
?>
<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="dashboard.css">
<h2>Welcome, <?= htmlspecialchars($_SESSION['name']); ?> (<?= $_SESSION['role']; ?>)</h2>

<?php if($_SESSION['role'] === 'admin'): ?>
 <a href="http://localhost/MEDICRE%20PROJECT/Medicre/admin/manage-users.php">Manage Users</a>

<?php elseif($_SESSION['role'] === 'doctor'): ?>
  <a href="appointments_list.php">My Appointments</a> |
  <a href="update-record.php">Add Record</a>

<?php elseif($_SESSION['role'] === 'receptionist'): ?>
  <a href="appointments_list.php">Manage Appointments</a>

<?php elseif($_SESSION['role'] === 'patient'): ?>
  <a href="appointments.php">Book Appointment</a> |
  <a href="view-record.php">My Records</a>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>