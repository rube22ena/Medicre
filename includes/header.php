<?php
require_once __DIR__ . '/auth.php';
?>

<header>
  <!-- <link rel="stylesheet" href="headerstyle.css"> -->
  <div class="header-container">
    <div class="logo-title">
      <a href="index.php" class="brand">🏥 Medicre Hospital System</a>
    </div>
    <nav class="nav-links">
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="dashboard.php">📊 Dashboard</a>
        <a href="logout.php">🚪 Logout</a>
      <?php else: ?>
        <a href="login.php">🔐 Login</a>
        <a href="register.php">📝 Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<hr>