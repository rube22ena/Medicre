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
            echo '<form method="POST" action="update_password.php">
                    <input type="hidden" name="email" value="'.$user['email'].'">
                    <input type="password" name="new_password" placeholder="New Password" required>
                    <button type="submit">Update Password</button>
                  </form>';
        } else {
            echo "Token expired.";
        }
    } else {
        echo "Invalid token.";
    }
}
?>