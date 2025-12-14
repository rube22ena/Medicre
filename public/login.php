<?php
require_once '../includes/db-connect.php';
include '../includes/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = strtolower(trim($_POST['email'] ?? ''));
  $pass = $_POST['password'] ?? '';

  $stmt = $pdo->prepare("SELECT * FROM user WHERE email=? LIMIT 1");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if ($user && password_verify($pass, $user['password_hash'])) {
    session_start();
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['name'] = $user['name'];

    // Redirect based on role
    if ($user['role'] === 'doctor') {
      header('Location: doctor_dashboard.php');
    } elseif ($user['role'] === 'receptionist') {
      header('Location: receptionist_dashboard.php');
    } elseif ($user['role'] === 'admin') {
      header('Location: admin_dashboard.php');
    } elseif ($user['role'] === 'patient') {
      header('Location: appointments.php');   // 👈 put it here
      exit;
    }
  }

} else {
  $error = "Invalid email or password.";
}


?>

<link rel="stylesheet" href="../includes/headerstyle.css">
<link rel="stylesheet" href="../css/stylelogin.css">

<div class="login-container">
  <h2>Login</h2>
 <form id="loginForm" method="post">
    <input type="email" id="loginEmail" name="email" placeholder="Email" required><br>
<input type="password" id="loginPassword" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
    <div class="links">
      <a href="#">Forgot Password?</a>
      <a href="http://localhost/MEDICREPROJECT/Medicre/public/register.php">Create an Account</a>
    </div>
  </form>
  <div id="loginErrorBox"></div>
  <?php if (isset($error))
    echo "<p class='error-message'>$error</p>"; ?>
</div>

<script src="../js/login.js"></script>