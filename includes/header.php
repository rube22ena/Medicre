<?php
require_once __DIR__ . '/auth.php';
?>
<header class="topbar">
  <div class="logo-title">
    <img src="pictures/logo-removebg-preview.png" alt="logo" height="50" width="50">
    <a href="index.php" class="brand">Medicre Hospital System</a>
  </div>
  <nav class="nav-links">
    <?php if(isset($_SESSION['user_id'])): ?>
      <a href="dashboard.php">📊 Dashboard</a>
      <a href="logout.php">🚪 Logout</a>
    <?php else: ?>
      <a href="login.php">🔐 Login</a>
      <!-- <a href="register.php">📝 Register</a> -->
    <?php endif; ?>
  </nav>
</header>
<hr>