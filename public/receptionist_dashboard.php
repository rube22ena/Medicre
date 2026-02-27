<?php
require_once '../includes/db-connect.php';
require_once '../includes/auth.php';
requireRole('receptionist');
require_once '../includes/header.php';
?>
<link rel="stylesheet" href="../includes/headerstyle.css">

<style>
.receptionist-dashboard {
  max-width: 900px;
  margin: 40px auto;
  padding: 25px;
  background: #f9f9f9;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.receptionist-dashboard h2 {
  font-size: 26px;
  color: #2c3e50;
  margin-bottom: 10px;
  border-bottom: 2px solid #3498db;
  padding-bottom: 6px;
}

.receptionist-dashboard p {
  font-size: 16px;
  color: #555;
  margin-bottom: 20px;
}
</style>
<div class="receptionist-dashboard">
  <h2>Receptionist Dashboard</h2>
  <p>Welcome, <?= htmlspecialchars($_SESSION['name']) ?>! Here’s your overview of patient appointments.</p>
</div>
  <div class="appointments-section">
    <?php include 'appointments_list.php'; ?>
  </div>


<?php include '../includes/footer.php'; ?>