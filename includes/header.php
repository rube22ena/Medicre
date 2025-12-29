<?php
require_once __DIR__ . '/auth.php';
?>
<link rel="stylesheet" href="/MedicreProject/Medicre/css/headerstyle.css">

<header class="topbar">
  <div class="logo-title">
    <img src="pictures/logo-removebg-preview.png" alt="Medicre logo" height="50" width="50">
    <a href="/MedicreProject/Medicre/public/index.php" class="brand">Medicre Hospital System</a>
  </div>
  <nav class="nav-links">
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="/MedicreProject/Medicre/public/dashboard.php">📊 Dashboard</a>
      <a href="/MedicreProject/Medicre/public/logout.php">🚪 Logout</a>
    <?php else: ?>
      <a href="/MedicreProject/Medicre/public/login.php">🔐 Login</a>
      <!-- <a href="/MedicreProject/Medicre/public/register.php">📝 Register</a> -->
    <?php endif; ?>
  </nav>
</header>
<hr>