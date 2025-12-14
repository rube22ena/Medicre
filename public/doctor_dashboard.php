<?php

require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole('doctor');

include '../includes/header.php';


?>
<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/doctor.css">
<h2>Doctor Dashboard</h2>
<p>Welcome, Dr. <?= htmlspecialchars($_SESSION['name']) ?>! Here are your appointments.</p>



<?php

// Include the shared appointment list with doctor actions
include 'appointments_list.php';
?> 


 