<?php
require_once '../includes/db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email']));
    $token = bin2hex(random_bytes(50));
    $expiry = date("Y-m-d H:i:s", strtotime("+30 minutes"));

    // Check if user exists
    $stmt = $pdo->prepare("SELECT user_id FROM user WHERE email=? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Save token + expiry
        $stmt = $pdo->prepare("UPDATE user SET reset_token=?, token_expiry=? WHERE email=?");
        $stmt->execute([$token, $expiry, $email]);

        // Instead of sending email, show link directly
        $resetLink = "http://localhost/MEDICREPROJECT/Medicre/password/reset_password.php?token=" . $token;

echo "<p>Password reset link generated successfully.</p>";
echo "<a href='$resetLink'>
        <button style='padding:10px 20px; background-color:#007BFF; color:white; border:none; border-radius:5px; cursor:pointer;'>
          Reset Password
        </button>
      </a>";
    }}
?>