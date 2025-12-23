<?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole('receptionist');
// include '../includes/header.php';
?>
<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/receptionist.css">

<div class="receptionist-dashboard">
  <h2>Receptionist Dashboard</h2>
  <p>Welcome, <?= htmlspecialchars($_SESSION['name']) ?>! Here you can manage patient appointments.</p>
  <!-- <a href="logout.php">Logout</a> -->

  <?php
  // Include the shared appointments list with receptionist actions
  include 'appointments_list.php';
  ?>
</div>


 