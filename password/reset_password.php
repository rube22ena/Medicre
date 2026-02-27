<?php
require_once '../includes/db-connect.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare("SELECT email, token_expiry FROM user WHERE reset_token=? LIMIT 1");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        if (strtotime($user['token_expiry']) > time()) {
            // Show reset form
            echo '
            <div class="reset-form-container">
              <h2>Reset Your Password</h2>
              <form method="POST" action="update_password.php">
                <input type="hidden" name="email" value="'.$user['email'].'">
                <input type="password" name="new_password" placeholder="New Password" required>
                <button type="submit">Update Password</button>
              </form>
            </div>';
        } else {
            echo "<p class='error-message'>Token expired.</p>";
        }
    } else {
        echo "<p class='error-message'>Invalid token.</p>";
    }
}
?>

<style>
/* Reset form container */
.reset-form-container {
  width: 350px;
  margin: 80px auto;
  padding: 30px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.2);
  text-align: center;
  font-family: "Segoe UI", Arial, sans-serif;
}

/* Heading */
.reset-form-container h2 {
  margin-bottom: 20px;
  color: #0f9691;
  font-size: 24px;
  font-weight: bold;
}

/* Input field */
.reset-form-container input[type="password"] {
  width: 90%;
  padding: 12px;
  margin: 12px 0;
  border: 1px solid #ccc;
  border-radius: 6px;
  outline: none;
  transition: border 0.3s;
}

.reset-form-container input[type="password"]:focus {
  border: 1px solid #0f9691;
}

/* Update button */
.reset-form-container button {
  width: 95%;
  padding: 14px;
  background: linear-gradient(135deg, #0f9691, #0c7a76); /* teal gradient */
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 16px;
  font-weight: bold;
  transition: all 0.3s ease;
  box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.reset-form-container button:hover {
  background: linear-gradient(135deg, #0c7a76, #095f5b);
  transform: translateY(-2px) scale(1.05);
  box-shadow: 0 6px 12px rgba(0,0,0,0.25);
}

/* Error messages */
.error-message {
  text-align: center;
  color: red;
  font-weight: bold;
  margin-top: 40px;
  font-family: "Segoe UI", Arial, sans-serif;
}
</style>