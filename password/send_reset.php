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

        echo "<div class='reset-success'>";
        echo "<p>Password reset link generated successfully.</p>";
        echo "<a href='$resetLink' class='reset-btn'>Reset Password</a>";
        echo "</div>";
    }
}
?>

<style>
/* Success container */
.reset-success {
  text-align: center;
  margin: 80px auto;
  padding: 30px;
  width: 400px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  font-family: 'Segoe UI', Arial, sans-serif;
}

/* Success message */
.reset-success p {
  color: #0f9691;
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 20px;
}

/* Reset button */
.reset-btn {
  display: inline-block;
  padding: 12px 24px;
  background: linear-gradient(135deg, #0f9691, #0c7a76); /* teal gradient */
  color: #fff;
  border-radius: 8px;
  text-decoration: none;
  font-size: 16px;
  font-weight: bold;
  transition: all 0.3s ease;
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.reset-btn:hover {
  background: linear-gradient(135deg, #0c7a76, #095f5b);
  transform: translateY(-2px) scale(1.05);
  box-shadow: 0 6px 12px rgba(0,0,0,0.25);
}
</style>