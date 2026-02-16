<?php include '../includes/header.php'; ?>

<div class="reset-container">
  <h2>Forgot Password</h2>
  <form method="POST" action="send_reset.php">
    <input type="email" name="email" placeholder="Enter your email" required>
    <button type="submit">Reset Password</button>
  </form>
</div>

<?php include '../includes/footer.php'; ?>