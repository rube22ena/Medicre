<?php
require_once __DIR__ . '/auth.php';
?>
<nav style="padding:8px; background:#f7f7f7;">
  <a href="http://localhost/MEDICRE%20PROJECT/Medicre/public/index.php">Medicre</a>
  <span style="float:right;">
    <?php if(isset($_SESSION['user_id'])): ?>
      <a href="http://localhost/MEDICRE%20PROJECT/Medicre/public/dashboard.php">Dashboard</a> |
      <a href="http://localhost/MEDICRE%20PROJECT/Medicre/public/login.php">Logout</a>
    <?php else: ?>
      <a href="/medicre/public/login.php">Login</a> |
      <a href="/medicre/public/register.php">Register</a>
    <?php endif; ?>
  </span>
</nav>
<hr>