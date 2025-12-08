<?php

require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole('receptionist');
include '../includes/header.php';
?>

<h2>Receptionist Dashboard</h2>
<p>Welcome, <?= htmlspecialchars($_SESSION['name']) ?>! Here you can manage patient appointments.</p>
<a href="logout.php">Logout</a>


<?php
// Include the shared appointment list with receptionist actions
include 'appointments_list.php';
?>

<!-- <?php include '../includes/footer.php';
 ?> -->