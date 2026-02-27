<?php
require_once __DIR__ . '/auth.php';
?>
<link rel="stylesheet" href="/MedicreProject/Medicre/includes/headerstyle.css">

<header class="topbar">
  <div class="logo-title">
    <img src="/MedicreProject/Medicre/public/pictures/logo-removebg-preview.png" 
         alt="Medicre logo" height="50" width="50">
    <a href="/MedicreProject/Medicre/public/index.php" class="brand">
      Medicre Hospital System
    </a>
  </div>

  <nav class="nav-links">
    <?php if (isset($_SESSION['user_id'])): ?>
      
      <!-- Patient view -->
      <?php if ($_SESSION['role'] === 'patient'): ?>
        <a href="/MedicreProject/Medicre/public/doctor_list.php">👩‍⚕️ Book Appointment</a>
        <a href="/MedicreProject/Medicre/public/appointments.php">📅 My Appointments</a>
        <a href="/MedicreProject/Medicre/public/doctor_list.php">👩‍⚕️ Find Doctors</a>
        <a href="/MedicreProject/Medicre/public/view-record.php">📄 My Records</a>

      <!-- Receptionist view -->
      <?php elseif ($_SESSION['role'] === 'receptionist'): ?>
        <a href="/MedicreProject/Medicre/public/receptionist_dashboard.php">📅 Manage Appointments</a>
        <a href="/MedicreProject/Medicre/public/doctor_schedule.php">🗓️ Doctor Schedules</a>
        <a href="/MedicreProject/Medicre/public/doctor_leave.php">📅 Doctor Leaves</a>

      <!-- Doctor view -->
      <?php elseif ($_SESSION['role'] === 'doctor'): ?>
        <!-- <a href="/MedicreProject/Medicre/public/update-record.php">➕ Add Record</a> -->
        <a href="/MedicreProject/Medicre/public/doctor_dashboard.php">📅 My Appointments</a>
          <a href="/MedicreProject/Medicre/public/doctor_leave_request.php">📅 Leave Request</a>
          <a href="/MedicreProject/Medicre/public/doctor_schedule_request.php">📅 Schedule Requests</a>
      <!-- Admin view -->
      <?php elseif ($_SESSION['role'] === 'admin'): ?>
        <a href="/MedicreProject/Medicre/admin/manage-users.php">👥 Manage Users</a>
          <a href="../public/admin_dashboard.php">📊 Admin Dashboard</a>
      <?php endif; ?>

      <!-- Common logout -->
      <a href="/MedicreProject/Medicre/public/logout.php">🚪 Logout</a>

    <?php else: ?>
      <a href="/MedicreProject/Medicre/public/login.php">🔐 Login</a>
      <!-- <a href="/MedicreProject/Medicre/public/register.php">📝 Register</a> -->
    <?php endif; ?>
  </nav>
</header>
